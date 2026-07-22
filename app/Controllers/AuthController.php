<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends BaseController
{
    public function login()
    {
        // แสดงหน้าฟอร์ม Login
        return view('auth/login');
    }

    public function loginCheck()
    {
        // รับค่าจากฟอร์ม
        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));

        if (empty($username) || empty($password)) {
            return redirect()->back()->with('error', 'กรุณากรอกชื่อผู้ใช้งานและรหัสผ่าน');
        }

        $db = \Config\Database::connect();

        // -------------------------------------------------------------
        // เงื่อนไขข้อที่ 1: ค้นหา username ก่อน
        // -------------------------------------------------------------
        $user = $db->table('tr_staff')
                   ->where('usr_name', $username)
                   ->get()
                   ->getRowArray();

        if (!$user) {
            // ไม่พบ Username ในระบบ
            return redirect()->back()->with('error', 'ไม่พบชื่อผู้ใช้งานนี้ในระบบ');
        }

        // -------------------------------------------------------------
        // เงื่อนไขข้อที่ 2: เข้ารหัสรหัสผ่านด้วย Key จาก .env แล้วเปรียบเทียบ
        // (คำนวณ Hash ด้วย HMAC-SHA256 ร่วมกับค่า Secret จาก env)
        // -------------------------------------------------------------
        $encryptKey = getenv('ENCRYPT_SECRET_KEY');
        $hashedInputPassword = hash('sha256', $password . $encryptKey);

        // หมายเหตุ: กรณีที่คุณเก็บรหัสผ่านในฐานข้อมูลแบบ password_hash() ของ PHP 
        // หรือเก็บเป็น String ตรงๆ ให้ใช้ password_verify หรือ $user['usr_pwd'] === $hashedInputPassword
        if ($user['usr_pwd'] !== $hashedInputPassword) {
            return redirect()->back()->with('error', 'รหัสผ่านไม่ถูกต้อง');
        }

        // -------------------------------------------------------------
        // เงื่อนไขข้อที่ 3: ส่งและบันทึกค่าลง Session
        // -------------------------------------------------------------
        $sessionData = [
            'login_id'     => $user['u_id'],
            'hoscode'      => $user['hcode'],
            'display_name' => $user['fname'],
            'permiss'      => $user['role'],
            'isLoggedIn'   => true
        ];
        
        session()->set($sessionData);

        // 💡 ดึง JWT Secret Key และตรวจสอบความยาว (ต้องไม่ต่ำกว่า 32 ตัวอักษร)
        $jwtKey = env('JWT_SECRET');

        if (empty($jwtKey) || strlen($jwtKey) < 32) {
            // Key สำรองที่มีความยาวมากกว่า 32 ตัวอักษร
            $jwtKey = 'MOPH_PBH_Training_Super_Secret_JWT_Key_2026_Secure_Key_32bytes';
        }

        $issuedAt = time();
        $expirationTime = $issuedAt + (int)(env('JWT_TIME_TO_LIVE') ?: 86400);

        $payload = [
            'iss'  => base_url(),
            'aud'  => base_url(),
            'iat'  => $issuedAt,
            'exp'  => $expirationTime,
            'data' => [
                'u_id'  => $user['u_id'],
                'hcode' => $user['hcode'],
                'fname' => $user['fname'],
                'role'  => $user['role']
            ]
        ];

        // เจน Token ผ่านฉลุย ไม่ติด Exception เรื่อง Key สั้นเกินไปแน่นอนครับ
        $token = JWT::encode($payload, $jwtKey, 'HS256');

        // บันทึก JWT Token ลงใน Session และ Cookie (เพื่อให้ดึงไปใช้ฝั่ง Frontend/API ได้ง่าย)
        session()->set('jwt_token', $token);
        
        // เก็บ Cookie แบบ HttpOnly เพิ่มความปลอดภัย
        setcookie("JWT_TOKEN", $token, [
            'expires'  => $expirationTime,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        // -------------------------------------------------------------
        // กรณีล็อกอินสำเร็จ คืนค่า Token + JSON
        // -------------------------------------------------------------
        $redirectUrl = (strtolower($user['role']) === 'admin') 
            ? base_url('admin') 
            : base_url('dashboard');

        return $this->response->setJSON([
            'status'       => 'success',
            'message'      => 'เข้าสู่ระบบสำเร็จ',
            'token'        => $token,
            'redirect_url' => $redirectUrl,
            'user'         => [
                'u_id'     => $user['u_id'],
                'fname'    => $user['fname'],
                'role'     => $user['role']
            ]
        ]);

        // นำทางไปยังหน้า Dashboard เมื่อเข้าสู่ระบบสำเร็จ
        //return redirect()->to(base_url('admin'))->with('success', 'เข้าสู่ระบบสำเร็จ');
    }

    // ฟังก์ชันสำหรับสคริปต์สมัครสมาชิก/สร้างรหัสผ่านทดสอบ
    public function generatePasswordHash($plainPassword)
    {
        $encryptKey = getenv('ENCRYPT_SECRET_KEY');
        return hash_hmac('sha256', $plainPassword, $encryptKey);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url(''));
    }
}