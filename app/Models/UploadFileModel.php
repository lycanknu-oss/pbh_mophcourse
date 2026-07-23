<?php

namespace App\Models;

use CodeIgniter\Model;

class UploadFileModel extends Model
{
    protected $table            = 'uploadfile';
    protected $primaryKey       = 'file_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'file_id',
        'cid',
        'emp_id',
        'course_id',
        'file_name',
        'file_path',
        'file_type',
        'd_update'
    ];
    protected $useTimestamps    = false; // เนื่องจากเราใช้ TIMESTAMP DEFAULT ใน SQL แล้ว

    public function countCourseFiles()
    {
        return $this->groupBy('cid')
                    ->countAllResults();
    }
}