<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

     /**
     * ⚡ Global Auto-Log Endpoint: บันทึก Log ทุกการคลิกในระบบ
     */
    public function logUserActivity()
    {
        // เรียกใช้งาน Database Connection ตามปกติ
        // $this->db = \Config\Database::connect();
        $request = \Config\Services::request();
        $session = session();

        // รับค่าจาก JS (รองรับทั้ง $_POST และ sendBeacon FormData)
        $eventType   = $request->getPost('event_type') ?? 'AUTO_CLICK';
        $eventTitle  = $request->getPost('event_title') ?? 'User Clicked Element';
        $eventDetail = $request->getPost('event_detail') ?? null;

        // ดึงชื่อ Controller / Method ที่กำลังใช้งาน
        $router     = \Config\Services::router();
        $controller = $router->controllerName();
        $method     = $router->methodName();

        $logData = [
            'emp_id'       => $session->get('emp_id') ?? $session->get('user_id') ?? null,
            'cid'          => $session->get('cid') ?? null,
            'event_type'   => $eventType,
            'event_title'  => $eventTitle,
            'event_detail' => $eventDetail,
            'controller'   => $controller,
            'method'       => $method,
            'remote_ip'    => $request->getIPAddress(), // 👈 ดึง IP Address ของผู้ใช้
            'user_agent'   => (string)$request->getUserAgent(),
            'created_at'   => date('Y-m-d H:i:s')
        ];

        $this->db->table('tr_event_logs')->insert($logData);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function index()
    {
        $employee = new \App\Models\EmployeeModel();
        $fileuploads = new \App\Models\UploadFileModel();
        $course = new \App\Models\CourseModel();

        $workgroup = $this->db->query("CALL chkWorkgroup()");

        $typecourse = [
            '1' => 'ผู้อำนวยการโรงพยาบาล',
            '2' => 'รองผู้อำนวยการโรงพยาบาล',
            '3' => 'หัวหน้ากลุ่มงาน',
            '4' => 'เจ้าหน้าที่ IT',
            '5' => 'เจ้าหน้าที่ / บุคลากรทั่วไป', 
        ];

        $data['employee'] = $employee->countAllResults();
        $data['fileuploads'] = $fileuploads->countCourseFiles();
        $data['course'] = $course->countAllResults();
        $indicator = $data['fileuploads'] / $data['employee'] * 100; // ตัวอย่างการคำนวณร้อยละตัวชี้วัดสะสม 

        $data['employeeList'] = $this->db->query("CALL getEmployee_UploadCourse()")->getResultArray();

        $data = [
            'title' => 'แดชบอร์ดข้อมูลฝึกอบรม | MOPH Training',
            'data_emp' => $data['employee'],
            'data_file' => $data['fileuploads'],
            'data_course' => $data['course'],
            'data_indicator' => $indicator, // ตัวอย่างค่าร้อยละตัวชี้วัดสะสม
            'workgroupList' => $workgroup->getResultArray(),
            'employeeList' => $data['employeeList'],
            'typecourse' => $typecourse
        ];
        
        // เรนเดอร์ไฟล์เนื้อหาหลัก ระบบจะดึง Layout มาสวมทับให้เอง
        return view('dashboard', $data);
    }


}