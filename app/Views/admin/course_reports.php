<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_content') ?>

<div class="space-y-6">

    <!-- 📌 Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-gray-800 font-heading flex items-center gap-2">
                <i class="bi bi-file-earmark-bar-graph text-[#154c9f]"></i>
                รายงานผลการอบรมบุคลากร
            </h1>
            <p class="text-xs text-gray-500 mt-1">สรุปข้อมูลการผ่านการอบรมและตรวจสอบเอกสารแนบแยกตามเงื่อนไข</p>
        </div>
        <div class="flex items-center gap-2">
            <button id="btnResetFilter" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition-all flex items-center gap-2">
                <i class="bi bi-arrow-counterclockwise"></i>
                ล้างตัวกรอง
            </button>
            <button id="btnExportExcel" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition-all flex items-center gap-2 shadow-sm">
                <i class="bi bi-file-earmark-excel"></i>
                ส่งออก Excel
            </button>
        </div>
    </div>

    <!-- 🔍 Filter Card Section -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
        
        <!-- ตัวกรองระดับที่ 1: เงื่อนไขการอบรม -->
        <div class="border-b border-gray-100 pb-4">
            <h3 class="text-xs font-bold text-[#154c9f] uppercase tracking-wider font-heading mb-3 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#154c9f]"></span>
                ตัวกรองระดับที่ 1 : ข้อมูลการอบรม
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">ระดับการอบรม</label>
                    <select id="filterLevel" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3.5 py-2.5 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#154c9f]/20 focus:border-[#154c9f] transition-all">
                        <option value="">-- ทั้งหมดทุกระดับ --</option>
                        <option value="Basic">ระดับพื้นฐาน (Basic)</option>
                        <option value="Intermediate">ระดับกลาง (Intermediate)</option>
                        <option value="Advanced">ระดับสูง (Advanced)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">หลักสูตรอบรม</label>
                    <select id="filterCourse" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3.5 py-2.5 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#154c9f]/20 focus:border-[#154c9f] transition-all">
                        <option value="">-- เลือกหลักสูตรอบรม --</option>
                        <?php if (!empty($courseList)): ?>
                            <?php foreach ($courseList as $course): ?>
                                <option value="<?= esc($course['course_name']) ?>"><?= esc($course['course_name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- ตัวกรองระดับที่ 2: สังกัดหน่วยงาน -->
        <div>
            <h3 class="text-xs font-bold text-teal-600 uppercase tracking-wider font-heading mb-3 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                ตัวกรองระดับที่ 2 : โครงสร้างหน่วยงาน
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">กลุ่มงาน</label>
                    <select id="filterWorkGroup" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3.5 py-2.5 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        <option value="">-- ทั้งหมดทุกกลุ่มงาน --</option>
                        <?php if (!empty($workGroupList)): ?>
                            <?php foreach ($workGroupList as $wg): ?>
                                <option value="<?= esc($wg['work_group']) ?>"><?= esc($wg['work_group']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">งาน / ย่อย</label>
                    <select id="filterSubGroup" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3.5 py-2.5 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        <option value="">-- ทั้งหมดทุกงาน --</option>
                        <?php if (!empty($subGroupList)): ?>
                            <?php foreach ($subGroupList as $sub): ?>
                                <option value="<?= esc($sub['sub_group']) ?>" data-parent="<?= esc($sub['work_group'] ?? '') ?>">
                                    <?= esc($sub['sub_group']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>

    </div>

    <!-- 📊 Table Section -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div class="overflow-x-auto">
            <table id="reportTable" class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-700 font-heading border-b border-gray-200">
                        <th class="py-3.5 px-4 text-center rounded-tl-xl w-12">ลำดับ</th>
                        <th class="py-3.5 px-4 min-w-[160px]">ชื่อ - นามสกุล</th>
                        <th class="py-3.5 px-4 min-w-[140px]">ตำแหน่ง</th>
                        <th class="py-3.5 px-4 min-w-[180px]">กลุ่มงาน / งาน</th>
                        <th class="py-3.5 px-4 min-w-[200px]">หลักสูตรที่อบรม</th>
                        <th class="py-3.5 px-4 text-center min-w-[120px]">วันที่อัพโหลดไฟล์</th>
                        <th class="py-3.5 px-4 text-center rounded-tr-xl w-24">ไฟล์ที่อบรม</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-600">
                    <?php if (!empty($employeeList)): ?>
                        <?php foreach ($employeeList as $index => $row): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors"
                                data-level="<?= esc($row['level_name'] ?? '') ?>"
                                data-course="<?= esc($row['course_name'] ?? '') ?>"
                                data-workgroup="<?= esc($row['work_group'] ?? '') ?>"
                                data-subgroup="<?= esc($row['sub_group'] ?? '') ?>">
                                
                                <td class="py-3.5 px-4 text-center font-medium"><?= $index + 1 ?></td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-gray-800 font-heading">
                                        <?= esc($row['fullname'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4"><?= esc($row['position'] ?? '-') ?></td>
                                <td class="py-3.5 px-4">
                                    <div class="leading-tight">
                                        <div class="font-medium text-gray-800"><?= esc($row['work_group'] ?? '-') ?></div>
                                        <div class="text-[10px] text-gray-400 mt-0.5"><?= esc($row['sub_group'] ?? '-') ?></div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div>
                                        <span class="inline-block px-2 py-0.5 text-[10px] rounded-full font-semibold bg-blue-50 text-blue-600 border border-blue-200 mb-1">
                                            <?= esc($row['level_name'] ?? 'ทั่วไป') ?>
                                        </span>
                                        <div class="text-xs text-gray-700"><?= esc($row['course_name'] ?? '-') ?></div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center text-gray-500 font-mono">
                                    <?= !empty($row['upload_date']) ? date('d/m/Y H:i', strtotime($row['upload_date'])) : '-' ?>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <?php if (!empty($row['file_path'])): ?>
                                        <a href="<?= base_url('uploads/certificates/' . $row['file_path']) ?>" target="_blank" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white transition-all shadow-sm"
                                           title="ดูไฟล์หลักฐาน">
                                            <i class="bi bi-file-earmark-pdf text-base"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-300 text-[10px] italic">ไม่มีไฟล์</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<!-- ⚡ Section สำหรับ Custom JS Filter -->
<?= $this->section('page_scripts') ?>
<script>
$(document).ready(function() {

    // 1. Initialize Client-side DataTables
    const table = $('#reportTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ทั้งหมด"]],
        language: {
            search:         "ค้นหาแบบรวดเร็ว:",
            lengthMenu:     "แสดง _MENU_ รายการ",
            info:           "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
            infoEmpty:      "ไม่พบข้อมูล",
            infoFiltered:   "(กรองจากทั้งหมด _MAX_ รายการ)",
            zeroRecords:    "ไม่พบข้อมูลที่ตรงกับการค้นหา",
            paginate: {
                first:      "หน้าแรก",
                previous:   "ก่อนหน้า",
                next:       "ถัดไป",
                last:       "หน้าสุดท้าย"
            }
        }
    });

    // 2. Custom Filter Logic สำหรับ DataTables ผ่าน `data-*` attributes บน <tr>
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        // ดึง <tr> ของแถวนั้นๆ
        const $row = $(settings.aoData[dataIndex].nTr);
        
        // รับค่า Filter ที่เลือก
        const selectedLevel     = $('#filterLevel').val();
        const selectedCourse    = $('#filterCourse').val();
        const selectedWorkGroup = $('#filterWorkGroup').val();
        const selectedSubGroup  = $('#filterSubGroup').val();

        // รับค่า data attribute ของแถวนั้น
        const rowLevel     = $row.attr('data-level') || '';
        const rowCourse    = $row.attr('data-course') || '';
        const rowWorkGroup = $row.attr('data-workgroup') || '';
        const rowSubGroup  = $row.attr('data-subgroup') || '';

        // เช็กเงื่อนไข Filter
        if (selectedLevel && rowLevel !== selectedLevel) return false;
        if (selectedCourse && rowCourse !== selectedCourse) return false;
        if (selectedWorkGroup && rowWorkGroup !== selectedWorkGroup) return false;
        if (selectedSubGroup && rowSubGroup !== selectedSubGroup) return false;

        return true; // แสดงแถวถ้าผ่านทุกเงื่อนไข
    });

    // 3. Trigger สั่ง DataTables วาดตารางใหม่เมื่อเปลี่ยนค่า Filter
    $('#filterLevel, #filterCourse, #filterWorkGroup, #filterSubGroup').on('change', function() {
        table.draw();
    });

    // 4. Filter เพิ่มเติม: ซ่อน/แสดง ตัวเลือก "งาน/ย่อย" สัมพันธ์กับ "กลุ่มงาน"
    $('#filterWorkGroup').on('change', function() {
        const wg = $(this).val();
        $('#filterSubGroup').val(''); // reset ค่า

        if (!wg) {
            $('#filterSubGroup option').show();
        } else {
            $('#filterSubGroup option').each(function() {
                const parentWg = $(this).attr('data-parent');
                if (!$(this).val() || parentWg === wg) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });

    // 5. ปุ่มล้างตัวกรอง (Reset Filter)
    $('#btnResetFilter').on('click', function() {
        $('#filterLevel').val('');
        $('#filterCourse').val('');
        $('#filterWorkGroup').val('');
        $('#filterSubGroup').val('').find('option').show();
        table.draw();
    });

});
</script>
<?= $this->endSection() ?>