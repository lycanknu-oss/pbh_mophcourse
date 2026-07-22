<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_content') ?>

<!-- ========================================================================= -->
<!-- 🏠 TAB 0: หน้าแรก (ภาพรวมระบบสำหรับ Admin) -->
<!-- ========================================================================= -->
<div id="overview-tab" class="admin-tab-content space-y-6">
    <!-- หัวข้อ -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-gray-900">ภาพรวมระบบผู้ดูแลระบบ (Admin Overview)</h3>
            <p class="text-xs text-gray-500 mt-1">สรุปข้อมูลสถิติต่าง ๆ ในระบบ MOPH Digital Training</p>
        </div>
        <div class="text-xs bg-blue-50 text-[#154c9f] px-3 py-1.5 rounded-xl font-semibold border border-blue-100">
            <i class="bi bi-clock-history"></i> อัปเดตล่าสุด: <?= date('Y-m-d H:i') ?>
        </div>
    </div>

    <!-- 📊 สถิติตัวเลขสรุปย่อ (Stat Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- การ์ด 1: จำนวนผู้ใช้งานระบบ -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500">ผู้ใช้งานในระบบ (tr_staff)</p>
                <h4 class="text-2xl font-bold text-gray-900 mt-1"><?= count($staffs ?? []) ?> <span class="text-xs font-normal text-gray-400">คน</span></h4>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-[#154c9f] rounded-2xl flex items-center justify-center text-xl font-bold">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>

        <!-- การ์ด 2: จำนวนบุคลากรทั้งหมด -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500">บุคลากรทั้งหมด (tr_employee)</p>
                <h4 class="text-2xl font-bold text-teal-600 mt-1"><?= count($employees ?? []) ?> <span class="text-xs font-normal text-gray-400">คน</span></h4>
            </div>
            <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center text-xl font-bold">
                <i class="bi bi-person-badge-fill"></i>
            </div>
        </div>

        <!-- การ์ด 3: จำนวนกลุ่มงาน -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500">กลุ่มงาน (Workgroups)</p>
                <h4 class="text-2xl font-bold text-indigo-600 mt-1"><?= count($workgroups ?? []) ?> <span class="text-xs font-normal text-gray-400">กลุ่ม</span></h4>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl font-bold">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
        </div>

        <!-- การ์ด 4: สถานะเซิร์ฟเวอร์ -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500">สถานะระบบ</p>
                <h4 class="text-lg font-bold text-emerald-600 mt-1 flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> ปกติ
                </h4>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl font-bold">
                <i class="bi bi-hdd-network-fill"></i>
            </div>
        </div>
    </div>

    <!-- ⚡ เมนูลัดการจัดการ (Quick Actions) -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h4 class="text-sm font-bold text-gray-800 mb-4">การจัดการด่วน (Quick Actions)</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button onclick="$('#nav-users').click()" class="p-4 bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 rounded-xl transition-all text-left group">
                <i class="bi bi-person-plus text-blue-600 text-xl block mb-2 group-hover:scale-110 transition-transform"></i>
                <p class="text-xs font-semibold text-gray-800">จัดการสิทธิ์ผู้ใช้งาน</p>
                <p class="text-[10px] text-gray-500 mt-0.5">เพิ่ม/แก้ไข บัญชี tr_staff</p>
            </button>

            <button onclick="$('#nav-employees').click()" class="p-4 bg-gray-50 hover:bg-teal-50 border border-gray-200 hover:border-teal-200 rounded-xl transition-all text-left group">
                <i class="bi bi-file-earmark-person text-teal-600 text-xl block mb-2 group-hover:scale-110 transition-transform"></i>
                <p class="text-xs font-semibold text-gray-800">จัดการบุคลากร</p>
                <p class="text-[10px] text-gray-500 mt-0.5">อัปเดตข้อมูล tr_employee</p>
            </button>

            <button onclick="$('#nav-courses').click()" class="p-4 bg-gray-50 hover:bg-indigo-50 border border-gray-200 hover:border-indigo-200 rounded-xl transition-all text-left group">
                <i class="bi bi-journal-plus text-indigo-600 text-xl block mb-2 group-hover:scale-110 transition-transform"></i>
                <p class="text-xs font-semibold text-gray-800">เพิ่มหลักสูตรใหม่</p>
                <p class="text-[10px] text-gray-500 mt-0.5">เปิดหลักสูตรการฝึกอบรม</p>
            </button>

            <a href="<?= base_url('dashboard.php') ?>" target="_blank" class="p-4 bg-gray-50 hover:bg-amber-50 border border-gray-200 hover:border-amber-200 rounded-xl transition-all text-left group">
                <i class="bi bi-box-arrow-up-right text-amber-600 text-xl block mb-2 group-hover:scale-110 transition-transform"></i>
                <p class="text-xs font-semibold text-gray-800">ดูหน้าเว็บฝั่งผู้ใช้งาน</p>
                <p class="text-[10px] text-gray-500 mt-0.5">เปิดหน้า User Dashboard</p>
            </a>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 📌 TAB 1: หน้าข้อมูลหลักสูตรอบรม -->
<!-- ========================================================================= -->
<div id="courses-tab" class="admin-tab-content space-y-6 hidden">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-gray-900">จัดการข้อมูลหลักสูตรอบรม</h3>
            <p class="text-xs text-gray-500 mt-1">เพิ่ม แก้ไข และจัดหมวดหมู่หลักสูตรอบรมการพัฒนาบุคลากร</p>
        </div>
        <button class="bg-[#154c9f] hover:bg-[#113b7a] text-white px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center gap-2 shadow-md transition-all">
            <i class="bi bi-plus-circle"></i> เพิ่มหลักสูตรใหม่
        </button>
    </div>

    <!-- ตารางแสดงหลักสูตร -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs text-gray-600">
            <thead class="bg-gray-50 text-gray-700 font-semibold uppercase border-b border-gray-200">
                <tr>
                    <th class="p-4">รหัสหลักสูตร</th>
                    <th class="p-4">ชื่อหลักสูตรอบรม</th>
                    <th class="p-4">กลุ่มเป้าหมาย</th>
                    <th class="p-4 text-center">สถานะ</th>
                    <th class="p-4 text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50/50">
                    <td class="p-4 font-semibold text-gray-900">CRS-2026-01</td>
                    <td class="p-4 font-medium text-gray-800">การใช้งานระบบสารสนเทศสุขภาพยุคดิจิทัล</td>
                    <td class="p-4">บุคลากรทางการแพทย์ และ IT</td>
                    <td class="p-4 text-center"><span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full font-semibold text-[10px]">เปิดรับสมัคร</span></td>
                    <td class="p-4 text-center space-x-2">
                        <button class="text-blue-600 hover:text-blue-800"><i class="bi bi-pencil-square text-base"></i></button>
                        <button class="text-rose-600 hover:text-rose-800"><i class="bi bi-trash text-base"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 📌 TAB 2: หน้าข้อมูลบุคลากร (ดึงตาราง tr_employee) -->
<!-- ========================================================================= -->
<div id="employees-tab" class="admin-tab-content space-y-6 hidden">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-gray-900">ทะเบียนข้อมูลบุคลากร (tr_employee)</h3>
            <p class="text-xs text-gray-500 mt-1">จัดการรายชื่อตำแหน่ง กลุ่มงาน และหน่วยงานสังกัด</p>
        </div>
        <button class="bg-[#154c9f] hover:bg-[#113b7a] text-white px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center gap-2 shadow-md transition-all">
            <i class="bi bi-person-plus"></i> เพิ่มบุคลากร
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs text-gray-600">
            <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200">
                <tr>
                    <th class="p-4">เลขบัตรประชาชน (CID)</th>
                    <th class="p-4">ชื่อ - สกุล</th>
                    <th class="p-4">ตำแหน่ง</th>
                    <th class="p-4">กลุ่มงาน / แผนก</th>
                    <th class="p-4 text-center">อัปเดตล่าสุด</th>
                    <th class="p-4 text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50/50">
                    <td class="p-4 font-mono font-semibold">134xxxxxxxxxx</td>
                    <td class="p-4 font-medium text-gray-900">นายสมชาย เข็มกลัด</td>
                    <td class="p-4">นักวิชาการคอมพิวเตอร์</td>
                    <td class="p-4">กลุ่มงานประกันสุขภาพ</td>
                    <td class="p-4 text-center text-gray-400">2026-07-20</td>
                    <td class="p-4 text-center space-x-2">
                        <button class="text-blue-600 hover:text-blue-800"><i class="bi bi-pencil-square text-base"></i></button>
                        <button class="text-rose-600 hover:text-rose-800"><i class="bi bi-trash text-base"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 📌 TAB 3: หน้าจัดการข้อมูลผู้ใช้งาน (ดึงตาราง tr_staff) -->
<!-- ========================================================================= -->
<div id="users-tab" class="admin-tab-content space-y-6 hidden">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-gray-900">จัดการข้อมูลผู้ใช้งานระบบ (tr_staff)</h3>
            <p class="text-xs text-gray-500 mt-1">จัดการบัญชีผู้ใช้งาน สิทธิ์การเข้าถึง (Role) และการรีเซ็ตรหัสผ่าน</p>
        </div>
        <button class="bg-[#154c9f] hover:bg-[#113b7a] text-white px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center gap-2 shadow-md transition-all">
            <i class="bi bi-shield-plus"></i> เพิ่มสิทธิ์ผู้ใช้งาน
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs text-gray-600">
            <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200">
                <tr>
                    <th class="p-4">ID</th>
                    <th class="p-4">ชื่อผู้ใช้งาน (Username)</th>
                    <th class="p-4">ชื่อ - สกุล</th>
                    <th class="p-4">รหัสหน่วยงาน (Hcode)</th>
                    <th class="p-4 text-center">สิทธิ์ (Role)</th>
                    <th class="p-4 text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50/50">
                    <td class="p-4 font-mono">1</td>
                    <td class="p-4 font-bold text-blue-900">admin</td>
                    <td class="p-4 font-medium text-gray-900">ผู้ดูแลระบบ</td>
                    <td class="p-4 font-mono">10956</td>
                    <td class="p-4 text-center">
                        <span class="bg-indigo-50 text-indigo-600 border border-indigo-200 px-2.5 py-0.5 rounded-full font-bold text-[10px]">
                            ADMIN
                        </span>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <button class="text-blue-600 hover:text-blue-800"><i class="bi bi-key text-base"></i></button>
                        <button class="text-rose-600 hover:text-rose-800"><i class="bi bi-trash text-base"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>