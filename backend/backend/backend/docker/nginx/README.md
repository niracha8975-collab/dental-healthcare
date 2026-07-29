# Dental Healthcare Management System

## Nginx Docker Configuration

Laravel 12 + PHP 8.3 FPM


---

# Overview

Folder นี้ใช้สำหรับจัดการ Nginx Web Server
ภายใน Docker Environment


หน้าที่หลัก:

- รับ HTTP Request
- Serve Static Files
- Forward PHP Request ไป PHP-FPM
- จัดการ Laravel Front Controller


---

# Folder Structure
docker/nginx/

├── default.conf

└── README.md
---

# Architecture Flow
Client

|

|

Nginx Container

|

|

Laravel Public Directory

|

|

PHP-FPM Container

|

|

Laravel Application
---

# Configuration File


## default.conf


หน้าที่:

กำหนด Nginx Server Configuration


ประกอบด้วย:


- Server Block
- Document Root
- Laravel Rewrite Rule
- PHP FastCGI
- Security Header
- Upload Limit


---

# Laravel Integration


Nginx ใช้:
root /var/www/html/public;
เพื่อให้ Application Entry Point อยู่ที่:
public/index.php
ป้องกัน:

- .env Exposure
- Source Code Exposure
- Configuration Leak


---

# PHP-FPM Connection


Nginx ส่ง PHP Request ไป:
app:9000
ผ่าน Docker Network


Flow:
Nginx

↓

PHP-FPM

↓

Laravel
---

# Static File Handling


ไฟล์ที่ Nginx จัดการโดยตรง:
.css

.js

.jpg

.png

.svg

.woff
ประโยชน์:


- ลด PHP Processing
- เพิ่ม Response Speed


---

# Security Standard


ระบบกำหนด:


## Hidden File Protection


Block:
.env

.git

config files
---

## Security Headers


เปิดใช้งาน:
X-Frame-Options

X-Content-Type-Options

X-XSS-Protection
---

# Upload Configuration


กำหนด:
client_max_body_size 20M;
รองรับ:


- Dental Image
- Patient Document
- Report Attachment


---

# Development Environment


สำหรับ Development:


ควรเปิด:
error_log debug;
เพื่อช่วย Debug


---

# Production Environment


Production ควร:
access_log off;

error_log warn;
และเพิ่ม:


- HTTPS
- SSL Certificate
- HSTS
- Rate Limit


---

# Deployment Checklist


ก่อน Deploy ตรวจสอบ:


- [ ] Nginx Container Running

- [ ] PHP-FPM Connected

- [ ] Laravel Public Path ถูกต้อง

- [ ] HTTPS Enabled

- [ ] Upload Limit ถูกต้อง

- [ ] Security Header Enabled


---

# Future Extension


สามารถเพิ่ม:
docker/nginx/

├── default.conf

├── ssl.conf

├── gzip.conf

├── security.conf

└── rate-limit.conf
รองรับ:


- Production Hardening
- High Traffic
- Load Balancer
- Reverse Proxy