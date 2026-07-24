<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('main_content') ?>

<div class="max-w-7xl mx-auto p-4 md:p-6 font-body text-sm">

    <!-- 📌 Header Banner -->
    <div class="liquid-glass-content p-4 rounded-2xl mb-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 shadow-sm border border-slate-200/80">
        <div>
            <h1 class="text-base font-bold text-slate-900 font-heading flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-[#154c9f] text-white flex items-center justify-center shadow-sm">
                    <i class="bi bi-cloud-arrow-up-fill text-sm"></i>
                </span>
                <span>ระบบอัปโหลดใบรับรอง (Certificate Upload)</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1 pl-10">เลือกระดับตำแหน่งเพื่อแสดงวิชาที่ต้องส่งหลักฐานการผ่านการอบรม</p>
        </div>
    </div>

    <!-- 1️⃣ เมนู Dropdown เลือกระดับการอบรม -->
    <div class="liquid-glass-content p-4 rounded-2xl mb-6 border border-slate-200/80 shadow-sm bg-white">
        <label for="selectCourseType" class="block font-bold text-slate-800 font-heading mb-2">
            <i class="bi bi-funnel-fill text-[#154c9f] mr-1"></i> ขั้นตอนที่ 1: โปรดเลือกระดับตำแหน่ง / กลุ่มเป้าหมาย
        </label>
        <select id="selectCourseType" class="w-full md:w-1/2 p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:ring-2 focus:ring-[#154c9f] focus:outline-none transition-all cursor-pointer">
            <option value="">-- กรุณาเลือกระดับตำแหน่ง --</option>
            <option value="1">1. ผู้อำนวยการโรงพยาบาล</option>
            <option value="2">2. รองผอ. / ผู้ช่วยผอ.</option>
            <option value="3">3. หัวหน้ากลุ่มงาน</option>
            <option value="4">4. เจ้าหน้าที่ IT / บุคลากรดิจิทัล</option>
            <option value="5">5. เจ้าหน้าที่ / บุคลากรทั่วไป</option>
        </select>
    </div>

    <!-- 2️⃣ ส่วนแสดงข้อมูลหลักสูตรการอบรม (จะแสดงเมื่อเลือก Dropdown) -->
    <div id="courseCardContainer" class="hidden">
        <div id="courseCardHeader" class="mb-3 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800 font-heading flex items-center gap-2">
                <i class="bi bi-journal-check text-emerald-600"></i>
                <span>วิชาที่ต้องส่งหลักฐาน (<span id="selectedCategoryName" class="text-[#154c9f]"></span>)</span>
            </h3>
        </div>

        <!-- Cards List Grid -->
        <div id="courseListGrid" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Render การ์ดวิชาผ่าน JavaScript -->
        </div>
    </div>

    <!-- Placeholder เมื่อยังไม่ได้เลือก Dropdown -->
    <div id="emptyState" class="liquid-glass-content p-8 text-center rounded-2xl border border-dashed border-slate-300">
        <i class="bi bi-hand-index-thumb text-3xl text-slate-400 mb-2 block"></i>
        <p class="text-slate-500 font-medium">กรุณาเลือกระดับตำแหน่งจาก Dropdown ด้านบน เพื่อเริ่มต้นอัปโหลดเอกสาร</p>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<!-- Autocomplete & Custom Drag and Drop Modal Scripts -->
