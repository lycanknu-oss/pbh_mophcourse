<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบแดชบอร์ดรายงานข้อมูลและหลักสูตร</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- เรียกใช้ไฟล์มินิฟายด์ที่ผ่านการ Build มาแล้ว -->
    <link rel="stylesheet" href="<?= config('App')->assetURL; ?>css/main.css">
    
    <style>
        body { 
            font-family: 'Prompt', sans-serif !important;
            background-color: #f1f5f9;
        }
        .btn-glossy {
            position: relative; overflow: hidden;
            background: linear-gradient(to bottom, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.4);
            transition: all 0.3s ease;
        }
        .btn-glossy::after {
            content: ''; position: absolute; top: 0; left: -50%; width: 200%; height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-25deg); transition: 0.75s;
        }
        .btn-glossy:hover::after { left: 125%; }
        .btn-glossy:active { transform: scale(0.98); }
        .drag-over { border-color: #3b82f6 !important; background-color: #eff6ff !important; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="sticky top-0 shadow-sm transition-all duration-300 bg-[#154c9f]" style="z-index: 1050;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
        
        <!-- ฝั่งซ้าย: โลโก้และชื่อระบบ -->
        <div class="flex items-center">
            <div class="flex-shrink-0 flex items-center gap-3">
            <div class="w-9 h-9 flex items-center justify-center text-white">
                <i class="bi bi-hospital text-2xl"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-base font-bold text-white tracking-wide block leading-tight">MOPH Digital Training</span>
                <span class="text-[10px] text-white/60 font-medium block">ระบบบริหารการอบรมบุคลากร</span>
            </div>
            </div>

            <!-- เมนูหลักบน Desktop -->
            <div class="hidden md:ml-8 md:flex md:space-x-1 h-full items-center">
            <a href="dashboard.php" class="px-3 py-2 rounded-xl text-sm font-semibold text-white bg-white/20 flex items-center gap-2 border border-white/10 transition-all">
                <i class="bi bi-bar-chart-line"></i>
                <span>แดชบอร์ด</span>
            </a>
            <a href="courses.php" class="px-3 py-2 rounded-xl text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 flex items-center gap-2 transition-all duration-200">
                <i class="bi bi-journal-bookmark"></i>
                <span>หลักสูตร</span>
            </a>
            <a href="upload.php" class="px-3 py-2 rounded-xl text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 flex items-center gap-2 transition-all duration-200">
                <i class="bi bi-cloud-arrow-up"></i>
                <span>อัปโหลด</span>
            </a>
            </div>
        </div>

        <!-- ฝั่งขวา: ข้อมูลสิทธิ์ผู้ใช้งาน (Dynamic Session) -->
        <div class="flex items-center gap-4">
            
            <?php if (!session()->get('login_id')): ?>
            <!-- ================= CASE 1: ผู้มาเยือน (ไม่มี Session) ================= -->
            <a href="<?= base_url('auth/login') ?>" class="flex items-center gap-3 pl-2 text-decoration-none group bg-white/5 hover:bg-white/10 px-3 py-1.5 rounded-xl transition-all border border-white/5">
                <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-white/90 group-hover:text-white transition-colors m-0">ยินดีต้อนรับผู้มาเยือน</p>
                <p class="text-[10px] font-medium text-white/50 m-0">คลิกเพื่อเข้าสู่ระบบ</p>
                </div>
                <!-- อวตารผู้มาเยือนเริ่มต้น -->
                <div class="w-9 h-9 rounded-xl bg-white/20 border-2 border-white/20 flex items-center justify-center text-white/80 group-hover:scale-105 transition-transform">
                <i class="bi bi-person-circle text-lg"></i>
                </div>
            </a>

            <?php else: ?>
            <!-- ================= CASE 2: สมาชิกเข้าสู่ระบบแล้ว (มี Session + Dropdown) ================= -->
            <div class="relative inline-block text-left group">
                <div class="flex items-center gap-3 pl-2 cursor-pointer bg-white/10 hover:bg-white/15 px-3 py-1.5 rounded-xl transition-all border border-white/10">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-white m-0"><?= session()->get('fullname') ?></p>
                    <p class="text-[10px] font-medium text-white/80 bg-white/20 px-1.5 py-0.5 rounded-md mt-0.5 inline-block border border-white/10 m-0"><?= session()->get('role') ?></p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-white/20 border-2 border-white/30 overflow-hidden shadow-sm">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="Profile" class="w-full h-full object-cover">
                </div>
                </div>

                <!-- กล่องเมนูย่อย Dropdown (แสดงผลนุ่มๆ เมื่อ hover ที่การ์ดโปรไฟล์บนเดสก์ท็อป) -->
                <div class="absolute right-0 mt-2 w-48 origin-top-right rounded-2xl bg-white shadow-lg ring-1 ring-black/5 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 p-1.5" style="z-index: 1060;">
                <a href="manage_training.php" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                    <i class="bi bi-collection-play text-gray-400"></i> จัดการข้อมูลอบรม
                </a>
                <a href="profile.php" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                    <i class="bi bi-person-gear text-gray-400"></i> จัดการข้อมูลส่วนตัว
                </a>
                <div class="h-[1px] bg-gray-100 my-1"></div>
                <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-2 px-3 py-2 text-sm text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                    <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
                </a>
                </div>
            </div>
            <?php endif; ?>

        </div>

        </div>
    </div>
    </nav>
    

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 py-8">

        <!-- 1. PAGE: DASHBOARD -->
        <section id="page-dashboard" class="page-content space-y-6">
            <h2 class="text-2xl font-bold text-slate-800"><i class="fa-solid fa-gauge-high mr-2 text-indigo-600"></i>ภาพรวมข้อมูลรายงาน</h2>
            <div class="container mx-auto p-6 bg-gray-50 min-h-screen text-gray-800">

            <!-- Row 1: 4 Summary Boxes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Box 1: บุคลากรทั้งหมด -->
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">บุคลากรทั้งหมด</p>
                        <h3 class="text-3xl font-bold text-gray-900 mt-1">150 <span class="text-sm font-normal text-gray-500">คน</span></h3>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>

                <!-- Box 2: เรียนครบหลักสูตร -->
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">เรียนครบหลักสูตร</p>
                        <h3 class="text-3xl font-bold text-green-600 mt-1">95 <span class="text-sm font-normal text-gray-500">คน</span></h3>
                    </div>
                    <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                        <i class="fas fa-graduation-cap text-2xl"></i>
                    </div>
                </div>

                <!-- Box 3: ยังไม่ครบทุกหลักสูตร -->
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">ยังไม่ครบทุกหลักสูตร</p>
                        <h3 class="text-3xl font-bold text-amber-500 mt-1">55 <span class="text-sm font-normal text-gray-500">คน</span></h3>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-500 rounded-xl">
                        <i class="fas fa-book-reader text-2xl"></i>
                    </div>
                </div>

                <!-- Box 4: Certificate -->
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">ออก Certificate แล้ว</p>
                        <h3 class="text-3xl font-bold text-indigo-600 mt-1">120 <span class="text-sm font-normal text-gray-500">ใบ</span></h3>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <i class="fas fa-award text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Row 2-3: Columns (Activity & Progress) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Column: ความเคลื่อนไหวรายวัน (Activity Log) -->
                <div class="lg:col-span-2 bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-bold text-gray-900">ความเคลื่อนไหวรายวัน (Activity)</h4>
                        <span class="text-xs text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full font-semibold">วันนี้</span>
                    </div>
                    <!-- Timeline items -->
                    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2">
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="w-2 h-2 rounded-full bg-blue-500 mt-2"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700"><span class="font-semibold text-gray-900">ภญ.สมหญิง รักดี</span> ทำแบบทดสอบผ่าน หลักสูตรการจ่ายยาเสพติดให้โทษ</p>
                                <span class="text-xs text-gray-400">10:45 น.</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="w-2 h-2 rounded-full bg-green-500 mt-2"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700"><span class="font-semibold text-gray-900">ดนัย ใจมั่น (กลุ่มงานการพยาบาล)</span> ได้รับ Certificate หลักสูตร CPR 2026</p>
                                <span class="text-xs text-gray-400">09:15 น.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column: อัตราความก้าวหน้า (Progress Bar Card) -->
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">อัตราความก้าวหน้าการอบรม</h4>
                        <p class="text-sm text-gray-500 mb-6">สัดส่วนบุคลากรที่อบรมครบหลักสูตรทั้งหมด</p>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Circular/Percentage Large Display -->
                        <div class="text-center">
                            <span class="text-5xl font-black text-blue-600">63.3%</span>
                            <p class="text-xs font-semibold text-gray-400 mt-1 uppercase tracking-wider">ภาพรวมความสำเร็จ</p>
                        </div>

                        <!-- Custom Progress Bar -->
                        <div>
                            <div class="flex justify-between text-sm mb-1.5 font-medium">
                                <span class="text-gray-600">อบรมครบแล้ว / ทั้งหมด</span>
                                <span class="text-gray-900">95 / 150 คน</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-500" style="width: 63.3%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 4: Graphs (แยกระดับการอบรม & แยกกลุ่มงาน) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- กราฟแยกระดับการอบรม -->
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">สัดส่วนระดับการอบรม</h4>
                    <div class="relative h-64">
                        <canvas id="trainingLevelChart"></canvas>
                    </div>
                </div>

                <!-- กราฟแยกกลุ่มงาน -->
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">สถิติการเข้าอบรมแยกตามกลุ่มงาน</h4>
                    <div class="relative h-64">
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
        </section>

        <!-- 2. PAGE: UPLOAD FILE -->
        <section id="page-upload" class="page-content space-y-6 hidden">
            <h2 class="text-2xl font-bold text-slate-800"><i class="fa-solid fa-cloud-arrow-up mr-2 text-indigo-600"></i>อัปโหลดหลักฐานเอกสาร</h2>
            <div class="bg-white p-6 rounded-xl shadow-sm max-w-2xl mx-auto">
                <form id="uploadForm" enctype="multipart/form-data">
                    <div id="dropzone" class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center cursor-pointer hover:border-indigo-400 transition bg-slate-50 flex flex-col items-center justify-center">
                        <i class="fa-solid fa-file-pdf text-5xl text-slate-400 mb-4"></i>
                        <p class="text-slate-600 font-medium">ลากไฟล์ภาพ หรือไฟล์ PDF มาวางที่นี่</p>
                        <input type="file" id="fileInput" name="attachments[]" accept="image/*, application/pdf" class="hidden" multiple>
                    </div>
                    <div id="fileList" class="mt-6 space-y-2 hidden">
                        <h4 class="font-semibold text-slate-700 text-sm">ไฟล์คิวที่เตรียมอัปโหลด:</h4>
                        <div id="previewContainer" class="space-y-2"></div>
                    </div>
                    <div class="mt-6 text-right">
                        <button type="submit" class="btn-glossy text-white font-semibold py-2.5 px-6 rounded-lg text-sm shadow-md">
                            <i class="fa-solid fa-paper-plane mr-2"></i>เริ่มอัปโหลดข้อมูลไปยังเซิร์ฟเวอร์
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- 3. PAGE: COURSE MANAGEMENT -->
        <section id="page-course" class="page-content space-y-6 hidden">
            <h2 class="text-2xl font-bold text-slate-800"><i class="fa-solid fa-graduation-cap mr-2 text-indigo-600"></i>ทำเนียบหลักสูตรอบรม</h2>
            
            <!-- NavTab จากฐานข้อมูล -->
            <div class="bg-white shadow-sm rounded-xl p-2 overflow-x-auto">
                <div class="flex space-x-1 min-w-[700px] md:min-w-full">
                    <button data-level="all" class="tab-btn flex-1 py-2.5 text-sm font-semibold rounded-lg bg-indigo-600 text-white shadow-sm">ทั้งหมด</button>
                    <?php foreach($levels as $level): ?>
                        <button data-level="<?= $level['level_id'] ?>" class="tab-btn flex-1 py-2.5 text-sm font-semibold rounded-lg text-slate-600 hover:bg-slate-100 transition"><?= esc($level['level_name']) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Course Grid Area -->
            <div id="course-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach($courses as $course): ?>
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100 course-card">
                        <div class="p-5 space-y-4">
                            <div class="flex justify-between items-start">
                                <span class="<?= $course['course_type'] === 'บังคับ' ? 'bg-rose-100 text-rose-700' : 'bg-indigo-100 text-indigo-700' ?> text-xs font-bold px-2.5 py-1 rounded-full">
                                    หลักสูตร<?= esc($course['course_type']) ?>
                                </span>
                                <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-full">
                                    <i class="fa-solid <?= $course['course_method'] === 'online' ? 'fa-house-laptop' : 'fa-users' ?> mr-1"></i><?= esc($course['course_method']) ?>
                                </span>
                            </div>
                            <h4 class="font-bold text-slate-800 text-lg"><?= esc($course['course_name']) ?></h4>
                            <p class="text-slate-400 text-xs">ระดับ: <?= esc($course['level_name']) ?></p>
                            <button class="w-full btn-glossy text-white text-center text-sm font-semibold py-2 rounded-lg mt-2">
                                สมัครอบรมหลักสูตรนี้
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- รวมไลบรารี Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- เรียกใช้ไฟล์สคริปต์หลักท้ายหน้าเว็บ -->
    <script src="<?= config('App')->assetURL; ?>js/main.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. กราฟแยกระดับการอบรม (แนะนำใช้ Donut/Pie Chart)
        const ctxLevel = document.getElementById('trainingLevelChart').getContext('2d');
        new Chart(ctxLevel, {
            type: 'doughnut',
            data: {
                labels: ['ระดับพื้นฐาน (Basic)', 'ระดับกลาง (Intermediate)', 'ระดับสูง (Advanced)'],
                datasets: [{
                    data: [60, 45, 15],
                    backgroundColor: ['#60a5fa', '#34d399', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '70%' // ทำเป็นโดนัทบางๆ ดูทันสมัย
            }
        });

        // 2. กราฟแยกกลุ่มงาน (แนะนำใช้ Bar Chart แนวนอน หรือ แนวตั้ง)
        const ctxDept = document.getElementById('departmentChart').getContext('2d');
        new Chart(ctxDept, {
            type: 'bar',
            data: {
                labels: ['องค์กรแพทย์', 'การพยาบาล', 'เทคนิคการแพทย์', 'เภสัชกรรม', 'บริหารทั่วไป', 'รพ.สต.'],
                datasets: [{
                    label: 'จำนวนคนอบรมครบ (คน)',
                    data: [12, 45, 8, 15, 20, 32],
                    backgroundColor: '#6366f1',
                    borderRadius: 8 // ลบมุมแท่งกราฟให้มนสวยงาม
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false } // ปิด Legend เพราะมี label แกนบอกอยู่แล้ว
                },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { color: '#f3f4f6' } }
                }
            }
        });

    });
    </script>
    <script>
        $(document).ready(function() {
            // สลับหน้าเมนู
            $('.nav-link').on('click', function(e) {
                e.preventDefault();
                const targetPage = $(this).data('target');
                $('.nav-link').removeClass('text-cyan-400 border-cyan-400 bg-slate-800').addClass('border-transparent text-slate-300');
                $(this).addClass('text-cyan-400 border-cyan-400 bg-slate-800');
                $('.page-content').addClass('hidden');
                $(`#${targetPage}`).removeClass('hidden');
            });

            // Drag and Drop Logic
            const dropzone = $('#dropzone');
            const fileInput = $('#fileInput');
            let fileBuffer = []; // เก็บอาร์เรย์ของไฟล์จริงเพื่อส่งฟอร์ม

            dropzone.on('click', function() { fileInput.click(); });
            dropzone.on('dragover', function(e) { e.preventDefault(); dropzone.addClass('drag-over'); });
            dropzone.on('dragleave drop', function(e) { e.preventDefault(); dropzone.removeClass('drag-over'); });
            dropzone.on('drop', function(e) { handleFiles(e.originalEvent.dataTransfer.files); });
            fileInput.on('change', function() { handleFiles(this.files); });

            function handleFiles(files) {
                if (files.length > 0) {
                    $('#fileList').removeClass('hidden');
                    $.each(files, function(i, file) {
                        fileBuffer.push(file);
                        const row = `
                            <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                                <span class="font-medium text-slate-700 truncate max-w-xs">${file.name}</span>
                                <button type="button" class="text-slate-400 hover:text-rose-500 remove-file-btn"><i class="fa-solid fa-trash"></i></button>
                            </div>`;
                        $('#previewContainer').append(row);
                    });
                }
            }

            // AJAX การอัปโหลดไฟล์จริงเข้า CodeIgniter 4 Backend
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();
                if(fileBuffer.length === 0) return alert('กรุณาเลือกไฟล์ก่อน');

                let formData = new FormData();
                $.each(fileBuffer, function(i, file){
                    formData.append('attachments[]', file);
                });

                $.ajax({
                    url: '<?= base_url("dashboard/upload") ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        alert(res.message);
                        fileBuffer = [];
                        $('#previewContainer').empty();
                        $('#fileList').addClass('hidden');
                    },
                    error: function(xhr) {
                        alert('เกิดข้อผิดพลาดในการอัปโหลดไฟล์');
                    }
                });
            });

            // AJAX การทำ Real-time Tab Filter ของคอร์ส
            $('.tab-btn').on('click', function() {
                $('.tab-btn').removeClass('bg-indigo-600 text-white shadow-sm').addClass('text-slate-600 hover:bg-slate-100');
                $(this).addClass('bg-indigo-600 text-white shadow-sm').removeClass('text-slate-600 hover:bg-slate-100');
                
                const levelId = $(this).data('level');

                $.ajax({
                    url: '<?= base_url("courses/filter") ?>',
                    type: 'GET',
                    data: { level_id: levelId },
                    success: function(courses) {
                        let html = '';
                        $.each(courses, function(i, course) {
                            let typeClass = course.course_type === 'บังคับ' ? 'bg-rose-100 text-rose-700' : 'bg-indigo-100 text-indigo-700';
                            let methodIcon = course.course_method === 'online' ? 'fa-house-laptop' : 'fa-users';
                            
                            html += `
                                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
                                    <div class="p-5 space-y-4">
                                        <div class="flex justify-between items-start">
                                            <span class="${typeClass} text-xs font-bold px-2.5 py-1 rounded-full">หลักสูตร${course.course_type}</span>
                                            <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-full"><i class="fa-solid ${methodIcon} mr-1"></i>${course.course_method}</span>
                                        </div>
                                        <h4 class="font-bold text-slate-800 text-lg">${course.course_name}</h4>
                                        <p class="text-slate-400 text-xs">ระดับ: ${course.level_name}</p>
                                        <button class="w-full btn-glossy text-white text-center text-sm font-semibold py-2 rounded-lg mt-2">สมัครอบรมหลักสูตรนี้</button>
                                    </div>
                                </div>`;
                        });
                        $('#course-container').html(html);
                    }
                });
            });
        });
    </script>
</body>
</html>