<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EmployeeModel;
use App\Models\UploadFileModel;
use App\Models\TrTempFileModel;
use App\Models\TrWorkgroupModel;

use Mpdf\Mpdf;
use ZipArchive; // 👈 เพิ่มบรรทัดนี้

class AdminController extends BaseController
{
    protected $db;
    protected $courseModel;
    protected $empModel;
    protected $fileModel;
    protected $tempFileModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->courseModel = new CourseModel();
        $this->empModel = new EmployeeModel();
        $this->fileModel = new UploadFileModel();
        $this->tempFileModel = new TrTempFileModel();
    }

    private function checkAdminAuth()
    {
        if (!session()->get('isLoggedIn')) {
            return false;
        }
        $role = session()->get('permiss') ?? session()->get('role');
        return strtolower($role) === 'admin';
    }

    public function dashboard()
    {
        $employee = new \App\Models\EmployeeModel();
        $fileuploads = new \App\Models\UploadFileModel();
        $course = new \App\Models\CourseModel();

        if (!$this->checkAdminAuth()) {
            return redirect()->to(base_url('auth/login'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $workgroup = $this->db->query("CALL chkWorkgroup()");

        $data['employee'] = $employee->countAllResults();
        $data['fileuploads'] = $fileuploads->countCourseFiles();
        $data['course'] = $course->countAllResults();

        $data = [
            'title' => 'แผงควบคุมผู้ดูแลระบบ | MOPH Admin',
            'data_emp' => $data['employee'],
            'data_file' => $data['fileuploads'],
            'data_course' => $data['course'],
            'workgroupList' => $workgroup->getResultArray()
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * 📚 หน้าแสดงรายการหลักสูตร
     */
    public function courses()
    {
        if (!$this->checkAdminAuth()) {
            return redirect()->to(base_url('auth/login'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $courses = $this->courseModel->orderBy('course_type', 'ASC')
                                     ->orderBy('course_id', 'DESC')
                                     ->findAll();

        $data = [
            'title'   => 'จัดการข้อมูลหลักสูตรอบรม | MOPH Admin',
            'courses' => $courses
        ];

        return view('admin/courses', $data);
    }

    /**
     * 💾 บันทึก/แก้ไขข้อมูลหลักสูตร (AJAX)
     */
    public function saveCourse()
    {
        if (!$this->checkAdminAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่มีสิทธิ์ในการจัดการข้อมูล']);
        }

        $course_id = $this->request->getPost('course_id');
        $course_id = (!empty($course_id) && is_numeric($course_id)) ? (int)$course_id : null;

        // รับค่า course_type เป็น Array จาก Multiple Checkbox
        $courseTypes = $this->request->getPost('course_type');

        if (empty($courseTypes)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาเลือกระดับการอบรมอย่างน้อย 1 รายการ']);
        }

        // แปลงให้แน่ใจว่าค่าที่ได้เป็น Array สม่ำเสมอ
        if (!is_array($courseTypes)) {
            $courseTypes = [$courseTypes];
        }

        $commonData = [
            'course_name'        => $this->request->getPost('course_name'),
            'course_depcription' => $this->request->getPost('course_depcription'),
            'course_method'      => $this->request->getPost('course_method'),
            'course_fix'         => $this->request->getPost('course_fix') ?? '1', // 🟢 เปลี่ยนตรงนี้
            'course_url'         => $this->request->getPost('course_url'),
            'd_update'           => date('Y-m-d H:i:s')
        ];

        if ($course_id !== null) {
            // 🟢 กรณีแก้ไขข้อมูลเดิม (Update) -> ปรับอัปเดตเฉพาะแถวนั้นโดยใช้ course_type ตัวแรกที่เลือก
            $updateData = array_merge($commonData, [
                'course_type' => $courseTypes[0]
            ]);
            
            $result = $this->courseModel->update($course_id, $updateData);
            $msg = 'อัปเดตข้อมูลหลักสูตรเรียบร้อยแล้ว';
        } else {
            // 🟢 กรณีเพิ่มข้อมูลใหม่ (Insert Batch) -> วนลูปสร้างชุดข้อมูลแยกตามแต่ละ course_type
            $batchData = [];
            foreach ($courseTypes as $type) {
                $batchData[] = array_merge($commonData, [
                    'course_type' => $type
                ]);
            }

            // สั่ง insertBatch เพิ่มข้อมูลทีเดียวหลายแถว
            $result = $this->courseModel->insertBatch($batchData);
            $msg = 'บันทึกข้อมูลหลักสูตรใหม่สำหรับ ' . count($batchData) . ' ระดับการอบรม เรียบร้อยแล้ว';
        }

        if ($result) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => $msg
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
    }

    public function courseDetails()
    {
        if (!$this->checkAdminAuth()) {
            return redirect()->to(base_url('auth/login'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $data['employeeList'] = $this->db->query("CALL getEmployee_UploadCourse()")->getResultArray();

        $data = [
            'title' => 'รายงานผลการอบรม | MOPH Admin', 
            'employeeList' => $data['employeeList']
        ];

         return view('admin/course_reports', $data);
    }

    public function courseDownloads()
    {
        $workgroup = new TrWorkgroupModel();

        $data['wgList'] = $workgroup->findWorkgroup();

        return view('admin/course_download',$data);
    }

    /**
     * 🗑️ ลบข้อมูลหลักสูตร (รองรับทั้ง POST 'course_id' และ 'id')
     */
    public function deleteCourse($course_id = null)
    {
        if (!$this->checkAdminAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่มีสิทธิ์ในการทำรายการนี้']);
        }

        if (empty($course_id)) {
            $course_id = $this->request->getPost('course_id') ?? $this->request->getPost('id');
        }

        if (empty($course_id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบ ID ของหลักสูตรที่ต้องการลบ']);
        }

        // สั่งลบโดยอ้างอิงจาก primaryKey ('course_id')
        if ($this->courseModel->delete($course_id)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'ลบข้อมูลหลักสูตรเรียบร้อยแล้ว'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลหลักสูตรได้']);
    }

   /**
     * 📝 สร้าง/อัปเดตไฟล์ CSV ตามเงื่อนไข (รองรับทั้ง course_type และ workgroup)
     */
    public function generateCsv()
    {
        $tempModel = new TrTempFileModel();

        // 1. รับค่าจาก Request (รองรับทั้ง GET และ POST)
        $filterType = $this->request->getVar('filter_type'); // 'course' หรือ 'workgroup'
        $courseType = $this->request->getVar('course_type');
        $courseName = $this->request->getVar('course_name');
        $workgroup  = $this->request->getVar('workgroup');
        $department = $this->request->getVar('department');

        $results = [];
        $file_mpdf_name = "รายงานผลการอบรม";

        // 2. ดึงข้อมูลจาก Stored Procedure ตามประเภท Filter
        if ($filterType === 'workgroup' || !empty($workgroup)) {
            // 🔹 กรณีมาจากกลุ่มงาน/ฝ่าย (#filter_workgroup / #filter_department)
            $results = $this->db->query(
                'CALL getEmployee_ByWorkgroup_Csv(?,?,?)', 
                [$workgroup, $department, "Y"]
            )->getResultArray();

            $file_mpdf_name = "หลักสูตรอบรมตามกลุ่มงาน_ฝ่าย";
        } else {
            // 🔹 กรณีมาจากระดับ/หัวข้อหลักสูตร (#filter_course_type / #filter_course_name)
            switch ($courseType) {
                case '1': 
                    $departType = "N";
                    $file_mpdf_name = "หลักสูตรอบรมระดับผู้อำนวยการ";                  
                    break;
                case '2': 
                    $departType = "N";
                    $file_mpdf_name = "หลักสูตรอบรมระดับรองผู้อำนวยการ"; 
                    break;
                case '3': 
                    $departType = "N";
                    $file_mpdf_name = "หลักสูตรอบรมระดับหัวหน้ากลุ่มงาน"; 
                    break;
                case '4': 
                    $courseType = 5;
                    $departType = "Y";
                    $file_mpdf_name = "หลักสูตรอบรมระดับหัวหน้างาน"; 
                    break;
                case '5': 
                default: 
                    $courseType = 5;
                    $departType = "N";
                    $file_mpdf_name = "หลักสูตรอบรมระดับเจ้าหน้าที่"; 
                    break;
            }

            $results = $this->db->query(
                'CALL getEmployee_exportCSv(?,?,?)', 
                [$courseType, $departType, $courseName]
            )->getResultArray();
        }

        // 3. กำหนด Path และสร้างโฟลเดอร์ cache หากยังไม่มี
        $dirPath = WRITEPATH . 'cache';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filePath = $dirPath . '/export_list.csv';

        // 4. เปิดไฟล์เพื่อเขียนข้อมูลใหม่ (โหมด 'w')
        $file = @fopen($filePath, 'w');

        if ($file === false) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'ไม่สามารถเขียนไฟล์ CSV ได้ กรุณาเช็กสิทธิ์ Folder writable/cache'
            ]);
        }

        // 5. เขียน UTF-8 BOM สำหรับป้องกันภาษาไทยต่างดาวใน Excel
        fputs($file, "\xEF\xBB\xBF");

        // 6. เขียน Header Row
        fputcsv($file, [
            'ID', 
            'Full Name', 
            'Position', 
            'Workgroup', 
            'Department', 
            'Course ID', 
            'Course Name', 
            'file_id', 
            'file_directory', 
            'file_name', 
            'Upload Date', 
            'file_pdf_name'
        ]);
        
        // 7. เคลียร์ข้อมูลเดิมในตาราง Temp
        $tempModel->truncateTable(); 
        $dataToInsert = [];

        // 8. วนลูปเขียนข้อมูลบุคลากรลงไฟล์ CSV และเตรียมข้อมูลสำหรับ Temp Model
        foreach ($results as $row) {
            $cId   = $row['course_id'] ?? '';
            $fId   = $row['file_id'] ?? '';
            $fDir  = $row['file_dir'] ?? '';
            $fPath = $row['file_path'] ?? '';

            if (!empty($cId)) {
                $dataToInsert[] = [ 
                    'file_id'     => $fId,
                    'file_path'   => $fDir, 
                    'file_name'   => $fPath,
                    'course_type' => $courseType ?? 0,
                    'course_id'   => $cId
                ];
            }

            fputcsv($file, [
                $row['cid'] ?? '',
                $row['fname'] ?? '',
                $row['position'] ?? '',
                $row['wg_name'] ?? '',
                $row['dp_name'] ?? '',
                $cId,
                $row['course_name'] ?? '',
                $fId,
                $fDir,
                $fPath,
                $row['upload_date'] ?? '',
                $file_mpdf_name
            ]);
        }

        // 9. บันทึกข้อมูล Temp Data เข้าฐานข้อมูล (กรณีมีรายการไฟล์)
        if (!empty($dataToInsert)) {
            $tempModel->insertBatchTempFiles($dataToInsert);
        }

        // 10. ปิดไฟล์ CSV
        fclose($file);

        // 11. ส่ง Response JSON กลับไปที่ AJAX
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'อัปเดตไฟล์ export_list.csv สำเร็จ',
            'count'   => count($results),
            'data'    => $results
        ]);
    }

    /**
     * 🏢 AJAX ดึงรายการ Department ตาม Workgroup ID
     */
    public function getDepartmentsByWorkgroup()
    {
        $wgId = $this->request->getVar('wg_id');

        if (empty($wgId)) {
            // 🛠️ เปลี่ยนจาก $db เป็น $this->db
            $departments = $this->db->table('tr_department')->get()->getResultArray();
        } else {
            // 🛠️ เปลี่ยนจาก $db เป็น $this->db
            $departments = $this->db->table('tr_department')
                            ->where('wg_id', $wgId)
                            ->get()->getResultArray();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $departments
        ]);
    }

    /**
     * 📊 อ่านไฟล์ export_list.csv ส่งกลับเป็น JSON สำหรับ DataTables
     */
    public function getExportCsvData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method Not Allowed']);
        }

        $csvPath = WRITEPATH . 'cache/export_list.csv';

        if (!file_exists($csvPath)) {
            return $this->response->setJSON(['status' => 'success', 'data' => []]);
        }

        $data = [];
        if (($handle = fopen($csvPath, 'r')) !== FALSE) {
            // ข้าม UTF-8 BOM
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            $headers = fgetcsv($handle);
            if ($headers !== FALSE) {
                // Clean headers ให้เป็น key ภาษาอังกฤษ
                $cleanHeaders = array_map(function($h) {
                    return strtolower(str_replace([' ', '_'], '', trim($h)));
                }, $headers);

                while (($row = fgetcsv($handle)) !== FALSE) {
                    if (count($cleanHeaders) === count($row)) {
                        $data[] = array_combine($cleanHeaders, $row);
                    }
                }
            }
            fclose($handle);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    // 🚀 API / Process สำหรับสร้างและดาวน์โหลด Zip
    public function exportZip()
    {
        $request = $this->request->getPost();
        
        $courseType = $request['course_type'] ?? '';
        $courseName = $request['course_name'] ?? '';

        switch ($courseType) {
            case '1': $filecours ="หลักสูตรอบรมระดับผู้อำนวยการ"; break;
            case '2': $filecours ="หลักสูตรอบรมระดับรองผู้อำนวยการ"; break;
            case '3': $filecours ="หลักสูตรอบรมระดับหัวหน้ากลุ่มงาน"; break;
            case '4': $filecours ="หลักสูตรอบรมระดับหัวหน้างาน"; break;
            case '5': $filecours ="หลักสูตรอบรมระดับเจ้าหน้าที่"; break;
            default: $filecours ="หลักสูตรอบรมระดับเจ้าหน้าที่"; break;
        }

        $fullfilename = $filecours . ($courseName ? '_' . $courseName : '') . "_" . date('Ymd_His') . ".zip";

        // 1️⃣ ดึง Hoscode จาก env หรือ config
        $hoscode = env('project.hoscode', '10956');

        // 2️⃣ อ่านข้อมูลจากไฟล์แคช cache/export_list.csv
        $csvPath = WRITEPATH . 'cache/export_list.csv';

        if (!file_exists($csvPath)) {
            return redirect()->back()->with('error', 'ไม่พบไฟล์ข้อมูลแคช กรุณาทำการกรองข้อมูลใหม่อีกครั้ง');
        }

        $results = [];
        if (($handle = fopen($csvPath, 'r')) !== FALSE) {
            // ข้าม UTF-8 BOM (ถ้ามี)
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            $headers = fgetcsv($handle);
            if ($headers !== FALSE) {
                // Clean headers เป็นคีย์ภาษาอังกฤษแบบตัวพิมพ์เล็ก (ไม่มีเว้นวรรค/ขีดล่าง)
                $cleanHeaders = array_map(function($h) {
                    return strtolower(str_replace([' ', '_'], '', trim($h)));
                }, $headers);

                while (($row = fgetcsv($handle)) !== FALSE) {
                    if (count($cleanHeaders) === count($row)) {
                        $results[] = array_combine($cleanHeaders, $row);
                    }
                }
            }
            fclose($handle);
        }

        if (empty($results)) {
            return redirect()->back()->with('error', 'ไม่พบรายการข้อมูลในไฟล์แคช');
        }

        // 3️⃣ เตรียมโฟลเดอร์และตั้งชื่อไฟล์ ZIP ชั่วคราว
        $uploadDir = WRITEPATH . 'uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $tempZipPath = $uploadDir . '/' . time() . '_export.zip';

        $zip = new \ZipArchive();
        if ($zip->open($tempZipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            return redirect()->back()->with('error', 'ไม่สามารถสร้างไฟล์ ZIP ได้ กรุณาเช็กสิทธิ์โฟลเดอร์ uploads');
        }

        $hasFiles = false;

        // 4️⃣ วนลูปอ่านข้อมูลบุคลากรและแนบไฟล์ต้นทางลง ZIP
        foreach ($results as $emp) {
            $fullnameRaw = $emp['fullname'] ?? $emp['full name'] ?? 'employee';
            $empName = preg_replace('/[^\w\s\d\p{Thai}-]/u', '', $fullnameRaw);
            
            $fileDir  = $emp['filedirectory'] ?? $emp['file_directory'] ?? '';
            $fileName = $emp['filename'] ?? $emp['file_name'] ?? '';

            if (empty($fileName)) {
                continue;
            }

            // จัดระเบียบ Path
            $cleanDir = trim($fileDir, '/\\');
            
            // รายการ Candidate Paths ที่อาจเก็บไฟล์จริงไว้
            $pathCandidates = [
                FCPATH . $cleanDir . '/' . $fileName,                     // public/uploads/...
                WRITEPATH . $cleanDir . '/' . $fileName,                    // writable/uploads/...
                FCPATH . 'uploads/' . $cleanDir . '/' . $fileName,         
                WRITEPATH . 'uploads/' . $cleanDir . '/' . $fileName,
                FCPATH . $fileName,
                WRITEPATH . 'uploads/' . $fileName
            ];

            $foundFilePath = null;
            foreach ($pathCandidates as $candidate) {
                if (file_exists($candidate) && is_file($candidate)) {
                    $foundFilePath = $candidate;
                    break;
                }
            }

            if ($foundFilePath) {
                // 📁 กำหนดโครงสร้างไฟล์ใน ZIP: "ชื่อบุคลากร/ชื่อไฟล์เดิม"
                $entryName = "{$empName}/{$fileName}";
                
                // แนบไฟล์ต้นทางเข้าไปใน ZIP โดยตรง (ไม่ต้องแปลงเป็น PDF)
                if ($zip->addFile($foundFilePath, $entryName)) {
                    $hasFiles = true;
                }
            } else {
                log_message('error', "ExportZip: ไม่พบไฟล์ต้นทางสำหรับ {$empName} ({$fileName})");
            }
        }

        // 5️⃣ ปิดไฟล์ ZIP
        $zip->close();

        // 🛑 6️⃣ ตรวจสอบความถูกต้องของไฟล์ ZIP ก่อนส่งดาวน์โหลด
        if (!$hasFiles || !file_exists($tempZipPath) || filesize($tempZipPath) === 0) {
            if (file_exists($tempZipPath)) {
                @unlink($tempZipPath);
            }
            return redirect()->back()->with('error', 'ไม่พบไฟล์แนบต้นทางในระบบ ไม่สามารถสร้าง ZIP ได้');
        }

        // 7️⃣ ส่งออกไฟล์ ZIP ให้ผู้ใช้ดาวน์โหลด
        $zipDownloadName = "{$hoscode}_".$fullfilename;
        return $this->response->download($tempZipPath, null)->setFileName($zipDownloadName);
    }

    // 📄 Helper Function: รวมรูปภาพ (JPG/PNG) และ PDF ให้เป็น 1 PDF
    /**
     * 🟢 ฟังก์ชันสำหรับสร้างและส่งออก PDF รวม
     */
    public function exportPdf()
    {
        $request = $this->request->getPost();

        $courseType = $this->request->getPost('course_type') ?? '';
        $courseName = $this->request->getPost('course_name') ?? '';

        switch ($courseType) {
            case '1': $filecours ="หลักสูตรอบรมระดับผู้อำนวยการ"; break;
            case '2': $filecours ="หลักสูตรอบรมระดับรองผู้อำนวยการ"; break;
            case '3': $filecours ="หลักสูตรอบรมระดับหัวหน้ากลุ่มงาน"; break;
            case '4': $filecours ="หลักสูตรอบรมระดับหัวหน้างาน"; break;
            case '5': $filecours ="หลักสูตรอบรมระดับเจ้าหน้าที่"; break;
            default: $filecours ="หลักสูตรอบรมระดับเจ้าหน้าที่"; break;
        }

        $hoscode = env('project.hoscode', '10956');

        // 1️⃣ อ่านข้อมูลจากไฟล์แคช cache/export_list.csv
        $csvPath = WRITEPATH . 'cache/export_list.csv';
        if (!file_exists($csvPath)) {
            return redirect()->back()->with('error', 'ไม่พบไฟล์ข้อมูลแคช กรุณาทำการกรองข้อมูลใหม่อีกครั้ง');
        }

        $results = [];
        if (($handle = fopen($csvPath, 'r')) !== FALSE) {
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            $headers = fgetcsv($handle);
            if ($headers !== FALSE) {
                $cleanHeaders = array_map(function($h) {
                    return strtolower(str_replace([' ', '_'], '', trim($h)));
                }, $headers);

                while (($row = fgetcsv($handle)) !== FALSE) {
                    if (count($cleanHeaders) === count($row)) {
                        $results[] = array_combine($cleanHeaders, $row);
                    }
                }
            }
            fclose($handle);
        }

        if (empty($results)) {
            return redirect()->back()->with('error', 'ไม่พบรายการข้อมูลในไฟล์แคช');
        }

        // 2️⃣ ค้นหาไฟล์แนบต้นทางทั้งหมดตามรายการใน CSV
        $allFiles = [];
        foreach ($results as $emp) {
            $fileDir  = $emp['filedirectory'] ?? $emp['file_directory'] ?? '';
            $fileName = $emp['filename'] ?? $emp['file_name'] ?? '';
            $file_pdf_name = $emp['file_pdf_name'] ?? '';

            if (empty($fileName)) {
                continue;
            }

            $cleanDir = trim($fileDir, '/\\');
            $pathCandidates = [
                FCPATH . $cleanDir . '/' . $fileName,                     // public/uploads/...
                WRITEPATH . $cleanDir . '/' . $fileName,                    // writable/uploads/...
                FCPATH . 'uploads/' . $cleanDir . '/' . $fileName,
                WRITEPATH . 'uploads/' . $cleanDir . '/' . $fileName,
                FCPATH . $fileName,
                WRITEPATH . 'uploads/' . $fileName
            ];

            foreach ($pathCandidates as $candidate) {
                if (file_exists($candidate) && is_file($candidate)) {
                    $allFiles[] = $candidate;
                    break;
                }
            }
        }

        if (empty($allFiles)) {
            return redirect()->back()->with('error', 'ไม่พบไฟล์แนบต้นทางในระบบ');
        }

        // 3️⃣ ประมวลผลรวมไฟล์ด้วย mPDF
        $pdfContent = $this->generateMergedPdfContent($allFiles);

        if (!$pdfContent) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการสร้างไฟล์ PDF ด้วย mPDF');
        }

        // 4️⃣ บันทึกเป็นไฟล์ชั่วคราวเพื่อเตรียมส่งออก
        $uploadDir = WRITEPATH . 'uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $tempPdfPath = $uploadDir . '/' . time() . '_merged_export.pdf';
        file_put_contents($tempPdfPath, $pdfContent);

        $pdfDownloadName = "{$hoscode}_{$filecours}_". date('Ymd_His') . ".pdf";

        // ส่งดาวน์โหลด (ใช้ setFileName เพื่อกำหนดชื่อไฟล์ให้ผู้ใช้)
        return $this->response->download($tempPdfPath, null)->setFileName($pdfDownloadName);
    }

    /**
     * 🟢 ฟังก์ชันรวมไฟล์เอกสารและรูปภาพเป็น PDF ไบนารีด้วย mPDF
     */
    private function generateMergedPdfContent(array $filePaths): ?string
    {
        if (empty($filePaths)) {
            return null;
        }

        try {
            // ตั้งค่า mPDF รองรับการรวมไฟล์ และรองรับ UTF-8 / ภาษาไทย
           $mpdf = new Mpdf([
                        'mode'          => 'utf-8',
                        'format'        => 'A4-L', // 👈 เพิ่ม -L ต่อท้าย A4 เพื่อกำหนดเป็น Landscape
                        'margin_left'   => 10,
                        'margin_right'  => 10,
                        'margin_top'    => 10,
                        'margin_bottom' => 10,
                        'tempDir'       => WRITEPATH . 'cache'
                    ]);

            $pageCountAdded = 0;

            foreach ($filePaths as $filePath) {
                if (!file_exists($filePath)) {
                    continue;
                }

                $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                // 📄 1. กรณีเป็นไฟล์ PDF
                if ($ext === 'pdf') {
                    try {
                        // mPDF 8.x + FPDI ในตัว
                        $pageCount = $mpdf->setSourceFile($filePath);

                        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                            if ($pageCountAdded > 0) {
                                $mpdf->AddPage('L', '', '', '', '', 10, 10, 10, 10); // L, orientation, ..., margins
                            }

                            $templateId = $mpdf->importPage($pageNo);
                            $mpdf->useTemplate($templateId);
                            $pageCountAdded++;
                        }
                    } catch (\Exception $e) {
                        log_message('error', "mPDF PDF Import Error ({$filePath}): " . $e->getMessage());
                    }
                } 
                // 🖼️ 2. กรณีเป็นไฟล์รูปภาพ (JPG, PNG, GIF)
                elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                    if ($pageCountAdded > 0) {
                        $mpdf->AddPage('L', '', '', '', '', 10, 10, 10, 10); // L, orientation, ..., margins
                    }

                    // สร้าง HTML จัดวางรูปภาพกึ่งกลางหน้า A4 พร้อมจำกัดขนาดไม่ให้เกินหน้า
                    $html = '
                    <div style="width: 100%; text-align: center;">
                        <img src="' . $filePath . '" style="max-width: 100%; max-height: 200mm; height: auto;" />
                    </div>
                    ';

                    $mpdf->WriteHTML($html);
                    $pageCountAdded++;
                }
            }

            if ($pageCountAdded === 0) {
                return null;
            }

            // ส่งออกเนื้อหา PDF เป็น Binary String
            return $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);

        } catch (\Exception $e) {
            log_message('error', 'generateMergedPdfContent Exception: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteFile()
    {
        $request  = $this->request->getPost();
        $recordId = $request['id'] ?? null;
        $fileName = $request['file_name'] ?? null;

        if (empty($recordId)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ไม่พบข้อมูล ID รายการที่ต้องการลบ'
            ]);
        }

        try {
            // 1. ลบไฟล์จริงในเครื่อง ( Physical File )
            if (!empty($fileName)) {
                $filePathCandidates = [
                    FCPATH . 'uploads/certificates/' . $fileName,
                    WRITEPATH . 'uploads/certificates/' . $fileName,
                    FCPATH . 'uploads/' . $fileName,
                    WRITEPATH . 'uploads/' . $fileName
                ];

                foreach ($filePathCandidates as $filePath) {
                    if (file_exists($filePath) && is_file($filePath)) {
                        @unlink($filePath); // ลบไฟล์ออกจากดิสก์
                        break;
                    }
                }
            }

            // 2. เคลียร์ชื่อไฟล์ในฐานข้อมูล (ปรับชื่อ Model/ตาราง ตามระบบของคุณ)
            $db = \Config\Database::connect();
            
            // ตัวอย่าง: อัปเดตฟิลด์ file_name/file_path ในตารางหลักให้เป็น NULL หรือลบทั้ง Record
            $db->table('your_table_name')
            ->where('id', $recordId)
            ->update([
                'file_name' => null,
                'file_path' => null
            ]);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'ลบไฟล์สำเร็จเรียบร้อยแล้ว'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'deleteFile Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'เกิดข้อผิดพลาดจากเซิร์ฟเวอร์: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 👥 หน้าแสดงรายการพนักงาน
     */
    public function employees()
    {
        if (!$this->checkAdminAuth()) {
            return redirect()->to(base_url('auth/login'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        if($this->db->query("CALL UpdateEmployeeData()")) {
            // เรียกใช้งาน Stored Procedure สำเร็จ
            $employees = $this->empModel->select('tr_employee.*, tr_department.dp_name, tr_workgroup.wg_name')
                                        ->join('tr_department', 'tr_employee.department_id = tr_department.dp_id', 'left')
                                        ->join('tr_workgroup', 'tr_employee.workgroup_id = tr_workgroup.wg_id', 'left') 
                                        ->orderBy('tr_department.dp_id', 'ASC')
                                        ->orderBy('tr_workgroup.wg_id', 'ASC')
                                        ->findAll();
        } else {
            // เกิดข้อผิดพลาดในการเรียกใช้งาน Stored Procedure
            $error = $this->db->error();
            log_message('error', 'Error calling stored procedure UpdateEmployeeData: ' . $error['message']);
        }

        $data = [
            'title'     => 'จัดการข้อมูลพนักงาน | iMeeting System',
            'employees' => $employees
        ];

        return view('admin/employees', $data);
    }

    /**
     * 💾 บันทึก / แก้ไขข้อมูลพนักงาน (AJAX)
     */
    public function saveEmployee()
    {
        if (!$this->checkAdminAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่มีสิทธิ์ทำรายการ']);
        }

        $emp_id = $this->request->getPost('emp_id');
        $emp_id = (!empty($emp_id) && is_numeric($emp_id)) ? (int)$emp_id : null;

        $data = [
            'hcode'     => $this->request->getPost('hcode'),
            'cid'       => $this->request->getPost('cid'),
            'fname'     => $this->request->getPost('fname'),
            'position'  => $this->request->getPost('position'),
            'depart'    => $this->request->getPost('depart'),
            'workgroup' => $this->request->getPost('workgroup'),
            'd_update'  => date('Y-m-d H:i:s')
        ];

        if ($emp_id !== null) {
            $result = $this->empModel->update($emp_id, $data);
            $msg = 'อัปเดตข้อมูลพนักงานเรียบร้อยแล้ว';
        } else {
            $result = $this->empModel->insert($data);
            $msg = 'บันทึกข้อมูลพนักงานใหม่เรียบร้อยแล้ว';
        }

        if ($result) {
            return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
    }

    /**
     * 🗑️ ลบข้อมูลพนักงาน (AJAX)
     */
    public function deleteEmployee($emp_id = null)
    {
        if (!$this->checkAdminAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่มีสิทธิ์ทำรายการ']);
        }

        if (empty($emp_id)) {
            $emp_id = $this->request->getPost('emp_id') ?? $this->request->getPost('id');
        }

        if (empty($emp_id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบ ID พนักงานที่ต้องการลบ']);
        }

        if ($this->empModel->delete($emp_id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบข้อมูลพนักงานเรียบร้อยแล้ว']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
    }

    /**
     * 🧹 ล้างแคชระบบ และ Destroy PHP Session
     */
    public function clearCacheSystem()
    {
        // 1. ล้าง Cache ของ CodeIgniter 4 (ถ้ามีการใช้งาน cache)
        cache()->clean();

        // 2. ลบ Flashdata / Session ฝั่ง Server (ถ้าต้องการให้ออกจากระบบด้วยให้ใช้ session()->destroy())
        // ในที่นี้ลบเฉพาะ Session ที่เกี่ยวข้องกับตัวแปรชั่วคราว
        session()->remove('popupSeen');
        
        // หากต้องการทำลาย PHP Session ทั้งหมดจริง ๆ (Logout ผู้ใช้):
        // session()->destroy();

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'ล้างแคชและรีเซ็ตระบบเรียบร้อยแล้ว'
        ]);
    }
}