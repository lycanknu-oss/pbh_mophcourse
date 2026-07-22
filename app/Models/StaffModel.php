<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $table            = 'staff';
    protected $primaryKey       = 'staff_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['provider_id', 'fullname', 'position', 'level_id'];
}