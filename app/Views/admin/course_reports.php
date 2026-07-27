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
        <div class="container mx-auto p-4 space-y-6">

        <!-- 📑 Tab Navigation List -->
        <div class="flex border-b border-slate-200 gap-2">
            <button type="button" id="tab1-btn" class="tab-link active border-b-2 border-[#154c9f] text-[#154c9f] font-bold py-2.5 px-4 text-sm flex items-center gap-2 transition-all">
                <i class="bi bi-1-circle-fill"></i>
                <span>1. เลือกหลักสูตร/ประเภท</span>
            </button>
            <button type="button" id="tab2-btn" disabled class="tab-link border-b-2 border-transparent text-slate-400 py-2.5 px-4 text-sm flex items-center gap-2 cursor-not-allowed transition-all opacity-60">
                <i class="bi bi-2-circle"></i>
                <span>2. กรองตามหน่วยงาน</span>
                <span class="text-[10px] bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full font-normal">Disabled</span>
            </button>
        </div>

        <!-- 🎯 Tab Content Panels -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            
            <!-- PANEL 1: ตัวกรองหลักสูตร -->
            <div id="panel-tab1" class="tab-panel">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">ประเภทหลักสูตร <span class="text-rose-500">*</span></label>
                        <select id="filter_course_type" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#154c9f] outline-none">
                            <option value="">-- กรุณาเลือกประเภทหลักสูตร --</option>
                            <option value="1">หลักสูตรผู้อำนวยการ (Level 1)</option>
                            <option value="2">หลักสูตรรองผู้อำนวยการ/ผู้รับมอบหมาย (Level 2)</option>
                            <option value="3">หลักสูตรหัวหน้ากลุ่มงาน (Level 3)</option>
                            <option value="4">หลักสูตรหัวหน้างาน (Level 4)</option>
                            <option value="5">หลักสูตรบุคลากรทั่วไป (Level 5)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">หัวข้อหลักสูตร</label>
                        <select id="filter_course_id" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#154c9f] outline-none">
                            <option value="">-- ทั้งหมด --</option>
                            <!-- ดึงรายการหัวข้อหลักสูตรมาใส่แบบ Dynamic -->
                        </select>
                    </div>
                </div>
            </div>

            <!-- PANEL 2: ตัวกรองหน่วยงาน (Client-side DataTables Filter) -->
            <div id="panel-tab2" class="tab-panel hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">กลุ่มงาน (Workgroup)</label>
                        <select id="filter_workgroup" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#154c9f] outline-none">
                            <option value="">-- แสดงทุกกลุ่มงาน --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">งาน/ฝ่าย (Department)</label>
                        <select id="filter_department" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#154c9f] outline-none">
                            <option value="">-- แสดงทุกงาน/ฝ่าย --</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <!-- 📊 Data Container (ซ่อนไว้เมื่อ load หน้าครั้งแรก) -->
        <div id="tableContainer" class="hidden transition-all duration-300">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <table id="employeeReportTable" class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                        <tr>
                            <th class="px-3 py-3 text-center">#</th>
                            <th class="px-3 py-3">ชื่อ-สกุล</th>
                            <th class="px-3 py-3">ตำแหน่ง</th>
                            <th class="px-3 py-3">กลุ่มงาน</th>
                            <th class="px-3 py-3">งาน/ฝ่าย</th>
                            <th class="px-3 py-3">หลักสูตร</th>
                            <th class="px-3 py-3 text-center">หลักฐาน/ดาวน์โหลด</th> <!-- 👈 คอลัมน์ที่ 6 -->
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>
    </div>           
