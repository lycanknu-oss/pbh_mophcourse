<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    /**
     * ⚡ Global Auto-Log Endpoint: บันทึก Log ทุกการคลิกในระบบ
     */
    public function logUserActivity()
    {
        $db      = \Config\Database::connect();
        $request = \Config\Services::request();
        $session = session();

        // 🚫 เช็คถ้าเป็น Localhost (::1 หรือ 127.0.0.1) ให้บันทึกเป็น null หรือข้าม
        if ($ip === '::1' || $ip === '127.0.0.1') {
            $ip = null; // หรือเปลี่ยนเป็น 'localhost' ตามต้องการ
        }

        // รับค่าจาก JS (รองรับทั้ง $_POST และ sendBeacon FormData)
        $eventType   = $request->getPost('event_type') ?? 'AUTO_CLICK';
        $eventTitle  = $request->getPost('event_title') ?? 'User Clicked Element';
        $eventDetail = $request->getPost('event_detail') ?? null;

        // ดึงชื่อ Controller / Method ที่กำลังใช้งาน
        $router     = \Config\Services::router();
        $controller = $router->controllerName();
        $method     = $router->methodName();

        $logData = [
            'emp_id'       => $session->get('emp_id') ?? $session->get('user_id') ?? null,
            'cid'          => $session->get('cid') ?? null,
            'event_type'   => $eventType,
            'event_title'  => $eventTitle,
            'event_detail' => $eventDetail,
            'controller'   => $controller,
            'method'       => $method,
            'remote_ip'    => $request->getIPAddress(), // 👈 ดึง IP Address ของผู้ใช้
            'user_agent'   => (string)$request->getUserAgent(),
            'created_at'   => date('Y-m-d H:i:s')
        ];

        $db->table('tr_event_logs')->insert($logData);

        return $this->response->setJSON(['status' => 'success']);
    }
}
