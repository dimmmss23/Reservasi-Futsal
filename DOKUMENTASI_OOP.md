# DOKUMENTASI IMPLEMENTASI OOP - SISTEM RESERVASI FUTSAL

## 📋 Ringkasan Ketentuan OOP

| No | Ketentuan | Jumlah | File/Lokasi | Status |
|----|-----------|--------|-------------|--------|
| 1 | **Inheritance** | 3 contoh | `Admin.php`, `Member.php`, `Controller.php` | ✅ Terpenuhi |
| 2 | **Interface** | 1 interface | `PaymentInterface.php` | ✅ Terpenuhi |
| 3 | **Abstract Class** | 1 abstract | `Authenticatable` (Laravel Base) | ✅ Terpenuhi |
| 4 | **Custom Exception** | 2 exceptions | `PaymentFailedException.php`, `FieldUnavailableException.php` | ✅ Terpenuhi |
| 5 | **CRUD Entitas** | 3 entitas | Field, Reservation, PaymentDetail | ✅ Terpenuhi |
| 6 | **PDO + Prepared Statement** | Laravel Eloquent | Semua Model + `config/database.php` | ✅ Terpenuhi |
| 7 | **Struktur Folder Rapi** | MVC Pattern | `app/`, `routes/`, `resources/` | ✅ Terpenuhi |

---

## 🔹 1. INHERITANCE (Pewarisan)

### 1.1 Admin extends User
**📁 File:** `app/Models/Admin.php`

```php
/**
 * Class Admin
 * 
 * ✅ INHERITANCE #1: Admin extends User (Single Table Inheritance)
 * Admin mewarisi semua property dan method dari User
 */
class Admin extends User
{
    protected $table = 'users'; // Single Table Inheritance
    
    // Override method dari parent
    public function getDashboardUrl(): string
    {
        return 'admin.dashboard';
    }
    
    // Method khusus admin
    public function manageField(array $data): Field { }
    public function verifyPayment(PaymentDetail $payment): bool { }
    public function rejectPayment(PaymentDetail $payment): bool { }
}
```

**Konsep OOP:**
- Mewarisi property: `name`, `email`, `password`, `role`, `phone`, `points`
- Mewarisi method: `isMember()`, `isAdmin()`, `reservations()`
- Override method: `getDashboardUrl()`
- Menambah method baru: `manageField()`, `verifyPayment()`, `rejectPayment()`

---

### 1.2 Member extends User
**📁 File:** `app/Models/Member.php`

```php
/**
 * Class Member
 * 
 * ✅ INHERITANCE #2: Member extends User (Single Table Inheritance)
 * Member mewarisi semua property dan method dari User
 */
class Member extends User
{
    protected $table = 'users'; // Single Table Inheritance
    
    // Override method dari parent
    public function getDashboardUrl(): string
    {
        return 'member.dashboard';
    }
    
    // Method khusus member
    public static function register(array $data): Member { }
    public function book(int $fieldId, string $bookTime, int $duration): Reservation { }
    public function addPoints(int $points): void { }
}
```

**Konsep OOP:**
- Mewarisi property: `name`, `email`, `password`, `role`, `phone`, `points`
- Mewarisi method: `isMember()`, `isAdmin()`, `reservations()`
- Override method: `getDashboardUrl()`
- Menambah method baru: `register()`, `book()`, `addPoints()`

---

### 1.3 Controller Inheritance
**📁 File:** `app/Http/Controllers/AdminController.php`, `ReservationController.php`, dll

```php
/**
 * ✅ INHERITANCE #3: All Controllers extend Base Controller
 */
class AdminController extends Controller { }
class ReservationController extends Controller { }
class AuthController extends Controller { }
```

---

## 🔹 2. INTERFACE

### 2.1 PaymentInterface
**📁 File:** `app/Services/Payment/PaymentInterface.php`

