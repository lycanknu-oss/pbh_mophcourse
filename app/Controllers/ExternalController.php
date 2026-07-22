<?php

namespace App\Controllers;

class ExternalController extends BaseController
{
    // 1️⃣ Redirect ไปยัง URL ภายนอกแบบระบุปลายทางตรงๆ (Hardcoded)
    public function registerManual()
    {
        // เช่น ลิงก์ไปยัง Google Docs หรือ PDF คู่มือภายนอก
        $url = 'https://mwg.moph.go.th/ln/regis.pdf';
        
        return redirect()->to($url);
    }

    public function providerIdHelp()
    {
        // เช่น ลิงก์ไปยังระบบช่วยเหลือ Provider ID ของ สป.สธ.
        $url = 'https://mwg.moph.go.th/ln/forgot_pin.pdf';
        
        return redirect()->to($url);
    }

    // 2️⃣ Dynamic Redirect: รับ Parameter หรือ Query String เพื่อเปิดลิงก์ภายนอก
    public function openLink()
    {
        $target = $this->request->getGet('url');

        // เช็คว่ามี URL และรูปแบบถูกต้องหรือไม่
        if (!empty($target) && filter_var($target, FILTER_VALIDATE_URL)) {
            return redirect()->to($target);
        }

        // หาก URL ไม่ถูกต้อง ให้กลับหน้าแรก
        return redirect()->to(base_url())->with('error', 'ลิงก์ไม่ถูกต้อง');
    }
}