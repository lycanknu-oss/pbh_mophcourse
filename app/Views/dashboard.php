<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('main_content') ?>
<?= $this->section('page_styles') ?>

<?= $this->endSection(); ?>

<div class="max-w-7xl mx-auto p-4 md:p-6 font-body text-xs">

    <!-- Header Banner -->
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

    <!-- 📊 Summary Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        
        <!-- Card 1 -->
        <div class="liquid-glass-content p-4 rounded-2xl border border-slate-200/80 flex items-center justify-between transition-all hover:-translate-y-0.5 shadow-sm">
            <div>
                <p class="text-[11px] font-medium text-slate-500">บุคลากรทั้งหมด</p>
                <h3 class="text-xl font-bold font-heading text-slate-800 mt-0.5">150 <span class="text-xs font-normal text-slate-400">คน</span></h3>
            </div>
            <div class="w-10 h-10 bg-blue-50 text-[#154c9f] rounded-xl flex items-center justify-center border border-blue-100">
                <i class="bi bi-people text-lg"></i>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="liquid-glass-content p-4 rounded-2xl border border-slate-200/80 flex items-center justify-between transition-all hover:-translate-y-0.5 shadow-sm">
            <div>
                <p class="text-[11px] font-medium text-slate-500">ผ่านการอบรมแล้ว</p>
                <h3 class="text-xl font-bold font-heading text-teal-600 mt-0.5">128 <span class="text-xs font-normal text-slate-400">คน</span></h3>
            </div>
            <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center border border-teal-100">
                <i class="bi bi-check-circle text-lg"></i>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="liquid-glass-content p-4 rounded-2xl border border-slate-200/80 flex items-center justify-between transition-all hover:-translate-y-0.5 shadow-sm">
            <div>
                <p class="text-[11px] font-medium text-slate-500">หลักสูตรเปิดทั้งหมด</p>
                <h3 class="text-xl font-bold font-heading text-indigo-600 mt-0.5">24 <span class="text-xs font-normal text-slate-400">วิชา</span></h3>
            </div>
            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center border border-indigo-100">
                <i class="bi bi-journal-text text-lg"></i>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="liquid-glass-content p-4 rounded-2xl border border-slate-200/80 flex items-center justify-between transition-all hover:-translate-y-0.5 shadow-sm">
            <div>
                <p class="text-[11px] font-medium text-slate-500">ร้อยละตัวชี้วัดสะสม</p>
                <h3 class="text-xl font-bold font-heading text-amber-500 mt-0.5">85.3%</h3>
            </div>
            <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center border border-amber-100">
                <i class="bi bi-award text-lg"></i>
            </div>
        </div>

    </div>

    <!-- 📈 Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        
        <!-- Left Chart -->
        <div class="liquid-glass-content p-5 rounded-2xl border border-slate-200/80 shadow-sm bg-white">
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                <h4 class="text-xs font-bold text-slate-800 font-heading">สัดส่วนระดับการพัฒนาอบรม</h4>
                <span class="text-[10px] text-slate-400">แบ่งตามสิทธิ์ผู้เรียน</span>
            </div>
            <div class="relative h-60 flex items-center justify-center">
                <canvas id="trainingLevelChart"></canvas>
            </div>
        </div>

        <!-- Right Chart -->
        <div class="liquid-glass-content p-5 rounded-2xl border border-slate-200/80 shadow-sm bg-white">
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                <h4 class="text-xs font-bold text-slate-800 font-heading">สถิติการเข้าอบรมจำแนกรายกลุ่มงาน</h4>
                <span class="text-[10px] text-slate-400">จำนวนการบันทึก (ครั้ง)</span>
            </div>
            <div class="relative h-60 flex items-center justify-center">
                <canvas id="departmentChart"></canvas>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Doughnut Chart
    const ctxLevel = document.getElementById('trainingLevelChart').getContext('2d');
    new Chart(ctxLevel, {
        type: 'doughnut',
        data: {
            labels: ['ระดับพื้นฐาน (Basic)', 'ระดับกลาง (Intermediate)', 'ระดับสูง (Advanced)'],
            datasets: [{
                data: [60, 45, 15],
                backgroundColor: ['#60a5fa', '#34d399', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { font: { family: 'Sarabun', size: 11 } } }
            },
            cutout: '70%'
        }
    });

    // 2. Bar Chart
    const ctxDept = document.getElementById('departmentChart').getContext('2d');
    new Chart(ctxDept, {
        type: 'bar',
        data: {
            labels: ['องค์กรแพทย์', 'การพยาบาล', 'เทคนิคการแพทย์', 'เภสัชกรรม', 'บริหารทั่วไป', 'รพ.สต.'],
            datasets: [{
                label: 'จำนวนคนอบรมครบ (คน)',
                data: [12, 45, 8, 15, 20, 32],
                backgroundColor: '#154c9f',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { display: false }, ticks: { font: { family: 'Sarabun', size: 10 } } },
                x: { grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Sarabun', size: 10 } } }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>