<script>
    // ข้อมูลจำลองหลักสูตร (ในระบบจริงส่งมาจาก Controller ได้ครับ)
    const coursesData = <?= json_encode($courses ?? []) ?>;

    const typeMeta = {
        1: { name: 'ผู้อำนวยการโรงพยาบาล', color: 'from-blue-600 to-indigo-700', icon: 'bi-award-fill' },
        2: { name: 'รองผอ. / ผู้ช่วยผอ.', color: 'from-teal-600 to-emerald-700', icon: 'bi-person-workspace' },
        3: { name: 'หัวหน้ากลุ่มงาน', color: 'from-indigo-600 to-purple-700', icon: 'bi-diagram-3-fill' },
        4: { name: 'เจ้าหน้าที่ IT', color: 'from-amber-500 to-orange-600', icon: 'bi-cpu-fill' },
        5: { name: 'เจ้าหน้าที่ / บุคลากรทั่วไป', color: 'from-slate-600 to-slate-800', icon: 'bi-people-fill' }
    };

    $(document).ready(function() {
        
        // 🔄 Event เลือก Dropdown ขั้นตอนที่ 1
        $('#selectCourseType').on('change', function() {
            const selectedType = $(this).val();

            if (!selectedType) {
                $('#courseCardContainer').addClass('hidden');
                $('#emptyState').removeClass('hidden');
                return;
            }

            $('#emptyState').addClass('hidden');
            $('#selectedCategoryName').text(typeMeta[selectedType].name);

            // กรองวิชาตาม course_type
            const filteredCourses = coursesData.filter(c => c.course_type == selectedType);
            renderCourseCards(filteredCourses, selectedType);
            $('#courseCardContainer').removeClass('hidden');
        });

    });

    // 🎨 ฟังก์ชัน Render การ์ดวิชาอบรม
    function renderCourseCards(courses, typeId) {
        const meta = typeMeta[typeId];
        let html = '';

        if (courses.length === 0) {
            html = `<div class="col-span-2 p-6 bg-white rounded-2xl text-center text-slate-400 border border-slate-200">ไม่พบรายชื่อหลักสูตรในหมวดหมู่นี้</div>`;
        } else {
            courses.forEach((course, index) => {
                const methodInfo = getCourseMethodInfo(course.course_method || 1);

                html += `
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between p-4">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div class="flex items-start gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-blue-100/80 text-[#154c9f] font-bold text-sm flex items-center justify-center shrink-0">
                                        ${index + 1}
                                    </span>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 font-heading leading-snug">${course.course_name}</h4>
                                        <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                            ${course.course_fix == 1 
                                                ? `<span class="px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold rounded-md"><i class="bi bi-exclamation-circle-fill text-rose-500"></i> บังคับ</span>`
                                                : `<span class="px-2 py-0.5 bg-sky-50 text-sky-700 border border-sky-200 text-[10px] font-bold rounded-md"><i class="bi bi-check2-circle text-sky-500"></i> เลือกเรียน 1 วิชา</span>`
                                            }
                                            <span class="px-2 py-0.5 border text-[10px] font-bold rounded-md ${methodInfo.badgeClass}">
                                                <i class="bi ${methodInfo.icon} ${methodInfo.iconColor}"></i> ${methodInfo.titleTh}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3️⃣ เมนูอัปโหลดไฟล์ประจำหลักสูตร -->
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400"><i class="bi bi-file-earmark-arrow-up"></i> แนบหลักฐาน PDF/JPG</span>
                            <button onclick="openUploadModal(${course.course_id}, '${escapeQuotes(course.course_name)}')" class="px-3 py-1.5 bg-[#154c9f] hover:bg-[#0f3877] text-white rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center gap-1.5 font-heading">
                                <i class="bi bi-cloud-upload-fill"></i>
                                <span>อัปโหลดหลักฐาน</span>
                            </button>
                        </div>
                    </div>
                `;
            });
        }

        $('#courseListGrid').html(html);
    }

    // 4️⃣ แสดง Modal อัปโหลด (4.1 Header, 4.2 Body: Autocomplete + Drag&Drop)
    function openUploadModal(courseId, courseName) {
        Swal.fire({
            title: `<div class="text-left font-heading text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">
                        <i class="bi bi-file-earmark-medical-fill text-[#154c9f]"></i> ${courseName}
                    </div>`,
            html: `
                <form id="uploadForm" class="text-left mt-3 space-y-4">
                    <input type="hidden" id="modal_course_id" value="${courseId}">
                    <input type="hidden" id="selected_emp_id" value="">
                    <input type="hidden" id="selected_cid" value="">
                    
                    <!-- Autocomplete Input -->
                    <div class="relative">
                        <label class="block text-sm font-bold text-slate-700 font-heading mb-1">
                            <i class="bi bi-person-search text-[#154c9f]"></i> ค้นหาเลขบัตรประชาชน / ชื่อบุคลากร <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="empSearchInput" placeholder="พิมพ์เลขบัตรประชาชน 13 หลัก หรือ ชื่อเพื่อค้นหา..." class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-[#154c9f] focus:outline-none">
                    </div>

                    <!-- Drag & Drop File Upload Area -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 font-heading mb-1">
                            <i class="bi bi-paperclip text-[#154c9f]"></i> แนบไฟล์ใบรับรอง (PDF, PNG, JPG) <span class="text-rose-500">*</span>
                        </label>
                        <div id="dropZone" class="border-2 border-dashed border-slate-300 hover:border-[#154c9f] bg-slate-50/80 hover:bg-blue-50/30 rounded-2xl p-5 text-center cursor-pointer transition-all">
                            <i class="bi bi-cloud-arrow-up text-3xl text-[#154c9f] block mb-1"></i>
                            <p class="text-sm font-semibold text-slate-700">ลากไฟล์มาวางที่นี่ หรือ <span class="text-[#154c9f] underline">คลิกเพื่อเลือกไฟล์</span></p>
                            <p class="text-[10px] text-slate-400 mt-1">รองรับขนาดไฟล์สูงสุด 5MB</p>
                            <input type="file" id="certFile" accept=".pdf,.png,.jpg,.jpeg" class="hidden">
                        </div>
                        <div id="fileNameDisplay" class="hidden mt-2 p-2 bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-xl flex items-center justify-between">
                            <span id="fileNameText" class="truncate font-medium"></span>
                            <i class="bi bi-x-circle-fill text-rose-500 cursor-pointer text-sm" onclick="clearFile()"></i>
                        </div>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-send-fill mr-1"></i> บันทึกข้อมูล',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#154c9f',
            customClass: { popup: 'liquid-glass-swal' },
            didOpen: () => {
                // 🔍 เปิดใช้งาน jQuery UI Autocomplete แบบ AJAX
                $("#empSearchInput").autocomplete({
                    appendTo: ".swal2-popup", // ✨ บังคับให้เมนูแสดงผลใน Modal
                    source: function(request, response) {
                        $.ajax({
                            url: "<?= base_url('data/emp_search.php') ?>",
                            dataType: "json",
                            data: { term: request.term },
                            success: function(data) {
                                if (!data || !data.length) {
                                    response([{ label: 'ไม่พบข้อมูลบุคลากร', value: '', emp_id: '', cid: '', isNull: true }]);
                                } else {
                                    response(data);
                                }
                            },
                            error: function() {
                                console.error("เกิดข้อผิดพลาดในการดึงข้อมูล Autocomplete");
                            }
                        });
                    },
                    minLength: 1, // เริ่มค้นหาเมื่อพิมพ์ 1 ตัวอักษรขึ้นไป
                    select: function(event, ui) {
                        if (ui.item.isNull) return false;

                        // ตั้งค่า Input เมื่อเลือกจาก Dropdown
                        $("#empSearchInput").val(ui.item.fullname);
                        $("#selected_emp_id").val(ui.item.emp_id);
                        $("#selected_cid").val(ui.item.cid); // 📌 บันทึก CID ลง Hidden Input
                        return false;
                    }
                }).autocomplete("instance")._renderItem = function(ul, item) {
                    if (item.isNull) {
                        return $("<li>")
                            .append(`<div class="p-2 text-sm text-slate-400 text-center">ไม่พบข้อมูลที่ค้นหา</div>`)
                            .appendTo(ul);
                    }

                    return $("<li>")
                        .append(`<div class="p-2 text-sm hover:bg-blue-50 cursor-pointer border-b border-slate-100 last:border-0">
                                    <span class="font-bold text-slate-800 block">${item.fullname}</span>
                                    <span class="text-[10px] text-slate-400">CID: ${item.position}</span>
                                </div>`)
                        .appendTo(ul);
                };

                // ⚠️ เคลียร์ CID ทันทีหากผู้ใช้ทำการพิมพ์/แก้ไขข้อความใน Input ด้วยตัวเองโดยไม่ได้เลือกจากผลการค้นหา
                $("#empSearchInput").on("input", function() {
                    $("#selected_emp_id").val("");
                    $("#selected_cid").val("");
                });

                // Drag & Drop Handling
                const dropZone = document.getElementById('dropZone');
                const fileInput = document.getElementById('certFile');

                dropZone.addEventListener('click', () => fileInput.click());
                
                ['dragover', 'dragenter'].forEach(evt => {
                    dropZone.addEventListener(evt, (e) => {
                        e.preventDefault();
                        dropZone.classList.add('border-[#154c9f]', 'bg-blue-50/50');
                    });
                });

                ['dragleave', 'drop'].forEach(evt => {
                    dropZone.addEventListener(evt, (e) => {
                        e.preventDefault();
                        dropZone.classList.remove('border-[#154c9f]', 'bg-blue-50/50');
                    });
                });

                dropZone.addEventListener('drop', (e) => {
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput.files = files;
                        handleFileChange(files[0]);
                    }
                });

                fileInput.addEventListener('change', (e) => {
                    if (e.target.files.length > 0) {
                        handleFileChange(e.target.files[0]);
                    }
                });
            },
            preConfirm: () => {
                const empName = $('#empSearchInput').val();
                const empId   = $('#selected_emp_id').val();
                const cid     = $('#selected_cid').val();
                const file    = $('#certFile')[0].files[0];

                // ❌ 1. ตรวจสอบการกรอกชื่อ / ค้นหา
                if (!empName) {
                    Swal.showValidationMessage('กรุณาระบุหรือค้นหารายชื่อบุคลากร');
                    return false;
                }

                // ❌ 2. เงื่อนไขสำคัญ: ตรวจสอบว่าต้องมี CID จากการค้นหาเท่านั้น
                if (!cid) {
                    Swal.showValidationMessage('กรุณาเลือกรายชื่อบุคลากรจากรายการค้นหาเพื่อระบุเลข CID ให้ถูกต้อง');
                    return false;
                }

                // ❌ 3. ตรวจสอบไฟล์
                if (!file) {
                    Swal.showValidationMessage('กรุณาแนบไฟล์ใบ Certificate');
                    return false;
                }

                return { courseId, empName, empId, cid, file };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                uploadCertificateAjax(result.value);
            }
        });
    }

    // แสดงชื่อไฟล์เมื่อแนบสำเร็จ
    function handleFileChange(file) {
        $('#fileNameText').text(`📎 ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`);
        $('#fileNameDisplay').removeClass('hidden');
    }

    function clearFile() {
        $('#certFile').val('');
        $('#fileNameDisplay').addClass('hidden');
    }

    function escapeQuotes(str) {
        return str.replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    // ฟังก์ชัน AJAX ส่งไฟล์เข้า Controller
    function uploadCertificateAjax(data) {
        let formData = new FormData();
        formData.append('course_id', data.courseId);
        formData.append('emp_name', data.empName);
        formData.append('cert_file', data.file);

        Swal.fire({
            title: 'กำลังอัปโหลดข้อมูล...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
            customClass: { popup: 'liquid-glass-swal' }
        });

        // AJAX Request
        $.ajax({
            url: '<?= base_url('upload_save.php') ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: 'อัปโหลดหลักฐานการอบรมเรียบร้อยแล้ว',
                    timer: 1800,
                    showConfirmButton: false,
                    customClass: { popup: 'liquid-glass-swal' }
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถอัปโหลดไฟล์ได้ กรุณาลองใหม่อีกครั้ง',
                    customClass: { popup: 'liquid-glass-swal' }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>