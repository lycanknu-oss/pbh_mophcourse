<div align="center">

  <h1>🏥 PBH-MOPHCOURSE</h1>
  <p><b>ระบบจัดการและติดตามรายงานผลการอบรมบุคลากร โรงพยาบาลพิบูลมังสาหาร</b></p>

  <!-- Badges Section -->
  <p>
    <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
    <img src="https://img.shields.io/badge/CodeIgniter-4.x-EF4223?style=for-the-badge&logo=codeigniter&logoColor=white" alt="CodeIgniter">
    <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38BDF8?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
    <img src="https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
    <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
  </p>

</div>

---

## 📑 สารบัญ (Table of Contents)
- [เกี่ยวกับโปรเจกต์ (About The Project)](#-เกี่ยวกับโปรเจกต์-about-the-project)
- [เทคโนโลยีที่ใช้ (Tech Stack)](#-เทคโนโลยีที่ใช้-tech-stack)
- [ฟีเจอร์เด่น (Key Features)](#-ฟีเจอร์เด่น-key-features)
- [โครงสร้างระบบฐานข้อมูล (Database Architecture)](#-โครงสร้างระบบฐานข้อมูล-database-architecture)
- [วิธีติดตั้งระบบ (Installation)](#-วิธีติดตั้งระบบ-installation)
- [วิธีใช้งาน (Usage)](#-วิธีใช้งาน-usage)
- [การสนับสนุนและการรับรอง (Organization)](#-หน่วยงานผู้พัฒนา-organization)
- [สิทธิ์การใช้งาน (License)](#-สิทธิ์การใช้งาน-license)

---

## 📖 เกี่ยวกับโปรเจกต์ (About The Project)

**PBH-MOPHCOURSE** เป็น web application สำหรับบริหารจัดการและรายงานผลการอบรมพัฒนาบุคลากรตามเกณฑ์มาตรฐานกระทรวงสาธารณสุข (MOPH) ของ **โรงพยาบาลพิบูลมังสาหาร จังหวัดอุบลราชธานี**

### 🎯 ปัญหาที่ระบบเข้ามาแก้ไข:
* **ความกระจัดกระจายของข้อมูล:** จัดเก็บข้อมูลการอบรมและไฟล์แนบหลักฐาน (เกียรติบัตร/ใบรับรอง) ให้อยู่ในฐานข้อมูลกลางที่ปลอดภัย
* **ความล่าช้าในการสรุปรายงาน:** ช่วยให้ผู้บริหารและกลุ่มงานบริหารทรัพยากรบุคคล สามารถตรวจสอบร้อยละการผ่านการอบรมแยกตามระดับตำแหน่งและหน่วยงานได้แบบ Real-time
* **ความซ้ำซ้อนในการจัดเก็บเอกสาร:** มีระบบบีบอัดไฟล์ภาพ/PDF แบบรวมรายบุคคล หรือรวมตามหลักสูตร (ZIP/PDF Merging) ช่วยลดเวลาการเตรียมเอกสารสำหรับการรับการตรวจประเมินคุณภาพ (HA / ITA)

---

## 🛠 เทคโนโลยีที่ใช้ (Tech Stack)

* **Backend Framework:** CodeIgniter 4 (PHP 8.2+)
* **Frontend Framework:** Tailwind CSS, Bootstrap 5, DataTables
* **Database Management:** MySQL / MariaDB (รองรับ Stored Procedures และ Triggers)
* **Libraries & Tools:** 
  * `mPDF` - สำหรับรวมและออกรายงานเอกสาร PDF
  * `ZipArchive` - สำหรับบีบอัดไฟล์หลักฐานแบบกลุ่ม
  * `SweetAlert2` - ระบบแจ้งเตือน Interactive UI

---

## ✨ ฟีเจอร์เด่น (Key Features)

* 📊 **Multi-Level Dashboard & Analytics:** สรุปภาพรวมการส่งผลการอบรมแยกตามระดับการอบรม (Level 1 - Level 5) และแยกตามกลุ่มงาน/ฝ่าย
* 📂 **Dynamic Export Engine:** รองรับการกรองข้อมูลและสร้างไฟล์แคช CSV พร้อมส่งออกไฟล์หลักฐานแนบเป็น ZIP Archive หรือ PDF รวมได้เพียงคลิกเดียว
* 🔍 **Smart Filter & Search:** ระบบค้นหาและตัวกรองข้อมูลบุคลากรแบบสองระดับ (Cascading Filter: Workgroup ➔ Department)
* 🛡 **Role-Based Access Control:** แบ่งสิทธิ์การเข้าถึงระหว่างบุคลากรทั่วไป (Upload Certificate) และผู้ดูแลระบบ Admin (Dashboard & Export Engine)
* ⚡ **Data Caching & Temp Optimization:** มีระบบจัดการไฟล์แคชชั่วคราวและ Truncate ตารางแคชอัตโนมัติเพื่อคงประสิทธิภาพของ Server

---

## 🗄 โครงสร้างระบบฐานข้อมูล (Database Architecture)

ตารางหลักที่ใช้ในการขับเคลื่อนระบบ:
* `tr_employee`: เก็บข้อมูลบุคลากร ตำแหน่ง และสังกัด
* `tr_workgroup`: เก็บข้อมูลกลุ่มงานหลัก (Workgroup)
* `tr_department`: เก็บข้อมูลฝ่าย/งานย่อย (Department)
* `tr_course`: รายการหลักสูตรอบรมตามเกณฑ์ MOPH
* `tr_temp_file_export`: ตารางพักข้อมูลชั่วคราวสำหรับการประมวลผล Export ไฟล์

---

## 📥 วิธีติดตั้งระบบ (Installation)

### ข้อกำหนดเบื้องต้น (Prerequisites)
* PHP >= 8.2 (พร้อมเปิดใช้งาน extension `gd`, `zip`, `mbstring`, `intl`)
* Composer
* MySQL / MariaDB Server
* Web Server (Apache / Nginx / XAMPP)

### ขั้นตอนการติดตั้ง (Step-by-Step)

1. **Clone Repository**
   ```bash
   git clone [https://github.com/your-organization/pbh-mophcourse.git](https://github.com/your-organization/pbh-mophcourse.git)
   cd pbh-mophcourse
