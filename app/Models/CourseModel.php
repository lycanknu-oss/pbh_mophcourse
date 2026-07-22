<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table            = 'tr_course';
    protected $primaryKey       = 'course_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // กำหนด ฟิลด์ที่อนุญาตให้บันทึก/แก้ไขได้
    protected $allowedFields    = [
        'course_id',
        'course_name',
        'course_depcription',
        'course_method',
        'course_type',
        'course_fix',
        'course_url',
        'd_update'
    ];

    // เปิดใช้งาน Timestamps (หากต้องการบันทึกเวลาสร้าง/อัปเดต)
    protected $useTimestamps = false;
}