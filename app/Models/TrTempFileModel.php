<?php

namespace App\Models;

use CodeIgniter\Model;

class TrTempFileModel extends Model
{
    protected $table            = 'tr_temp_file_export';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'file_id',
        'file_path', 
        'file_name',
        'course_type',
        'course_id',
        'date_create'
    ];

    // Dates & Timestamps
    protected $useTimestamps = false; // ใช้ Trigger/Default ของ MySQL (CURRENT_TIMESTAMP ON UPDATE)

    // Validation Rules (ถ้ามี)
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    /**
     * 🟢 บันทึกรายการไฟล์ชั่วคราวแบบ Bulk Insert
     * 
     * @param array $dataRows
     * @return bool
     */
    public function insertBatchTempFiles(array $dataRows): bool
    {
        if (empty($dataRows)) {
            return false;
        }

        return $this->insertBatch($dataRows) !== false;
    }

    /**
     * 🟢 ดึงข้อมูลไฟล์ตามประเภทหลักสูตรและ ID หลักสูตร
     * 
     * @param string|null $courseType
     * @param string|null $courseId
     * @return array
     */
    public function getTempFiles(?string $courseType = null, ?string $courseId = null): array
    {
        $builder = $this->builder();

        if (!empty($courseType)) {
            $builder->where('course_type', $courseType);
        }

        if (!empty($courseId)) {
            $builder->where('course_id', $courseId);
        }

        return $builder->orderBy('id', 'ASC')->get()->getResultArray();
    }

    /**
     * 🧹 เคลียร์ข้อมูล Temp ตามเงื่อนไขก่อนสร้างใหม่
     * 
     * @param string|null $courseType
     * @param string|null $courseId
     * @return bool
     */
    public function clearTempFiles(?string $courseType = null, ?string $courseId = null): bool
    {
        $builder = $this->builder();

        if (!empty($courseType)) {
            $builder->where('course_type', $courseType);
        }

        if (!empty($courseId)) {
            $builder->where('course_id', $courseId);
        }

        // หากไม่ระบุเงื่อนไขเลย จะทำการ truncate/delete ทั้งหมด
        return $builder->delete() !== false;
    }

    /**
     * 🧹 ล้างข้อมูลทั้งหมดในตารางและรีเซ็ต ID (Truncate)
     * 
     * @return bool
     */
    public function truncateTable(): bool
    {
        return $this->db->table($this->table)->truncate();
    }
}