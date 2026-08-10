<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_content') ?>

<div class="space-y-6 max-w-5xl mx-auto">

    <!-- 📌 Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-file-earmark-zip-fill text-[#154c9f]"></i>
                ดาวน์โหลดเอกสารอบรมแบบรวมไฟล์ (ZIP)
            </h1>
            <p class="text-xs text-slate-500 mt-1">ส่งออกไฟล์รายงานการอบรมรวมรูปภาพและ PDF เป็น Zip รวมรายบุคคล</p>
        </div>
    </div>

    <!-- 🔍 Main Card Filter Form -->
    <form id="exportForm" action="<?= base_url('admin/export/exportZip') ?>" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <?= csrf_field() ?>

        <!-- 📑 Tab Navigation -->
        <div class="flex border-b border-slate-200 bg-slate-50/50 p-2 gap-2">
            <button type="button" id="tab1-btn" class="tab-btn active px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-white text-[#154c9f] shadow-xs">
                <i class="bi bi-journal-bookmark-fill"></i>
                <span>Tab 1: ตามประเภท/หลักสูตร</span>
            </button>
            <button type="button" id="tab2-btn" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-700 transition-all flex items-center gap-2">
                <i class="bi bi-building"></i>
                <span>Tab 2: ตามหน่วยงาน</span>
            </button>
        </div>

        <!-- 🎯 Tab Content Panels -->
        <div class="p-6">
            
            <!-- 🔹 PANEL TAB 1: กรองตามประเภท & หัวข้อหลักสูตร -->
            <div id="panel-tab1" class="tab-panel space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">ประเภทหลักสูตร <span class="text-rose-500">*</span></label>
                        <select name="course_type" id="filter_course_type" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#154c9f] outline-none">
                            <option value="">-- กรุณาเลือกประเภทหลักสูตร --</option>
                            <option value="1">หลักสูตรผู้อำนวยการ (Level 1)</option>
                            <option value="2">หลักสูตรรองผู้อำนวยการ/ผู้รับมอบหมาย (Level 2)</option>
                            <option value="3">หลักสูตรหัวหน้ากลุ่มงาน (Level 3)</option>
                            <option value="4">หลักสูตรหัวหน้างาน (Level 4)</option>
                            <option value="5">หลักสูตรบุคลากรทั่วไป (Level 5)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">หัวข้อหลักสูตรอบรม</label>
                        <select name="course_name" id="filter_course_name" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#154c9f] outline-none">
                            <option value="" checked>-- ทุกหลักสูตร --</option>
                        </select>
                    </div>
                </div> 
            </div>

            <!-- 🔹 PANEL TAB 2: กรองตามกลุ่มงาน & งาน -->
            <div id="panel-tab2" class="tab-panel hidden space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">กลุ่มงาน (Workgroup)</label>
                        <select name="workgroup" id="filter_workgroup" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#154c9f] outline-none">
                            <option value="">-- แสดงทุกกลุ่มงาน --</option>
                        <?php
                            if(!empty($wgList)): 
                                foreach ($wgList as $key => $value) {
                                    echo '<option value="'.$value['wg_id'].'" >'.$value['wg_name'].'</option>';
                                }
                            endif;
                        ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">งาน/ฝ่าย (Department)</label>
                        <select name="department" id="filter_department" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#154c9f] outline-none">
                            <option value="">-- แสดงทุกงาน/ฝ่าย --</option>
                        </select>
                    </div>
                </div> 
            </div>
            <div class="flex flex-wrap items-center gap-3 pt-4">
                <!-- ปุ่มส่งออก ZIP (Primary Outline) -->
                <button type="submit" formmethod="post" formaction="<?= base_url('admin/export/exportZip') ?>" class="inline-flex items-center gap-2 rounded-lg border border-blue-600 bg-white px-4 py-2.5 text-sm font-medium text-blue-600 shadow-sm transition-all hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500/50 active:bg-blue-100">
                    <!-- Icon Zip -->
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v1a2 2 0 01-2 2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                    <span>ส่งออกเป็น ZIP</span>
                </button>

                <!-- ปุ่มส่งออก PDF (Danger Solid) -->
                <button type="submit" formmethod="post" formaction="<?= base_url('admin/export/exportPdf') ?>" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white shadow-md transition-all hover:bg-red-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500/50 active:bg-red-800">
                    <!-- Icon PDF -->
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>ส่งออกเป็น PDF รวม</span>
                </button>
            </div>
        </div>
    </form>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-file-type-csv text-blue-600 text-lg"></i>
                รายการข้อมูลล่าสุดในไฟล์แคช (export_list.csv)
            </h3>
            <span id="csvRecordCount" class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full font-medium border border-blue-100">
                0 รายการ
            </span>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table id="csvExportTable" class="w-full text-xs text-left text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-3 text-center">#</th>
                        <th class="py-3 px-3">ชื่อ-นามสกุล</th>
                        <th class="py-3 px-3">ตำแหน่ง</th>
                        <th class="py-3 px-3">กลุ่มงาน</th>
                        <th class="py-3 px-3">ฝ่าย/งาน</th>
                        <th class="py-3 px-3">หลักสูตร</th>
                        <th class="py-3 px-3 text-center">วันที่อัปโหลด</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('page_scripts') ?>
<script>
$(document).ready(function() {
    let csvTable;

    // 1️⃣ สลับ Tab UI & Reset Form
    $('#tab1-btn').on('click', function() {
        switchTab($(this), $('#tab2-btn'), $('#panel-tab1'), $('#panel-tab2'));
        $('#filter_workgroup, #filter_department').val('');
    });

    $('#tab2-btn').on('click', function() {
        switchTab($(this), $('#tab1-btn'), $('#panel-tab2'), $('#panel-tab1'));
        $('#filter_course_type, #filter_course_name').val('');
    });

    function switchTab(activeBtn, inactiveBtn, showPanel, hidePanel) {
        activeBtn.addClass('bg-white text-[#154c9f] font-bold shadow-xs')
                 .removeClass('text-slate-500 font-semibold');
        inactiveBtn.removeClass('bg-white text-[#154c9f] font-bold shadow-xs')
                   .addClass('text-slate-500 font-semibold');
        showPanel.removeClass('hidden');
        hidePanel.addClass('hidden');
    }

    // 2️⃣ Helper Toast Alerts
    function showLoadingToast(titleText = 'กำลังประมวลผลข้อมูล...') {
        Swal.fire({
            title: titleText,
            html: 'กรุณารอสักครู่ ระบบกำลังอัปเดตข้อมูลไฟล์ CSV',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => { Swal.showLoading(); }
        });
    }

    function hideLoadingToast(successText = null) {
        if (successText) {
            Swal.fire({
                icon: 'success',
                title: successText,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.close();
        }
    }

    // 3️⃣ โหลด DataTables ครั้งแรก
    function initCsvTable() {
        csvTable = $('#csvExportTable').DataTable({
            processing: false,
            responsive: true,
            ajax: {
                url: '<?= base_url("admin/export/getCsvData") ?>',
                type: 'GET',
                dataSrc: function(json) {
                    const data = json.data || [];
                    $('#csvRecordCount').text(`${data.length} รายการ`);
                    return data;
                }
            },
            columns: [
                { 
                    data: null, 
                    className: 'text-center align-middle',
                    render: (data, type, row, meta) => meta.row + 1 
                },
                { data: 'fullname', defaultContent: '-' },
                { data: 'position', defaultContent: '-' },
                { data: 'workgroup', defaultContent: '-' },
                { data: 'department', defaultContent: '-' },
                { data: 'coursename', defaultContent: '-' },
                { data: 'uploaddate', className: 'text-center whitespace-nowrap', defaultContent: '-' }
            ],
            language: {
                emptyTable: "ยังไม่มีข้อมูลในไฟล์ export_list.csv",
                info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                infoEmpty: "แสดง 0 ถึง 0 จากทั้งหมด 0 รายการ",
                lengthMenu: "แสดง _MENU_ รายการ",
                search: "ค้นหาใน CSV:",
                paginate: { previous: "ก่อนหน้า", next: "ถัดไป" }
            }
        });
    }

    initCsvTable();
 
    // 🎯 ฟังก์ชันส่ง AJAX ไปยัง generateCsv() และ Reset DataTable
    function updateExportCsv(params = {}) {
        // 1️⃣ Reset DataTable เป็นค่าว่างชั่วคราวก่อนเริ่มโหลดข้อมูลใหม่
        if ($.fn.DataTable.isDataTable('#csvExportTable')) {
            csvTable.clear().draw(); // เคลียร์แถวในตารางทั้งหมดออก
        }

        showLoadingToast('กำลังสร้างและอัปเดตไฟล์ CSV...');

        $.ajax({
            url: '<?= base_url("admin/export/generateCsv") ?>',
            type: 'POST',
            data: $.extend({
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            }, params),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // 2️⃣ สั่ง Reload ดึงข้อมูลใหม่เข้ามาแสดงใน DataTables
                    csvTable.ajax.reload(function() {
                        hideLoadingToast('อัปเดตตารางข้อมูล CSV เรียบร้อยแล้ว');
                    }, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: response.message || 'ไม่สามารถประมวลผล CSV ได้'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้ (' + error + ')'
                });
            }
        });
    }

    // 🎯 EVENT TAB 1: ตามประเภท/หลักสูตร (#filter_course_type, #filter_course_name)
    $('#filter_course_type').on('change', function() {
        const level = $(this).val();
        const courseSelect = $('#filter_course_name');

        if (!level) {
            courseSelect.html('<option value="">-- ทุกหลักสูตร --</option>');
            csvTable.clear().draw();
            $('#csvRecordCount').text('0 รายการ');
            return;
        }

        courseSelect.html('<option value="">-- กำลังโหลดหัวข้อหลักสูตร... --</option>').prop('disabled', true);

        $.ajax({
            url: '<?= base_url("data/employees_bylevel.php") ?>',
            type: 'GET',
            data: { level: level },
            dataType: 'json',
            success: function(response) {
                courseSelect.html('<option value="">-- ทุกหลักสูตร --</option>');
                const items = Array.isArray(response) ? response : (response.group || []);
                
                items.forEach(function(item) {
                    if (item.course_name) {
                        courseSelect.append(`<option value="${item.course_name}">${item.course_name}</option>`);
                    }
                });

                courseSelect.prop('disabled', false);

                // ส่ง AJAX อัปเดต CSV
                updateExportCsv({
                    filter_type: 'course',
                    course_type: level,
                    course_name: $('#filter_course_name').val()
                });
            },
            error: function() {
                courseSelect.html('<option value="">-- ไม่พบหัวข้อหลักสูตร --</option>').prop('disabled', false);
            }
        });
    });

    $('#filter_course_name').on('change', function() {
        const courseName = $(this).val();
        if (!courseName) {
            // หากเลือกค่าว่าง ให้เคลียร์ตารางทันที
            csvTable.clear().draw();
            $('#csvRecordCount').text('0 รายการ');
            return;
        }

        updateExportCsv({
            filter_type: 'course',
            course_type: $('#filter_course_type').val(),
            course_name: $(this).val()
        });
    });


    // 🎯 EVENT TAB 2: ตามกลุ่มงาน & ฝ่าย (#filter_workgroup, #filter_department)
    $('#filter_workgroup').on('change', function() {
        const wgId = $(this).val();
        const deptSelect = $('#filter_department');

        if (!wgId) {
            // หากเลือกค่าว่าง ให้เคลียร์ตารางทันที
            csvTable.clear().draw();
            $('#csvRecordCount').text('0 รายการ');
            return;
        }

        // 🟢 1. ดึงข้อมูล Department มาแสดงใน #filter_department: { value: dp_id, text: dp_name }
        deptSelect.html('<option value="">-- กำลังโหลดงาน/ฝ่าย... --</option>').prop('disabled', true);

        $.ajax({
            url: '<?= base_url("admin/export/getDepartmentsByWorkgroup") ?>',
            type: 'GET',
            data: { wg_id: wgId },
            dataType: 'json',
            success: function(response) {
                deptSelect.html('<option value="">-- แสดงทุกงาน/ฝ่าย --</option>');

                if (response.status === 'success' && Array.isArray(response.data)) {
                    response.data.forEach(function(item) {
                        // แมป { value: dp_id, text: dp_name }
                        deptSelect.append(new Option(item.dp_name, item.dp_id));
                    });
                }
                deptSelect.prop('disabled', false);

                // ปลดล็อกปุ่ม Submit (ถ้ามีการเลือก)
                if (wgId) {
                    $('.btn_submit_form').removeAttr('disabled');
                } else {
                    $('.btn_submit_form').attr('disabled', 'disabled');
                }

                // 🟢 2. ยิง AJAX ไปประมวลผล generateCsv สำหรับ Workgroup
                updateExportCsv({
                    filter_type: 'workgroup',
                    workgroup: wgId,
                    department: $('#filter_department').val()
                });
            },
            error: function() {
                deptSelect.html('<option value="">-- ไม่พบข้อมูลงาน/ฝ่าย --</option>').prop('disabled', false);
            }
        });
    });

    // 🟢 3. เมื่อ #filter_department onchange ให้ทำซ้ำ AJAX เดิม
    $('#filter_department').on('change', function() {

        const departname = $(this).val();
        if (!departname) {
            // หากเลือกค่าว่าง ให้เคลียร์ตารางทันที
            csvTable.clear().draw();
            $('#csvRecordCount').text('0 รายการ');
            return;
        }

        updateExportCsv({
            filter_type: 'workgroup',
            workgroup: $('#filter_workgroup').val(),
            department: $(this).val()
        });
    });

    // 🎯 Form Submit Zip
    $('#exportForm').on('submit', function() {
        showLoadingToast('กำลังมัดรวมไฟล์ ZIP และเตรียมการดาวน์โหลด...');
        setTimeout(() => { Swal.close(); }, 4000);
    });

});
</script>
<?= $this->endSection() ?>