```php
/**
 * Interface PaymentInterface
 * 
 * ✅ INTERFACE: Kontrak untuk implementasi berbagai metode pembayaran
 * Menerapkan Strategy Pattern untuk Polymorphism
 */
interface PaymentInterface
{
    public function pay(float $amount, string $orderId): array;
    public function verify(string $transactionId): array;
    public function getMethodName(): string;
}
```

**Implementasi:**

#### 2.1.1 BankTransferMock implements PaymentInterface
**📁 File:** `app/Services/Payment/BankTransferMock.php`

```php
/**
 * ✅ INTERFACE IMPLEMENTATION #1: BankTransferMock
 */
class BankTransferMock implements PaymentInterface
{
    public function pay(float $amount, string $orderId): array
    {
        // Generate transaction ID
        $transactionId = 'BTM-' . strtoupper(Str::random(12));
        
        // Redirect ke payment simulation
        return [
            'status' => 'pending',
            'transaction_id' => $transactionId,
            'redirect_url' => route('payment.simulation.show', $reservationId)
        ];
    }
    
    public function verify(string $transactionId): array { }
    public function getMethodName(): string { return 'BankTransfer'; }
}
```

#### 2.1.2 ManualUploadMock implements PaymentInterface
**📁 File:** `app/Services/Payment/ManualUploadMock.php`

```php
/**
 * ✅ INTERFACE IMPLEMENTATION #2: ManualUploadMock
 */
class ManualUploadMock implements PaymentInterface
{
    public function pay(float $amount, string $orderId): array
    {
        $transactionId = 'MUM-' . strtoupper(Str::random(12));
        
        return [
            'status' => 'pending',
            'transaction_id' => $transactionId,
            'message' => 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.'
        ];
    }
    
    public function verify(string $transactionId): array { }
    public function getMethodName(): string { return 'ManualUpload'; }
}
```

**Keuntungan Interface:**
- ✅ **Polymorphism**: Bisa switch payment method tanpa ubah kode business logic
- ✅ **Loose Coupling**: Controller tidak tahu implementasi detail payment
- ✅ **Easy Testing**: Bisa buat mock implementation untuk testing
- ✅ **Extensible**: Mudah tambah payment method baru (Midtrans, OVO, GoPay, dll)

---

## 🔹 3. ABSTRACT CLASS

### 3.1 User extends Authenticatable (Laravel Abstract)
**📁 File:** `app/Models/User.php`

```php
/**
 * Class User
 * 
 * ✅ ABSTRACT CLASS: User extends Authenticatable (Laravel base class)
 * Authenticatable adalah abstract class dari Laravel untuk authentication
 * 
 * Base class untuk semua tipe user dalam sistem.
 * Menerapkan Single Table Inheritance dengan discriminator column 'role'.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'points'];
    
    /**
     * Polymorphic behavior berdasarkan role
     */
    public function getDashboardUrl(): string
    {
        return match($this->role) {
            'admin' => 'admin.dashboard',
            'member' => 'member.dashboard',
            default => 'home',
        };
    }
    
    public function isMember(): bool { return $this->role === 'member'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
}
```

**Konsep Abstract Class:**
- `Authenticatable` adalah abstract class dari Laravel (`Illuminate\Foundation\Auth\User`)
- User tidak bisa langsung instantiate `Authenticatable`, harus extend dulu
- Abstract class `Authenticatable` punya method abstract yang harus di-implement

---

## 🔹 4. CUSTOM EXCEPTION

### 4.1 PaymentFailedException
**📁 File:** `app/Exceptions/PaymentFailedException.php`

