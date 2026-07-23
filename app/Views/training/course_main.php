<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('main_content') ?>

<?php
$typeMapping = [
    1 => ['name' => 'ผู้อำนวยการโรงพยาบาล', 'desc' => 'ผู้บริหารสูงสุดขององค์กร', 'icon' => 'bi-award-fill', 'color' => 'from-blue-600 to-indigo-700', 'badge_color' => 'bg-blue-100 text-blue-700'],
    2 => ['name' => 'รองผู้อำนวยการโรงพยาบาล', 'desc' => 'ผู้บริหารหรือผู้ได้รับมอบหมาย', 'icon' => 'bi-person-workspace', 'color' => 'from-teal-600 to-emerald-700', 'badge_color' => 'bg-teal-100 text-teal-700'],
    3 => ['name' => 'หัวหน้ากลุ่มงาน', 'desc' => 'ระดับบริหารกลุ่มงาน', 'icon' => 'bi-diagram-3-fill', 'color' => 'from-indigo-600 to-purple-700', 'badge_color' => 'bg-indigo-100 text-indigo-700'],
    4 => ['name' => 'เจ้าหน้าที่ IT', 'desc' => 'บุคลากรด้านดิจิทัล', 'icon' => 'bi-cpu-fill', 'color' => 'from-amber-500 to-orange-600', 'badge_color' => 'bg-amber-100 text-amber-700'],
    5 => ['name' => 'เจ้าหน้าที่ / บุคลากรทั่วไป', 'desc' => 'เจ้าหน้าที่ผู้ปฏิบัติงานทุกระดับ', 'icon' => 'bi-people-fill', 'color' => 'from-slate-600 to-slate-800', 'badge_color' => 'bg-slate-200 text-slate-700']
];

$groupedCourses = [];
if (!empty($courses)){
    foreach ($courses as $c) {
        $groupedCourses[$c['course_type']][] = $c;
    }
}
?>


