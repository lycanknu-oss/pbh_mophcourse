<?php

namespace App\Models;

use CodeIgniter\Model;

class TrDepartModel extends Model
{
    protected $table            = 'tr_department';
    protected $primaryKey       = 'dp_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['dp_name'];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules      = [
        'dp_name' => 'required',
    ];
    protected $validationMessages   = [
        'dp_name' => [
            'required' => 'กรุณากรอกชื่อฝ่าย/งาน'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}