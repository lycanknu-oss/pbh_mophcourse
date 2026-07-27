<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EmployeeModel;
use App\Models\UploadFileModel; // 👈 เพิ่มบรรทัดนี้ครับ

class ListController extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->courseModel = new CourseModel();
        $this->uploadModel = new UploadFileModel();
        $this->employeeModel = new EmployeeModel();
    }

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

    public function getWorkgroupStats()
    {
        // ตัวอย่าง Query คำนวณเปอร์เซ็นต์ผู้ผ่านการอบรมแยกตามกลุ่มงาน
        // (ปรับเงื่อนไขคำนวณ % ตามโครงสร้าง DB ของคุณได้เลยครับ)
        $sql = $this->db->query("CALL chkWorkgroup()");

        $labels = [];
        $data = [];

        foreach ($sql->getResultArray() as $row) {
            $percent = $row['all_emp'] > 0 ? ($row['count_wg'] / $row['all_emp']) * 100 : 0; // กำหนดค่าเริ่มต้นเป็น 0 หากไม่มีข้อมูล

            $labels[] = $row['wg_name'];
            $data[] = (float)$percent; // ค่าเปอร์เซ็นต์ (0 - 100)
        }

        return $this->response->setJSON([
            'status' => 'success',
            'labels' => $labels,
            'data'   => $data
        ]);
    }

    /**
 * ⚡ AJAX Method: ดึงรายชื่อผู้ผ่านการอบรมส่งให้ DataTables
 */
public function getPassedEmployeesByLevel()
{
    $db = \Config\Database::connect();

    if (!$this->request->isAJAX()) {
        return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method Not Allowed']);
    }

    $level = $this->request->getGet('level');

    switch ($level) {
        case '1':
        case '2':
        case '3':
            $dataemplyee = $db->query("Call getEmplyee_HeadQ(?,?)", [$level, "N"])->getResultArray();
<<<<<<< HEAD
            $groupcourse = $db->query("Call getEmployee_HeadQ_Group(?,?)", [$level, "N"])->getResultArray();
            break;
        case '4':
            $dataemplyee = $db->query("Call getEmplyee_HeadQ(?,?)", ["NULL", "Y"])->getResultArray();
            $groupcourse = $db->query("Call getEmployee_HeadQ_Group(?,?)", ["NULL", "Y"])->getResultArray();
            break;        
        default:
            $dataemplyee = $db->query("Call getEmplyee_HeadQ(?,?)", ["NULL", "N"])->getResultArray();
            $groupcourse = $db->query("Call getEmployee_HeadQ_Group(?,?)", ["NULL", "N"])->getResultArray();
=======
            break;
        case '4':
            $dataemplyee = $db->query("Call getEmplyee_HeadQ(?,?)", ["NULL", "Y"])->getResultArray();
            break;
        
        default:
            $dataemplyee = $db->query("Call getEmplyee_HeadQ(?,?)", ["NULL", "N"])->getResultArray();
>>>>>>> 57e836121eb369a2b1750a67352f2bdf88aac1ee
            break;
    }

    return $this->response->setJSON([
        'status' => 'success',
<<<<<<< HEAD
        'data'   => $dataemplyee,
        'group'  => $groupcourse
=======
        'data'   => $dataemplyee
>>>>>>> 57e836121eb369a2b1750a67352f2bdf88aac1ee
    ]);
}

}