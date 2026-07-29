# Dental Healthcare Management System

## Redis Docker Configuration

Redis 7 Alpine


---

# Overview

Redis เป็น Infrastructure Service สำหรับจัดการข้อมูลแบบ In-Memory

ระบบใช้ Redis สำหรับ:

- Cache
- Queue
- Session
- Rate Limiting
- Background Processing


---

# Folder Structure
docker/redis/

├── redis.conf

└── README.md
---

# Architecture
Laravel Application
    |

    |
Redis Service
    |
|          |          |

Cache    Queue     Session
---

# Redis Configuration


## redis.conf


ใช้กำหนด:

- Memory Management
- Persistence
- Connection
- Security
- Performance


---

# Laravel Integration


## Cache


Laravel ใช้ Redis สำหรับ:
CACHE_STORE=redis
ใช้กับ:

- Configuration Cache
- Query Cache
- Temporary Data


---

## Queue


Laravel Queue ใช้:
QUEUE_CONNECTION=redis
รองรับ:

- Notification
- Appointment Reminder
- MyPCU Sync
- Report Processing


---

## Session


ระบบสามารถใช้ Redis Session:
SESSION_DRIVER=redis
ประโยชน์:

- รองรับหลาย Instance
- ลด Database Load


---

# Persistence


เปิด:
appendonly yes
เพื่อเก็บข้อมูล Redis ลง Disk


รองรับ:

- Queue Recovery
- Service Restart


---

# Memory Management


กำหนด:
maxmemory-policy allkeys-lru
เมื่อ Memory เต็ม:

Redis จะลบ Cache ที่ไม่ได้ใช้งาน


---

# Security Standard


Production ต้อง:


- ไม่เปิด Redis Public Internet
- ใช้ Private Network
- ตั้ง Password Authentication
- จำกัด Container Access


---

# Monitoring


ควรติดตาม:


## Memory Usage
used_memory
## Connection
connected_clients
## Queue
queue_length
---

# Maintenance


Restart Redis:


```bash
docker compose restart redis
ดู Log:
docker compose logs redis
เข้า Redis CLI:
docker compose exec redis redis-cli
Backup Strategy

Redis Backup:
RDB Snapshot

+

AOF Persistence
Production ควร Backup:

* Daily
* Before Deployment
* Before Configuration Change
Production Checklist

ก่อนใช้งานจริง:

* Redis Password Enabled
* Private Network Enabled
* Memory Limit Configured
* Persistence Enabled
* Monitoring Enabled

⸻

Future Extension

สามารถเพิ่ม:
docker/redis/

├── redis.conf

├── sentinel.conf

├── backup/

└── monitoring/
รองรับ:

* Redis Sentinel
* High Availability
* Failover
* Scaling