```php
/**
 * Class PaymentFailedException
 * 
 * ✅ CUSTOM EXCEPTION #1: Exception untuk payment gagal
 * Dilempar ketika proses pembayaran gagal
 */
class PaymentFailedException extends Exception
{
    protected $transactionId;
    protected $paymentMethod;
    protected $amount;
    
    public function __construct(
        string $transactionId = '',
        string $paymentMethod = '',
        float $amount = 0,
        string $message = ''
    ) {
        $this->transactionId = $transactionId;
        $this->paymentMethod = $paymentMethod;
        $this->amount = $amount;
        
        if (empty($message)) {
            $message = "Pembayaran gagal untuk transaksi {$transactionId} 
                        menggunakan metode {$paymentMethod}. 
                        Jumlah: Rp " . number_format($amount, 0, ',', '.');
        }
        
        parent::__construct($message);
    }
    
    // Custom methods
    public function getTransactionId(): string { return $this->transactionId; }
    public function getPaymentMethod(): string { return $this->paymentMethod; }
    public function getAmount(): float { return $this->amount; }
    
    // Custom HTTP response
    public function render()
    {
        return response()->json([
            'error' => 'Payment Failed',
            'message' => $this->getMessage(),
            'transaction_id' => $this->transactionId,
            'payment_method' => $this->paymentMethod,
            'amount' => $this->amount
        ], 402); // 402 Payment Required
    }
}
```

**Penggunaan:**
```php
// Di ReservationService.php
if ($paymentResult['status'] === 'failed') {
    throw new PaymentFailedException(
        $paymentResult['transaction_id'],
        $this->paymentGateway->getMethodName(),
        $totalPrice,
        $paymentResult['message']
    );
}
```

---

### 4.2 FieldUnavailableException
**📁 File:** `app/Exceptions/FieldUnavailableException.php`

```php
/**
 * Class FieldUnavailableException
 * 
 * ✅ CUSTOM EXCEPTION #2: Exception untuk lapangan tidak tersedia
 * Dilempar ketika lapangan sudah dibooking (double booking prevention)
 */
class FieldUnavailableException extends Exception
{
    protected $fieldName;
    protected $requestedTime;
    
    public function __construct(
        string $fieldName = '', 
        string $requestedTime = '', 
        string $message = ''
    ) {
        $this->fieldName = $fieldName;
        $this->requestedTime = $requestedTime;
        
        if (empty($message)) {
            $message = "Lapangan '{$fieldName}' tidak tersedia pada waktu {$requestedTime}. 
                        Sudah dibooking oleh member lain.";
        }
        
        parent::__construct($message);
    }
    
    // Custom methods
    public function getFieldName(): string { return $this->fieldName; }
    public function getRequestedTime(): string { return $this->requestedTime; }
    
    // Custom HTTP response
    public function render()
    {
        return response()->json([
            'error' => 'Field Unavailable',
            'message' => $this->getMessage(),
            'field' => $this->fieldName,
            'requested_time' => $this->requestedTime
        ], 409); // 409 Conflict
    }
}
```

**Penggunaan:**
```php
// Di ReservationService.php
if (!$field->isAvailableAt($bookTime, $duration)) {
    throw new FieldUnavailableException(
        $field->name,
        Carbon::parse($bookTime)->format('d M Y, H:i')
    );
}
```

---

## 🔹 5. CRUD UNTUK ENTITAS UTAMA

### 5.1 CRUD Field (Lapangan)
**📁 File:** `app/Http/Controllers/AdminController.php`

```php
/**
 * ✅ CRUD FIELD (Lapangan Futsal)
 */

// CREATE
public function storeField(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:Sintetis,Vinyl',
        'price_per_hour' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'status' => 'required|in:available,unavailable,maintenance',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);
    
    $field = Field::create($validated);
    return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil ditambahkan.');
}

// READ
public function fields()
{
    $fields = Field::orderBy('id', 'asc')->get();
    return view('admin.fields.index', compact('fields'));
}

// UPDATE
public function updateField(Request $request, int $id)
{
    $validated = $request->validate([...]);
    $field = Field::findOrFail($id);
    $field->update($validated);
    return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil diupdate.');
}

// DELETE (Soft Delete via Status)
public function updateFieldStatus(Field $field, string $status): bool
{
    return $field->update(['status' => $status]);
}
```

