<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_styles') ?>
<link rel="stylesheet" href="<?= config('App')->assetURL; ?>css/dashboard.css" />
<?= $this->endSection(); ?>

<?= $this->section('main_content') ?>

<div class="max-w-7xl mx-auto p-4 md:p-6 font-body text-sm">

    <!-- 1️⃣ ส่วนหัวรายงาน (Header Banner) -->
    <div class="liquid-glass-content p-4 rounded-2xl mb-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 shadow-sm border border-slate-200/80">
        <div>
            <h1 class="text-base font-bold text-slate-900 font-heading flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-[#154c9f] text-white flex items-center justify-center shadow-sm">
                    <i class="bi bi-pie-chart-fill text-sm"></i>
                </span>
                <span>แดชบอร์ดข้อมูลและรายงานผลฝึกอบรม</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1 pl-10">ระบบรายงานผลสัมฤทธิ์ตัวชี้วัดการฝึกอบรมบุคลากร</p>
        </div>
        
        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200/80 text-sm shadow-xs self-end md:self-auto">
            <i class="bi bi-calendar3 text-[#154c9f]"></i>
            <span class="text-slate-600 font-semibold">ปีงบประมาณ 2026</span>
        </div>
    </div>

    <!-- 2️⃣ Box สถิติ (Summary Cards Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        
        <!-- Card 1 -->
        <div class="liquid-glass-content p-4 rounded-2xl border border-slate-200/80 flex items-center justify-between transition-all hover:-translate-y-0.5 shadow-sm">
            <div>
                <p class="text-[11px] font-medium text-slate-500">บุคลากรทั้งหมด</p>
                <h3 class="text-xl font-bold font-heading text-slate-800 mt-0.5"><?= $data_emp ?? 0 ?> <span class="text-sm font-normal text-slate-400">คน</span></h3>
            </div>
            <div class="w-10 h-10 bg-blue-50 text-[#154c9f] rounded-xl flex items-center justify-center border border-blue-100">
                <i class="bi bi-people text-lg"></i>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="liquid-glass-content p-4 rounded-2xl border border-slate-200/80 flex items-center justify-between transition-all hover:-translate-y-0.5 shadow-sm">
            <div>
                <p class="text-[11px] font-medium text-slate-500">ผ่านการอบรมแล้ว</p>
                <h3 class="text-xl font-bold font-heading text-teal-600 mt-0.5"><?= $data_file ?? 0 ?> <span class="text-sm font-normal text-slate-400">คน</span></h3>
            </div>
            <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center border border-teal-100">
                <i class="bi bi-check-circle text-lg"></i>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="liquid-glass-content p-4 rounded-2xl border border-slate-200/80 flex items-center justify-between transition-all hover:-translate-y-0.5 shadow-sm">
            <div>
                <p class="text-[11px] font-medium text-slate-500">หลักสูตรเปิดทั้งหมด</p>
                <h3 class="text-xl font-bold font-heading text-indigo-600 mt-0.5"><?= $data_course ?? 0 ?> <span class="text-sm font-normal text-slate-400">วิชา</span></h3>
            </div>
            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center border border-indigo-100">
                <i class="bi bi-journal-text text-lg"></i>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="liquid-glass-content p-4 rounded-2xl border border-slate-200/80 flex items-center justify-between transition-all hover:-translate-y-0.5 shadow-sm">
            <div>
                <p class="text-[11px] font-medium text-slate-500">ร้อยละตัวชี้วัดสะสม</p>
                <h3 class="text-xl font-bold font-heading text-amber-500 mt-0.5"><?= number_format($data_indicator ?? 0, 1) ?>%</h3>
            </div>
            <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center border border-amber-100">
                <i class="bi bi-award text-lg"></i>
            </div>
        </div>

    </div>

    <!-- 3️⃣ แถวที่ 1: เพิ่ม Progress Bar ความก้าวหน้าการอบรม -->
    <div class="liquid-glass-content p-5 rounded-2xl border border-slate-200/80 shadow-sm bg-white mb-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
            <div>
                <h4 class="text-sm font-bold text-slate-800 font-heading flex items-center gap-2">
                    <i class="bi bi-speedometer2 text-[#154c9f]"></i>
                    <span>ความก้าวหน้าการส่งหลักฐานการอบรมตามเป้าหมาย (Progress Bar)</span>
                </h4>
                <p class="text-[11px] text-slate-400 mt-0.5">คำนวณจากสัดส่วนบุคลากรที่ผ่านหลักสูตรเทียบกับเป้าหมายทั้งหมด</p>
            </div>
            <span class="text-sm font-bold font-heading text-[#154c9f] self-end sm:self-auto">
                <?= number_format($data_indicator ?? 0, 1) ?>%
            </span>
        </div>
        <!-- Progress Track Bar -->
        <div class="w-full h-3.5 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200/60">
            <div class="h-full bg-gradient-to-r from-[#154c9f] via-blue-500 to-teal-400 rounded-full transition-all duration-1000 shadow-xs" 
                 style="width: <?= min(100, max(0, $data_indicator ?? 0)) ?>%;"></div>
        </div>
        <div class="flex justify-between items-center text-[10px] text-slate-400 mt-1.5 font-medium">
            <span>เริ่มต้น 0%</span>
            <span>เป้าหมายขั้นต่ำ 80%</span>
            <span>เป้าหมายสูงสุด 100%</span>
        </div>
    </div>

    <!-- 4️⃣ แถวที่ 2: อัตราส่วน 7:3 (70% กราฟระดับการอบรม : 30% รายชื่อหน่วยงาน) -->
    <div class="grid grid-cols-1 lg:grid-cols-10 gap-4">
        
        <!-- 📊 ฝั่งซ้าย (70% - Grid 7/10): กราฟระดับการอบรม -->
        <div class="lg:col-span-7 liquid-glass-content p-5 rounded-2xl border border-slate-200/80 shadow-sm bg-white flex flex-col justify-between h-full">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2 shrink-0">
                <div>
                    <h4 class="text-sm font-bold text-slate-800 font-heading flex items-center gap-1.5">
                        <i class="bi bi-diagram-3-fill text-[#154c9f]"></i>
                        <span>สัดส่วนระดับการพัฒนาอบรม</span>
                    </h4>
                    <p class="text-[10px] text-slate-400">แบ่งตามระดับความรู้ / สิทธิ์ผู้เรียนหลักสูตร</p>
                </div>
                <span class="px-2 font-semibold py-0.5 bg-blue-50 text-[#154c9f] text-[10px] rounded-lg border border-blue-100">สัดส่วน 70%</span>
            </div>
            
            <!-- 📌 DIV ครอบ Canvas: ตั้งค่า w-full h-full flex-1 เพื่อให้ขยายสูงตาม Parent DIV อัตโนมัติ -->
            <div class="relative w-full h-full flex-1 min-h-[260px] flex items-center justify-center p-2">
                <canvas id="trainingLevelChart"></canvas>
            </div>

        </div>

        <!-- 📋 ฝั่งขวา (30% - Grid 3/10): รายชื่อหน่วยงาน และเปอร์เซ็นต์ท้ายรายการ -->
        <div class="lg:col-span-3 liquid-glass-content p-5 rounded-2xl border border-slate-200/80 shadow-sm bg-white flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                    <h4 class="text-sm font-bold text-slate-800 font-heading flex items-center gap-1.5">
                        <i class="bi bi-[#154c9f] bi-building-check text-[#154c9f]"></i>
                        <span>ผลการอบรมจำแนกรายหน่วยงาน</span>
                    </h4>
                    <span class="text-[10px] text-slate-400">ร้อยละ (%)</span>
                </div>

                <!-- รายการหน่วยงาน -->
                <div class="space-y-3 mt-3">
                    <?php 
                    // ข้อมูลจำลองหน่วยงาน (ในระบบจริงสามารถดึง array $departments มาวนลูปได้ครับ)
                    foreach ($workgroupList as $dept): 
                        $dept['percent'] = $dept['all_emp'] > 0 ? ($dept['count_wg'] / $dept['all_emp']) * 100 : 0; // กำหนดค่าเริ่มต้นเป็น 0 หากไม่มีข้อมูล
                        // เลือกโทนสีตามเปอร์เซ็นต์
                        $badgeColor = $dept['percent'] >= 80 ? 'text-teal-600 bg-teal-50' : 'text-amber-600 bg-amber-50';
                        $barColor   = $dept['percent'] >= 80 ? 'bg-teal-500' : 'bg-amber-500';
                    ?>
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-sm font-medium text-slate-700">
                                <span class="truncate pr-2 text-[11px]" title="<?= esc($dept['wg_name']) ?>"><?= esc($dept['wg_name']) ?></span>
                                <span class="font-bold font-heading shrink-0 px-1.5 py-0.2 rounded-md text-[10px] <?= $badgeColor ?>">
                                    <?= number_format($dept['percent'], 1) ?>%
                                </span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full <?= $barColor ?> rounded-full transition-all duration-500" style="width: <?= $dept['percent'] ?>%;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 text-[10px] text-slate-400 text-center">
                <i class="bi bi-clock-history"></i> อัปเดตข้อมูลล่าสุดเมื่อสักครู่
            </div>
        </div>

    </div><!-- 📊 Row 4: ตารางรายชื่อผู้ผ่านการอบรม (แก้ไขเป็น Tab List + AJAX) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="liquid-glass-content p-4 rounded-2xl border border-slate-200/80 shadow-sm bg-white">
                
                <!-- Header ตาราง -->
                <div class="flex flex-col md:flex-row md:items-center justify-between pb-3 mb-3 border-b border-slate-100 gap-2">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 font-heading flex items-center gap-2">
                            <i class="bi bi-person-check-fill text-[#154c9f]"></i>
                            <span>รายชื่อผู้ผ่านการอบรมและแนบหลักฐาน</span>
                        </h4>
                        <p class="text-xs text-slate-400 mt-0.5">รายการบุคลากรที่ได้รับการอนุมัติ/ผ่านการอบรมพร้อมเอกสารแนบ</p>
                    </div>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-semibold rounded-xl border border-emerald-100 flex items-center gap-1 w-fit">
                        <i class="bi bi-shield-check"></i> ข้อมูลอัปเดตล่าสุด
                    </span>
                </div>

                <!-- 📌 Tab List 5 รายการ -->
                <div class="border-b border-slate-200 mb-4 overflow-x-auto">
                    <nav class="flex space-x-1 min-w-max" aria-label="Tabs">
                        <button type="button" data-level="1" class="tab-btn active px-4 py-2.5 text-xs font-medium border-b-2 border-[#154c9f] text-[#154c9f] bg-blue-50/50 rounded-t-xl transition-all flex items-center gap-1.5 font-bold">
                            <i class="bi bi-person-workspace text-sm"></i>
                            <span>หลักสูตรผู้อำนวยการ</span>
                        </button>
                        <button type="button" data-level="2" class="tab-btn px-4 py-2.5 text-xs font-medium border-b-2 border-transparent text-slate-500 hover:text-[#154c9f] rounded-t-xl transition-all flex items-center gap-1.5">
                            <i class="bi bi-person-up text-sm"></i>
                            <span>หลักสูตรรองผู้อำนวยการ/ผู้รับมอบหมาย</span>
                        </button>
                        <button type="button" data-level="3" class="tab-btn px-4 py-2.5 text-xs font-medium border-b-2 border-transparent text-slate-500 hover:text-[#154c9f] rounded-t-xl transition-all flex items-center gap-1.5">
                            <i class="bi bi-people-fill text-sm"></i>
                            <span>หลักสูตรหัวหน้ากลุ่มงาน</span>
                        </button>
                        <button type="button" data-level="4" class="tab-btn px-4 py-2.5 text-xs font-medium border-b-2 border-transparent text-slate-500 hover:text-[#154c9f] rounded-t-xl transition-all flex items-center gap-1.5">
                            <i class="bi bi-laptop text-sm"></i>
                            <span>หลักสูตรหัวหน้างาน</span>
                        </button>
                        <button type="button" data-level="general_staff" class="tab-btn px-4 py-2.5 text-xs font-medium border-b-2 border-transparent text-slate-500 hover:text-[#154c9f] rounded-t-xl transition-all flex items-center gap-1.5">
                            <i class="bi bi-person-badge-fill text-sm"></i>
                            <span>หลักสูตรเจ้าหน้า/บุคลากรทั่วไป</span>
                        </button>
                    </nav>
                </div>

                <!-- 🔍 Search Box Custom -->
                <div class="mb-4 bg-slate-50/60 p-3 rounded-xl border border-slate-200/60">
                    <label class="block text-[11px] font-bold text-slate-600 mb-1 font-heading">
                        <i class="bi bi-search text-[#154c9f]"></i> ค้นหาข้อมูล (ชื่อ-สกุล, CID, ตำแหน่ง, หลักสูตร)
                    </label>
                    <div class="relative">
                        <input type="text" id="customSearchInput" placeholder="พิมพ์คำค้นหา..." class="w-full p-2 pl-8 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:ring-2 focus:ring-[#154c9f] focus:outline-none">
                        <i class="bi bi-search absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="table-responsive">
                    <table id="passedTrainingTable" class="w-full text-xs text-left text-slate-700 stripe hover display" style="width:100%">
                        <thead class="bg-gradient-to-r from-slate-100/80 via-slate-50 to-slate-100/80 border-b border-slate-200/80 text-slate-700 font-heading text-xs uppercase tracking-wider">
                            <tr>
                                <th class="py-3.5 px-3 text-center rounded-l-2xl font-bold w-12">#</th>
                                <th class="py-3.5 px-3 font-bold">
                                    <span class="inline-flex items-center gap-1.5"><i class="bi bi-person text-[#154c9f]"></i> ชื่อ-สกุล</span>
                                </th>
                                <th class="py-3.5 px-3 font-bold">
                                    <span class="inline-flex items-center gap-1.5"><i class="bi bi-person-badge text-[#154c9f]"></i> ตำแหน่ง</span>
                                </th>
                                <th class="py-3.5 px-3 font-bold">
                                    <span class="inline-flex items-center gap-1.5"><i class="bi bi-building text-[#154c9f]"></i> ฝ่าย / กลุ่มงาน</span>
                                </th>
                                <th class="py-3.5 px-3 font-bold">
                                    <span class="inline-flex items-center gap-1.5"><i class="bi bi-journal-check text-[#154c9f]"></i> หลักสูตรที่ผ่าน</span>
                                </th>
                                <th class="py-3.5 px-3 text-center font-bold">
                                    <span class="inline-flex items-center justify-center gap-1.5"><i class="bi bi-calendar-event text-[#154c9f]"></i> วันที่อัปโหลด</span>
                                </th>
                                <th class="py-3.5 px-3 text-center rounded-r-2xl font-bold w-28">
                                    <span class="inline-flex items-center justify-center gap-1.5"><i class="bi bi-paperclip text-[#154c9f]"></i> หลักฐาน</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white/50 text-slate-700 text-xs">
                            <!-- โหลดข้อมูลผ่าน AJAX DataTables -->
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctxLevel = document.getElementById('trainingLevelChart').getContext('2d');
    let trainingChart = null;

    // ชุด Palette สีสไตล์ Modern Liquid Glass
    const defaultColors = [
        '#154c9f', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6',
        '#ec4899', '#06b6d4', '#84cc16', '#6366f1', '#f97316'
    ];

    // ฟังก์ชันสุ่ม/สร้างชุดสีให้พอดีกับจำนวนกลุ่มงาน
    function generateColors(count) {
        let colors = [];
        for (let i = 0; i < count; i++) {
            colors.push(defaultColors[i % defaultColors.length]);
        }
        return colors;
    }

    // 🔄 ดึงข้อมูลผ่าน AJAX ( jQuery $.ajax หรือ fetch )
    $.ajax({
        url: '<?= base_url("data/workgroup_stats.php") ?>', // URL API ของคุณ
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.data.length > 0) {
                
                const backgroundColors = generateColors(response.labels.length);

                // หากมี Instance เดิมอยู่ให้ Destroy ก่อนสร้างใหม่
                if (trainingChart) {
                    trainingChart.destroy();
                }

                trainingChart = new Chart(ctxLevel, {
                    type: 'doughnut',
                    data: {
                        labels: response.labels, // ชื่อกลุ่มงาน
                        datasets: [{
                            data: response.data, // ค่าเปอร์เซ็นต์
                            backgroundColor: backgroundColors,
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: { family: 'Sarabun', size: 11 },
                                    usePointStyle: true,
                                    padding: 12
                                }
                            },
                            // 💬 แสดงสัญลักษณ์ % ใน Tooltip เมื่อชี้ที่กราฟ
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.parsed || 0;
                                        return ` ${label}: ${value}%`;
                                    }
                                }
                            }
                        },
                        cutout: '68%' // ขนาดรูตรงกลาง Doughnut
                    }
                });

            } else {
                console.warn('ไม่พบข้อมูลสำหรับแสดงกราฟ');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching chart data:', error);
        }
    });
});
</script>
<script>
$(document).ready(function() {
    // 📌 กำหนดค่า Default Level เป็น 1 (หลักสูตรผู้อำนวยการ)
    let currentLevel = '1';

    // 📋 DataTables Configuration
    var table = $('#passedTrainingTable').DataTable({
        processing: true,
        responsive: true,
        autoWidth: false,
        ajax: {
            url: '<?= base_url("data/employees_bylevel.php") ?>',
            type: 'GET',
            data: function(d) {
                d.level = currentLevel;
            },
            dataSrc: 'data'
        },
        columns: [
            { 
                data: null, 
                defaultContent: '',
                className: 'text-center align-top font-semibold text-slate-400',
                render: function (data, type, row, meta) {
                    return String(meta.row + 1).padStart(2, '0');
                }
            },
            { 
                data: 'fname',
                defaultContent: '-',
                className: 'align-top',
                render: function(data, type, row) {
                    const name = data || '-';
                    const firstChar = name.trim().charAt(0) || 'U';
                    const cidHtml = row && row.cid ? `<span class="text-[10px] text-slate-400 font-mono">-</span>` : '';
                    return `<div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#154c9f] to-indigo-500 text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                                    ${firstChar}
                                </div>
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block leading-tight">${name}</span>
                                    ${cidHtml}
                                </div>
                            </div>`;
                }
            },
            { 
                // คอลัมน์ ตำแหน่ง (position)
                data: 'position',
                defaultContent: '-',
                className: 'align-top',
                render: function(data, type, row) {
                    let posText = data || '-';
                    let workgroupName = (row && row.wg_name) ? row.wg_name : '';

                    // ⚙️ เช็คเงื่อนไขตาม Level
                    if (currentLevel == '1' || currentLevel == 1) {
                        posText = `${directorTitle}<br/>(${posText})`;
                    } else if (currentLevel == '2' || currentLevel == 2) {
                        posText = `${subDirectorTitle}<br/>(${posText})`;
                    } else if (currentLevel == '3' || currentLevel == 3) {
                        // 📌 Level 3: "หัวหน้ากลุ่มงาน" + wg_name
                        posText = `หัวหน้ากลุ่มงาน${workgroupName}<br/>(${posText})`;
                    }

                    return `<span class="inline-flex items-start gap-1 px-2.5 py-1 bg-slate-100 text-slate-700 font-medium rounded-lg text-xs leading-normal">
                                <i class="bi bi-person-badge text-slate-400 mt-0.5"></i> 
                                <div>${posText}</div>
                            </span>`;
                }
            },
            { 
                // 2️⃣ คอลัมน์ ฝ่าย / กลุ่มงาน (dp_name)
                data: 'dp_name',
                defaultContent: '-',
                className: 'align-top',
                render: function(data, type, row) {
                    let departmentName = data || '-';
                    let workgroupName = (row && row.wg_name) ? row.wg_name : '';

                    // ⚙️ เช็คเงื่อนไขตาม Level
                    if (currentLevel == '1' || currentLevel == 1) {
                        departmentName = ''; // ให้ dp_name = ""
                    } else if (currentLevel == '2' || currentLevel == 2) {
                        departmentName = workgroupName; // ให้ dp_name = wg_name
                    }

                    const deptHtml = departmentName 
                        ? `<div class="font-medium text-slate-800 flex items-center gap-1"><i class="bi bi-building text-[#154c9f]"></i>${departmentName}</div>` 
                        : '';

                    const wgHtml = (workgroupName && (currentLevel != '2' && currentLevel != 2)) 
                        ? `<div class="text-[11px] text-slate-400 flex items-center gap-1 pl-3.5"><i class="bi bi-diagram-2 text-slate-300"></i>${workgroupName}</div>` 
                        : '';

                    if (!deptHtml && !wgHtml) {
                        return '<span class="text-slate-400 text-xs">-</span>';
                    }

                    return `<div class="space-y-0.5">${deptHtml}${wgHtml}</div>`;
                }
            },
            { 
                data: 'course_name',
                defaultContent: '-',
                className: 'align-top',
                render: function(data) {
                    if (!data) return '<span class="text-slate-400 text-xs">-</span>';
                    const courses = String(data).split(',');
                    return `<div class="flex flex-wrap gap-1.5 max-w-xs">` + 
                        courses.map(c => `
                            <span class="inline-flex items-start gap-1 px-2.5 py-1 bg-blue-50/80 text-[#154c9f] border border-blue-100 rounded-lg text-[11px] font-medium leading-relaxed">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-[10px] mt-0.5 shrink-0"></i>
                                <span>${c.trim()}</span>
                            </span>`).join('') + 
                        `</div>`;
                }
            },
            { 
                data: 'upload_date',
                defaultContent: null,
                className: 'text-center align-top whitespace-nowrap',
                render: function(data) {
                    if (!data) return '<span class="text-slate-400">-</span>';
                    const dateObj = new Date(data);
                    if (isNaN(dateObj.getTime())) return `<span class="text-slate-600 font-medium text-[11px]">${data}</span>`;
                    const formatted = dateObj.toLocaleDateString('th-TH', { day: '2-digit', month: '2-digit', year: 'numeric' });
                    return `<span class="inline-flex items-center gap-1 text-slate-600 font-medium bg-slate-50 px-2 py-1 rounded-md border border-slate-200/60 text-[11px]">
                                <i class="bi bi-calendar3 text-slate-400"></i> ${formatted}
                            </span>`;
                }
            },
            { 
                data: 'file_path',
                defaultContent: null,
                className: 'text-center align-top whitespace-nowrap',
                render: function(data, type, row) {
                    const filePath = data || (row ? row.file_name || row.file_id : null);
                    if (filePath) {
                        return `<div class="flex items-center justify-center gap-1.5">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-full text-[11px] font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> อัปโหลดแล้ว
                                    </span>
                                    <a href="<?= base_url('uploads/certificates/') ?>/${filePath}" target="_blank" class="p-1.5 bg-blue-50 hover:bg-[#154c9f] text-[#154c9f] hover:text-white rounded-lg transition-all shadow-2xs" title="เปิดดูเอกสาร">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                    </a>
                                </div>`;
                    }
                    return `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-400 rounded-full text-[11px]">
                                <i class="bi bi-file-earmark-x"></i> ยังไม่แนบไฟล์
                            </span>`;
                }
            }
        ],
        dom: 'rt<"flex flex-col sm:flex-row justify-between items-center mt-4 gap-3"<"text-slate-500 text-xs"i><"pagination-sm"p>>',
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json'
        }
    });

    // ⚡ สลับ Tab และ Reload ข้อมูล AJAX
    $('.tab-btn').on('click', function() {
        $('.tab-btn').removeClass('active border-[#154c9f] text-[#154c9f] bg-blue-50/50 font-bold')
                     .addClass('border-transparent text-slate-500');
        
        $(this).addClass('active border-[#154c9f] text-[#154c9f] bg-blue-50/50 font-bold')
               .removeClass('border-transparent text-slate-500');

        // รับค่า data-level (เช่น 1, 2, 3, 4, 5)
        currentLevel = $(this).data('level');
        
        // สั่งดึงข้อมูลใหม่ผ่าน AJAX
        table.ajax.reload();
    });

    // 🔍 Search Box Event
    $('#customSearchInput').on('keyup change clear', function() {
        table.search(this.value).draw();
    });
});
</script> 
<?= $this->endSection() ?>