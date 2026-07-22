<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    // 🟢 หากฐานข้อมูล pbhos_training เป็น DB หลักของระบบ ใช้ 'default' ได้เลย
    // (หากตั้งค่าใน .env เป็นกลุ่มอื่น เช่น 'training' ให้เปลี่ยนค่า $DBGroup ตรงนี้ครับ)
    protected $DBGroup          = 'default';
    protected $table            = 'tr_employee'; // ตาราง tr_employee ในฐานข้อมูล pbhos_training
    protected $primaryKey       = 'emp_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // 📋 คอลัมน์ที่อนุญาตให้บันทึก/แก้ไขข้อมูลได้ ตามโครงสร้างตารางใหม่
    protected $allowedFields    = [
        'emp_id',
        'hcode',
        'cid',
        'prefix_name',
        'fullname',
        'phone',
        'position',
        'workgroup_id',
        'department_id',
        'd_update'
    ];

    // ปิด Timestamps อัตโนมัติ (ใช้ d_update ที่ส่งมาจาก Query/Controller)
    protected $useTimestamps = false;
}