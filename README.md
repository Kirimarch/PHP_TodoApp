# 📝 PHP TODO List Web Application

โปรเจกต์เว็บแอป **TODO List** ที่เราทำขึ้นเพื่อฝึกฝนการเขียนโค้ดด้วย **PHP**
ในโปรเจกต์นี้ ผู้ใช้สามารถสมัครสมาชิก ล็อกอิน จัดการ TODO ของตัวเอง ตั้งกำหนดเวลาที่ต้องเสร็จของแต่ละ TODO อัปโหลดรูปเป็นไอคอน และยังมีการแสดง countdown เวลาที่เหลือของ TODO นั้นด้วย

---

## 🚀 **ฟีเจอร์หลัก**

✅ ระบบสมาชิก (สมัคร / เข้าสู่ระบบ / ออกจากระบบ)  
✅ เพิ่ม แก้ไข ลบ TODO ได้ (CRUD)  
✅ ตั้งวันและเวลาที่ต้องเสร็จ (Due Date & Time)  
✅ แสดง countdown วัน-ชั่วโมงของ TODO  
✅ Upload รูปภาพเพื่อใช้เป็นไอคอนของ TODO  
✅ Popup ยืนยันก่อนลบและ logout ด้วย SweetAlert2  
✅ ส่วนของ UI ฉันลองใช้ **Bootstrap 5** โดยให้ **AI ช่วยออกแบบและแนะนำ**


---

## 💻 **เทคโนโลยีที่ใช้**

- **Backend:** PHP (Vanilla + PDO)
- **Database:** MySQL
- **Frontend:** Bootstrap 5, SweetAlert2
- **Environment:** Local (XAMPP / Laragon)

---

## 📂 **ขั้นตอนติดตั้งบน Local**

1. Clone repository

   ```bash
   git clone https://github.com/Kirimarch/PHP_TodoApp.git
   cd PHP_TodoApp

2. สร้างฐานข้อมูลในMySQL(PHP MyAdmin)

    SQL command

   1.CREATE DATABASE todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   2.CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
        );

   3.CREATE TABLE todos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        image VARCHAR(255),
        due_date DATE,
        due_time TIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
         );

3.ตั้งค่าไฟล์ db.php ให้เชื่อมต่อฐานข้อมูล local

   ##php
            $host = 'localhost';
            $dbname = 'todo_app';
            $username = 'root';
            $password = '';

4.เปิด Apache & MySQL (ใน XAMPP)

5.เข้าผ่าน browser: http://localhost/PHP_TodoApp

