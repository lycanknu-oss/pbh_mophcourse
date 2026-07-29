<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?= config('App')->assetURL; ?>img/favicon3.png" />

    <title><?= $title ?? 'MOPH Digital Training System' ?></title>

    <!-- 🔤 Google Fonts: Sarabun & IBM Plex Sans Thai -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <!-- 2️⃣ โหลด CSS ของ DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <?= $this->renderSection('page_styles') ?>
    <script>
        // ดึงค่าจาก .env ผ่านฟังก์ชัน env() ของ CI4
        const hosName = "<?= env('project.hosname', 'โรงพยาบาล') ?>"; 
        
        // ตั้งค่าข้อความตำแหน่งตามที่ต้องการ
        const directorTitle = "ผู้อำนวยการ" + hosName;
        const subDirectorTitle = "รองผู้อำนวยการ" + hosName;
    </script>
    <!-- Tailwind CSS -->
    <script>
    // ปิดข้อความแจ้งเตือน Console Warning ของ Tailwind CDN
        window.tailwind = { config: { suppressDeprecationWarnings: true } };
    </script>
    <script src="https://cdn.tailwindcss.com"></script> 
    <script src="<?= config('App')->assetURL; ?>js/rendercourse.js"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Sarabun', 'IBM Plex Sans Thai', 'sans-serif'],
              heading: ['IBM Plex Sans Thai', 'Sarabun', 'sans-serif'],
            }
          }
        }
      }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?= config('App')->assetURL; ?>css/main.css">
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col relative text-sm md:text-base">

    <!-- 💎 Soft White Liquid Glass Navbar -->
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200 text-slate-800 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <!-- ฝั่งซ้าย: โลโก้ และ เมนูหลัก -->
                <div class="flex items-center gap-6">
                    <!-- Brand / Logo -->
                    <a href="<?= base_url() ?>" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-2xl bg-[#154c9f] flex items-center justify-center text-white shadow-md group-hover:scale-105 transition-transform">
                            <i class="bi bi-hospital text-xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-bold font-heading text-slate-900 leading-tight">MOPH Digital Training</span>
                            <span class="text-sm text-slate-500 font-medium">โรงพยาบาลพิบูลมังสาหาร</span>
                        </div>
                    </a>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-1">
                        
                        <!-- 1. แดชบอร์ด -->
                        <a href="<?= base_url('dashboard.php') ?>" 
                        class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 <?= url_is('dashboard*') || url_is('/') ? 'text-[#154c9f] bg-blue-50 border border-blue-100 shadow-xs' : 'text-slate-600 hover:text-[#154c9f] hover:bg-slate-100' ?>">
                            <i class="bi bi-pie-chart-fill"></i> แดชบอร์ด
                        </a>

                        <!-- 2. หลักสูตร -->
                        <a href="<?= base_url('courses.php') ?>" 
                        class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 <?= url_is('courses*') ? 'text-[#154c9f] bg-blue-50 border border-blue-100 shadow-xs' : 'text-slate-600 hover:text-[#154c9f] hover:bg-slate-100' ?>">
                            <i class="bi bi-journal-bookmark-fill"></i> หลักสูตร
                        </a>

                        <!-- 3. อัปโหลด -->
                        <a href="<?= base_url('upload.php') ?>" 
                        class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 <?= url_is('upload*') ? 'text-[#154c9f] bg-blue-50 border border-blue-100 shadow-xs' : 'text-slate-600 hover:text-[#154c9f] hover:bg-slate-100' ?>">
                            <i class="bi bi-cloud-arrow-up-fill"></i> อัปโหลด
                        </a>

                        <!-- 4. 📄 เมนูเอกสาร (Dropdown) -->
                        <div class="relative inline-block text-left group">
                            <button type="button" class="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-[#154c9f] hover:bg-slate-100 transition-all flex items-center gap-1.5 cursor-pointer">
                                <i class="bi bi-file-earmark-text-fill"></i>
                                <span>เอกสาร</span>
                                <i class="bi bi-chevron-down text-[10px] text-slate-400 group-hover:rotate-180 transition-transform"></i>
                            </button>

                            <!-- Sub-menu Dropdown -->
                            <div class="absolute left-0 mt-1 w-48 origin-top-left rounded-2xl bg-white shadow-lg ring-1 ring-black/5 border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 p-1.5 z-50">
                                <a href="<?= base_url('docs/register-manual.php') ?>" target="_blank" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-blue-50 hover:text-[#154c9f] rounded-xl transition-colors">
                                    <i class="bi bi-book text-slate-400"></i> คู่มือการสมัครอบรม
                                </a>
                                <a href="<?= base_url('docs/forgot-provider-id.php') ?>" target="_blank" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-blue-50 hover:text-[#154c9f] rounded-xl transition-colors">
                                    <i class="bi bi-key text-slate-400"></i> ลืม ProviderID
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ฝั่งขวา: ส่วนสมาชิก / ผู้เยี่ยมชม (Admin & Session Check) -->
                <div class="flex items-center gap-3">
                    
                    <?php if (!session()->get('login_id')): ?>
                        <!-- ================= CASE 1: หากไม่พบ Session login_id ให้แสดง "ผู้เยี่ยมชม (Guest)" ================= -->
                        <div class="hidden md:flex items-center gap-2 bg-slate-100/80 px-3.5 py-1.5 rounded-2xl border border-slate-200/80">
                            <div class="w-7 h-7 rounded-xl bg-slate-200 flex items-center justify-center text-slate-500">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-600 font-heading">ผู้เยี่ยมชม (Guest)</span>
                            <a href="<?= base_url('auth/login.php') ?>" class="ml-1 text-sm text-[#154c9f] hover:underline font-semibold">(เข้าสู่ระบบ)</a>
                        </div>

                        <!-- 📱 [แสดงเฉพาะจอเล็ก < 768px] Dropdown "เริ่มการอบรม" -->
                        <div class="relative md:hidden">
                            <!-- ปุ่มกดเปิด Dropdown -->
                            <button id="mobileMenuBtn" 
                                    type="button" 
                                    class="px-3 py-1.5 bg-[#154c9f] hover:bg-[#0f3877] text-white text-sm font-bold rounded-xl shadow-xs transition-all flex items-center gap-1.5 font-heading cursor-pointer">
                                <i class="bi bi-play-circle-fill"></i>
                                <span>รายงานการอบรม</span>
                                <i id="mobileMenuArrow" class="bi bi-chevron-down text-[10px] transition-transform duration-200"></i>
                            </button>

                            <!-- เมนู Dropdown รายการ -->
                            <div id="mobileDropdownMenu" 
                                class="hidden absolute right-0 mt-2 w-48 bg-white border border-slate-200/80 rounded-2xl shadow-xl py-1.5 z-50 text-sm font-heading transition-all">
                                
                                <a href="<?= base_url('') ?>" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-[#154c9f] transition-colors">
                                    <i class="bi bi-graph-up-arrow text-[#51CD66]"></i>
                                    <span>Dashboard</span>
                                </a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <a href="<?= base_url('courses.php') ?>" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-[#154c9f] transition-colors">
                                    <i class="bi bi-journal-bookmark-fill text-[#154c9f]"></i>
                                    <span>หลักสูตรอบรม</span>
                                </a>  
                                <a href="<?= base_url('upload.php') ?>" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-[#154c9f] transition-colors">
                                    <i class="bi bi-cloud-arrow-up-fill text-emerald-600"></i>
                                    <span>ส่งหลักฐานอบรม</span>
                                </a>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- ================= CASE 2: หากพบ Session login_id ให้แสดง display_name + Dropdown ================= -->
                        <div class="relative inline-block text-left group">
                            
                            <!-- Display Name Card -->
                            <div class="flex items-center gap-2.5 px-3 py-1.5 bg-slate-100/80 hover:bg-blue-50/80 rounded-2xl border border-slate-200/80 cursor-pointer transition-all">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-bold text-slate-800 font-heading m-0 leading-tight">
                                        <?= esc(session()->get('display_name')) ?>
                                    </p>
                                    <span class="text-[10px] bg-teal-100 text-teal-800 px-2 py-0.2 rounded-full font-semibold border border-teal-200">
                                        <?= esc(session()->get('permiss') ?? 'ผู้ใช้ระบบ') ?>
                                    </span>
                                </div>
                                <div class="w-8 h-8 rounded-xl bg-[#154c9f] text-white flex items-center justify-center font-bold text-sm shadow-xs">
                                    <i class="bi bi-person-circle text-lg"></i>
                                </div>
                                <i class="bi bi-chevron-down text-[10px] text-slate-400 group-hover:rotate-180 transition-transform"></i>
                            </div>

                            <!-- Dropdown Menu ส่วน Admin -->
                            <div class="absolute right-0 mt-1 w-56 origin-top-right rounded-2xl bg-white shadow-lg ring-1 ring-black/5 border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 p-1.5 z-50">
                                <a href="<?= base_url('admin/courses') ?>" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-blue-50 hover:text-[#154c9f] rounded-xl transition-colors">
                                    <i class="bi bi-journal-gear text-slate-400"></i> จัดการหลักสูตรอบรม
                                </a>

                                <!-- 🧹 เมนูล้างแคชระบบ -->
                                <button type="button" onclick="handleClearAllCache()" class="w-full flex items-center gap-2 px-3 py-2 text-sm font-medium text-amber-700 hover:bg-amber-50 rounded-xl transition-colors text-left cursor-pointer">
                                    <i class="bi bi-trash3 text-amber-500"></i> ล้างแคชระบบ (Clear Cache)
                                </button>

                                <div class="h-[1px] bg-slate-100 my-1"></div>
                                
                                <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                                    <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
                                </a>
                            </div>

                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </nav>
    

    <!-- Main Content -->
    <main class="flex-grow z-10">
        <?= $this->renderSection('main_content') ?>
    </main>

    <footer class="bg-white border-t border-slate-200 py-4 text-center text-sm text-slate-500">
        &copy; 2026 MOPH Digital Training System | Ministry of Public Health. All Rights Reserved.
    </footer>

    <!-- 💎 Welcome Announcement Popup (Modal) -->
    <div id="welcomeModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-all duration-300">
        <div class="liquid-glass-content bg-white w-full max-w-lg rounded-3xl border border-slate-200/80 shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="welcomeModalCard">
            
            <!-- Header Popup -->
            <div class="bg-gradient-to-r from-[#154c9f] to-indigo-700 p-5 text-white flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-xl shadow-inner border border-white/20">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm font-heading leading-tight">ประกาศประชาสัมพันธ์</h3>
                        <p class="text-[11px] text-white/80 mt-0.5">ระบบพัฒนาทักษะดิจิทัล MOPH Digital Training | โรงพยาบาลพิบูลมังสาหาร</p>
                    </div>
                </div>
                <button onclick="closeWelcomeModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-sm transition-colors border border-white/20">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Body Content -->
            <div class="p-6 space-y-4 text-sm text-slate-700 bg-white">
                <!-- Banner Icon -->
                <div class="rounded-2xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center p-4">
                    <i class="bi bi-award text-4xl text-[#154c9f]"></i>
                </div>

                <div>
                    <h4 class="font-bold text-sm text-slate-900 font-heading mb-1.5">
                        🎉 เปิดให้บันทึกผลการอบรมประจำปีงบประมาณ 2026
                    </h4>
                    <p class="text-slate-600 leading-relaxed">
                        ขอให้บุคลากรทุกท่านเข้าตรวจสอบและอัปโหลดใบรับรอง (Certificate) การอบรมหมวดหมู่บังคับตามเกณฑ์ที่กำหนดให้ครบถ้วนก่อนสิ้นสุดไตรมาส
                    </p>
                </div>

                <!-- ปุ่มเปลี่ยนจาก <a href="..."> เป็น <button onclick="..."> -->
                <button onclick="handleCourseRedirect()" class="w-full py-2.5 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-xl text-sm transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 font-heading">
                    <i class="bi bi-journal-check text-sm"></i>
                    <span>เข้าสู่หน้าหลักสูตรการอบรม</span>
                    <i class="bi bi-arrow-right text-sm"></i>
                </button>

                <!-- ข้อความเน้นย้ำ / หมายเหตุ -->
                <div class="p-3 bg-blue-50/80 border border-blue-100 rounded-xl text-blue-900 text-[11px] flex items-start gap-2">
                    <i class="bi bi-info-circle-fill text-[#154c9f] text-sm shrink-0 mt-0.5"></i>
                    <span>สามารถศึกษาคู่มือการลงทะเบียนใช้งานและรับ ProviderID ได้จากเมนู "เอกสาร" ในแถบด้านบน</span>
                </div>
            </div>

            <!-- Footer & Option Don't Show Again -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200/80 flex items-center justify-between gap-3">
                <label class="flex items-center gap-2 cursor-pointer select-none text-[11px] text-slate-500 hover:text-slate-700">
                    <input type="checkbox" id="dontShowToday" class="w-3.5 h-3.5 text-[#154c9f] rounded border-slate-300 focus:ring-[#154c9f] cursor-pointer">
                    <span>ไม่ต้องแสดงอีกในวันนี้</span>
                </label>

                <button onclick="closeWelcomeModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-sm transition-all flex items-center gap-1.5 font-heading">
                    <span>ปิดหน้าต่าง</span>
                </button>
            </div>

        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <!-- ➕ เพิ่ม Alpine.js ไว้ใน <head> -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> 

    <!-- 3️⃣ โหลด JS ของ DataTables (ต้องอยู่หลัง jQuery) -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- 4️⃣ (Optional) หากใช้ Responsive Extension -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <Script>
        /**
         * 🧹 ฟังก์ชันจัดการล้างแคชทั้ง JS (Client) และ PHP (Server)
         */
        function handleClearAllCache() {
            Swal.fire({
                title: 'ยืนยันการล้างแคชระบบ?',
                text: 'ระบบจะทำการลบ SessionStorage, LocalStorage และ Clear Cache บนเซิร์ฟเวอร์',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#154c9f',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ใช่, ล้างแคชทันที',
                cancelButtonText: 'ยกเลิก',
                customClass: { popup: 'liquid-glass-swal' }
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    // 1. 🧹 Destroy Session & Cache ฝั่ง JS (Client)
                    sessionStorage.clear();
                    localStorage.clear();

                    // 2. 🧹 เรียก AJAX สั่ง Clear Cache & PHP Session ฝั่ง Server
                    $.ajax({
                        url: "<?= base_url('admin/clear-cache.php') ?>",
                        type: "POST",
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'ล้างแคชสำเร็จ!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false,
                                customClass: { popup: 'liquid-glass-swal' }
                            }).then(() => {
                                // โหลดหน้าเว็บใหม่เพื่อให้เห็นผลลัพธ์ทันที
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถล้างแคชบนเซิร์ฟเวอร์ได้',
                                customClass: { popup: 'liquid-glass-swal' }
                            });
                        }
                    });

                }
            });
        }
    </Script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btn = document.getElementById("mobileMenuBtn");
            const menu = document.getElementById("mobileDropdownMenu");
            const arrow = document.getElementById("mobileMenuArrow");

            if (btn && menu) {
                // กดปุ่มเพื่อเปิด-ปิด
                btn.addEventListener("click", function (e) {
                    e.stopPropagation();
                    menu.classList.toggle("hidden");
                    if (arrow) arrow.classList.toggle("rotate-180");
                });

                // คลิกพื้นที่อื่นนอก Dropdown ให้ปิดเมนูลดความเกะกะ
                document.addEventListener("click", function (e) {
                    if (!menu.contains(e.target) && !btn.contains(e.target)) {
                        menu.classList.add("hidden");
                        if (arrow) arrow.classList.remove("rotate-180");
                    }
                });
            }
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            checkAndShowWelcomeModal();
        });

        // 1. ตรวจสอบก่อนแสดง Modal
        function checkAndShowWelcomeModal() {
            const hideUntil = localStorage.getItem("hideWelcomePopupUntil");
            const sessionSeen = sessionStorage.getItem("popupSeen"); // ตรวจสอบ Session ที่เราตั้งไว้
            const today = new Date().toDateString();

            // แสดง Popup ก็ต่อเมื่อ:
            // 1. วันนี้ยังไม่ปิดถาวร (LocalStorage)
            // 2. และยังไม่ได้กดปุ่มเข้าหน้าหลักสูตรใน Session นี้ (SessionStorage)
            if (hideUntil !== today && sessionSeen !== "true") {
                const modal = document.getElementById("welcomeModal");
                const card = document.getElementById("welcomeModalCard");

                modal.classList.remove("hidden");
                modal.classList.add("flex");

                setTimeout(() => {
                    card.classList.remove("scale-95", "opacity-0");
                    card.classList.add("scale-100", "opacity-100");
                }, 50);
            }
        }

        // 2. ฟังก์ชันเมื่อกดปุ่ม "เข้าสู่หน้าหลักสูตร"
        function handleCourseRedirect() {
            // บันทึก session_id (หรือ flag) เพื่อให้รู้ว่าเข้าหน้านี้แล้ว
            sessionStorage.setItem("popupSeen", "true");
            
            // Redirect ไปหน้า courses
            window.location.href = "<?= base_url('courses.php') ?>";
        }

        // 3. ฟังก์ชันปิด Modal ปกติ
        function closeWelcomeModal() {
            const dontShow = document.getElementById("dontShowToday").checked;
            const modal = document.getElementById("welcomeModal");
            const card = document.getElementById("welcomeModalCard");

            if (dontShow) {
                const today = new Date().toDateString();
                localStorage.setItem("hideWelcomePopupUntil", today);
            }

            card.classList.remove("scale-100", "opacity-100");
            card.classList.add("scale-95", "opacity-0");

            setTimeout(() => {
                modal.classList.remove("flex");
                modal.classList.add("hidden");
            }, 300);
        }
    </script>
    <?= $this->renderSection('page_scripts') ?>
</body>
</html>