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

    </div><!-- 📊 Row 4: ตารางรายชื่อผู้ผ่านการอบรม -->
    <div class="row mt-4">
    <div class="col-12">
        <div class="liquid-glass-content p-4 rounded-2xl border border-slate-200/80 shadow-sm bg-white">
            
            <!-- Header ตาราง -->
            <div class="flex flex-col md:flex-row md:items-center justify-between pb-3 mb-4 border-b border-slate-100 gap-2">
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

            <!-- 🔍 Filter & Search Bar Custom UI -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 bg-slate-50/60 p-3 rounded-xl border border-slate-200/60">
                
                <!-- 1️⃣ Filter เลือกกลุ่มงาน (Workgroup Filter) -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1 font-heading">
                        <i class="bi bi-diagram-2 text-[#154c9f]"></i> กรองตามฝ่าย / กลุ่มงาน
                    </label>
                    <select id="filterWorkgroup" class="w-full p-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:ring-2 focus:ring-[#154c9f] focus:outline-none">
                        <option value="">-- แสดงทุกฝ่าย / กลุ่มงาน --</option>
                        <?php 
                        // ดึงรายชื่อกลุ่มงานที่ไม่ซ้ำมาสร้าง Option Dropdown
                        if (!empty($employeeList)) {
                            $workgroups = array_unique(array_filter(array_column($employeeList, 'wg_name')));
                            sort($workgroups);
                            foreach ($workgroups as $wg) {
                                echo '<option value="' . esc($wg) . '">' . esc($wg) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- 2️⃣ Custom Search Box (ค้นหาชื่อ / CID / หลักสูตร) -->
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 mb-1 font-heading">
                        <i class="bi bi-search text-[#154c9f]"></i> ค้นหาข้อมูล (ชื่อ-สกุล, CID, หลักสูตร)
                    </label>
                    <div class="relative">
                        <input type="text" id="customSearchInput" placeholder="พิมพ์คำค้นหา..." class="w-full p-2 pl-8 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:ring-2 focus:ring-[#154c9f] focus:outline-none">
                        <i class="bi bi-search absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                    </div>
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
                        <?php if(!empty($employeeList) && is_array($employeeList)): ?>
                            <?php foreach($employeeList as $index => $emp): 
                                $courseNames = !empty($emp['course_name']) ? explode(',', $emp['course_name']) : [];
                            ?>
                                <tr class="hover:bg-blue-50/40 transition-colors duration-150">
                                    
                                    <!-- 1️⃣ ลำดับ -->
                                    <td class="py-3 px-3 text-center align-top font-semibold text-slate-400">
                                        <?= sprintf('%02d', $index + 1) ?>
                                    </td>

                                    <!-- 2️⃣ ชื่อ-สกุล -->
                                    <td class="py-3 px-3 align-top">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#154c9f] to-indigo-500 text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                                                <?= mb_substr(trim(esc($emp['fname'])), 0, 1, 'UTF-8') ?>
                                            </div>
                                            <div>
                                                <span class="font-bold text-slate-800 text-sm block leading-tight">
                                                    <?= esc($emp['fname']) ?>
                                                </span>
                                                <?php if(!empty($emp['cid'])): ?>
                                                    <span class="text-[10px] text-slate-400 font-mono">CID: <?= esc($emp['cid']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 3️⃣ ตำแหน่ง -->
                                    <td class="py-3 px-3 align-top">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-700 font-medium rounded-lg text-xs">
                                            <i class="bi bi-person-badge text-slate-400"></i>
                                            <?= esc($emp['position'] ?? '-') ?>
                                        </span>
                                    </td>

                                    <!-- 4️⃣ ฝ่าย/กลุ่มงาน -->
                                    <td class="py-3 px-3 align-top">
                                        <div class="space-y-0.5">
                                            <div class="font-medium text-slate-800 flex items-center gap-1">
                                                <i class="bi bi-building text-[#154c9f]"></i>
                                                <?= esc($emp['dp_name'] ?? '-') ?>
                                            </div>
                                            <?php if(!empty($emp['wg_name'])): ?>
                                                <div class="text-[11px] text-slate-400 flex items-center gap-1 pl-3.5">
                                                    <i class="bi bi-diagram-2 text-slate-300"></i>
                                                    <?= esc($emp['wg_name']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- 5️⃣ หลักสูตรที่ผ่านการอบรม -->
                                    <td class="py-3 px-3 align-top">
                                        <div class="flex flex-wrap gap-1.5 max-w-xs">
                                            <?php if(!empty($courseNames)): ?>
                                                <?php foreach($courseNames as $i => $course): ?>
                                                    <span class="inline-flex items-start gap-1 px-2.5 py-1 bg-blue-50/80 text-[#154c9f] border border-blue-100 rounded-lg text-[11px] font-medium leading-relaxed">
                                                        <i class="bi bi-check-circle-fill text-emerald-500 text-[10px] mt-0.5 shrink-0"></i>
                                                        <span><?= esc(trim($course)) ?></span>
                                                    </span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="text-slate-400 text-xs">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- 6️⃣ วันที่อัปโหลด -->
                                    <td class="py-3 px-3 text-center align-top whitespace-nowrap">
                                        <?php if(!empty($emp['upload_date'])): ?>
                                            <span class="inline-flex items-center gap-1 text-slate-600 font-medium bg-slate-50 px-2 py-1 rounded-md border border-slate-200/60 text-[11px]">
                                                <i class="bi bi-calendar3 text-slate-400"></i>
                                                <?= date('d/m/Y', strtotime($emp['upload_date'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-400">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- 7️⃣ เอกสารหลักฐาน -->
                                    <td class="py-3 px-3 text-center align-top whitespace-nowrap">
                                        <?php if(!empty($emp['file_id']) || !empty($emp['file_path'])): ?>
                                            <div class="flex items-center justify-center gap-1.5">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-full text-[11px] font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    อัปโหลดแล้ว
                                                </span>
                                                
                                                <?php if(!empty($emp['file_path'])): ?>
                                                    <button type="button" 
                                                            onclick="previewFile('<?= esc($emp['file_path']) ?>')"
                                                            class="p-1.5 bg-blue-50 hover:bg-[#154c9f] text-[#154c9f] hover:text-white rounded-lg transition-all shadow-2xs"
                                                            title="เปิดดูเอกสาร">
                                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-400 rounded-full text-[11px]">
                                                <i class="bi bi-file-earmark-x"></i>
                                                ยังไม่แนบไฟล์
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Empty State เมื่อไม่มีข้อมูล -->
                                    <tr>
                                        <td colspan="7" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-slate-400">
                                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-2">
                                                    <i class="bi bi-inbox text-2xl text-slate-300"></i>
                                                </div>
                                                <p class="font-bold text-slate-600 text-sm font-heading">ไม่พบข้อมูลผู้ผ่านการอบรม</p>
                                                <p class="text-xs text-slate-400 mt-0.5">ยังไม่มีรายการบันทึกส่งหลักฐานในขณะนี้</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
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
    var table = $('#passedTrainingTable').DataTable({
        responsive: {
            details: {
                type: 'column',
                target: 'tr'
            }
        },
        autoWidth: false,
        order: [[0, 'desc']], // เรียงตามวันที่อัปโหลดล่าสุด
        dom: 'rt<"flex flex-col sm:flex-row justify-between items-center mt-4 gap-3"<"text-slate-500 text-xs"i><"pagination-sm"p>>',
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json',
            paginate: {
                previous: '<i class="bi bi-chevron-left text-[10px]"></i> <span class="hidden sm:inline ml-1">ย้อนกลับ</span>',
                next: '<span class="hidden sm:inline mr-1">ถัดไป</span> <i class="bi bi-chevron-right text-[10px]"></i>'
            }
        },
        columnDefs: [
            { targets: [0, 5, 6], className: 'text-center' },
            // 📌 กำหนด Priority คอลัมน์ที่ไม่ต้องการให้ซ่อนง่ายๆ บนจอเล็ก (Priority 1 = สำคัญที่สุด)
            { responsivePriority: 1, targets: 1 }, // ชื่อ-สกุล
            { responsivePriority: 3, targets: 6 }, // เอกสารหลักฐาน
            { responsivePriority: 2, targets: 4 }, // หลักสูตร
            { responsivePriority: 4, targets: 3 }, // ฝ่าย/กลุ่มงาน
            { responsivePriority: 5, targets: 2 }  // ตำแหน่ง
        ]
    });

    // 🔍 Filter กลุ่มงาน & Search Box (ตามโค้ดเดิม)
    $('#filterWorkgroup').on('change', function() {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        table.column(3).search(val ? val : '', true, false).draw();
    });

    $('#customSearchInput').on('keyup change clear', function() {
        table.search(this.value).draw();
    });
});
</script>
<?= $this->endSection() ?>