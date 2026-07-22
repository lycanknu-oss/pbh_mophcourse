<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_content') ?>
<div class="container mx-auto px-4 py-6 font-body">

    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 bg-white/70 backdrop-blur-md p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-800 font-heading flex items-center gap-2">
                <i class="bi bi-people-fill text-[#154c9f]"></i>
                <span>จัดการข้อมูลพนักงาน / บุคลากร</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">ฐานข้อมูลระบบ iMeeting (pbhospit_imeeting)</p>
        </div>
        <button class="disabled px-4 py-2.5 bg-[#154c9f] hover:bg-[#0f3877] text-white text-xs font-semibold rounded-xl shadow-sm transition-all flex items-center gap-2 self-start md:self-auto">
            <i class="bi bi-person-plus-fill text-sm"></i>
            <span>เพิ่มพนักงานใหม่</span>
        </button>
    </div>

    <!-- 📋 ตารางพนักงาน (DataTables) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
        <div class="overflow-x-auto">
            <table id="empTable" class="w-full text-xs text-left text-slate-600 display responsive nowrap" style="width:100%">
                <thead class="text-xs text-slate-700 uppercase bg-slate-100/80 border-b border-slate-200 font-heading">
                    <tr>
                        <th class="py-3.5 px-4 text-center">ลำดับ</th>
                        <th class="py-3.5 px-4">ชื่อ - สกุล</th>
                        <th class="py-3.5 px-4">เลขบัตรประชาชน (CID)</th>
                        <th class="py-3.5 px-4">ตำแหน่ง</th>
                        <th class="py-3.5 px-4">แผนก/กลุ่มงาน</th>
                        <th class="py-3.5 px-4 text-center">รหัสหน่วยงาน</th>
                        <th class="py-3.5 px-4 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="empTableBody">
                    <?php if (!empty($employees)): ?>
                        <?php foreach ($employees as $index => $e): ?>
                            <tr id="emp-row-<?= $e['emp_id'] ?>" class="emp-row bg-white border-b border-slate-100 hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 text-center font-medium text-slate-400"><?= $index + 1 ?></td>
                                <td class="py-3.5 px-4 font-bold text-slate-800">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-100 text-[#154c9f] flex items-center justify-center font-bold text-xs shrink-0">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <span><?= esc(($e['prefix_name'] ?? '') . ' ' . ($e['fullname'] ?? '')) ?></span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-slate-600"><?= esc($e['phone'] ?? '-') ?></td>
                                <td class="py-3.5 px-4 font-semibold text-slate-700"><?= esc($e['position'] ?? '-') ?></td>
                                <td class="py-3.5 px-4">
                                    <div class="text-slate-800 font-medium"><?= esc($e['dp_name'] ?? '-') ?></div>
                                    <div class="text-[10px] text-slate-400"><?= esc($e['wg_name'] ?? '-') ?></div>
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono text-xs text-slate-500"><?= esc($e['hcode'] ?? '-') ?></td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button onclick='editEmployee(<?= json_encode($e) ?>)' class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="แก้ไข">
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </button>
                                        <button onclick="deleteEmployee(<?= $e['emp_id'] ?>)" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="ลบ">
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 📌 MODAL: เพิ่ม/แก้ไขข้อมูลพนักงาน (Modal-LG: max-w-2xl) -->
<!-- ========================================================================= -->
<div id="empModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
        
        <!-- Modal Header -->
        <div class="bg-[#154c9f] px-6 py-4 text-white flex items-center justify-between shrink-0 font-heading">
            <h3 id="modalTitle" class="text-sm font-bold flex items-center gap-2">
                <i class="bi bi-person-plus-fill"></i>
                <span>เพิ่มพนักงานใหม่</span>
            </h3>
            <button type="button" onclick="closeEmpModal()" class="text-white/80 hover:text-white text-lg transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Modal Form Body -->
        <form id="empForm" class="p-6 space-y-4 font-body overflow-y-auto">
            <input type="hidden" name="emp_id" id="emp_id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- ชื่อ-นามสกุล -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 font-heading mb-1">
                        ชื่อ <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="fname" id="fname" required placeholder="นายสมชาย" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#154c9f]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 font-heading mb-1">
                        นามสกุล
                    </label>
                    <input type="text" name="lname" id="lname" placeholder="ใจดี" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#154c9f]">
                </div>

                <!-- เลขบัตรประชาชน (CID) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 font-heading mb-1">เลขบัตรประชาชน (13 หลัก)</label>
                    <input type="text" name="cid" id="cid" maxlength="13" placeholder="1340100XXXXXX" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#154c9f]">
                </div>

                <!-- ตำแหน่ง -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 font-heading mb-1">ตำแหน่ง (ID / ชื่อ)</label>
                    <input type="text" name="position" id="position" placeholder="นักวิชาการคอมพิวเตอร์, พยาบาลวิชาชีพ" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#154c9f]">
                </div>

                <!-- แผนก (Depart) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 font-heading mb-1">แผนก/กลุ่มงาน</label>
                    <input type="text" name="depart" id="depart" placeholder="กลุ่มงานดิจิทัลการแพทย์" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#154c9f]">
                </div>

                <!-- ฝ่าย/งาน (Workgroup) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 font-heading mb-1">ฝ่าย/งานย่อย</label>
                    <input type="text" name="workgroup" id="workgroup" placeholder="งานเทคโนโลยีสารสนเทศ" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#154c9f]">
                </div>

                <!-- รหัสหน่วยงาน (Hcode) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 font-heading mb-1">รหัสหน่วยงาน (Hcode)</label>
                    <input type="text" name="hcode" id="hcode" placeholder="10702" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#154c9f]">
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 font-heading shrink-0">
                <button type="button" onclick="closeEmpModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-medium transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" class="px-5 py-2 bg-[#154c9f] hover:bg-[#0f3877] text-white rounded-xl text-xs font-medium shadow-md transition-colors flex items-center gap-1.5">
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
    let empDataTable;

    $(document).ready(function() {
        // 📊 Initial DataTables พร้อมภาษาไทย
        empDataTable = $('#empTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ทั้งหมด"]],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "ค้นหาข้อมูลพนักงาน...",
                lengthMenu: "แสดง _MENU_ รายการ",
                info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                infoEmpty: "ไม่พบรายการข้อมูล",
                infoFiltered: "(กรองจากทั้งหมด _MAX_ รายการ)",
                zeroRecords: "ไม่พบข้อมูลที่ตรงกัน",
                paginate: {
                    first: "หน้าแรก",
                    last: "หน้าสุดท้าย",
                    next: "ถัดไป",
                    previous: "ก่อนหน้า"
                }
            },
            columnDefs: [
                { orderable: false, targets: [0, 6] } // ล็อคไม่ให้เรียงลำดับคอลัมน์ ลำดับ และ ปุ่มจัดการ
            ]
        });
    });

    function openEmpModal(mode = 'add') {
        $('#empForm')[0].reset();
        $('#emp_id').val('');

        if (mode === 'add') {
            $('#modalTitle').html('<i class="bi bi-person-plus-fill"></i><span>เพิ่มพนักงานใหม่</span>');
        } else {
            $('#modalTitle').html('<i class="bi bi-pencil-square"></i><span>แก้ไขข้อมูลพนักงาน</span>');
        }

        $('#empModal').removeClass('hidden');
        $('body').css('overflow', 'hidden');
    }

    function closeEmpModal() {
        $('#empModal').addClass('hidden');
        $('body').css('overflow', 'auto');
        $('body').css('overflow-y', 'auto');
        $('body').removeClass('modal-open overflow-hidden');
    }

    function editEmployee(emp) {
        if (typeof emp === 'object') {
            openEmpModal('edit');
            $('#emp_id').val(emp.pbd_id || emp.emp_id);
            $('#fname').val(emp.pbd_fname || emp.fname);
            $('#lname').val(emp.pbd_lname || '');
            $('#cid').val(emp.pbd_cid || emp.cid);
            $('#position').val(emp.pbpos_id || emp.position);
            $('#depart').val(emp.depart);
            $('#workgroup').val(emp.workgroup);
            $('#hcode').val(emp.hcode);
        }
    }

    // Submit AJAX
    $('#empForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url('admin/saveEmployee') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    closeEmpModal();
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
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
            }
        });
    });

    // Delete Employee AJAX
    function deleteEmployee(empId) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณต้องการลบข้อมูลพนักงานรายนี้ใช่หรือไม่!",
            icon: 'warning',
            showCancelButton: true,
            confirmColor: '#ef4444',
            cancelColor: '#64748b',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('admin/deleteEmployee') ?>/' + empId,
                    type: 'POST',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            // ลบแถวออกจาก DataTables โดยตรง
                            empDataTable.row($(`#emp-row-${empId}`)).remove().draw();
                            Swal.fire('ลบเรียบร้อย!', res.message, 'success');
                        } else {
                            Swal.fire('ข้อผิดพลาด', res.message, 'error');
                        }
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>