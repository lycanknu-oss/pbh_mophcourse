<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupLevelModel extends Model
{
    protected $table            = 'group_level';
    protected $primaryKey       = 'level_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['level_name'];
}