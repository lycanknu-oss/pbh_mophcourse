<div class="container mx-auto p-6 bg-gray-50 min-h-screen">
    <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold text-gray-900 mb-2">🔒 ระบบข้อมูลหลักบุคลากร (สิทธิ์เข้าถึงเฉพาะเจ้าหน้าที่)</h2>
        <p class="text-sm text-gray-500">ยินดีต้อนรับคุณ <span class="text-[#154c9f] font-semibold"><?= session()->get('fullname') ?></span> เข้าสู่พื้นที่ข้อมูลความปลอดภัยสูง</p>
        
        <hr class="my-4 border-gray-100">
        <!-- ใส่ตารางข้อมูลหรือฟอร์มจัดการตรงนี้ -->
        <p class="text-sm text-gray-700">แสดงผลเฉพาะข้อมูลการฝึกอบรมและสิทธิ์การประเมินผลระดับกลุ่มงาน...</p>
    </div>
</div>