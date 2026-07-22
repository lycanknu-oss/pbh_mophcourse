$(document).ready(function() {
    console.log("MOPH Navigation Initialized with jQuery.");

    // เอฟเฟกต์เปลี่ยนสีหรือเพิ่มเงา Navbar เมื่อมีการ Scroll หน้าจอลงมา
    $(window).on('scroll', function() {
        if ($(window).scrollTop() > 20) {
            $('.moph-navbar').addClass('shadow-md');
        } else {
            $('.moph-navbar').removeClass('shadow-md');
        }
    });

    // โค้ดสำหรับจัดการดรอปดาวน์โปรไฟล์ (ถ้ามี)
    $('.user-profile-card').on('click', function(e) {
        e.stopPropagation();
        $('.moph-dropdown-menu').toggleClass('show');
    });

    $(document).on('click', function() {
        $('.moph-dropdown-menu').removeClass('show');
    });
});
