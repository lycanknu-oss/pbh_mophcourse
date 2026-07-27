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
                            <option value="">-- ทุกหลักสูตร --</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-[#154c9f] hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2" disabled>
                        <i class="bi bi-file-earmark-zip"></i>
                        <span>ดาวน์โหลด ZIP (Tab 1)</span>
                    </button>
                </div>
            </div>

            <!-- 🔹 PANEL TAB 2: กรองตามกลุ่มงาน & งาน -->
            <div id="panel-tab2" class="tab-panel hidden space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">กลุ่มงาน (Workgroup)</label>
                        <select name="workgroup" id="filter_workgroup" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#154c9f] outline-none">
                            <option value="">-- แสดงทุกกลุ่มงาน --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">งาน/ฝ่าย (Department)</label>
                        <select name="department" id="filter_department" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#154c9f] outline-none">
                            <option value="">-- แสดงทุกงาน/ฝ่าย --</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-[#154c9f] hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2" disabled>
                        <i class="bi bi-file-earmark-zip"></i>
                        <span>ดาวน์โหลด ZIP (Tab 2)</span>
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>

<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script>
$(document).ready(function() {
    
    // 1️⃣ การสลับ Tab UI
    $('#tab1-btn').on('click', function() {
        switchTab($(this), $('#tab2-btn'), $('#panel-tab1'), $('#panel-tab2'));
        // ล้างค่าของ Tab 2 เมื่อสลับมา Tab 1
        $('#filter_workgroup, #filter_department').val('');
    });

    $('#tab2-btn').on('click', function() {
        switchTab($(this), $('#tab1-btn'), $('#panel-tab2'), $('#panel-tab1'));
        // ล้างค่าของ Tab 1 เมื่อสลับมา Tab 2
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

    // 2️⃣ เมื่อเลือก "ประเภทหลักสูตร" ใน Tab 1 ให้ AJAX ดึงหัวข้อหลักสูตร
    $('#filter_course_type').on('change', function() {
        const level = $(this).val();
        const courseSelect = $('#filter_course_name');

        if (!level) {
            courseSelect.html('<option value="">-- ทุกหลักสูตร --</option>');
            return;
        }

        courseSelect.html('<option value="">-- กำลังโหลดหัวข้อหลักสูตร... --</option>').prop('disabled', true);

        $.ajax({
            url: '<?= base_url("data/employees_bylevel.php") ?>',
            type: 'GET',
            data: { level: level },
            dataType: 'json',
            success: function(response) {
                
                const rawJson = response.group;
                courseSelect.html('<option value="">-- ทุกหลักสูตร --</option>');

                if (response && response.status === 'success' && Array.isArray(response.group)) {
                    $.each(response.group, function(index, item) {
                        if (item.course_name) {
                            courseSelect.append(`<option value="${item.course_name}">${item.course_name}</option>`);
                        }
                    });
                }
                
                courseSelect.prop('disabled', false); 
                
                
                if(rawJson.length > 0){
                    $('button[type="submit"]').removeAttr('disabled');
                } else {
                    $('button[type="submit"]').attr('disabled','disabled');
                }

                //console.log(myArray);
                
            },
            error: function() {
                courseSelect.html('<option value="">-- ไม่พบหัวข้อหลักสูตร --</option>').prop('disabled', false);
                //$('button[type="button"], input[type="button"]').prop("disabled", true);
            }
        });
    });

});
</script>
<?= $this->endSection() ?>