<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_styles') ?>
<!-- Custom Styles (ถ้ามี) -->
<?= $this->endSection(); ?>

<?= $this->section('main_content') ?>

<div class="max-w-7xl mx-auto p-4 md:p-6 font-body text-xs">

    <!-- 1️⃣ ส่วนหัวรายงาน (Header Banner) -->
    <div class="liquid-glass-content p-4 rounded-2xl mb-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 shadow-sm border border-slate-200/80">
        <div>
            <h1 class="text-base font-bold text-slate-900 font-heading flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-[#154c9f] text-white flex items-center justify-center shadow-sm">
                    <i class="bi bi-pie-chart-fill text-sm"></i>
                </span>
                <span>แดชบอร์ดข้อมูลและรายงานผลฝึกอบรม</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1 pl-10">ระบบรายงานผลสัมฤทธิ์ตัวชี้วัดการฝึกอบรมบุคลากร</p>
        </div>
        
        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200/80 text-xs shadow-xs self-end md:self-auto">
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
                <h3 class="text-xl font-bold font-heading text-slate-800 mt-0.5"><?= $data_emp ?? 0 ?> <span class="text-xs font-normal text-slate-400">คน</span></h3>
            </div>
            <div class="w-10 h-10 bg-blue-50 text-[#154c9f] rounded-xl flex items-center justify-center border border-blue-100">
                <i class="bi bi-people text-lg"></i>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="liquid-glass-content p-4 rounded-2xl border border-slate-200/80 flex items-center justify-between transition-all hover:-translate-y-0.5 shadow-sm">
            <div>
                <p class="text-[11px] font-medium text-slate-500">ผ่านการอบรมแล้ว</p>
                <h3 class="text-xl font-bold font-heading text-teal-600 mt-0.5"><?= $data_file ?? 0 ?> <span class="text-xs font-normal text-slate-400">คน</span></h3>
            </div>
            <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center border border-teal-100">
                <i class="bi bi-check-circle text-lg"></i>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="liquid-glass-content p-4 rounded-2xl border border-slate-200/80 flex items-center justify-between transition-all hover:-translate-y-0.5 shadow-sm">
            <div>
                <p class="text-[11px] font-medium text-slate-500">หลักสูตรเปิดทั้งหมด</p>
                <h3 class="text-xl font-bold font-heading text-indigo-600 mt-0.5"><?= $data_course ?? 0 ?> <span class="text-xs font-normal text-slate-400">วิชา</span></h3>
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
                <h4 class="text-xs font-bold text-slate-800 font-heading flex items-center gap-2">
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
                    <h4 class="text-xs font-bold text-slate-800 font-heading flex items-center gap-1.5">
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
                    <h4 class="text-xs font-bold text-slate-800 font-heading flex items-center gap-1.5">
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
                            <div class="flex items-center justify-between text-xs font-medium text-slate-700">
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

    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    const ctxLevel = document.getElementById('trainingLevelChart').getContext('2d');
    new Chart(ctxLevel, {
        type: 'doughnut',
        data: {
            labels: ['ระดับพื้นฐาน (Basic)', 'ระดับกลาง (Intermediate)', 'ระดับสูง (Advanced)'],
            datasets: [{
                data: [60, 45, 15],
                backgroundColor: ['#154c9f', '#34d399', '#f59e0b'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,           // ✨ ยืดขยายตามขนาดหน้าจออัตโนมัติ
            maintainAspectRatio: false, // ✨ ปลดล็อกสัดส่วนคงที่ เพื่อให้ยืดตาม Width 100% ของ Container
            plugins: {
                legend: { 
                    position: 'bottom', 
                    labels: { 
                        font: { family: 'Sarabun', size: 11 },
                        usePointStyle: true,
                        padding: 15
                    } 
                }
            },
            cutout: '68%' // ขนาดรูตรงกลางของ Doughnut
        }
    });

});
</script>
<?= $this->endSection() ?>