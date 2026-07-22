<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_content') ?>
<style>
    .filter-btn.active {
        background-color: #154c9f !important;
        color: #ffffff !important;
        border-color: #154c9f !important;
        box-shadow: 0 4px 10px rgba(21, 76, 159, 0.2);
    }
    .pos-card-wrapper.pos-2 {
        width: 100% !important;
        max-width: 100% !important;
    }
</style>

<!-- 🚀 Header Section -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-heading flex items-center gap-2">
            <i class="bi bi-journal-bookmark-fill text-[#154c9f]"></i>
            จัดการข้อมูลหลักสูตรอบรม (MOPH Training)
        </h1>
        <p class="text-sm text-gray-500 font-body">บริหารจัดการหมวดหมู่หลักสูตร และระบบการอบรมสำหรับบุคลากร</p>
    </div>
    
    <button onclick="openCourseModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#154c9f] hover:bg-[#0f3877] text-white font-medium text-sm rounded-xl shadow-md transition-all font-heading">
        <i class="bi bi-plus-circle text-lg"></i>
        <span>เพิ่มหลักสูตรอบรม</span>
    </button>
</div>

<!-- 🎛️ Tab ตัวกรองตำแหน่งอบรม -->
<div class="mb-6 flex gap-2 flex-wrap font-heading" id="filter-tabs">
    <button class="filter-btn active bg-white text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold border border-gray-200" data-target="all">
        <i class="bi bi-grid-fill me-1"></i>ทุกตำแหน่ง
    </button>
    <button class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold border border-gray-200" data-target="pos-1">
        <i class="bi bi-person-badge-fill text-blue-600 me-1"></i>ผู้อำนวยการโรงพยาบาล
    </button>
    <button class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold border border-gray-200" data-target="pos-2">
        <i class="bi bi-person-workspace text-teal-600 me-1"></i>รองผู้อำนวยการ / ผู้ช่วยผู้อำนวยการ
    </button>
    <button class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold border border-gray-200" data-target="pos-3">
        <i class="bi bi-diagram-3-fill text-indigo-600 me-1"></i>หัวหน้ากลุ่มงาน
    </button>
    <button class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold border border-gray-200" data-target="pos-4">
        <i class="bi bi-cpu-fill text-amber-600 me-1"></i>เจ้าหน้าที่ IT / บุคลากรดิจิทัล
    </button>
    <button class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold border border-gray-200" data-target="pos-5">
        <i class="bi bi-people-fill text-rose-600 me-1"></i>เจ้าหน้าที่ / บุคลากรทั่วไป
    </button>
</div>

