📊 Training Dashboard & Evaluation System
ระบบรายงานผลสัมฤทธิ์และติดตามการส่งหลักฐานการฝึกอบรมบุคลากร

เว็บแอปพลิเคชันสำหรับจัดเก็บ แสดงผลสถิติ และติดตามผู้ผ่านการฝึกอบรมแบบ Real-time ตามระดับกลุ่มหลักสูตร พัฒนาด้วย CodeIgniter 4, DataTables (AJAX) และ Tailwind CSS

🌟 ฟีเจอร์หลัก (Key Features)
Dashboard Overview & KPIs:

สรุปสถิติตัวเลขบุคลากรทั้งหมด, ผู้ผ่านการอบรม, หลักสูตรที่เปิด และร้อยละตัวชี้วัดสะสม

Progress Bar ความก้าวหน้าการส่งหลักฐานเทียบเป้าหมายขั้นต่ำ (80%) และเป้าหมายสูงสุด (100%)

Data Visualization (Chart.js):

สัดส่วนระดับการพัฒนาอบรม (Doughnut Chart 70%)

สถิติผลการอบรมจำแนกตามรายหน่วยงาน (Progress List 30%)

Dynamic Tab List & AJAX DataTables (5 Level Tabs):

แยกตารางรายชื่อออกเป็น 5 ระดับหลักสูตร (1: ผู้อำนวยการ, 2: รองผู้อำนวยการ/ผู้รับมอบหมาย, 3: หัวหน้ากลุ่มงาน, 4: เจ้าหน้าที่ IT, 5: บุคลากรทั่วไป)

โหลดข้อมูลตารางผ่าน AJAX แบบ dynamic ไม่ต้อง reload หน้าเว็บ

Custom Position & Department Logic:

ดึงชื่อโรงพยาบาลจาก .env (project.hosname) มาต่อท้ายตำแหน่งอัตโนมัติ

Level 1: ตำแหน่ง = ผู้อำนวยการ + project.hosname, ฝ่าย/กลุ่มงาน = ""

Level 2: ตำแหน่ง = รองผู้อำนวยการ + project.hosname, ฝ่าย/กลุ่มงาน = wg_name

Level 3: ตำแหน่ง = หัวหน้ากลุ่มงาน + wg_name

File Attachment Handling: ตรวจสอบและแสดงปุ่มเปิดดูวุฒิบัตร/หลักฐาน PDF (file_path) พร้อมป้ายสถานะการอัปโหลด

🛠 Tech Stack & Dependencies
Backend: CodeIgniter 4 (PHP 8.1+)

Database: MariaDB / MySQL 5.7+

Frontend Framework & Styling:

Tailwind CSS v3 (Glassmorphism UI Theme)

Bootstrap Icons

JavaScript Libraries:

jQuery

DataTables v1.13+ (Client/AJAX Processing)

Chart.js v4

⚙️ การตั้งค่าและติดตั้ง (Installation & Configuration)
1. ความต้องการของระบบ (Prerequisites)
PHP >= 8.1 (รองรับ extension mysqli, intl, mbstring, json)

Composer

MariaDB / MySQL Server

2. ตั้งค่าไฟล์สภาพแวดล้อม (.env)
คัดลอกไฟล์ env เป็น .env แล้วตั้งค่าการเชื่อมต่อฐานข้อมูลและชื่อหน่วยงาน:

Ini, TOML
# Application Setup
CI_ENVIRONMENT = development

# Database Setup
database.default.hostname = localhost
database.default.database = db_training
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306

# Project Configuration
project.hosname = "โรงพยาบาลโพธิ์ไทร"
🗄️ Database Structure & Query Example
ตารางหลักที่ใช้งานในระบบ: tr_employee, tr_workgroup, tr_department, tr_uploadfile, tr_course

SQL Query สำหรับดึงข้อมูลส่ง AJAX (Controller)
SQL
SELECT
  emp.emp_id,
  emp.hcode,
  emp.cid,
  CONCAT(emp.prefix_name, emp.fullname) AS fname,
  emp.position,
  dp.dp_name,
  wg.wg_name,
  fu.file_id,
  fu.file_path,
  GROUP_CONCAT(DISTINCT fu.course_name SEPARATOR ', ') AS course_name,
  fu.course_type,
  MAX(fu.upload_date) AS upload_date
FROM
  tr_employee emp
  LEFT JOIN tr_workgroup wg ON wg.wg_id = emp.workgroup_id
  LEFT JOIN tr_department dp ON dp.dp_id = emp.department_id
  LEFT JOIN (
    SELECT
      fi.file_id,
      fi.cid,
      fi.file_name AS file_path,
      fi.d_update AS upload_date,
      cr.course_id,
      cr.course_name,
      cr.course_type
    FROM
      tr_uploadfile fi
      LEFT JOIN tr_course cr ON cr.course_id = fi.course_id
  ) fu ON fu.cid = emp.cid
WHERE
  ( CASE 
      WHEN dataworkgroup NOT IN(1,2,3,4) THEN emp.workgroup_hq IS NULL
      ELSE emp.workgroup_hq = dataworkgroup 
    END )
  AND department_hq = datadepart
GROUP BY
  emp.emp_id, emp.hcode, emp.prefix_name, emp.fullname, emp.position, dp.dp_name, wg.wg_name
ORDER BY
  emp.workgroup_id ASC, emp.department_id ASC;
⚡ API Endpoint (AJAX Request)
GET /dashboard/getPassedEmployeesByLevel
ดึงข้อมูลรายชื่อผู้ผ่านการอบรมแยกตามระดับหลักสูตรสำหรับ DataTables

Query Parameters:

level (int): 1 | 2 | 3 | 4 | 5

JSON Response Structure Example:

JSON
{
  "status": "success",
  "data": [
    {
      "emp_id": "1",
      "cid": "3340100XXXXXX",
      "fname": "นายสมชาย ใจดี",
      "position": "นักจัดการงานทั่วไปชำนาญการ",
      "dp_name": "กลุ่มงานบริหารทั่วไป",
      "wg_name": "งานสารสนเทศ",
      "course_name": "Cybersecurity & Public Health Data Governance",
      "upload_date": "2026-03-20 10:30:00",
      "file_path": "cert_101.pdf"
    }
  ]
}
📂 Project Structure
Plaintext
app/
├── Controllers/
│   └── Dashboard.php                 # Controller หลัก และ AJAX Endpoint
├── Views/
│   ├── dashboard.php                 # Main Dashboard View (UI, Tabs, Chart & DataTables Script)
│   └── layouts/
│       └── main_layout.php           # Master Layout
public/
├── uploads/
│   └── certificates/                 # โฟลเดอร์เก็บไฟล์เอกสาร/วุฒิบัตรแนบ (.pdf)
└── css/
    └── dashboard.css                 # Custom Styling
📄 License
ระบบนี้จัดทำขึ้นเพื่อใช้งานภายในหน่วยงาน สิทธิในซอร์สโค้ดและการใช้งานเป็นไปตามข้อกำหนดขององค์ก