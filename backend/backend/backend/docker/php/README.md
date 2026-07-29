# Dental Healthcare Management System

## PHP Docker Configuration

Laravel 12 + PHP 8.3 FPM


---

# Overview

Folder นี้ใช้สำหรับจัดการ PHP Runtime Configuration
ภายใน Docker Container


โครงสร้าง:docker/php/

├── php.ini

├── opcache.ini

├── www.conf

└── README.md
---

# Files Description


## php.ini

หน้าที่:

กำหนด PHP Runtime Configuration

ตัวอย่าง:

- Memory Limit
- Upload Size
- Timezone
- Error Logging
- Security Setting


ใช้สำหรับ:

- Laravel Application
- API Processing
- File Upload


---

## opcache.ini

หน้าที่:

กำหนด PHP OPcache Performance


ควบคุม:

- PHP Bytecode Cache
- Memory Allocation
- Execution Performance


ใช้สำหรับ:

- Production Optimization
- API Performance


---

## www.conf

หน้าที่:

กำหนด PHP-FPM Worker Configuration


ควบคุม:

- Worker Process
- Memory Usage
- Request Handling


---

# Environment


## Development

ควรใช้:APP_ENV=local

APP_DEBUG=true

opcache.validate_timestamps=1
เพื่อให้แก้ไข Code ได้ทันที


---

## Production

ควรใช้:APP_ENV=production

APP_DEBUG=false

opcache.validate_timestamps=0
เพื่อเพิ่ม Performance


---

# Performance Guidelines


## Memory

Default:
512M
สามารถปรับตาม Server Resource


---

## PHP Worker

ปรับค่า:
pm.max_children
ตามจำนวน:

- CPU Core
- RAM
- Concurrent Users


---

# Security Guidelines


ห้าม:

- ใส่ Secret ใน Configuration File
- Commit Production Credential
- เปิด Debug Mode บน Production


---

# Maintenance


เมื่อแก้ไข Configuration:


1. Update File

2. Rebuild Container


Command:


```bash
docker compose build app
Restart:docker compose up -d
Future Extension

สามารถเพิ่ม:docker/php/

├── php.dev.ini

├── php.staging.ini

├── php.prod.ini

└── monitoring.ini
เพื่อรองรับหลาย Environment