<?php

namespace App\Controllers;

use App\Models\CourseModel; 
use App\Models\UploadFileModel;
use App\Models\EmployeeModel;

class CourseController extends BaseController
{
    protected $courseModel;
    protected $uploadModel;
    protected $employeeModel;   

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->uploadModel = new UploadFileModel();
        $this->employeeModel = new EmployeeModel();
    }

    public function index()
    {
        // ดึงข้อมูลหลักสูตรทั้งหมด โดยเรียงตามลำดับและประเภท
        $courses = $this->courseModel->orderBy('course_type', 'ASC')
                                     ->orderBy('course_id', 'ASC')
                                     ->findAll();

        $data = [
            'title'   => 'หลักสูตรการอบรม Digital Health',
            'courses' => $courses
        ];

        return view('training/course_main', $data);
    }

    // 1️⃣ หน้า Uploads
    public function upload()
    {
        $data = [
            'title'   => 'ระบบอัปโหลดใบรับรอง (Certificate Upload)',
            'courses' => $this->courseModel->orderBy('course_type', 'ASC')->findAll()
        ];

        return view('training/upload', $data);
    }

    // 2️⃣ AJAX บันทึกการอัปโหลดไฟล์
    public function saveUpload()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method Not Allowed']);
        }

        $validationRule = [
            'course_id' => 'required|numeric',
            'cert_file' => [
                'label' => 'Certificate File',
                'rules' => 'uploaded[cert_file]|max_size[cert_file,5120]|ext_in[cert_file,pdf,png,jpg,jpeg]',
            ],
        ];

        if (!$this->validate($validationRule)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $this->validator->getErrors()
            ]);
        }

        $file     = $this->request->getFile('cert_file');
        $courseId = $this->request->getPost('course_id');
        $empName  = $this->request->getPost('emp_name'); // รับจาก Autocomplete

        // ค้นหา emp_id และ cid จากชื่อบุคลากร (ถ้ามี)
        $empData = null;
        if (!empty($empName)) {
            $empData = $this->employeeModel->like('fullname', trim($empName))->first();
        }

        if ($file->isValid() && !$file->hasMoved()) {
            // ตั้งชื่อไดเรกทอรีจัดเก็บ
            $uploadDirectory = 'uploads/certificates/' . date('Ym') . '/';
            $targetPath      = WRITEPATH . $uploadDirectory;

            // สุ่มชื่อไฟล์ใหม่เพื่อป้องกันชื่อซ้ำ
            $newName = $file->getRandomName();
            $file->move($targetPath, $newName);

            // บันทึกลงตาราง tr_uploadfile
            $saveData = [
                'cid'       => $empData['cid'] ?? null,
                'emp_id'    => $empData['emp_id'] ?? null,
                'course_id' => $courseId,
                'file_name' => $newName,
                'file_path' => $uploadDirectory,
                'file_type' => $file->getClientMimeType(),
                'd_update'  => date('Y-m-d H:i:s')
            ];

            $this->uploadModel->insert($saveData);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'อัปโหลดหลักฐานการอบรมเรียบร้อยแล้ว'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'ไม่สามารถย้ายไฟล์ไปยังโฟลเดอร์ปลายทางได้'
        ]);
    }

    // app/Controllers/CourseController.php

    public function searchEmployee()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([]);
        }

        $keyword = trim($this->request->getGet('term') ?? $this->request->getGet('keyword') ?? '');

        if (empty($keyword)) {
            return $this->response->setJSON([]);
        }

        // ค้นหา fullname LIKE %keyword% OR cid LIKE %keyword%
        $results = $this->employeeModel
                        ->groupStart()
                            ->like('fullname', $keyword)
                            ->orLike('cid', $keyword)
                        ->groupEnd()
                        ->limit(10)
                        ->findAll();

        // จัดรูปแบบข้อมูลสำหรับ Autocomplete
        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'label'    => $row['fullname'] . ' (CID: ' . $row['cid'] . ')',
                'value'    => $row['fullname'],
                'emp_id'   => $row['emp_id'],
                'cid'      => $row['cid'],
                'fullname' => $row['fullname'],
                'prefix' => $row['prefix_name'],
                'position' => $row['position']
            ];
        }

        return $this->response->setJSON($data);
    }
}