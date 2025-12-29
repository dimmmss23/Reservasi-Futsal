<div align="center">

# ⚽ FUTSAL ID - Sistem Reservasi Lapangan Futsal

### *Modern Futsal Booking System with Pure OOP Architecture* 

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)

<p align="center">
  <img src="https://img.shields.io/badge/Status-Active-success?style=flat-square" alt="Status">
  <img src="https://img.shields.io/badge/License-Educational-blue?style=flat-square" alt="License">
  <img src="https://img.shields.io/badge/Made%20with-❤️-red?style=flat-square" alt="Made with Love">
</p>

**[📖 Dokumentasi](#-dokumentasi)** • 
**[🚀 Quick Start](#-quick-start)** • 
**[✨ Features](#-fitur-utama)** • 
**[🏗️ Architecture](#-arsitektur-oop)** • 
**[📸 Demo](#-screenshots)**

---

</div>

## 🌟 Tentang Aplikasi

**Futsal ID** adalah platform reservasi lapangan futsal modern yang dibangun dengan **Laravel 11** dan menerapkan prinsip **Object-Oriented Programming (OOP)** secara konsisten. Sistem ini dirancang untuk memudahkan pengelolaan booking lapangan futsal dengan antarmuka yang elegan dan alur kerja yang efisien.

### 💡 Kenapa Futsal ID?

```diff
+ ✅ Implementasi OOP murni dengan 12+ classes
+ ✅ Arsitektur service layer untuk business logic
+ ✅ Design patterns (Strategy, Dependency Injection, STI)
+ ✅ Custom exception handling untuk error management
+ ✅ Real-time booking validation
+ ✅ Modern dark theme UI dengan Tailwind CSS
```

### ✨ Fitur Utama

<table>
<tr>
<td width="50%">

#### 👥 Untuk Member
- 🔐 **Register & Login** - Sistem autentikasi aman
- 📅 **Booking Lapangan** - Reservasi dengan validasi real-time
- 💳 **Payment Gateway** - Multiple payment methods
- 📊 **Dashboard Pribadi** - Track booking history
- 📧 **Email Notification** - Konfirmasi otomatis
- 🖼️ **Upload Bukti Bayar** - Manual payment proof

</td>
<td width="50%">

#### 👨‍💼 Untuk Admin
- ⚽ **Manajemen Lapangan** - Full CRUD operations
- ✅ **Verifikasi Pembayaran** - Payment approval system
- 📈 **Statistics Dashboard** - Real-time analytics
- 👥 **User Management** - Manage members
- 📋 **Booking Management** - Oversee all reservations
- 🔔 **Notification Center** - Pending actions alert

</td>
</tr>
</table>

### 🎨 Design Highlights

- 🌙 **Dark Mode First** - Modern dark theme dengan neon green accent
- 📱 **Responsive Design** - Mobile-friendly interface
- ⚡ **Fast & Smooth** - Optimized performance
- 🎯 **User-Centric** - Intuitive user experience

---

## 🏗️ Arsitektur OOP

### 📐 Persyaratan Akademik ✅

<div align="center">

| 🎯 Requirement | ✅ Status | 📝 Implementation | 🔢 Count |
|---------------|----------|-------------------|----------|
| **Classes** | ✅ Passed | User, Member, Admin, Field, Reservation, PaymentDetail, Services, Exceptions | **12+** |
| **Inheritance** | ✅ Passed | Member & Admin extends User (abstract) | **2** |
| **Interface/Abstract** | ✅ Passed | User (abstract), PaymentInterface | **2** |
| **Custom Exception** | ✅ Passed | FieldUnavailableException, PaymentFailedException | **2** |
| **Full CRUD** | ✅ Passed | Reservation entity with complete operations | **1** |
| **Service Layer** | ✅ Passed | ReservationService for business logic | **1** |

</div>

### 🎯 Design Patterns Implemented

<table>
<tr>
<td width="33%">

**🏛️ Strategy Pattern**
```
PaymentInterface
├── BankTransferMock
└── ManualUploadMock
```
*Different payment strategies*

</td>
<td width="33%">

**👥 STI Pattern**
```
User (Abstract)
├── Member
└── Admin
```
*Single Table Inheritance*

</td>
<td width="33%">

**💎 Composition**
```
Reservation ◆→ PaymentDetail
```
*Strong relationship with cascade delete*

</td>
</tr>
</table>

### 🔧 Additional Patterns

- 🎯 **Dependency Injection** - Loose coupling between components
- 🛡️ **Repository Pattern** - Data access abstraction via Eloquent
- 🎨 **MVC Architecture** - Clear separation of concerns
- ⚡ **Service Layer** - Business logic encapsulation
- 🚨 **Exception Handling** - Custom exceptions for domain errors

---

## 🚀 Quick Start

### 📋 Prerequisites

Pastikan sistem Anda sudah terinstall:

- ![PHP](https://img.shields.io/badge/PHP-≥8.2-777BB4?style=flat-square&logo=php) PHP 8.2 atau lebih tinggi
- ![Composer](https://img.shields.io/badge/Composer-Latest-885630?style=flat-square&logo=composer) Composer
- ![MySQL](https://img.shields.io/badge/MySQL-≥8.0-4479A1?style=flat-square&logo=mysql) MySQL/MariaDB
- ![Node.js](https://img.shields.io/badge/Node.js-Optional-339933?style=flat-square&logo=node.js) Node.js (untuk asset compilation)

### ⚙️ Installation

```bash
# 1️⃣ Clone repository
git clone https://github.com/dimmmss23/Reservasi-Futsal.git
cd Reservasi-Futsal

# 2️⃣ Install dependencies
composer install
npm install  # optional, jika ingin compile assets

# 3️⃣ Setup environment
cp .env.example .env

# 4️⃣ Generate application key
php artisan key:generate

# 5️⃣ Setup database
# Buat database 'futsal_id' di MySQL
# Update .env dengan kredensial database Anda:
#   DB_DATABASE=futsal_id
#   DB_USERNAME=root
#   DB_PASSWORD=

# 6️⃣ Run migrations & seeders
php artisan migrate:fresh --seed

# 7️⃣ Create storage link
php artisan storage:link

# 8️⃣ Start development server
php artisan serve
```

🎉 **Done!** Buka browser dan akses: `http://localhost:8000`

### 🔑 Default Login Credentials

<table>
<tr>
<th>👨‍💼 Admin Account</th>
<th>👤 Member Account</th>
</tr>
<tr>
<td>

```
📧 Email    : admin@futsalid.com
🔐 Password : admin123
```

</td>
<td>

```
📧 Email    : john@example.com
🔐 Password : password
```

</td>
</tr>
</table>

### 🎯 Quick Test Flow

1. **Login sebagai Member** → Booking lapangan → Upload bukti bayar
2. **Login sebagai Admin** → Verifikasi pembayaran → Approve booking
3. **Check Member Dashboard** → Lihat status booking berubah jadi "paid"

---

## 📚 Dokumentasi

Dokumentasi lengkap tersedia dalam file terpisah untuk kemudahan navigasi:

| 📄 File | 📝 Deskripsi | 🔗 Link |
|---------|-------------|---------|
| 📘 **INSTALLATION_GUIDE.md** | Panduan instalasi detail & troubleshooting | [View →](INSTALLATION_GUIDE.md) |
| 🎓 **DOKUMENTASI_OOP.md** | Penjelasan konsep OOP yang diterapkan | [View →](DOKUMENTASI_OOP.md) |
| 📊 **Class Diagram** | Visualisasi struktur class & relationships | *Coming Soon* |
| 💌 **EMAIL_TEMPLATE.md** | Template email notification system | [View →](EMAIL_TEMPLATE.md) |
| 📦 **PACKAGING_CHECKLIST.md** | Checklist untuk distribusi project | [View →](PACKAGING_CHECKLIST.md) |

---

## 📊 Class Overview

### 🎨 Architecture Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                     PRESENTATION LAYER                       │
│  Controllers: Admin, Auth, Dashboard, Reservation, Payment  │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│                      BUSINESS LAYER                          │
│          Services: ReservationService                        │
│          Interfaces: PaymentInterface                        │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│                       DATA LAYER                             │
│  Models: User, Member, Admin, Field, Reservation, Payment   │
└─────────────────────────────────────────────────────────────┘
```

### 📦 Core Classes (12 Total)

<table>
<tr>
<td width="50%">

**🏛️ Domain Models**
1. 👤 **User** (Abstract) - Base authentication
2. 👨 **Member** - Customer dengan role member
3. 👨‍💼 **Admin** - Admin dengan role admin  
4. ⚽ **Field** - Entity lapangan futsal
5. 📅 **Reservation** - Entity booking
6. 💳 **PaymentDetail** - Entity pembayaran

</td>
<td width="50%">

**⚙️ Services & Patterns**
7. 🔧 **ReservationService** - Business logic
8. 💰 **PaymentInterface** - Payment contract
9. 🏦 **BankTransferMock** - Auto-verify payment
10. 📤 **ManualUploadMock** - Manual verify payment
11. ⚠️ **FieldUnavailableException** - Custom exception
12. 🚨 **PaymentFailedException** - Custom exception

</td>
</tr>
</table>

---

## 🛠️ Tech Stack

<div align="center">

### Backend
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

### Frontend
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Blade](https://img.shields.io/badge/Blade-Template-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/docs/blade)
[![Font Awesome](https://img.shields.io/badge/Font_Awesome-Icons-339AF0?style=for-the-badge&logo=font-awesome&logoColor=white)](https://fontawesome.com)

### Architecture
![MVC](https://img.shields.io/badge/Pattern-MVC-success?style=for-the-badge)
![OOP](https://img.shields.io/badge/Paradigm-OOP-blue?style=for-the-badge)
![Service Layer](https://img.shields.io/badge/Architecture-Service_Layer-orange?style=for-the-badge)

</div>

---

## 🧪 Testing & Demo Scenarios

### 🔬 Test 1: Inheritance & Polymorphism

Buktikan bahwa Member dan Admin adalah turunan dari User dengan behavior berbeda:

```php
# Jalankan Laravel Tinker
php artisan tinker

# Test polymorphism
$member = App\Models\Member::find(2);
echo $member->getDashboardUrl();
// Output: /member/dashboard

$admin = App\Models\Admin::find(1);
echo $admin->getDashboardUrl();
// Output: /admin/dashboard

# Keduanya instance dari User
$member instanceof App\Models\User;  // true
$admin instanceof App\Models\User;   // true
```

### ⚠️ Test 2: Exception Handling

Test custom exception saat booking bentrok:

1. 🟢 Login sebagai member
2. 🟢 Book **Lapangan A** untuk **hari ini jam 10:00-11:00**
3. 🟢 Booking berhasil (status: pending)
4. 🔴 Coba book lagi **Lapangan A** di **waktu yang sama**
5. 🔴 **Result:** `FieldUnavailableException` thrown dengan error message

### 💳 Test 3: Strategy Pattern (Payment)

Test bahwa payment method berbeda memiliki behavior berbeda:

**Scenario A: Bank Transfer**
- Member pilih payment method: **Bank Transfer**
- Status otomatis: **Paid** (auto-verify)
- Tidak perlu upload bukti bayar

**Scenario B: Manual Upload**
- Member pilih payment method: **Manual Upload**
- Status: **Pending** (need verification)
- Member upload bukti bayar
- Admin verify → Status berubah **Paid**

### 🔄 Test 4: Full CRUD Flow

Test complete CRUD operations pada Reservation:

- **Create** ✅ - Member buat booking baru
- **Read** ✅ - View daftar booking (member & admin)
- **Update** ✅ - Admin update status pembayaran
- **Delete** ✅ - Member/Admin batalkan booking

---


## 🎓 Konsep OOP yang Diterapkan

### 1. 🔒 Encapsulation (Data Hiding)

Menyembunyikan implementasi internal dan hanya expose interface yang diperlukan:

```php
class User extends Authenticatable {
    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];
    
    // Public interface
    public function getName(): string {
        return $this->name;
    }
    
    // Private data protected dari akses eksternal
}
```

**✅ Benefit:** Data integrity terjaga, perubahan internal tidak affect external code

---

### 2. 🧬 Inheritance (Pewarisan)

Member dan Admin mewarisi properti dan method dari User:

```php
abstract class User extends Authenticatable {
    // Shared properties & methods
    protected $fillable = ['name', 'email', 'password', 'role'];
    
    abstract public function getDashboardUrl(): string;
}

class Member extends User {
    public function getDashboardUrl(): string {
        return '/member/dashboard';  // Specific implementation
    }
}

class Admin extends User {
    public function getDashboardUrl(): string {
        return '/admin/dashboard';  // Different implementation
    }
}
```

**✅ Benefit:** Code reuse, avoid duplication, logical hierarchy

---

### 3. 🔄 Polymorphism (Banyak Bentuk)

Objek berbeda merespon method yang sama dengan cara berbeda:

```php
// Same method call, different behavior
$user = Auth::user();
return redirect($user->getDashboardUrl());

// Jika $user adalah Member → redirect ke /member/dashboard
// Jika $user adalah Admin  → redirect ke /admin/dashboard
```

**✅ Benefit:** Flexible code, easier maintenance, runtime behavior selection

---

### 4. 🎨 Abstraction (Penyederhanaan)

Menyembunyikan kompleksitas dan hanya show essential features:

```php
// Abstract class
abstract class User {
    abstract public function getDashboardUrl(): string;
    // Force subclass to implement
}

// Interface
interface PaymentInterface {
    public function pay(float $amount, string $orderId): array;
    public function verify(string $paymentId): bool;
}
```

**✅ Benefit:** Enforce contract, standardize behavior, loose coupling

---

### 5. 🧩 Composition (Strong Relationship)

PaymentDetail tidak bisa exist tanpa Reservation:

```php
class Reservation extends Model {
    public function paymentDetail() {
        return $this->hasOne(PaymentDetail::class);
    }
}

// Cascade delete - hapus reservation = hapus payment detail
Schema::table('payment_details', function (Blueprint $table) {
    $table->foreignId('reservation_id')
          ->constrained()
          ->onDelete('cascade');  // Composition implementation
});
```

**✅ Benefit:** Strong relationship, data consistency, automatic cleanup

---

## 🚀 Roadmap & Future Enhancements

- [ ] 📱 Progressive Web App (PWA) support
- [ ] 🔔 Real-time notification dengan WebSocket
- [ ] 📊 Advanced analytics & reporting
- [ ] 🌐 Multi-language support (ID/EN)
- [ ] 💰 Integrasi payment gateway real (Midtrans/Xendit)
- [ ] 📧 Email notification system
- [ ] 🎫 QR Code untuk check-in
- [ ] ⭐ Rating & review system
- [ ] 📅 Recurring booking feature
- [ ] 🤖 Chatbot customer support

---

## 🤝 Contributing

Project ini dibuat untuk **keperluan akademik** (Tugas Akhir PBO). 

Namun, kontribusi tetap welcome untuk:
- 🐛 Bug fixes
- 📝 Documentation improvements
- ✨ Feature suggestions
- 🎨 UI/UX enhancements

### How to Contribute?

1. Fork repository ini
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📞 Contact & Support

<div align="center">

**Dimas Agung Subayu**

[![GitHub](https://img.shields.io/badge/GitHub-dimmmss23-181717?style=for-the-badge&logo=github)](https://github.com/dimmmss23)
[![Email](https://img.shields.io/badge/Email-dascreation7878@gmail.com-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:dascreation7878@gmail.com)

</div>

### 💬 Need Help?

- 📖 Baca [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md) untuk panduan lengkap
- 🐛 Report bugs via [GitHub Issues](https://github.com/dimmmss23/Reservasi-Futsal/issues)
- 💡 Request features via [GitHub Discussions](https://github.com/dimmmss23/Reservasi-Futsal/discussions)

---

## 🎓 Academic Information

| Info | Detail |
|------|--------|
| **Mata Kuliah** | Pemrograman Berorientasi Objek (PBO) |
| **Framework** | Laravel 11 |
| **Database** | MySQL |
| **Semester** | Genap 2024/2025 |
| **Status** | ✅ Active Development |

---

## 📄 License

```
Copyright (c) 2025 Dimas Agung Subayu

This project is created for EDUCATIONAL PURPOSES ONLY.
Not licensed for commercial use without permission.
```

<div align="center">

### ⭐ Star This Repository!

Jika project ini membantu Anda, jangan lupa untuk memberikan ⭐ 

Made with ❤️ by [Dimas Agung Subayu](https://github.com/dimmmss23)

---

**[⬆ Back to Top](#-futsal-id---sistem-reservasi-lapangan-futsal)**

</div>