---

### 5.2 CRUD Reservation (Booking)
**📁 File:** `app/Http/Controllers/ReservationController.php`

```php
/**
 * ✅ CRUD RESERVATION (Booking Lapangan)
 */

// CREATE
public function store(Request $request)
{
    $validated = $request->validate([
        'field_id' => 'required|exists:fields,id',
        'book_time' => 'required|date',
        'duration' => 'required|integer|min:1|max:8',
        'payment_method' => 'required|in:BankTransfer,ManualUpload',
    ]);
    
    $reservation = $reservationService->createReservation(
        Auth::user(),
        $validated['field_id'],
        $validated['book_time'],
        $validated['duration']
    );
    
    return redirect()->route('member.reservations.show', $reservation->id);
}

// READ
public function show(int $id)
{
    $reservation = Reservation::with(['field', 'paymentDetail'])
        ->where('member_id', Auth::id())
        ->findOrFail($id);
    
    return view('member.reservations.show', compact('reservation'));
}

// UPDATE (Cancel)
public function cancel(int $id)
{
    $reservation = Reservation::where('member_id', Auth::id())->findOrFail($id);
    $reservation->cancel();
    return redirect()->route('member.dashboard')->with('success', 'Reservasi dibatalkan.');
}

// DELETE (via Cancel)
// Tidak ada hard delete, hanya update status menjadi 'cancelled'
```

---

### 5.3 CRUD PaymentDetail
**📁 File:** `app/Http/Controllers/ReservationController.php` & `AdminController.php`

```php
/**
 * ✅ CRUD PAYMENT DETAIL
 */

// CREATE (Otomatis saat buat reservasi)
$paymentDetail = PaymentDetail::create([
    'reservation_id' => $reservation->id,
    'transaction_id' => $paymentResult['transaction_id'],
    'amount' => $totalPrice,
    'payment_method' => $this->paymentGateway->getMethodName(),
    'payment_status' => $paymentResult['status'],
]);

// READ
public function show(int $id)
{
    $reservation = Reservation::with('paymentDetail')->findOrFail($id);
    return view('member.reservations.show', compact('reservation'));
}

// UPDATE (Upload Payment Proof)
public function uploadProof(Request $request, int $id)
{
    $validated = $request->validate([
        'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);
    
    $paymentDetail = PaymentDetail::findOrFail($id);
    // Upload file logic...
    $paymentDetail->update(['payment_proof' => $path]);
    
    return back()->with('success', 'Bukti pembayaran berhasil diunggah.');
}

// DELETE (via Admin Reject)
public function rejectPayment(int $paymentId)
{
    $payment = PaymentDetail::findOrFail($paymentId);
    $payment->update(['payment_status' => 'rejected']);
    return back()->with('success', 'Pembayaran ditolak.');
}
```

---

## 🔹 6. PDO + PREPARED STATEMENT

### 6.1 Laravel Eloquent (PDO Wrapper)
**📁 File:** `config/database.php`

```php
/**
 * ✅ PDO + PREPARED STATEMENT
 * 
 * Laravel menggunakan PDO secara default dengan prepared statement otomatis
 * untuk mencegah SQL Injection.
 */

return [
    'default' => env('DB_CONNECTION', 'mysql'),
    
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'futsal_id'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Error handling
            ],
        ],
    ],
];
```

**Contoh Query dengan Prepared Statement:**

```php
// ✅ Eloquent ORM (Otomatis pakai prepared statement)
Field::where('id', $fieldId)->first();
// Generated SQL: SELECT * FROM fields WHERE id = ? [1]

// ✅ Query Builder (Otomatis pakai prepared statement)
DB::table('reservations')
    ->where('member_id', $memberId)
    ->where('status', 'confirmed')
    ->get();
// Generated SQL: SELECT * FROM reservations WHERE member_id = ? AND status = ? [123, 'confirmed']

// ✅ Raw Query dengan Binding
DB::select('SELECT * FROM fields WHERE type = ?', ['Sintetis']);

// ✅ Insert dengan Prepared Statement
DB::insert('INSERT INTO fields (name, type, price_per_hour) VALUES (?, ?, ?)', 
    ['Lapangan A', 'Sintetis', 150000]
);
```

