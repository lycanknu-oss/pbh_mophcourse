// แจ้งให้ TypeScript รับรู้โมดูล SCSS (ป้องกันข้อผิดพลาดการประกาศไทป์)


// โค้ด TypeScript อื่นๆ ของคุณ...
// อินเทอร์เฟซสำหรับข้อมูลผู้ใช้งาน (รองรับ Dynamic Session ที่ส่งมาจากฝั่ง PHP)
interface UserSession {
    loginId: number | null;
    fullname: string;
    role: string;
}

class AppNavigation {
    private navbarElement: HTMLElement | null;

    constructor() {
        this.navbarElement = document.querySelector('.moph-navbar');
        this.init();
    }

    private init(): void {
        if (this.navbarElement) {
            console.log("MOPH Navigation Initialized with TypeScript.");
            this.bindEvents();
        }
    }

    private bindEvents(): void {
        // เอฟเฟกต์เปลี่ยนสี Navbar เล็กน้อยเมื่อมีการ Scroll หน้าจอลงมา
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                this.navbarElement?.classList.add('shadow-md');
            } else {
                this.navbarElement?.classList.remove('shadow-md');
            }
        });
    }
}

// รันระบบเมื่อ DOM พร้อมใช้งาน
document.addEventListener('DOMContentLoaded', () => {
    new AppNavigation();
});