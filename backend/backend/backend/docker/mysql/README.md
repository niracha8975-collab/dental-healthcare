# Dental Healthcare Management System

## MySQL Docker Configuration

MySQL 8.4 LTS


---

# Overview

Folder นี้ใช้สำหรับจัดการ MySQL Database Container

หน้าที่:

- เก็บข้อมูลระบบ
- จัดการ Transaction
- รองรับ Laravel Database Layer
- รองรับ Healthcare Data


---

# Folder Structure
docker/mysql/

├── my.cnf

└── README.md
---

# Database Architecture
Laravel Application
    |

    |
PDO MySQL Driver
    |

    |
MySQL 8.4 Container
    |

    |
Persistent Volume
---

# Configuration File


## my.cnf


หน้าที่:

กำหนด Database Server Configuration


ประกอบด้วย:


- Character Set
- Collation
- InnoDB Engine
- Connection Limit
- Logging
- Performance


---

# Character Encoding


ระบบใช้:
InnoDB
เหตุผล:


รองรับ:

- Transaction
- Foreign Key
- Data Consistency


เหมาะกับข้อมูล:


- Patient
- Appointment
- Treatment History


---

# Timezone


กำหนด:
+07:00
ให้ตรงกับ:

ประเทศไทย


---

# Connection Management


ค่าเริ่มต้น:
max_connections = 300
รองรับ:


- Mobile User
- Admin User
- Background Worker


---

# Logging


เปิด:
slow_query_log
เพื่อ:


- วิเคราะห์ Query ช้า
- Database Optimization
- Performance Monitoring


---

# Backup Strategy


Production ต้องมี:


## Daily Backup
ทุกวัน 02:00
เก็บ:

- Database Dump
- Transaction Backup


---

## Backup Retention


แนะนำ:
Daily 7 วัน

Weekly 4 สัปดาห์

Monthly 12 เดือน
---

# Security Guideline


ห้าม:


- เปิด Database Public Internet
- ใช้ Root Account ใน Application
- Commit Password ลง Git


---

# Application Database User


ควรใช้:
dental_user
แทน:
root
---

# Migration Policy


Database Schema ต้องเปลี่ยนผ่าน:
Laravel Migration
ไม่แก้ไข Database โดยตรง


---

# Production Checklist


ก่อนใช้งานจริง:


- [ ] Backup ทำงาน

- [ ] User Permission ถูกต้อง

- [ ] UTF-8 ถูกต้อง

- [ ] Slow Query Monitoring เปิด

- [ ] Database Password เปลี่ยน


---

# Future Extension


รองรับ:
docker/mysql/

├── my.cnf

├── backup/

├── replication/

└── monitoring/
เพื่อรองรับ:


- Database Replica
- Disaster Recovery
- High Availability