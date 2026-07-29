<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="icon" type="image/png" href="<?= config('App')->assetURL; ?>img/favicon2.png" />
    <title><?= $title ?? 'ระบบผู้ดูแลระบบ | MOPH Digital Training' ?></title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- 🔤 Google Fonts: Prompt (Headings) + Sarabun (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">

    <!-- 📊 DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <style>
        /* 💎 DataTables Tailwind Integration Styling */
        .dataTables_wrapper .dataTables_length select {
            padding: 0.35rem 2rem 0.35rem 0.75rem !important;
            border-radius: 0.75rem !important;
            border: 1px solid #cbd5e1 !important;
            background-color: #f8fafc !important;
            font-size: 0.75rem !important;
        }
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.35rem 0.75rem !important;
            border-radius: 0.75rem !important;
            border: 1px solid #cbd5e1 !important;
            background-color: #f8fafc !important;
            font-size: 0.75rem !important;
            outline: none !important;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #154c9f !important;
            box-shadow: 0 0 0 2px rgba(21, 76, 159, 0.2) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #154c9f !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e2e8f0 !important;
            color: #1e293b !important;
            border: none !important;
            border-radius: 0.5rem !important;
        }
        table.dataTable.no-footer {
            border-bottom: 1px solid #e2e8f0 !important;
        }
        /* Custom Arrow for Submenu Accordion */
        details[open] .dropdown-chevron {
            transform: rotate(180deg);
        }
    </style>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="<?= config('App')->assetURL; ?>js/rendercourse.js"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Sarabun', 'sans-serif'],
              heading: ['Prompt', 'sans-serif'],
              body: ['Sarabun', 'sans-serif'],
            }
          }
        }
      }
    </script>
    
    <!-- 🎨 SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS หลักประจำระบบ -->
    <link rel="stylesheet" href="<?= config('App')->assetURL; ?>css/main.css">
    <link rel="stylesheet" href="<?= config('App')->assetURL; ?>css/button_style.css" />

    <style>
        body { font-family: 'Sarabun', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading, .admin-nav-item { font-family: 'Prompt', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col font-sans antialiased">

    <div class="flex min-h-screen">
        
        <!-- 🏢 Sidebar ด้านซ้าย (Admin Menu) -->
        <aside class="w-64 bg-[#154c9f] text-white flex flex-col justify-between shadow-xl flex-shrink-0">
            <div>
                <!-- Brand Header -->
                <div class="p-5 border-b border-white/10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-xl shadow-inner">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold tracking-wide leading-tight font-heading">MOPH Admin</h1>
                        <p class="text-[10px] text-white/70">ระบบบริหารจัดการข้อมูล</p>
                    </div>
                </div>

                <!-- Profile Brief -->
                <div class="px-5 py-4 bg-black/10 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-semibold truncate font-heading"><?= session()->get('display_name') ?? 'Admin' ?></p>
                        <span class="text-[9px] bg-teal-400/20 text-teal-200 px-2 py-0.5 rounded-full border border-teal-300/30">
                            <?= strtoupper(session()->get('permiss') ?? 'ADMIN') ?>
                        </span>
                    </div>
                </div>

                <!-- 📌 Navigation Links -->
                <nav class="p-3 space-y-1 text-sm font-heading">
                    <!-- 🏠 เมนูหน้าแรก (ภาพรวม) -->
                    <a href="<?= base_url('admin/dashboard') ?>" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/10 text-white/80 <?= current_url() == base_url('admin/dashboard') ? 'bg-white/20 text-white font-semibold' : '' ?>">
                        <i class="bi bi-speedometer2 text-lg"></i>
                        <span>หน้าแรก (ภาพรวม)</span>
                    </a>

                    <?php 
                        // เช็กว่าอยู่ใน Submenu ตัวไหนของหลักสูตรอบรมหรือไม่ เพื่อเปิด Dropdown ค้างไว้
                        $isCourseActive = (strpos(current_url(), 'admin/courses') !== false || strpos(current_url(), 'admin/reports') !== false || strpos(current_url(), 'admin/export') !== false);
                    ?>

                    <!-- 📚 Dropdown: จัดการหลักสูตรอบรม -->
                    <details class="group border-none" <?= $isCourseActive ? 'open' : '' ?>>
                        <summary class="admin-nav-item list-none flex items-center justify-between px-4 py-3 rounded-xl transition-all hover:bg-white/10 text-white/80 cursor-pointer select-none <?= $isCourseActive ? 'bg-white/10 text-white font-semibold' : '' ?>">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-journal-bookmark text-lg"></i>
                                <span>จัดการหลักสูตรอบรม</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform duration-200 dropdown-chevron"></i>
                        </summary>
                        
                        <!-- เมนูย่อย (Submenu) -->
                        <div class="pl-9 pr-2 py-1 space-y-1 text-xs bg-black/10 rounded-lg mt-1">
                            <!-- 1. ข้อมูลหลักสูตรอบรม -->
                            <a href="<?= base_url('admin/courses') ?>" class="flex items-center text-sm gap-2 px-3 py-2 rounded-lg transition-all text-white/70 hover:text-white hover:bg-white/10 <?= current_url() == base_url('admin/courses') ? 'bg-white/20 text-white font-semibold' : '' ?>">
                                <i class="bi bi-box-arrow-in-up-right"></i>
                                <span>ข้อมูลหลักสูตรอบรม</span>
                            </a>
                            <!-- 2. รายงานผลการอบรม -->
                            <a href="<?= base_url('admin/course-reports') ?>" class="flex items-center text-sm gap-2 px-3 py-2 rounded-lg transition-all text-white/70 hover:text-white hover:bg-white/10 <?= current_url() == base_url('admin/course-reports') ? 'bg-white/20 text-white font-semibold' : '' ?>">
                                <i class="bi bi-box-arrow-in-up-right"></i>
                                <span>รายงานผลการอบรม</span>
                            </a>
                            <!-- 3. ส่งออกผลการอบรม -->
                            <a href="<?= base_url('admin/course-export') ?>" class="flex items-center text-sm gap-2 px-3 py-2 rounded-lg transition-all text-white/70 hover:text-white hover:bg-white/10 <?= current_url() == base_url('admin/export') ? 'bg-white/20 text-white font-semibold' : '' ?>">
                                <i class="bi bi-box-arrow-in-up-right"></i>
                                <span>ส่งออกผลการอบรม</span>
                            </a>
                        </div>
                    </details>

                    <!-- 👔 เมนูข้อมูลบุคลากร -->
                    <a href="<?= base_url('admin/employees') ?>" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/10 text-white/80 <?= current_url() == base_url('admin/employees') ? 'bg-white/20 text-white font-semibold' : '' ?>">
                        <i class="bi bi-person-badge text-lg"></i>
                        <span>ข้อมูลบุคลากร</span>
                    </a>

                    <!-- 👥 เมนูจัดการข้อมูลผู้ใช้งาน -->
                    <a href="<?= base_url('admin/users') ?>" class="admin-nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/10 text-white/80 <?= current_url() == base_url('admin/users') ? 'bg-white/20 text-white font-semibold' : '' ?>">
                        <i class="bi bi-people-fill text-lg"></i>
                        <span>จัดการข้อมูลผู้ใช้งาน</span>
                    </a>
                </nav>
            </div>

            <!-- 🔴 ปุ่มออกจากระบบ -->
            <div class="p-3 border-t border-white/10 font-heading">
                <button onclick="confirmLogout()" class="w-full flex items-center gap-3 px-4 py-3 text-red-200 hover:bg-red-600/20 rounded-xl transition-all text-sm font-medium">
                    <i class="bi bi-box-arrow-right text-lg"></i>
                    <span>ออกจากระบบ</span>
                </button>
            </div>
        </aside>

        <!-- 🖥️ Main Content Area (ฝั่งขวา) -->
        <div class="flex-grow flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between shadow-sm">
                <h2 class="text-lg font-bold text-gray-800 font-heading"><?= $title ?? 'ระบบบริหารจัดการ' ?></h2>
                <div class="text-xs text-gray-500 font-body">
                    หน่วยงาน: <span class="font-semibold text-gray-700 font-mono"><?= session()->get('hoscode') ?? '10956' ?></span>
                </div>
            </header>

            <!-- Render Section -->
            <main class="p-6 md:p-8 flex-grow">
                <?= $this->renderSection('admin_content') ?>
            </main>
        </div>

    </div>

    <!-- สคริปต์ jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="<?= config('App')->assetURL; ?>js/main.js"></script>

    <!-- ⚡ สคริปต์ตรวจเช็ก JWT Token & SweetAlert2 Logout -->
    <script>
        function parseJwt(token) {
            try {
                const base64Url = token.split('.')[1];
                const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                const jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                }).join(''));

                return JSON.parse(jsonPayload);
            } catch (e) {
                return null;
            }
        }

        function verifyAdminToken() {
            const token = localStorage.getItem('jwt_token');

            if (!token) {
                handleExpiredSession('ไม่พบ JWT Token กรุณาเข้าสู่ระบบใหม่');
                return;
            }

            const decoded = parseJwt(token);
            if (!decoded || !decoded.exp) {
                handleExpiredSession('รูปแบบ Token ไม่ถูกต้อง');
                return;
            }

            const currentTime = Math.floor(Date.now() / 1000);
            if (decoded.exp < currentTime) {
                handleExpiredSession('ระยะเวลาการใช้งานของคุณหมดอายุแล้ว');
                return;
            }
        }

        function handleExpiredSession(msg) {
            Swal.fire({
                icon: 'warning',
                title: 'เซสชันหมดอายุ',
                text: msg,
                confirmButtonColor: '#154c9f',
                allowOutsideClick: false
            }).then(() => {
                localStorage.removeItem('jwt_token');
                window.location.href = '<?= base_url('auth/logout') ?>';
            });
        }

        function confirmLogout() {
            Swal.fire({
                title: 'ยืนยันการออกจากระบบ?',
                text: 'คุณต้องการออกจากระบบผู้ดูแลระบบใช่หรือไม่',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#154c9f',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ออกจากระบบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem('jwt_token');
                    window.location.href = '<?= base_url('auth/logout') ?>';
                }
            });
        }

        $(document).ready(function() {
            verifyAdminToken();
        });
    </script>
    
    <!-- 📊 Global Auto Logger Script -->
    <script>
    (function() {
        'use strict';

        document.addEventListener('click', function(e) {
            // 1. หา Element ที่มีนัยสำคัญในการคลิก (ปุ่ม, ลิงก์, แท็บ, อินพุต, เมนู)
            const targetEl = e.target.closest('button, a, input, select, textarea, .tab-btn, [data-level], tr, nav a, .btn') || e.target;
            
            // กรองไม่บันทึกการคลิกพื้นที่ว่างเปล่าทั่วไป (ถ้าต้องการบันทึกเฉพาะจุดที่ปฏิสัมพันธ์ได้)
            const tagName = targetEl.tagName.toLowerCase();
            
            // 2. รวบรวมข้อมูลบริบทของการคลิก
            const elementId = targetEl.id ? `#${targetEl.id}` : '';
            const innerText = targetEl.innerText ? targetEl.innerText.trim().replace(/\s+/g, ' ').substring(0, 60) : '';
            const hrefAttr  = targetEl.getAttribute('href') || '';
            
            const eventType   = 'AUTO_CLICK';
            const eventTitle  = `Click <${tagName}> ${elementId} [${innerText || 'Icon/Element'}]`;
            
            const eventDetail = JSON.stringify({
                tag: tagName,
                id: targetEl.id || null,
                class: targetEl.className || null,
                text: innerText || null,
                href: hrefAttr || null,
                data_level: targetEl.getAttribute('data-level') || null,
                page_title: document.title,
                page_url: window.location.href
            });

            // 3. เตรียม Data สำหรับส่งไปยัง Server
            const formData = new FormData();
            formData.append('event_type', eventType);
            formData.append('event_title', eventTitle);
            formData.append('event_detail', eventDetail);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            // 4. ส่งข้อมูลด้วย sendBeacon (ทำงานเบื้องหลัง ประสิทธิภาพสูง หน้าเว็บไม่หน่วง)
            const logEndpoint = '<?= base_url("api/log_user_activity.php") ?>';

            if (navigator.sendBeacon) {
                navigator.sendBeacon(logEndpoint, formData);
            } else {
                // Fallback สำหรับเบราว์เซอร์เก่า
                fetch(logEndpoint, {
                    method: 'POST',
                    body: formData,
                    keepalive: true
                }).catch(err => console.error('Log error:', err));
            }
        }, true); // ใช้ Event Capturing (true) เพื่อดักจับ Event คลิกในทุกระดับของ DOM
    })();
    </script>

    <?= $this->renderSection('page_scripts') ?>
</body>
</html>