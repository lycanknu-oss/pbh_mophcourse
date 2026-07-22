<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'เข้าสู่ระบบ | MOPH Digital Training' ?></title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- 🔤 Google Fonts: Prompt & Sarabun -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Prompt', 'sans-serif'],
            }
          }
        }
      }
    </script>
    
    <!-- 🎨 SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- เรียกใช้ไฟล์ CSS หลักประจำระบบ -->
    <link rel="stylesheet" href="<?= config('App')->assetURL; ?>assets/css/main.css">

    <style>
        .login-bg {
            background: linear-gradient(135deg, #eef2f7 0%, #d9e2ec 50%, #154c9f 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center p-4 font-sans antialiased">

    <!-- 🌟 กล่อง Login สไตล์กระจกฝ้า -->
    <div class="glass-card w-full max-w-md p-8 rounded-3xl shadow-2xl transition-all duration-300">
        
        <!-- โลโก้และหัวข้อ -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#154c9f] text-white shadow-lg mb-4">
                <i class="bi bi-hospital text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-wide">MOPH Digital Training</h1>
            <p class="text-xs text-gray-500 mt-1">กระทรวงสาธารณสุข | Ministry of Public Health</p>
        </div>

        <!-- ฟอร์มเข้าสู่ระบบ (ส่งผ่าน AJAX) -->
        <form id="loginForm" class="space-y-5">
            <?= csrf_field() ?>

            <!-- ไอดีผู้ใช้งาน -->
            <div>
                <label for="username" class="block text-xs font-semibold text-gray-700 mb-1.5 pl-1">ชื่อผู้ใช้งาน (Username)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                        <i class="bi bi-person-vcard"></i>
                    </span>
                    <input type="text" name="username" id="username" required
                        class="w-full pl-10 pr-4 py-3 bg-white/80 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#154c9f] focus:border-transparent transition-all placeholder:text-gray-400"
                        placeholder="กรอกชื่อผู้ใช้งานของคุณ">
                </div>
            </div>

            <!-- รหัสผ่าน -->
            <div>
                <div class="flex items-center justify-between mb-1.5 pl-1">
                    <label for="password" class="block text-xs font-semibold text-gray-700">รหัสผ่าน (Password)</label>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" name="password" id="password" required
                        class="w-full pl-10 pr-11 py-3 bg-white/80 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#154c9f] focus:border-transparent transition-all placeholder:text-gray-400"
                        placeholder="••••••••">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- ปุ่มส่งข้อมูล -->
            <button type="submit" id="btnSubmit" class="w-full py-3 bg-[#154c9f] hover:bg-[#113b7a] text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-900/20 hover:shadow-xl transition-all flex items-center justify-center gap-2 group">
                <span>เข้าสู่ระบบ</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1" id="btnIcon"></i>
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                &copy; 2026 MOPH Digital Training System
            </p>
        </div>

    </div>

    <!-- ⚡ สคริปต์ jQuery + SweetAlert2 + localStorage -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
        extend: {
            fontFamily: {
            sans: ['Sarabun', 'sans-serif'],    /* ค่าเริ่มต้นเนื้อหาทั่วไปใช้ Sarabun */
            heading: ['Prompt', 'sans-serif'],  /* คลาสพิเศษสำหรับหัวข้อ/เน้นข้อความ */
            body: ['Sarabun', 'sans-serif'],
            }
        }
        }
    }
    </script>
    <script>
        $(document).ready(function() {
            // 👁️ 1. เปิด-ปิดตาดูรหัสผ่าน
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#password');
                const eyeIcon = $('#eyeIcon');
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    eyeIcon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    eyeIcon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });

            // ⚡ 2. จัดการส่งฟอร์มด้วย AJAX และแสดง SweetAlert2
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                const btn = $('#btnSubmit');
                const btnIcon = $('#btnIcon');
                
                // ล็อกปุ่มชั่วคราว
                btn.prop('disabled', true).addClass('opacity-75');
                btn.find('span').text('กำลังตรวจสอบข้อมูล...');
                btnIcon.removeClass('bi-arrow-right').addClass('bi-arrow-repeat animate-spin');

                $.ajax({
                    url: '<?= base_url('auth/loginCheck') ?>',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            
                            // 🔑 เก็บ JWT Token และข้อมูลสำคัญลง localStorage
                            if (response.token) {
                                localStorage.setItem('jwt_token', response.token);
                                localStorage.setItem('user_info', JSON.stringify(response.user || {}));
                            }

                            // 🟢 แสดง SweetAlert2 แจ้งเตือนเข้าสู่ระบบสำเร็จ
                            Swal.fire({
                                icon: 'success',
                                title: 'เข้าสู่ระบบสำเร็จ!',
                                text: 'กำลังพาคุณเข้าสู่ระบบ...',
                                confirmButtonColor: '#154c9f',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then(() => {
                                // ย้ายหน้าไปยังแดชบอร์ดตามสิทธิ์ผู้ใช้
                                window.location.href = response.redirect_url || '<?= base_url('dashboard.php') ?>';
                            });

                        } else {
                            // 🔴 แสดง SweetAlert2 แจ้งเตือนข้อผิดพลาดจาก Backend
                            Swal.fire({
                                icon: 'error',
                                title: 'เข้าสู่ระบบไม่สำเร็จ',
                                text: response.message || 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง',
                                confirmButtonColor: '#154c9f'
                            });

                            resetButton();
                        }
                    },
                    error: function(xhr, status, error) {
                        // 🔴 แจ้งเตือนกรณีเกิด Server Error
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้ กรุณาลองใหม่อีกครั้ง',
                            confirmButtonColor: '#e11d48'
                        });

                        resetButton();
                    }
                });

                function resetButton() {
                    btn.prop('disabled', false).removeClass('opacity-75');
                    btn.find('span').text('เข้าสู่ระบบ');
                    btnIcon.removeClass('bi-arrow-repeat animate-spin').addClass('bi-arrow-right');
                }
            });
        });
    </script>
</body>
</html>