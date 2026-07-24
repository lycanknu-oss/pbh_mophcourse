<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
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