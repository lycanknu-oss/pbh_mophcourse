<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // ถ้าไม่มี session login_id ให้เด้งไปหน้า login ทันที
        if (!session()->get('login_id')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'กรุณาเข้าสู่ระบบก่อนใช้งาน');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ไม่ต้องทำอะไร
    }
}