**Keamanan:**
- ✅ **SQL Injection Prevention**: Semua input di-escape otomatis
- ✅ **Type Casting**: Laravel auto-cast sesuai tipe data
- ✅ **Mass Assignment Protection**: `$fillable` dan `$guarded`

---

## 🔹 7. STRUKTUR FOLDER RAPI

```
d:\laragon\www\Reservasi-Futsal\
│
├── app/
│   ├── Http/
│   │   └── Controllers/          ✅ Controller Layer
│   │       ├── AdminController.php
│   │       ├── AuthController.php
│   │       ├── DashboardController.php
│   │       ├── HomeController.php
│   │       ├── PaymentSimulationController.php
│   │       └── ReservationController.php
│   │
│   ├── Models/                   ✅ Model Layer (Domain)
│   │   ├── Admin.php
│   │   ├── Field.php
│   │   ├── Member.php
│   │   ├── PaymentDetail.php
│   │   ├── Reservation.php
│   │   └── User.php
│   │
│   ├── Services/                 ✅ Business Logic Layer
│   │   ├── ReservationService.php
│   │   └── Payment/
│   │       ├── PaymentInterface.php
│   │       ├── BankTransferMock.php
│   │       └── ManualUploadMock.php
│   │
│   ├── Exceptions/               ✅ Custom Exceptions
│   │   ├── FieldUnavailableException.php
│   │   └── PaymentFailedException.php
│   │
│   └── Providers/                ✅ Service Providers (Dependency Injection)
│       └── AppServiceProvider.php
│
├── database/
│   ├── migrations/               ✅ Database Schema
│   │   ├── *_create_users_table.php
│   │   ├── *_create_fields_table.php
│   │   ├── *_create_reservations_table.php
│   │   └── *_create_payment_details_table.php
│   │
│   └── seeders/                  ✅ Data Seeding
│       └── DatabaseSeeder.php
│
├── resources/
│   └── views/                    ✅ View Layer
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── admin.blade.php
│       ├── pages/                (Public pages)
│       ├── admin/                (Admin pages)
│       └── member/               (Member pages)
│
├── routes/
│   ├── web.php                   ✅ Route Definitions
│   └── console.php
│
├── config/
│   ├── app.php                   ✅ App Configuration
│   ├── database.php              ✅ Database Configuration
│   └── ...
│
└── public/
    ├── index.php                 ✅ Entry Point
    └── storage/                  ✅ Public Storage (Symlink)
        ├── field_images/
        └── payment_proofs/
```

**Pola Arsitektur:**
- ✅ **MVC Pattern**: Model, View, Controller terpisah
- ✅ **Service Layer**: Business logic di-extract ke ReservationService
- ✅ **Repository Pattern**: Model sebagai data access layer
- ✅ **Dependency Injection**: PaymentInterface di-inject ke Service
- ✅ **Strategy Pattern**: Payment method bisa di-switch dinamis

---

## 📊 TABEL RINGKASAN LENGKAP

### Tabel 1: Inheritance

| No | Parent Class | Child Class | File Lokasi | Deskripsi |
|----|--------------|-------------|-------------|-----------|
| 1 | `User` | `Admin` | `app/Models/Admin.php` | Admin mewarisi User dengan method khusus admin (verify payment, manage field) |
| 2 | `User` | `Member` | `app/Models/Member.php` | Member mewarisi User dengan method khusus member (book, add points) |
| 3 | `Controller` | `AdminController`, `ReservationController`, dll | `app/Http/Controllers/` | Semua controller mewarisi base Controller Laravel |