<!-- 🏢 POS Card Wrapper (pos-2 เต็มความกว้าง) -->
<div id="course-cards-container" class="space-y-6">
    <div class="pos-card-wrapper pos-2 bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6" data-pos="pos-2">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-5">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl font-bold">
                    <i class="bi bi-journal-check"></i>
                </span>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 font-heading">ทะเบียนหลักสูตรอบรม (ตาราง tr_course)</h3>
                    <p class="text-sm text-gray-500">จัดการข้อมูลระดับ ประเภท และคำอธิบายหลักสูตร</p>
                </div>
            </div>
            <span class="px-3 py-1 bg-teal-100 text-teal-700 text-sm font-semibold rounded-full font-heading">
                รวม <?= count($courses ?? []) ?> หลักสูตร
            </span>
        </div>

        <!-- 🔍 ส่วน Filter กรองตาม course_type -->
        <div class="mb-4 flex flex-col md:flex-row items-center justify-between gap-3 bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center gap-2 w-full md:w-auto">
                <label for="filterCourseType" class="text-sm font-semibold text-slate-700 whitespace-nowrap">
                    <i class="bi bi-funnel-fill text-blue-600"></i> กรองตามระดับ:
                </label>
                <select id="filterCourseType" onchange="filterCourses()" class="w-full md:w-64 text-sm bg-slate-50 border border-slate-300 text-slate-800 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition-colors">
                    <option value="all">-- ทั้งหมด --</option>
                    <option value="1">1. ผู้บริหารสูงสุดขององค์กร</option>
                    <option value="2">2. ผู้บริหารหรือผู้ที่ได้รับมอบหมาย</option>
                    <option value="3">3. หัวหน้ากลุ่มงาน</option>
                    <option value="4">4. เจ้าหน้าที่ IT</option>
                    <option value="5">5. เจ้าหน้าที่ / บุคคลทั่วไป</option>
                </select>
            </div>
            
            <div class="text-sm text-slate-500 font-medium">
                แสดงทั้งหมด <span id="courseCount" class="font-bold text-blue-600">0</span> รายการ
            </div>
        </div>

        <!-- 📋 ตารางรายการหลักสูตร -->
        <div class="overflow-x-auto rounded-xl border border-slate-200/80 shadow-sm">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-100/80 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 text-center">ลำดับ</th>
                        <th class="py-3 px-4">ชื่อหลักสูตร</th>
                        <th class="py-3 px-4">ระดับการอบรม</th>
                        <th class="py-3 px-4">ประเภทการอบรม</th>
                        <th class="py-3 px-4 text-center">ความจำเป็น</th> <!-- 🟢 เพิ่มหัวข้อนี้ -->
                        <th class="py-3 px-4 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="courseTableBody">
                    <?php if (!empty($courses)): ?>
                        <?php foreach ($courses as $index => $c): ?>
                            <tr id="course-row-<?= $c['course_id'] ?? $c['id'] ?>" data-type="<?= esc($c['course_type']) ?>" class="course-row bg-white border-b hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 text-center font-medium text-slate-500"><?= $index + 1 ?></td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800"><?= esc($c['course_name']) ?></td>
                                <td class="py-3.5 px-4">
                                    <div class="badge-course-type" data-type="<?= esc($c['course_type']) ?>"></div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="badge-course-method" data-method="<?= esc($c['course_method']) ?>"></div>
                                </td>
                                <!-- 🟢 เพิ่มคอลัมน์แสดงความจำเป็น -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="badge-course-fix" data-fix="<?= esc($c['course_fix'] ?? '1') ?>"></div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button onclick='editCourse(<?= json_encode($c) ?>)' class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="แก้ไข">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </button>
                                        <button onclick="deleteCourse(<?= $c['course_id'] ?? $c['id'] ?>)" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="ลบ">
                                            <i class="bi bi-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="noDataRow">
                            <td colspan="6" class="py-8 text-center text-slate-400">ไม่พบข้อมูลหลักสูตร</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 📌 MODAL: เพิ่ม/แก้ไขหลักสูตรอบรม (ขยายเป็น Modal-LG ด้วย max-w-2xl) -->