<div class="max-w-7xl mx-auto p-4 md:p-6 font-body text-sm">

    <!-- Header Banner -->
    <div class="liquid-glass-content p-4 rounded-2xl mb-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 shadow-sm border border-slate-200/80">
        <div>
            <h1 class="text-base font-bold text-slate-900 font-heading flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-[#154c9f] text-white flex items-center justify-center shadow-sm">
                    <i class="bi bi-journal-bookmark-fill text-sm"></i>
                </span>
                <span>หลักสูตรการอบรม Digital Health</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1 pl-10">หลักสูตรพัฒนาทักษะดิจิทัลสำหรับบุคลากรตามเกณฑ์กระทรวงสาธารณสุข</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-5 bg-white/80 backdrop-blur-md p-2 rounded-2xl border border-slate-200/80 shadow-sm overflow-x-auto">
        <div class="flex items-center gap-1.5 min-w-max">
            <button type="button" onclick="filterCourseType('all')" class="filter-tab-btn active px-3 py-1.5 rounded-xl text-sm font-semibold border border-slate-200 bg-slate-100 text-slate-700 hover:bg-slate-200 flex items-center gap-1.5">
                <i class="bi bi-grid-3x3-gap-fill text-sm"></i>
                <span>ทุกระดับตำแหน่ง</span>
                <span class="px-1.5 py-0.2 bg-white/20 rounded-md text-[10px] font-mono"><?= count($courses) ?></span>
            </button>

            <?php foreach ($typeMapping as $typeId => $meta): ?>
                <?php if (isset($groupedCourses[$typeId])): ?>
                    <button type="button" onclick="filterCourseType(<?= $typeId ?>)" class="filter-tab-btn px-3 py-1.5 rounded-xl text-sm font-medium border border-slate-200/80 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 flex items-center gap-1.5">
                        <i class="bi <?= $meta['icon'] ?> text-sm"></i>
                        <span><?= $meta['name'] ?></span>
                        <span class="px-1.5 py-0.2 rounded-md text-[10px] font-bold font-mono <?= $meta['badge_color'] ?>">
                            <?= count($groupedCourses[$typeId]) ?>
                        </span>
                    </button>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Course Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php foreach ($typeMapping as $typeId => $meta): ?>
            <?php if (isset($groupedCourses[$typeId])): ?>
                <div class="course-card-group liquid-glass-content rounded-2xl overflow-hidden flex flex-col border border-slate-200/80 shadow-sm" data-type="<?= $typeId ?>">
                    
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r <?= $meta['color'] ?> px-4 py-3 text-white flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-sm shadow-inner border border-white/20">
                                <i class="bi <?= $meta['icon'] ?>"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg font-heading leading-tight"><?= $meta['name'] ?></h3>
                                <p class="text-[14px] text-white/80 mt-0.5"><?= $meta['desc'] ?></p>
                            </div>
                        </div>
                        <span class="bg-white/20 backdrop-blur-md px-2.5 py-0.5 rounded-full text-[10px] font-semibold border border-white/20">
                            <?= count($groupedCourses[$typeId]) ?> วิชา
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-3.5 space-y-2.5 flex-grow bg-white">
                        <?php foreach ($groupedCourses[$typeId] as $index => $course): ?>
                            <div class="p-2.5 rounded-xl bg-slate-50/80 border border-slate-200/60 hover:bg-blue-50/40 hover:border-blue-200 transition-all">
                                <div class="flex items-start justify-between gap-2.5">
                                    <div class="flex items-start gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-blue-100/80 text-[#154c9f] font-bold text-sm flex items-center justify-center shrink-0">
                                            <?= $index + 1 ?>
                                        </span>
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-800 font-heading leading-snug"><?= esc($course['course_name']) ?></h4>
                                            
                                            <!-- Badges: ความจำเป็น (course_fix) + รูปแบบการเรียน (course_method) -->
                                            <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                                <!-- Badge: course_fix -->
                                                <?php if (($course['course_fix'] ?? '1') == '1'): ?>
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200/80 text-[10px] font-bold rounded-md">
                                                        <i class="bi bi-exclamation-circle-fill text-rose-500 text-[9px]"></i> บังคับ
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-sky-50 text-sky-700 border border-sky-200/80 text-[10px] font-bold rounded-md">
                                                        <i class="bi bi-check2-circle text-sky-500 text-[9px]"></i> เลือกเรียน 1 วิชา
                                                    </span>
                                                <?php endif; ?>

                                                <!-- Badge: course_method -->
                                                <?php if (!empty($course['course_method'])): ?>
                                                    <div class="methodContainer" data-method="<?= esc($course['course_method']) ?>"></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (!empty($course['course_url'])): ?>
                                        <a href="<?= esc($course['course_url']) ?>" target="_blank" class="px-2.5 py-1 bg-blue-50 hover:bg-[#154c9f] text-[#154c9f] hover:text-white rounded-lg text-sm font-medium transition-colors flex items-center gap-1 shrink-0 border border-blue-100">
                                            <span>เข้าเรียน</span>
                                            <i class="bi bi-box-arrow-up-right text-[9px]"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!-- ปุ่มส่งหลักฐานการอบรม -->
                        <a href="<?= base_url('upload.php') ?>" 
                        class="px-3.5 py-1.5 bg-gradient-to-r from-[#154c9f] to-indigo-600 hover:from-[#0f3877] hover:to-indigo-700 text-white font-bold rounded-xl text-xs transition-all shadow-xs hover:shadow-md flex items-center gap-1.5 font-heading">
                            <i class="bi bi-cloud-arrow-up text-sm"></i>
                            <span>ส่งหลักฐานการอบรม</span>
                        </a>
                    </div>                    
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script>

</script>
<script>
    function filterCourseType(typeId) {
        $('.filter-tab-btn').removeClass('active bg-[#154c9f] text-white').addClass('bg-white text-slate-600');
        $(event.currentTarget).addClass('active').removeClass('bg-white text-slate-600');

        if (typeId === 'all') {
            $('.course-card-group').fadeIn(200);
        } else {
            $('.course-card-group').hide();
            $(`.course-card-group[data-type="${typeId}"]`).fadeIn(200);
        }
    }
</script>
<script>
    $(document).ready(function() {
        // ตรวจสอบว่ามีปุ่มที่มีคลาส 'active' หรือไม่
        if ($('.filter-tab-btn.active').length === 0) {
            // ถ้าไม่มี ให้ตั้งค่าให้ปุ่มแรกเป็น active
            $('.filter-tab-btn').first().addClass('active bg-[#154c9f] text-white');        
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('.methodContainer').each(function() {
            $(this).html(renderCourseMethodBadge($(this).data('method')));
        });
    });
</script>
<?= $this->endSection() ?>