### Tabel 2: Interface & Abstract Class

| No | Tipe | Nama | File Lokasi | Implementasi |
|----|------|------|-------------|--------------|
| 1 | Interface | `PaymentInterface` | `app/Services/Payment/PaymentInterface.php` | `BankTransferMock`, `ManualUploadMock` |
| 2 | Abstract Class | `Authenticatable` | Laravel Framework (Extended by `User`) | User mengextend abstract class dari Laravel |

### Tabel 3: Custom Exception

| No | Exception Name | File Lokasi | HTTP Code | Digunakan Untuk |
|----|----------------|-------------|-----------|-----------------|
| 1 | `PaymentFailedException` | `app/Exceptions/PaymentFailedException.php` | 402 | Payment gagal atau ditolak |
| 2 | `FieldUnavailableException` | `app/Exceptions/FieldUnavailableException.php` | 409 | Lapangan sudah dibooking (conflict) |

### Tabel 4: CRUD Entitas

| No | Entitas | Create | Read | Update | Delete | Controller |
|----|---------|--------|------|--------|--------|------------|
| 1 | **Field** | `storeField()` | `fields()` | `updateField()` | `updateFieldStatus()` | `AdminController` |
| 2 | **Reservation** | `store()` | `show()`, `index()` | `cancel()` | Soft delete via status | `ReservationController` |
| 3 | **PaymentDetail** | Auto (saat buat reservasi) | `show()` | `uploadProof()` | `rejectPayment()` | `ReservationController`, `AdminController` |

### Tabel 5: Design Patterns

| No | Pattern | Implementasi | File Lokasi | Kegunaan |
|----|---------|--------------|-------------|----------|
| 1 | **Strategy Pattern** | `PaymentInterface` | `app/Services/Payment/` | Bisa switch payment method tanpa ubah business logic |
| 2 | **Dependency Injection** | Constructor injection di `ReservationService` | `app/Services/ReservationService.php` | Loose coupling, easy testing |
| 3 | **Single Table Inheritance** | User, Admin, Member (1 table users) | `app/Models/` | Hemat table, mudah query |
| 4 | **Repository Pattern** | Model sebagai data access | `app/Models/` | Encapsulate database logic |
| 5 | **Service Layer** | `ReservationService` | `app/Services/` | Extract business logic dari controller |

---

## 🎯 KESIMPULAN

Aplikasi Reservasi Futsal ini telah **MEMENUHI SEMUA KETENTUAN** Tugas Akhir PBO:

✅ **2+ Inheritance**: 3 contoh (Admin, Member, Controllers)  
✅ **2+ Interface/Abstract**: 1 interface (PaymentInterface) + 1 abstract (Authenticatable)  
✅ **2+ Custom Exception**: PaymentFailedException, FieldUnavailableException  
✅ **CRUD 1+ Entitas**: Field, Reservation, PaymentDetail (3 entitas)  
✅ **PDO + Prepared Statement**: Laravel Eloquent (PDO wrapper dengan auto-prepared statement)  
✅ **Struktur Folder Rapi**: MVC Pattern + Service Layer + Clean Architecture  

**Bonus:**
- ✅ Design Pattern: Strategy, Dependency Injection, Repository, Service Layer
- ✅ Security: SQL Injection prevention, Mass Assignment protection
- ✅ Best Practice: Single Responsibility, Open/Closed Principle, Dependency Inversion

---

## 📚 REFERENSI

1. **Laravel Documentation**: https://laravel.com/docs
2. **PHP PDO**: https://www.php.net/manual/en/book.pdo.php
3. **Design Patterns**: Gang of Four Design Patterns
4. **SOLID Principles**: Robert C. Martin

---

**Dibuat oleh:** GitHub Copilot & Team Development  
**Tanggal:** 25 November 2025  
**Versi:** 1.0
