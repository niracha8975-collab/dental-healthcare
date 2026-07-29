# Dental Healthcare Management System

## PHP Runtime Configuration

PHP 8.3 FPM + Laravel 12


---

# Overview

Folder นี้ใช้จัดการ PHP Runtime Configuration

สำหรับ Laravel Backend Application


ประกอบด้วย:

- PHP Runtime
- PHP-FPM Pool
- OPcache
- Performance Configuration


---

# Folder Structure
docker/php/

├── php.ini

├── www.conf

├── opcache.ini

└── README.md
---

# Architecture
Nginx Container
    |

    |
PHP-FPM Container
    |

    |
Laravel 12 Application
    |

    |
MySQL / Redis
---

# PHP Runtime


ระบบใช้:
PHP 8.3 FPM
เหตุผล:


- รองรับ Laravel 12
- Performance ดี
- Security Update ระยะยาว


---

# PHP Extensions


Extension หลัก:
pdo_mysql

mbstring

intl

zip

opcache
หน้าที่:


## pdo_mysql

เชื่อมต่อ MySQL Database


## mbstring

รองรับข้อความภาษาไทย


## intl

จัดการ Locale


## zip

รองรับ Package และ File Processing


## opcache

เพิ่ม PHP Performance


---

# PHP Configuration


ไฟล์:
php.ini
ควบคุม:


- Memory Limit
- Upload Limit
- Timezone
- Error Logging
- Security


---

# PHP-FPM Configuration


ไฟล์:
www.conf
ควบคุม:


- Worker Process
- Connection
- Request Handling
- Resource Usage


---

# OPcache


ไฟล์:
opcache.ini
หน้าที่:


- Cache PHP Bytecode
- ลด CPU Usage
- เพิ่ม Response Speed


Production เปิด:
opcache.validate_timestamps=0
หลัง Deploy ต้อง Restart Container


---

# Resource Guideline


ค่าพื้นฐาน:
Memory Limit:

512M

Upload:

25MB

PHP Workers:
50
ควรปรับตาม:


- CPU
- RAM
- จำนวนผู้ใช้งาน


---

# Development Environment


Development:
APP_DEBUG=true
เปิด:

- Error Detail
- Debug Tool


---

# Production Environment


Production:
APP_DEBUG=false
เปิด:


- Error Logging
- Security Header
- Performance Optimization


---

# Security Standard


ต้อง:


- ปิด expose_php
- ไม่แสดง Error ต่อ User
- ใช้ Non-root Container User
- ไม่เก็บ Secret ใน Image


---

# Monitoring


ควรติดตาม:


## PHP-FPM
active processes

idle processes

max children reached
## Application
response time

error rate

memory usage
---

# Maintenance


ตรวจสอบ PHP Container:


```bash
docker compose logs app
Restart:
docker compose restart app
Future Extension

รองรับการเพิ่ม:
docker/php/

├── supervisor/

├── worker.conf

├── scheduler.conf

└── monitoring.conf
เพื่อรองรับ:

* Laravel Queue Worker
* Scheduler
* Horizon
* Monitoring
