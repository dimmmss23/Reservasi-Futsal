# 🎯 FUTSAL ID - Sistem Reservasi Lapangan Futsal

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Tailwind](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)

> Sistem reservasi lapangan futsal modern dengan implementasi **Strict OOP Principles**

---

## 📖 Tentang Aplikasi

**Futsal ID** adalah sistem manajemen reservasi lapangan futsal berbasis web yang dibangun dengan Laravel 11, mengimplementasikan konsep **Object-Oriented Programming (OOP)** secara murni.

### ✨ Fitur Utama

- 🔐 **Authentication System** - Register, Login, Logout
- ⚽ **Field Management** - CRUD lapangan futsal (Admin)
- 📅 **Booking System** - Reservasi lapangan dengan validasi ketersediaan
- 💳 **Mock Payment Gateway** - Simulasi pembayaran (Bank Transfer & Manual Upload)
- 🔔 **Payment Verification** - Verifikasi pembayaran oleh admin
- 📊 **Dashboard & Statistics** - Monitoring untuk member dan admin
- 🎨 **Modern Dark Theme** - UI/UX dengan Tailwind CSS (Neon Green accent)

---

## 🏗️ Arsitektur OOP

### ✅ Persyaratan Akademik Terpenuhi

| Requirement | Status | Detail |
|-------------|--------|--------|
| **10+ Classes** | ✅ | 12 classes implemented |
| **2+ Inheritance** | ✅ | Member & Admin extends User |
| **2+ Interface/Abstract** | ✅ | User (abstract), PaymentInterface |
| **2+ Custom Exception** | ✅ | FieldUnavailableException, PaymentFailedException |
| **1 Full CRUD** | ✅ | Reservation entity |
| **Service Layer** | ✅ | ReservationService |

### 🎯 Design Patterns

- **Single Table Inheritance (STI)** - User → Member, Admin
- **Strategy Pattern** - PaymentInterface dengan multiple implementations
- **Dependency Injection** - Loose coupling, testable code
- **Composition** - Reservation ◆→ PaymentDetail
- **Exception Handling** - Custom exceptions untuk business logic

---

## 🚀 Quick Start

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Laragon/XAMPP

### Installation

```bash
# 1. Setup database
CREATE DATABASE futsal_id;

# 2. Configure environment
cp .env.example .env
# Edit .env → Set DB_DATABASE=futsal_id

# 3. Install dependencies
composer install

# 4. Generate key
php artisan key:generate

# 5. Run migrations & seeders
php artisan migrate:fresh --seed

# 6. Start server
php artisan serve
```

**Akses:** `http://localhost:8000`

### 🔑 Default Credentials

**Admin:**
- Email: `admin@futsalid.com`
- Password: `admin123`

**Member:**
- Email: `john@example.com`
- Password: `password`

---

## 📚 Dokumentasi

Dokumentasi lengkap tersedia dalam beberapa file terpisah:

| File | Deskripsi |
|------|-----------|
| [`INSTALLATION.md`](INSTALLATION.md) | Panduan instalasi detail & troubleshooting |
| [`QUICK_START.md`](QUICK_START.md) | Panduan cepat testing & demo flow |
| [`DOCUMENTATION.md`](DOCUMENTATION.md) | Dokumentasi teknis lengkap |
| [`CLASS_DIAGRAM.md`](CLASS_DIAGRAM.md) | Diagram class & relationship UML |
| [`LAPORAN_PBO.md`](LAPORAN_PBO.md) | Laporan akademik untuk presentasi |

---

## 📊 Class Overview

### Core Classes (12 Total)

1. **User** (Abstract) - Base class untuk authentication
2. **Member** - User dengan role member (Inheritance)
3. **Admin** - User dengan role admin (Inheritance)
4. **Field** - Entity lapangan futsal
5. **Reservation** - Entity booking
6. **PaymentDetail** - Entity pembayaran (Composition)
7. **ReservationService** - Business logic layer
8. **PaymentInterface** - Contract payment gateway
9. **BankTransferMock** - Payment strategy #1
10. **ManualUploadMock** - Payment strategy #2
11. **FieldUnavailableException** - Custom exception
12. **PaymentFailedException** - Custom exception

---

## 🎨 Tech Stack

- **Backend:** Laravel 11, PHP 8.3
- **Database:** MySQL
- **Frontend:** Tailwind CSS, Font Awesome
- **Architecture:** MVC + Service Layer
- **Paradigm:** Strict OOP

---

## 🧪 Testing Scenarios

### Test Inheritance & Polymorphism

```php
php artisan tinker

$member = App\Models\Member::find(2);
echo $member->getDashboardUrl(); // /member/dashboard

$admin = App\Models\Admin::find(1);
echo $admin->getDashboardUrl(); // /admin/dashboard
```

### Test Exception Handling

1. Booking lapangan A jam 10:00 (success)
2. Coba booking lagi lapangan A jam 10:00
3. **Result:** `FieldUnavailableException` thrown

### Test Strategy Pattern

- **BankTransfer** → Auto-verify (instant confirmation)
- **ManualUpload** → Pending (need admin verification)

---

## 📸 Screenshots

### Landing Page
Dark theme dengan hero section dan field listing

### Booking Form
Form reservasi dengan real-time price calculation

### Member Dashboard
Statistik personal dan daftar reservasi

### Admin Panel
Management lapangan dan verifikasi pembayaran

---

## 🎓 Konsep OOP Diterapkan

### 1. Encapsulation
```php
class User {
    protected $fillable = ['name', 'email', 'password'];
    // Data hiding dengan protected/private
}
```

### 2. Inheritance
```php
abstract class User { ... }
class Member extends User { ... }
class Admin extends User { ... }
```

### 3. Polymorphism
```php
// Method yang sama, implementasi berbeda
$user->getDashboardUrl(); // Output depends on class type
```

### 4. Abstraction
```php
abstract class User {
    abstract public function getDashboardUrl(): string;
}

interface PaymentInterface {
    public function pay(float $amount, string $orderId): array;
}
```

### 5. Composition
```php
// PaymentDetail tidak bisa exist tanpa Reservation
Reservation ◆→ PaymentDetail (cascade delete)
```

---

## 🤝 Contributing

Ini adalah project tugas akhir akademik. Untuk keperluan edukasi dan referensi.

---

## 👨‍💻 Author

**Mata Kuliah:** Pemrograman Berorientasi Objek (PBO)  
**Framework:** Laravel 11  
**Tahun:** 2025

---

## 📄 License

Educational Purpose Only

---


