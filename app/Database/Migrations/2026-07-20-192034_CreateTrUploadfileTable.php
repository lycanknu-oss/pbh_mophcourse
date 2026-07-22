<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrUploadfileTable extends Migration
{
    // เมธอดสำหรับสร้าง/ปรับปรุงตาราง
    public function up()
    {
        $this->forge->addField([
            'file_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'cid' => [
                'type'       => 'VARCHAR',
                'constraint' => '13',
                'null'       => true,
            ],
            'emp_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'course_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'file_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'd_update' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // กำหนด Primary Key
        $this->forge->addKey('file_id', true);

        // กำหนด Foreign Keys (ถ้ามี)
        $this->forge->addForeignKey('course_id', 'tr_course', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('emp_id', 'tr_emloyee', 'emp_id', 'SET NULL', 'CASCADE');

        // สร้างตาราง
        $this->forge->createTable('tr_uploadfile', true);
    }

    // เมธอดสำหรับยกเลิก/ลบตาราง (ใช้เมื่อ Rollback)
    public function down()
    {
        $this->forge->dropTable('tr_uploadfile', true);
    }
}