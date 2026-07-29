# Dental Healthcare Backend System

ระบบจัดการงานทันตกรรมสำหรับโรงพยาบาลส่งเสริมสุขภาพตำบล

รองรับ:

- ระบบจองคิวทันตกรรม
- ระบบนัดหมาย
- ประวัติผู้รับบริการ
- Dashboard เจ้าหน้าที่
- Notification
- Integration กับ MyPCU


---

# Technology Stack

## Backend

- Laravel 12
- PHP 8.3
- MySQL
- REST API
- Laravel Sanctum


## Authentication

- Laravel Fortify
- Role Permission


## Real-time

- Laravel Reverb
- WebSocket


## Notification

- Firebase Cloud Messaging



---

# Installation


## 1. Clone Project

```bash
git clone dental-healthcare-backend
2. Install Dependency
composer install
3. Create Environment
cp .env.example .env
4. Generate Application Key
php artisan key:generate
5. Configure Database

แก้ไขไฟล์:
.env
ตัวอย่าง:
DB_DATABASE=dental_healthcare

DB_USERNAME=root

DB_PASSWORD=
6. Run Migration
php artisan migrate
7. Seed Initial Data
php artisan db:seed
Run Application
Start Server:
php artisan serve
ระบบจะทำงานที่:
http://localhost:8000
API Structure

Base URL:
/api/v1
ตัวอย่าง:

Authentication:
POST /api/v1/auth/login
Appointment:
GET /api/v1/appointments
Patient:
GET /api/v1/patients/me
Project Structure
backend/

├── app/

│   ├── Models/

│   ├── Services/

│   ├── Jobs/

│   └── Http/


├── config/


├── database/


├── routes/


├── storage/


└── tests/
Main Modules

Appointment Module

จัดการ:

* จองคิว
* นัดหมาย
* เปลี่ยนแปลงคิว
* แจ้งเตือน

Patient Module

จัดการ:

* ข้อมูลผู้รับบริการ
* ประวัติพื้นฐาน

Dental Module

จัดการ:

* บริการทันตกรรม
* Treatment Record
* Dental Report

Notification Module

รองรับ:

* Push Notification
* Email
* Real-time Alert

MyPCU Integration

รองรับ:

* Sync ข้อมูลสุขภาพ
* เชื่อมระบบภายนอก

⸻

Scheduler

คำสั่งอัตโนมัติ:

ส่งเตือนนัด:
appointment:reminder
Sync MyPCU:
mypcu:sync
สร้างรายงาน:
dental:report
Testing

Run Test:
php artisan test
Production Deployment

ก่อนใช้งานจริง:
php artisan optimize

php artisan config:cache

php artisan route:cache

php artisan view:cache
Security

ระบบรองรับ:

* HTTPS
* Cookie Security
* Sanctum Token
* Role Permission
* Audit Logging
* PDPA Compliance
Developer

Dental Healthcare Platform

สำหรับ:

โรงพยาบาลส่งเสริมสุขภาพตำบล

Version:

1.0.0