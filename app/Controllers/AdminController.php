<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EmployeeModel;
use App\Models\UploadFileModel;
use ZipArchive; // 👈 เพิ่มบรรทัดนี้

class AdminController extends BaseController
{
    protected $db;
    protected $courseModel;
    protected $empModel;
    protected $fileModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->courseModel = new CourseModel();
        $this->empModel = new EmployeeModel();
        $this->fileModel = new UploadFileModel();
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
        return view('admin/course_download');
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
     * 📝 สร้าง/อัปเดตไฟล์ CSV ตามเงื่อนไข filter_course_type และ filter_course_name
     */
    public function generateCsv()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method Not Allowed']);
        }

        $courseType = $this->request->getGet('course_type');
        $courseName = $this->request->getGet('course_name');

        // กำหนดค่าเริ่มต้นเพื่อป้องกัน Undefined Variable
        $results = [];

        switch ($courseType) {
            case '1':
            case '2':
            case '3':
                $departType = "N";
                break;
            case '4':
                $courseType = 5;
                $departType = "Y";
                break;
            default:
                $courseType = 5;
                $departType = "N";
                break;
        }
        //$departType = ($courseType < 4) ? "N" : "Y";

        // 1. Query ข้อมูลตามเงื่อนไข
        $db = \Config\Database::connect();

        if (!empty($courseType) && empty($courseName)) {
            $results = $db->query('CALL getEmplyee_HeadQ(?,?)', [$courseType, $departType])->getResultArray();
        } elseif (!empty($courseType) && !empty($courseName)) {
            $results = $db->query('CALL getEmployee_HeadQ_LikeCourse(?,?,?)', [$courseType, $departType, $courseName])->getResultArray();
        }

        // 2. กำหนด Path และสร้างโฟลเดอร์ cache หากยังไม่มี
        $dirPath = WRITEPATH . 'cache';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filePath = $dirPath . '/export_list.csv';

        // 3. เปิดไฟล์แบบเขียนใหม่ (โหมด 'w' จะเคลียร์เนื้อหาเดิมให้อัตโนมัติโดยไม่ต้องสั่ง unlink)
        $file = @fopen($filePath, 'w');

        if ($file === false) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'ไม่สามารถเขียนไฟล์ CSV ได้ กรุณาเช็กสิทธิ์ Folder writable/cache'
            ]);
        }

        // 4. เขียน UTF-8 BOM เพื่อให้ภาษาไทยใน Excel ไม่เป็นภาษาต่างดาว
        fputs($file, "\xEF\xBB\xBF");

        // 5. เขียน Header เพียงชุดเดียว
        fputcsv($file, ['ID', 'Full Name', 'Position', 'Workgroup', 'Department', 'Course ID', 'Course Name', 'file_id', 'file_directory', 'file_name', 'Upload Date']);

        // 6. เขียนข้อมูลบุคลากรลง CSV
        foreach ($results as $row) {
            if($row['course_id'] !=''):
            fputcsv($file, [
                $row['cid'] ?? '',
                $row['fname'] ?? '',
                $row['position'] ?? '',
                $row['wg_name'] ?? '',
                $row['dp_name'] ?? '',
                $row['course_id'] ?? '',
                $row['course_name'] ?? '',
                $row['file_id'] ?? '',
                $row['file_dir'] ?? '',
                $row['file_path'] ?? '',
                $row['upload_date'] ?? ''
            ]);
            endif;
        }

        // 7. ปิดไฟล์หลังจากเขียนข้อมูลทั้งหมดเสร็จสิ้น
        fclose($file);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'อัปเดตไฟล์ export_list.csv สำเร็จ ',
            'count' => count($results),
            'data' => $results
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

        // 1️⃣ ดึง Hoscode จาก env หรือ config
        $hoscode = env('project.hoscode', '10956');

        // 2️⃣ ตั้งชื่อไฟล์ PDF ย่อย
        $courseText = !empty($courseName) ? $courseName : 'ทุกหลักสูตร';
        $cleanCourseText = preg_replace('/[^\w\s\d\p{Thai}-]/u', '', $courseText);
        $pdfFileName = "{$hoscode}_ประเภท{$courseType}_{$cleanCourseText}.pdf";

        // 3️⃣ อ่านข้อมูลจากไฟล์ cache/export_list.csv
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

            // ดึง Header มาจับคู่ Key กับ Value
            $headers = fgetcsv($handle);

            if ($headers !== FALSE) {
                // แปลง Header เป็น lowercase หรือชื่อคีย์ที่ใช้งานง่าย (เช่น 'full name' -> 'fullname', 'file path' -> 'file_path')
                $cleanHeaders = array_map(function($header) {
                    $h = strtolower(trim($header));
                    $h = str_replace([' ', '_'], '', $h);
                    return $h;
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
            return redirect()->back()->with('error', 'ไม่พบข้อมูลไฟล์ใน CSV ตามเงื่อนไข');
        }

        // 4️⃣ สร้าง Zip File ชั่วคราว
        $zip = new ZipArchive();
        $tempDir = WRITEPATH . 'uploads';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $tempZipPath = $tempDir . '/' . time() . '_export.zip';

        if ($zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return redirect()->back()->with('error', 'ไม่สามารถสร้างไฟล์ Zip ได้');
        }

        // 5️⃣ วนลูปอ่านข้อมูลบุคลากรจาก CSV แล้วสร้าง PDF ใส่ลง Zip 
            foreach ($results as $emp) {
                $fullnameRaw = $emp['fullname'] ?? $emp['full name'] ?? 'employee';
                $empName = preg_replace('/[^\w\s\d\p{Thai}-]/u', '', $fullnameRaw);
                
                // 📁 ดึง path ไฟล์จากคอลัมน์ใน CSV (ผสม file_directory + file_name)
                $fileDir  = $emp['filedirectory'] ?? $emp['file_directory'] ?? '';
                $fileName = $emp['filename'] ?? $emp['file_name'] ?? '';

                // สร้าง Full Absolute Path
                $files = [];
                if (!empty($fileDir) && !empty($fileName)) {
                    // ต่อ path เช่น WRITEPATH . 'uploads/' . directory . '/' . filename
                    $fullFilePath = FCPATH . rtrim($fileDir, '/') . '/' . $fileName; 
                    if (file_exists($fullFilePath)) {
                        $files[] = $fullFilePath;
                    }
                }

                if (empty($files)) continue;

                // รวมไฟล์ (PNG, JPG, PDF) ของคนนี้ให้เป็น 1 PDF
                $mergedPdfContent = $this->generateMergedPdfContent($files);

                if ($mergedPdfContent) {
                    $entryName = "{$empName}/{$pdfFileName}";
                    $zip->addFromString($entryName, $mergedPdfContent);
                }
            }

        $zip->close();

        // 6️⃣ ส่งออกไฟล์ Zip ให้ผู้ใช้ดาวน์โหลด
        $zipDownloadName = "{$hoscode}_Report_" . date('Ymd_His') . ".zip";
        return $this->response->download($tempZipPath, null)->setFileName($zipDownloadName);
    }

    // 📄 Helper Function: รวมรูปภาพ (JPG/PNG) และ PDF ให้เป็น 1 PDF
    private function generateMergedPdfContent(array $fileList)
    {
        // ใช้ FPDF/FPDI หรือ FPDF ธรรมดา
        // ในที่นี้สมมติโครงสร้างการแปลงรูปภาพ/PDF รวมเป็น Single Stream
        // สามารถปรับใช้ mPDF / FPDF ตาม Library ที่ท่านติดตั้งไว้
        
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

        foreach ($fileList as $index => $fileName) {
            $filePath = FCPATH . 'uploads/certificates/' . trim($fileName);
            if (!file_exists($filePath)) continue;

            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            if ($index > 0) {
                $mpdf->AddPage();
            }

            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                // ถ้ารูปภาพ ให้แสดงเต็มหน้า A4
                $mpdf->WriteHTML("<div style='text-align:center;'><img src='{$filePath}' style='max-width:100%; max-height:900px;' /></div>");
            } elseif ($ext === 'pdf') {
                // ถ้าเป็น PDF ให้ Import หน้า PDF เข้ามา
                $pageCount = $mpdf->setSourceFile($filePath);
                for ($i = 1; $i <= $pageCount; $i++) {
                    if ($i > 1 || $index > 0) $mpdf->AddPage();
                    $tplId = $mpdf->importPage($i);
                    $mpdf->useTemplate($tplId);
                }
            }
        }

        return $mpdf->Output('', 'S'); // คืนค่าเป็น String Stream
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