</div>
<!-- 📑 Modal แสดงรายการผลการอบรม/ดาวน์โหลดไฟล์ -->
<div id="fileDownloadModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-lg overflow-hidden transform transition-all">
        
        <!-- Header Modal -->
        <div class="bg-gradient-to-r from-[#154c9f] to-blue-700 px-6 py-4 text-white flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="bi bi-folder-symlink-fill text-xl text-blue-200"></i>
                <h3 class="font-bold text-base">หลักฐานและผลการอบรม</h3>
            </div>
            <button type="button" onclick="closeFileModal()" class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-1.5 transition-all">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <!-- Body Modal -->
        <div class="p-6 space-y-4">
            <!-- แสดงชื่อเจ้าของข้อมูล -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-[#154c9f] flex items-center justify-center font-bold text-base">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">บุคลากร / เจ้าของหลักฐาน</span>
                    <h4 id="modal_owner_name" class="font-bold text-slate-800 text-sm">-</h4>
                </div>
            </div>

            <!-- รายการเอกสาร / ผลการอบรม -->
            <div>
                <span class="text-xs font-bold text-slate-600 block mb-2">รายการหลักสูตรที่ผ่านและไฟล์แนบ:</span>
                <div id="modal_file_list" class="space-y-2 max-h-60 overflow-y-auto pr-1">
                    <!-- รายการไฟล์จะถูกสร้างด้วย JS -->
                </div>
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="closeFileModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-xl transition-all">
                ปิดหน้าต่าง
            </button>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

<!-- ⚡ Section สำหรับ Custom JS Filter -->
<?= $this->section('page_scripts') ?>
<script>


            // 2️⃣ ฟังก์ชันเปิด Modal และแสดงข้อมูล
            function openFileModal(encodedRowData) {
                const row = JSON.parse(decodeURIComponent(encodedRowData));
                
                // ตั้งชื่อเจ้าของตาราง
                $('#modal_owner_name').text(row.fname || row.fullname || '-');
                
                const fileListContainer = $('#modal_file_list');
                fileListContainer.empty();

                // ดึงข้อมูลไฟล์แนบ (รองรับทั้งไฟล์เดียว หรือหลายไฟล์ที่แยกด้วยคอมม่า)
                const filePath = row.file_path || row.file_name || null;
                const courses = row.course_name ? row.course_name.split(',') : ['หลักสูตรอบรม'];

                if (filePath) {
                    // หากมีไฟล์แนบ
                    courses.forEach((courseTitle, idx) => {
                        const cleanTitle = courseTitle.trim();
                        const downloadUrl = `<?= base_url('uploads/certificates/') ?>/${filePath}`;
                        
                        fileListContainer.append(`
                            <div class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded-xl hover:border-blue-300 transition-all">
                                <div class="flex items-center gap-2.5 overflow-hidden pr-2">
                                    <i class="bi bi-file-earmark-pdf-fill text-rose-500 text-xl flex-shrink-0"></i>
                                    <span class="text-xs font-medium text-slate-700 truncate" title="${cleanTitle}">${cleanTitle}</span>
                                </div>
                                <a href="${downloadUrl}" target="_blank" download
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-medium flex-shrink-0 transition-all shadow-2xs">
                                    <i class="bi bi-[#154c9f] bi-download"></i>
                                    <span>เปิด/ดาวน์โหลด</span>
                                </a>
                            </div>
                        `);
                    });
                } else {
                    // กรณีไม่มีไฟล์แนบ
                    fileListContainer.html(`
                        <div class="p-4 bg-slate-50 border border-dashed border-slate-200 rounded-xl text-center text-slate-400">
                            <i class="bi bi-file-earmark-x text-2xl block mb-1"></i>
                            <span class="text-xs">ยังไม่มีไฟล์หลักฐานการอบรมในระบบ</span>
                        </div>
                    `);
                }

                // แสดง Modal
                $('#fileDownloadModal').removeClass('hidden');
            }

            // 3️⃣ ฟังก์ชันปิด Modal
            function closeFileModal() {
                $('#fileDownloadModal').addClass('hidden');
            }

            // ปิด Modal เมื่อกดปุ่ม ESC
            $(document).on('keydown', function(e) {
                if (e.key === "Escape") {
                    closeFileModal();
                }
            });
</script>
<script>
$(document).ready(function() {
    let table = null;

    // 1️⃣ ฟังก์ชันสลับ Tab Panel
    $('.tab-link').on('click', function() {
        if ($(this).is(':disabled')) return;

        $('.tab-link').removeClass('active border-[#154c9f] text-[#154c9f] font-bold')
                     .addClass('border-transparent text-slate-400');
        $(this).addClass('active border-[#154c9f] text-[#154c9f] font-bold')
               .removeClass('border-transparent text-slate-400');

        $('.tab-panel').addClass('hidden');
        if ($(this).attr('id') === 'tab1-btn') {
            $('#panel-tab1').removeClass('hidden');
        } else {
            $('#panel-tab2').removeClass('hidden');
        }
    });

    // 2️⃣ เมื่อเลือก "ประเภทหลักสูตร" ใน Tab 1 -> เรียก AJAX โหลดตาราง & ปลดล็อค Tab 2 
    $('#filter_course_type').on('change', function() {
        const selectedLevel = $(this).val();

        if (!selectedLevel) {
            // ถ้าไม่เลือก ให้รีเซ็ต Dropdown หัวข้อหลักสูตร, ซ่อนตาราง และล็อค Tab 2
            resetCourseDropdown();
            $('#tableContainer').addClass('hidden');
            disableTab2();
            return;
        }

        // 1️⃣ โหลดรายการหัวข้อหลักสูตรเข้า #filter_course_id ผ่าน AJAX
        loadCourseDropdown(selectedLevel);

        // 2️⃣ แสดงตารางข้อมูล และปลดล็อค Tab 2
        $('#tableContainer').removeClass('hidden');
        enableTab2();

        // 3️⃣ โหลดข้อมูลตาราง DataTables
        initOrReloadTable(selectedLevel);
    });

    // 📌 ฟังก์ชัน AJAX ดึงข้อมูล group มาใส่ #filter_course_id
    function loadCourseDropdown(level) {
        const courseSelect = $('#filter_course_id');
        
        // แสดงสถานะกำลังโหลด
        courseSelect.html('<option value="">-- กำลังโหลดหัวข้อหลักสูตร... --</option>').prop('disabled', true);

        $.ajax({
            url: '<?= base_url("data/employees_bylevel.php") ?>',
            type: 'GET',
            data: { level: level },
            dataType: 'json',
            success: function(response) {
                courseSelect.html('<option value="">-- ทั้งหมด --</option>');

                // 🎯 ดึง Array จาก key "group" โดยตรงตามโครงสร้าง response
                if (response && response.status === 'success' && Array.isArray(response.group)) {
                    
                    $.each(response.group, function(index, item) {
                        // ใช้ course_name แสดงชื่อหลักสูตรใน Dropdown
                        if (item.course_name) {
                            courseSelect.append(`<option value="${item.course_name}">${item.course_name}</option>`);
                        }
                    });
                }

                courseSelect.prop('disabled', false);
                if (table) {
                    // ทำการ Client-side Filter ที่คอลัมน์หลักสูตร (สมมติว่าเป็น Column Index 5)
                    table.column(5).search('', false, false).draw();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading course group:', error);
                courseSelect.html('<option value="">-- ไม่พบหัวข้อหลักสูตร --</option>').prop('disabled', false);
            }
        });
    }

    // 📌 ฟังก์ชันสั่งกรองตารางเมื่อเลือก "หัวข้อหลักสูตร" ใน Tab 1
    $('#filter_course_id').on('change', function() {
        const selectedCourseName = $(this).val();
        
        if (table) {
            // ทำการ Client-side Filter ที่คอลัมน์หลักสูตร (สมมติว่าเป็น Column Index 5)
            table.column(5).search(selectedCourseName ? selectedCourseName : '', false, false).draw();
        }
    });

    // 3️⃣ ฟังก์ชันสร้าง/โหลดตาราง DataTables
    function initOrReloadTable(level) {
        // กำหนด Endpoint ให้ตรงกับ Controller CI4 ของคุณ
        const ajaxUrl = '<?= base_url("data/employees_bylevel.php") ?>';

        if ($.fn.DataTable.isDataTable('#employeeReportTable')) {
            // ถ้ามี DataTable อยู่แล้ว ให้ reload AJAX ดึง level ใหม่
            table.ajax.url(`${ajaxUrl}?level=${level}`).load(function() {
                populateTab2Dropdowns(); // เติม Dropdown กลุ่มงาน/งาน อัตโนมัติหลังดึงข้อมูลเสร็จ
            });
        } else {
            // 1️⃣ ตั้งค่า DataTables
            table = $('#employeeReportTable').DataTable({
                processing: true,
                responsive: true,
                ajax: {
                    url: ajaxUrl,
                    type: 'GET',
                    data: function(d) {
                        d.level = $('#filter_course_type').val();
                    },
                    dataSrc: function(json) {
                        if (!json || !json.data) return [];
                        return json.data.filter(function(row) {
                            return row.course_name && row.course_name.trim() !== '';
                        });
                    }
                },
                columns: [
                    { 
                        data: null, 
                        className: 'text-center',
                        render: (data, type, row, meta) => meta.row + 1 
                    },
                    { data: 'fullname', defaultContent: '-' },
                    { data: 'position', defaultContent: '-' },
                    { data: 'wg_name', defaultContent: '-' },
                    { data: 'dp_name', defaultContent: '-' },
                    { data: 'course_name', defaultContent: '-' },
                    
                    // 🎯 คอลัมน์ที่ 6: ดาวน์โหลดไฟล์ (ปุ่มเปิด Modal)
                    {
                        data: null,
                        className: 'text-center align-middle whitespace-nowrap',
                        render: function(data, type, row) {
                            // แปลงข้อมูลแถวเป็น JSON String เพื่อส่งเข้า Modal
                            const rowDataStr = encodeURIComponent(JSON.stringify(row));
                            return `
                                <button type="button" onclick="openFileModal('${rowDataStr}')" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-[#154c9f] text-[#154c9f] hover:text-white border border-blue-200 hover:border-transparent rounded-xl text-xs font-semibold transition-all shadow-2xs">
                                    <i class="bi bi-file-earmark-arrow-down-fill text-sm"></i>
                                    <span>ดาวน์โหลด</span>
                                </button>
                            `;
                        }
                    }
                ],
                language: {
                    emptyTable: "ไม่พบข้อมูลในตาราง",
                    info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                    infoEmpty: "แสดง 0 ถึง 0 จากทั้งหมด 0 รายการ",
                    infoFiltered: "(กรองข้อมูลจากทั้งหมด _MAX_ รายการ)",
                    lengthMenu: "แสดง _MENU_ รายการ",
                    loadingRecords: "กำลังโหลดข้อมูล...",
                    processing: "กำลังประมวลผล...",
                    search: "ค้นหา:",
                    zeroRecords: "ไม่พบข้อมูลที่ตรงกัน",
                    paginate: {
                        first: "หน้าแรก",
                        previous: "ก่อนหน้า",
                        next: "ถัดไป",
                        last: "หน้าสุดท้าย"
                    }
                }
            });
        }
    }

    // 4️⃣ ตัวกรองใน Tab 2: ใช้คุณสมบัติ DataTables ในการกรอง (Client-side Search/Filter)
    $('#filter_workgroup').on('change', function() {
        const val = $.fn.dataTable.util.escapeRegex($(this).val());
        // กรองที่ คอลัมน์ index 3 (wg_name)
        table.column(3).search(val ? '^' + val + '$' : '', true, false).draw();
    });

    $('#filter_department').on('change', function() {
        const val = $.fn.dataTable.util.escapeRegex($(this).val());
        // กรองที่ คอลัมน์ index 4 (dp_name)
        table.column(4).search(val ? '^' + val + '$' : '', true, false).draw();
    });

    // 5️⃣ ตัวกรองหัวข้อหลักสูตรใน Tab 1 (Client-side Filter เช่นกัน)
    $('#filter_course_id').on('change', function() {
        const val = $(this).val();
        table.column(5).search(val).draw();
    });

    // 🛠️ Helper Functions ปลดล็อค / ล็อค Tab 2
    function enableTab2() {
        $('#tab2-btn').prop('disabled', false)
                      .removeClass('cursor-not-allowed opacity-60 text-slate-400')
                      .addClass('text-slate-600 hover:text-[#154c9f]');
        $('#tab2-btn span:last-child').addClass('hidden'); // ซ่อนป้าย Disabled
    }

    function disableTab2() {
        $('#tab2-btn').prop('disabled', true)
                      .addClass('cursor-not-allowed opacity-60 text-slate-400')
                      .removeClass('text-slate-600 hover:text-[#154c9f]');
        $('#tab2-btn span:last-child').removeClass('hidden');
        $('#tab1-btn').trigger('click'); // เด้งกลับ Tab 1
    }

    // 🛠️ Dynamic Populate Dropdowns สำหรับ Tab 2 จากข้อมูลที่โหลดมาแล้ว
    function populateTab2Dropdowns() {
        if (!table) return;

        const wgSelect = $('#filter_workgroup');
        const dpSelect = $('#filter_department');

        const currentWg = wgSelect.val();
        const currentDp = dpSelect.val();

        wgSelect.html('<option value="">-- แสดงทุกกลุ่มงาน --</option>');
        dpSelect.html('<option value="">-- แสดงทุกงาน/ฝ่าย --</option>');

        // ดึง Unique Values จากคอลัมน์ wg_name (index 3)
        table.column(3, { search: 'applied' }).data().unique().sort().each(function(d) {
            if (d) wgSelect.append(`<option value="${d}" ${d === currentWg ? 'selected' : ''}>${d}</option>`);
        });

        // ดึง Unique Values จากคอลัมน์ dp_name (index 4)
        table.column(4, { search: 'applied' }).data().unique().sort().each(function(d) {
            if (d) dpSelect.append(`<option value="${d}" ${d === currentDp ? 'selected' : ''}>${d}</option>`);
        });
    }
});
</script>
<?= $this->endSection() ?>