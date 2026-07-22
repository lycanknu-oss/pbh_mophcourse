
   
/**
 * 🎯 ฟังก์ชันตรวจสอบและคืนค่าชื่อระดับการอบรม (course_type)
 * @param {string|number} type - ค่า course_type ที่ส่งมาจาก Database หรือ Form
 * @returns {string} ชื่อระดับการอบรมภาษาไทย
 */
function getCourseTypeName(type) {
    if (type === null || type === undefined) return 'ไม่ระบุ';

    const typeStr = type.toString().trim();

    switch (typeStr) {
        case '1':
        case 'ผู้บริหารสูงสุดขององค์กร':
            return 'ผู้บริหารสูงสุดขององค์กร';
            
        case '2':
        case 'ผู้บริหารหรือผู้ที่ได้รับมอบหมาย':
            return 'ผู้บริหารหรือผู้ที่ได้รับมอบหมาย';
            
        case '3':
        case 'หัวหน้ากลุ่มงาน':
            return 'หัวหน้ากลุ่มงาน';
            
        case '4':
        case 'เจ้าหน้าที่ IT':
            return 'เจ้าหน้าที่ IT';
            
        case '5':
        case 'เจ้าหน้าที่ / บุคคลทั่วไป':
        case 'เจ้าหน้าที่/บุคคลทั่วไป':
            return 'เจ้าหน้าที่ / บุคคลทั่วไป';
            
        default:
            return typeStr || 'ไม่ระบุ';
    }
}

/**
 * 🎯 ฟังก์ชันตรวจสอบและคืนค่าประเภทการอบรม (course_method)
 * @param {string|number} method - ค่า course_method ที่ส่งมาจาก Database หรือ Form
 * @returns {string} ชื่อประเภทการอบรมภาษาไทย
 */
function getCourseMethodName(method) {
    if (method === null || method === undefined) return 'ไม่ระบุ';

    const methodStr = method.toString().trim();

    switch (methodStr) {
        case '1':
        case 'E-Learning (เรียนออนไลน์)':
        case 'E-Learning':
            return 'E-Learning (เรียนออนไลน์)';
            
        case '2':
        case 'On-Site (อบรมในห้องเรียน)':
        case 'On-Site':
            return 'On-Site (อบรมในห้องเรียน)';
            
        case '3':
        case 'Hybrid (ผสมผสาน)':
        case 'Hybrid':
            return 'Hybrid (ผสมผสาน)';
            
        case '4':
        case 'Workshop (เชิงปฏิบัติการ)':
        case 'Workshop':
            return 'Workshop (เชิงปฏิบัติการ)';
            
        default:
            return methodStr || 'ไม่ระบุ';
    }
}
/**
 * 🏷️ คืนค่า HTML Badge สำหรับระดับการอบรม (course_type)
 */
function renderCourseTypeBadge(type) {
    const typeStr = type ? type.toString().trim() : '';

    switch (typeStr) {
        case '1':
        case 'ผู้บริหารสูงสุดขององค์กร':
            return `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200 text-xs font-semibold rounded-lg">
                        <i class="bi bi-award-fill"></i> ผู้บริหารสูงสุดขององค์กร
                    </span>`;

        case '2':
        case 'ผู้บริหารหรือผู้ที่ได้รับมอบหมาย':
            return `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-50 text-teal-700 border border-teal-200 text-xs font-semibold rounded-lg">
                        <i class="bi bi-person-workspace"></i> ผู้บริหารหรือผู้ที่ได้รับมอบหมาย
                    </span>`;

        case '3':
        case 'หัวหน้ากลุ่มงาน':
            return `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs font-semibold rounded-lg">
                        <i class="bi bi-diagram-3-fill"></i> หัวหน้ากลุ่มงาน
                    </span>`;

        case '4':
        case 'เจ้าหน้าที่ IT':
            return `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold rounded-lg">
                        <i class="bi bi-cpu-fill"></i> เจ้าหน้าที่ IT
                    </span>`;

        case '5':
        case 'เจ้าหน้าที่ / บุคคลทั่วไป':
            return `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold rounded-lg">
                        <i class="bi bi-people-fill"></i> เจ้าหน้าที่ / บุคคลทั่วไป
                    </span>`;

        default:
            return `<span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-md">${typeStr || 'ไม่ระบุ'}</span>`;
    }
}

