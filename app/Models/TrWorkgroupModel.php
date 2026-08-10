<?php

namespace App\Models;

use CodeIgniter\Model;

class TrWorkgroupModel extends Model
{
    protected $table            = 'tr_workgroup';
    protected $primaryKey       = 'wg_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['wg_name'];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules      = [
        'wg_name' => 'required',
    ];
    protected $validationMessages   = [
        'wg_name' => [
            'required' => 'กรุณากรอกชื่อกลุ่มงาน'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function findWorkgroup() {
        return $this->where('wg_name !="ไม่ระบุ"')->orderBy('wg_name','ASC')->findAll();
    }
}