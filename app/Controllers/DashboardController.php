<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'แดชบอร์ดข้อมูลฝึกอบรม | MOPH Training'
        ];
        
        // เรนเดอร์ไฟล์เนื้อหาหลัก ระบบจะดึง Layout มาสวมทับให้เอง
        return view('dashboard', $data);
    }
}