/**
 * 🏷️ คืนค่า HTML Badge สำหรับประเภทการอบรม (course_method)
 */
function renderCourseMethodBadge(method) {
    const methodStr = method ? method.toString().trim() : '';

    switch (methodStr) {
        case '1':
        case 'E-Learning (เรียนออนไลน์)':
            return `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold rounded-lg">
                        <i class="bi bi-laptop"></i> E-Learning (เรียนออนไลน์)
                    </span>`;

        case '2':
        case 'On-Site (อบรมในห้องเรียน)':
            return `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold rounded-lg">
                        <i class="bi bi-building"></i> On-Site (อบรมในห้องเรียน)
                    </span>`;

        case '3':
        case 'Hybrid (ผสมผสาน)':
            return `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-sky-50 text-sky-700 border border-sky-200 text-xs font-semibold rounded-lg">
                        <i class="bi bi-arrow-repeat"></i> Hybrid (ผสมผสาน)
                    </span>`;

        case '4':
        case 'Workshop (เชิงปฏิบัติการ)':
            return `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-orange-50 text-orange-700 border border-orange-200 text-xs font-semibold rounded-lg">
                        <i class="bi bi-tools"></i> Workshop (เชิงปฏิบัติการ)
                    </span>`;

        default:
            return `<span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-md">${methodStr || 'ไม่ระบุ'}</span>`;
    }
}

/**
 * 🔍 ดึงข้อมูล Metadata ของ course_method
 */
function getCourseMethodInfo(method) {
    const methodMap = {
        1: { id: 1, key: 'elearning', titleTh: 'E-Learning', titleSub: 'เรียนออนไลน์', icon: 'bi-laptop', badgeClass: 'bg-emerald-50 text-emerald-700 border-emerald-200/80', iconColor: 'text-emerald-600' },
        2: { id: 2, key: 'onsite', titleTh: 'On-Site', titleSub: 'อบรมในห้องเรียน', icon: 'bi-building-check', badgeClass: 'bg-blue-50 text-blue-700 border-blue-200/80', iconColor: 'text-blue-600' },
        3: { id: 3, key: 'hybrid', titleTh: 'Hybrid', titleSub: 'ผสมผสาน', icon: 'bi-diagram-2', badgeClass: 'bg-purple-50 text-purple-700 border-purple-200/80', iconColor: 'text-purple-600' },
        4: { id: 4, key: 'workshop', titleTh: 'Workshop', titleSub: 'เชิงปฏิบัติการ', icon: 'bi-tools', badgeClass: 'bg-amber-50 text-amber-700 border-amber-200/80', iconColor: 'text-amber-600' }
    };

    const normalizedKey = String(method).toLowerCase().trim();
    if (['1', 'elearning', 'e-learning', 'เรียนออนไลน์'].includes(normalizedKey)) return methodMap[1];
    if (['2', 'onsite', 'on-site', 'อบรมในห้องเรียน'].includes(normalizedKey)) return methodMap[2];
    if (['3', 'hybrid', 'ผสมผสาน'].includes(normalizedKey)) return methodMap[3];
    if (['4', 'workshop', 'เชิงปฏิบัติการ'].includes(normalizedKey)) return methodMap[4];

    return { id: 0, key: 'unknown', titleTh: 'ไม่ระบุรูปแบบ', titleSub: '-', icon: 'bi-question-circle', badgeClass: 'bg-slate-100 text-slate-600 border-slate-200', iconColor: 'text-slate-500' };
}

    /**
 * 🏷️ สร้าง HTML Badge ของ course_method สไตล์ Tailwind
 * @param {string|number} method - ค่ารูปแบบการเรียน
 * @returns {string} HTML String
 */
function renderCourseMethodBadge(method) {
    const info = getCourseMethodInfo(method);
    
    return `
        <span class="inline-flex items-center gap-1 px-2 py-0.5 border text-[10px] font-bold rounded-md ${info.badgeClass}">
            <i class="bi ${info.icon} ${info.iconColor} text-[9px]"></i>
            <span>${info.titleTh}</span>
            <span class="opacity-70 font-normal">(${info.titleSub})</span>
        </span>
    `;
}

// 💡 ตัวอย่างการนำไปใช้กับ jQuery:
// $('#methodContainer').html(renderCourseMethodBadge(1));