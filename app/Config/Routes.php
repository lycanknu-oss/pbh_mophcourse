<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
//$routes->get('/', 'Home::index');
$routes->get('/', 'DashboardController::index');
// Route สำหรับบันทึก Event Log ทั่วทั้งระบบ
$routes->post('api/log-activity.php', 'DashboardController::logUserActivity'); 
// หรือชี้ไปที่ Controller หลักที่คุณใช้งาน เช่น 'Home::logUserActivity'

$routes->get('dashboard.php', 'DashboardController::index');
$routes->get('courses.php', 'CourseController::index');
$routes->get('upload.php', 'CourseController::upload');
$routes->post('upload_save.php', 'CourseController::saveUpload');
//$routes->get('emp_search.php', 'CourseController::searchEmployee'); 
$routes->get('docs/register-manual.php', 'ExternalController::registerManual');
$routes->get('docs/forgot-provider-id.php', 'ExternalController::providerIdHelp');
//$routes->get('docs/open-link.php', 'ExternalController::openLink');

$routes->group('data', function ($routes) {
    $routes->get('emp_search.php', 'Data\ListController::searchEmployee');
    $routes->get('workgroup_stats.php', 'Data\ListController::getWorkgroupStats');
    $routes->get('employees_bylevel.php', 'Data\ListController::getPassedEmployeesByLevel');
});


$routes->group('auth', function ($routes) {
    $routes->get('login.php', 'AuthController::login');
    $routes->post('loginCheck', 'AuthController::loginCheck');
    $routes->get('logout', 'AuthController::logout');
});

$routes->get('admin', 'AdminController::dashboard');


// --------------------------------------------------------------------
// 🛡️ 4. Admin Routes (สำหรับผู้ดูแลระบบ - ต้องผ่านสิทธิ์ Admin)
// --------------------------------------------------------------------
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    
    // 🖥️ แผงควบคุมหลักผู้ดูแลระบบ
    $routes->get('dashboard', 'AdminController::dashboard');

    // 📚 การจัดการข้อมูลหลักสูตรอบรม (Courses Management)
    $routes->get('courses', 'AdminController::courses');
    $routes->post('saveCourse', 'AdminController::saveCourse');
    $routes->post('deleteCourse', 'AdminController::deleteCourse');
    $routes->get('course-reports', 'AdminController::courseDetails/$1');

    // 👔 การจัดการข้อมูลบุคลากร (tr_employee Management)
    $routes->get('employees', 'AdminController::employees');
    $routes->post('saveEmployee', 'AdminController::saveEmployee');
    $routes->post('deleteEmployee', 'AdminController::deleteEmployee');

    // 👥 การจัดการข้อมูลผู้ใช้งานระบบ (tr_staff Management)
    $routes->get('users', 'AdminController::users');
    $routes->post('resetPassword', 'AdminController::resetPassword');
    $routes->get('deleteUser/(:num)', 'AdminController::deleteUser/$1');

    // 📊 การจัดการข้อมูลสถิติ (Statistics Managemen
    $routes->post('clear-cache.php', 'AdminController::clearCacheSystem');
});