<!-- ========================================================================= -->
<div id="courseModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden backdrop-blur-sm">
    
    <!-- 🟢 เปลี่ยน max-w-lg เป็น max-w-2xl (หรือ max-w-3xl) เพื่อขยายขนาด Modal -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all">
        
        <!-- Modal Header -->
        <div class="bg-[#154c9f] px-6 py-4 text-white flex items-center justify-between shrink-0">
            <h3 id="modalTitle" class="text-base font-bold font-heading flex items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>เพิ่มหลักสูตรอบรม</span>
            </h3>
            <button type="button" onclick="closeCourseModal()" class="text-white/80 hover:text-white text-xl transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Modal Form Body (เพิ่ม overflow-y-auto ป้องกันกรณีจอมือถือสั้น) -->
        <form id="courseForm" class="p-6 space-y-4 font-body overflow-y-auto custom-scrollbar">
            <input type="hidden" name="course_id" id="course_id">

            <!-- 1. ระดับการอบรม (course_type) -> Glassmorphic Button Group Grid -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-bold text-gray-800 font-heading">
                        1. ระดับการอบรม <span class="text-[11px] font-normal text-slate-500">(เลือกได้หลายระดับ)</span> <span class="text-red-500">*</span>
                    </label>
                    <button type="button" onclick="selectAllCourseTypes()" class="text-[11px] text-[#154c9f] hover:underline font-semibold font-heading flex items-center gap-1">
                        <i class="bi bi-check-all text-sm"></i>
                        <span>เลือกทั้งหมด</span>
                    </button>
                </div>

                <div class="glass-btn-group" id="course_type_wrapper">
                    <!-- Button 1 -->
                    <label class="glass-btn-item">
                        <input type="checkbox" name="course_type[]" value="1" class="chk-course-type hidden">
                        <div class="flex items-center gap-2 overflow-hidden">
                            <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center text-sm font-bold shrink-0">
                                <i class="bi bi-award-fill"></i>
                            </span>
                            <div class="truncate">
                                <div class="text-sm font-bold text-slate-800 font-heading">1. ผู้อำนวยการ รพ.</div>
                                <div class="text-[10px] text-slate-400 font-normal truncate">ผู้บริหารสูงสุด</div>
                            </div>
                        </div>
                        <div class="glass-btn-check ms-1">
                            <i class="bi bi-check-lg text-[10px] font-bold"></i>
                        </div>
                    </label>

                    <!-- Button 2 -->
                    <label class="glass-btn-item">
                        <input type="checkbox" name="course_type[]" value="2" class="chk-course-type hidden">
                        <div class="flex items-center gap-2 overflow-hidden">
                            <span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center text-sm font-bold shrink-0">
                                <i class="bi bi-person-workspace"></i>
                            </span>
                            <div class="truncate">
                                <div class="text-sm font-bold text-slate-800 font-heading">2. รองผอ. / ผู้ช่วยผอ.</div>
                                <div class="text-[10px] text-slate-400 font-normal truncate">ผู้บริหาร/ผู้ได้รับมอบหมาย</div>
                            </div>
                        </div>
                        <div class="glass-btn-check ms-1">
                            <i class="bi bi-check-lg text-[10px] font-bold"></i>
                        </div>
                    </label>

                    <!-- Button 3 -->
                    <label class="glass-btn-item">
                        <input type="checkbox" name="course_type[]" value="3" class="chk-course-type hidden">
                        <div class="flex items-center gap-2 overflow-hidden">
                            <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm font-bold shrink-0">
                                <i class="bi bi-diagram-3-fill"></i>
                            </span>
                            <div class="truncate">
                                <div class="text-sm font-bold text-slate-800 font-heading">3. หัวหน้ากลุ่มงาน</div>
                                <div class="text-[10px] text-slate-400 font-normal truncate">ระดับบริหารกลุ่มงาน</div>
                            </div>
                        </div>
                        <div class="glass-btn-check ms-1">
                            <i class="bi bi-check-lg text-[10px] font-bold"></i>
                        </div>
                    </label>

                    <!-- Button 4 -->
                    <label class="glass-btn-item">
                        <input type="checkbox" name="course_type[]" value="4" class="chk-course-type hidden">
                        <div class="flex items-center gap-2 overflow-hidden">
                            <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold shrink-0">
                                <i class="bi bi-cpu-fill"></i>
                            </span>
                            <div class="truncate">
                                <div class="text-sm font-bold text-slate-800 font-heading">4. เจ้าหน้าที่ IT</div>
                                <div class="text-[10px] text-slate-400 font-normal truncate">บุคลากรด้านดิจิทัล</div>
                            </div>
                        </div>
                        <div class="glass-btn-check ms-1">
                            <i class="bi bi-check-lg text-[10px] font-bold"></i>
                        </div>
                    </label>

                    <!-- Button 5 -->
                    <label class="glass-btn-item sm:col-span-2">
                        <input type="checkbox" name="course_type[]" value="5" class="chk-course-type hidden">
                        <div class="flex items-center gap-2 overflow-hidden">
                            <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-sm font-bold shrink-0">
                                <i class="bi bi-people-fill"></i>
                            </span>
                            <div class="truncate">
                                <div class="text-sm font-bold text-slate-800 font-heading">5. เจ้าหน้าที่ / บุคลากรทั่วไป</div>
                                <div class="text-[10px] text-slate-400 font-normal truncate">เจ้าหน้าที่ผู้ปฏิบัติงานทุกระดับ</div>
                            </div>
                        </div>
                        <div class="glass-btn-check ms-1">
                            <i class="bi bi-check-lg text-[10px] font-bold"></i>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 2. ประเภทการอบรม (course_method) & 3. ชื่อหลักสูตร (วางคู่กันแบบ 2 Columns ได้เพราะกว้างขึ้นแล้ว) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-bold text-gray-700 font-heading mb-1.5">
                        2. ประเภทการอบรม <span class="text-red-500">*</span>
                    </label>
                    <select name="course_method" id="course_method" required class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#154c9f]">
                        <option value="">-- เลือกประเภท --</option>
                        <option value="1">E-Learning (เรียนออนไลน์)</option>
                        <option value="2">On-Site (อบรมในห้องเรียน)</option>
                        <option value="3">Hybrid (ผสมผสาน)</option>
                        <option value="4">Workshop (เชิงปฏิบัติการ)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 font-heading mb-1.5">
                        3. หลักสูตรอบรม (ชื่อหลักสูตร) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="course_name" id="course_name" required placeholder="กรอกชื่อหลักสูตรอบรม" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#154c9f]">
                </div>
            </div>

            <!-- 4. คำอธิบายหลักสูตร (course_depcription) -->
            <div>
                <label class="block text-sm font-bold text-gray-700 font-heading mb-1.5">
                    4. คำอธิบายหลักสูตร
                </label>
                <textarea name="course_depcription" id="course_depcription" rows="2" placeholder="ระบุรายละเอียด หรือคำอธิบายหลักสูตรสังเขป" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#154c9f]"></textarea>
            </div>

            <!-- ลิงก์และลำดับเพิ่มเติม -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-bold text-gray-700 font-heading mb-1">
                        ความจำเป็น <span class="text-red-500">*</span>
                    </label>
                    <select name="course_fix" id="course_fix" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#154c9f]">
                        <option value="1">1. บังคับ</option>
                        <option value="2">2. เลือกเรียน 1 วิชา</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 font-heading mb-1">URL เรียนรู้ (ถ้ามี)</label>
                    <input type="url" name="course_url" id="course_url" placeholder="https://..." class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                </div>
            </div>

            <!-- 5. ปุ่มบันทึกข้อมูล -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 font-heading shrink-0">
                <button type="button" onclick="closeCourseModal()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" class="px-5 py-2.5 bg-[#154c9f] hover:bg-[#0f3877] text-white rounded-xl text-sm font-medium shadow-md transition-colors flex items-center gap-1.5">
                    <i class="bi bi-floppy-fill"></i>
                    <span>บันทึกข้อมูล</span>
                </button>
            </div>
        </form>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script>
    // Helper Render Badge Functions
    function renderCourseTypeBadge(type) {
        const t = type ? type.toString().trim() : '';
        switch(t) {
            case '1': return `<span class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200 text-sm font-semibold rounded-lg"><i class="bi bi-award-fill me-1"></i>ผู้อำนวยการโรงพยาบาล</span>`;
            case '2': return `<span class="px-2.5 py-1 bg-teal-50 text-teal-700 border border-teal-200 text-sm font-semibold rounded-lg"><i class="bi bi-person-workspace me-1"></i>รองผอ./ผู้ช่วยผอ.</span>`;
            case '3': return `<span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 text-sm font-semibold rounded-lg"><i class="bi bi-diagram-3-fill me-1"></i>หัวหน้ากลุ่มงาน</span>`;
            case '4': return `<span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-sm font-semibold rounded-lg"><i class="bi bi-cpu-fill me-1"></i>เจ้าหน้าที่ IT</span>`;
            case '5': return `<span class="px-2.5 py-1 bg-slate-50 text-slate-700 border border-slate-200 text-sm font-semibold rounded-lg"><i class="bi bi-people-fill me-1"></i>เจ้าหน้าที่ทั่วไป</span>`;
            default: return `<span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-md">${t || 'ไม่ระบุ'}</span>`;
        }
    }

    function renderCourseMethodBadge(method) {
        const m = method ? method.toString().trim() : '';
        switch(m) {
            case '1': return `<span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 text-sm font-semibold rounded-lg"><i class="bi bi-laptop me-1"></i>E-Learning</span>`;
            case '2': return `<span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-sm font-semibold rounded-lg"><i class="bi bi-building me-1"></i>On-Site</span>`;
            case '3': return `<span class="px-2.5 py-1 bg-sky-50 text-sky-700 border border-sky-200 text-sm font-semibold rounded-lg"><i class="bi bi-arrow-repeat me-1"></i>Hybrid</span>`;
            case '4': return `<span class="px-2.5 py-1 bg-orange-50 text-orange-700 border border-orange-200 text-sm font-semibold rounded-lg"><i class="bi bi-tools me-1"></i>Workshop</span>`;
            default: return `<span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-md">${m || 'ไม่ระบุ'}</span>`;
        }
    }

    // ⚡ ฟังก์ชัน เลือกทั้งหมด / ยกเลิกทั้งหมด
    function selectAllCourseTypes(status = true) {
        const allChecked = $('.chk-course-type:checked').length === $('.chk-course-type').length;
        // สลับสถานะ ถ้าเลือกครบหมดแล้วกดซ้ำจะขอยกเลิกทั้งหมด
        $('.chk-course-type').prop('checked', !allChecked).trigger('change');
    }

    function renderCourseFixBadge(fix) {
        const f = fix ? fix.toString().trim() : '1';
        switch(f) {
            case '1':
                return `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold rounded-lg"><i class="bi bi-exclamation-circle-fill"></i> บังคับ</span>`;
            case '2':
                return `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-sky-50 text-sky-700 border border-sky-200 text-xs font-semibold rounded-lg"><i class="bi bi-check2-circle"></i> เลือกเรียน 1 วิชา</span>`;
            default:
                return `<span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-md">ไม่ระบุ</span>`;
        }
    }

    // ==========================================
    // 🔍 JS Function: เช็กและแสดงผล Badge ความจำเป็น (course_fix)
    // ==========================================
    function checkCourseFix(fixValue) {
        const val = fixValue ? fixValue.toString().trim() : '1';

        if (val === '1' || val === 'บังคับ') {
            return `
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200/80 text-xs font-semibold rounded-lg shadow-sm">
                    <i class="bi bi-exclamation-circle-fill text-rose-500"></i>
                    <span>บังคับ</span>
                </span>
            `;
        } else if (val === '2' || val === 'เลือกเรียน') {
            return `
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-sky-50 text-sky-700 border border-sky-200/80 text-xs font-semibold rounded-lg shadow-sm">
                    <i class="bi bi-check2-circle text-sky-500"></i>
                    <span>เลือกเรียน 1 วิชา</span>
                </span>
            `;
        } else {
            return `
                <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-md">
                    ไม่ระบุ
                </span>
            `;
        }
    }

    // ==========================================
    // 🚀 รันเรนเดอร์ Badge ในตารางเมื่อ DOM พร้อมใช้งาน
    // ==========================================
    $(document).ready(function() {
        // เรนเดอร์ Badge ระดับ
        $('.badge-course-type').each(function() {
            $(this).html(renderCourseTypeBadge($(this).data('type')));
        });

        // เรนเดอร์ Badge ประเภท
        $('.badge-course-method').each(function() {
            $(this).html(renderCourseMethodBadge($(this).data('method')));
        });

        // 🟢 เรนเดอร์ Badge ความจำเป็น (course_fix)
        $('.badge-course-fix').each(function() {
            const rawFix = $(this).data('fix');
            $(this).html(checkCourseFix(rawFix));
        });

        filterCourses();
    });

        // เคลียร์ค่าเมื่อเปิด Modal เพิ่มใหม่
        function openCourseModal(mode = 'add') {
            $('#courseForm')[0].reset();
            $('#course_id').val('');
            $('#course_fix').val('1'); // Set Default เป็น 1 (บังคับ)
            $('.chk-course-type').prop('checked', false);

            if (mode === 'add') {
                $('#modalTitle').html('<i class="bi bi-plus-circle"></i><span>เพิ่มหลักสูตรอบรมใหม่</span>');
            } else {
                $('#modalTitle').html('<i class="bi bi-pencil-square"></i><span>แก้ไขข้อมูลหลักสูตรอบรม</span>');
            }

            $('#courseModal').removeClass('hidden');
            $('body').css('overflow', 'hidden');
        }

        function closeCourseModal() {
            // 1. ซ่อนตัว Modal
            $('#courseModal').addClass('hidden');

            // 2. ล้างการล็อก Scroll ที่ body ( คืนค่าให้กลับมาเลื่อนได้ปกติ )
            $('body').css('overflow', 'auto');
            $('body').css('overflow-y', 'auto'); // สำหรับความชัวร์ในบางเบราว์เซอร์
            $('body').removeClass('modal-open overflow-hidden');

            // 3. ล้างฉากหลังดำ ค้าง (ถ้ามี)
            $('.modal-backdrop').remove();
        }

        function editCourse(course) {
        if (typeof course === 'object') {
            openCourseModal('edit');
            $('#course_id').val(course.course_id || course.id);
            
            // ติ๊กเลือก course_type
            $('.chk-course-type').prop('checked', false);
            $(`.chk-course-type[value="${course.course_type}"]`).prop('checked', true);

            $('#course_method').val(course.course_method);
            $('#course_name').val(course.course_name);
            $('#course_depcription').val(course.course_depcription);
            
            // 🟢 เซ็ตค่าความจำเป็น (course_fix) ใน Select Dropdown (ถ้าไม่มีค่า default เป็น 1)
            $('#course_fix').val(course.course_fix || '1');
            
            $('#course_url').val(course.course_url);
        }
    }

    // ส่งฟอร์มบันทึกด้วย AJAX
    $('#courseForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '<?= base_url('admin/saveCourse') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    closeCourseModal(); // ปิด Modal ทันที
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('ข้อผิดพลาด', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
            }
        });
    });

    // สั่งลบหลักสูตร
    function deleteCourse(courseId) {
        if (!courseId) return;

        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: 'ต้องการลบข้อมูลหลักสูตรอบรมนี้ใช่หรือไม่',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'ลบข้อมูล',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('admin/deleteCourse') ?>',
                    type: 'POST',
                    data: { 
                        course_id: courseId,
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('ลบสำเร็จ!', res.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('ข้อผิดพลาด', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('ข้อผิดพลาด', 'ไม่สามารถลบข้อมูลได้', 'error');
                    }
                });
            }
        });
    }

    // ------------------------------------
    // Filter Functionality
    // ------------------------------------
    function filterCourses() {
        const selectedType = $('#filterCourseType').val().toString().trim();
        let visibleCount = 0;

        $('.course-row').each(function() {
            const rowType = $(this).data('type') ? $(this).data('type').toString().trim() : '';

            if (selectedType === 'all' || rowType === selectedType) {
                $(this).fadeIn(150);
                visibleCount++;
            } else {
                $(this).fadeOut(150);
            }
        });

        $('#courseCount').text(visibleCount);

        if (visibleCount === 0) {
            if ($('#noFilterResult').length === 0) {
                $('#courseTableBody').append(`
                    <tr id="noFilterResult">
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            <i class="bi bi-search text-2xl block mb-1"></i>
                            ไม่พบหลักสูตรในระดับที่เลือก
                        </td>
                    </tr>
                `);
            } else {
                $('#noFilterResult').show();
            }
        } else {
            $('#noFilterResult').hide();
        }
    }

    // Ready Document
    $(document).ready(function() {
        // Render Badges
        $('.badge-course-type').each(function() {
            const rawType = $(this).data('type');
            $(this).html(renderCourseTypeBadge(rawType));
        });

        $('.badge-course-method').each(function() {
            const rawMethod = $(this).data('method');
            $(this).html(renderCourseMethodBadge(rawMethod));
        });

        // Filter Tabs (Top Navigation)
        $('.filter-btn').on('click', function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');

            const target = $(this).data('target');
            if (target === 'all') {
                $('#filterCourseType').val('all').trigger('change');
            } else {
                const posNum = target.replace('pos-', '');
                $('#filterCourseType').val(posNum).trigger('change');
            }
        });

        filterCourses();
    });
</script>
<?= $this->endSection() ?>