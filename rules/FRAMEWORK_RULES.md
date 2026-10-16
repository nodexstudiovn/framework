# 🚀 NODEX STUDIO FRAMEWORK v1.1.0 — CODING & ARCHITECTURE RULES

## 📌 OVERVIEW
Bộ quy chuẩn lập trình và kiến trúc phát triển dành riêng cho **NodeX Studio Framework v1.1.0** (nền tảng PHP 8.4+). Tất cả các module, controller, action, model và view được phát triển trong dự án đều BẮT BUỘC tuân thủ các quy tắc này.

---

## 1. ⚙️ QUY TẮC CHUNG (GENERAL & ENVIRONMENT)
- **PHP Version:** PHP 8.4+ (Sử dụng tối đa các tính năng mới: *Asymmetric Visibility `public private(set)`*, *Property Hooks*, *Match Expressions*, *Named Arguments*, *Typed Properties*).
- **Strict Types:** Mọi file PHP BẮT BUỘC khai báo `declare(strict_types=1);` ở dòng đầu tiên ngay sau thẻ `<?php`.
- **Múi giờ hệ thống (Timezone):** `Asia/Ho_Chi_Minh`.
- **Bản quyền & Chữ ký (Copyright Header):** Mọi file PHP do AI hoặc nhân sự tạo ra/chỉnh sửa BẮT BUỘC phải đính kèm chữ ký bản quyền NodeX Studio:
  ```php
  /**
   * Design by: NodeX Studio
   * Telegram: @nodexstudio
   * Module: [Tên Module / Action / Class]
   */
  ```
- **Ngôn ngữ Comment:** Sử dụng **100% tiếng Việt** cho toàn bộ comment giải thích logic mã nguồn.

---

## 2. 🏛️ KẾT CẤU KIẾN TRÚC BACKEND (HYBRID ACTION PATTERN)

### 2.1. Logic Isolation (Tách biệt Logic với Actions)
- **100% Business Logic** BẮT BUỘC phải nằm trong các lớp **Action** tại thư mục `app/Actions/`.
- **Controllers (`app/Controllers/`)** chỉ đóng vai trò:
  1. Nhận `Request`
  2. Xử lý Validate
  3. Gọi `Action` thực thi
  4. Trả về `Response` (JSON hoặc View)
- **Dependency Injection (DI):** Luôn tiêm (inject) Action vào Controller thông qua `__construct` hoặc Method Injection.

### 2.2. Model Refactoring (Clean Eloquent Models)
- Các Model Eloquent lớn chứa nhiều logic/quan hệ (như `User`, `Order`) BẮT BUỘC phân rã thành các **Traits** nhỏ đặt tại `app/Models/Traits/` (VD: `UserRelations`, `UserAttributes`, `UserScopes`).
- Tuyệt đối KHÔNG viết một file Model vượt quá **300 dòng**.

### 2.3. Standard Exception & Error Response
Trả về lỗi theo chuẩn JSON Doanh nghiệp của NodeX Studio:
```json
{
  "error": {
    "code": "ERROR_CODE",
    "message": "Thông báo lỗi bằng tiếng Việt rõ ràng",
    "details": []
  }
}
```

---

## 3. 💾 DATABASE & SECURITY (ELOQUENT ORM & CONCURRENCY)
- **Eloquent ORM:** Sử dụng `Illuminate\Database\Capsule\Manager` được khởi tạo sẵn trong NodeX Studio Application Container.
- **CSRF Token Protection:** Tự động bảo mật request POST/PUT/DELETE bằng `CsrfMiddleware` và chỉ thị Blade `@csrf`.
- **Tối ưu Query:**
  - Luôn dùng **Eager Loading** (`with()`) khi truy vấn để triệt tiêu lỗi N+1 Query.
  - Đánh **Index** cho các trường đối soát và tìm kiếm thường xuyên (`email`, `username`, `order_id`, `trans_id`).
- **Xử lý Đóng băng / Giao dịch Số dư (Concurrency):**
  - Mọi thao tác biến động số dư, ví nội bộ, nạp/trừ tiền, thanh toán đơn hàng BẮT BUỘC bọc trong `DB::transaction()`.
  - Sử dụng `lockForUpdate()` (**Pessimistic Locking**) khi đọc/ghi bản ghi tài chính hoặc tranh chấp nhận đơn hàng để chống Race Condition.

---

## 4. 🎨 FRONTEND & UI/UX STANDARDS (STRICT VIEW HYGIENE)
- **Blade Template:** Sử dụng Laravel Blade Engine v13.x làm template mặc định (`resources/views/`).
- **Vite Asset Bundler:** Sử dụng chỉ thị `@vite(['resources/css/app.css', 'resources/js/app.js'])` trong layout Blade để tích hợp HMR & Asset Bundle.
- **Strict View Hygiene Enforcement (Bắt Lỗi Tầng Ứng Dụng):**
  - **CẤM NHẬP PHP THUẦN:** Cấm hoàn toàn `<?php`, `<?=`, `<%`, `@php` trong View. 100% Business Logic phải xử lý ở Action/Controller.
  - **CẤM CSS/JS INLINE:** Cấm thẻ `<style>`, `<script>` trong sub-views, cấm các thuộc tính inline `onclick=`, `style=`.
  - **BẮT LỖI TỰ ĐỘNG:** Bộ quét `NodeX\View\ViewHygieneChecker` sẽ tự động ném ra ngoại lệ `ViewHygieneViolationException` dừng chương trình ngay lập tức nếu phát hiện vi phạm.

---

---

## 5. ✨ CÚ PHÁP HIỆN ĐẠI & GLOBAL FLUENT HELPERS (PHP 8.4+ STANDARDS)

Nhằm giúp mã nguồn trong NodeX Framework đạt độ tinh gọn, thanh thoát (tương tự vẻ đẹp của Python, TypeScript hay Kotlin), toàn bộ dự án áp dụng 4 nguyên tắc kiến trúc cú pháp sau:

### 5.1. Bộ Global Fluent Helpers
Không bắt buộc phải gõ namespace dài hoặc khởi tạo instance thủ công. Luôn ưu tiên dùng các helper toàn cục:
* **Response & View:**
  ```php
  return response()->json(['status' => 'success']);
  return response()->redirect('/dashboard');
  return view('user.profile', compact('user'));
  ```
* **Dependency & Config:**
  ```php
  $service = app(OrderService::class);
  $dbHost = config('DB_HOST', '127.0.0.1');
  ```
* **Action Runner & An toàn ngoại lệ:**
  ```php
  // Chạy Action trong 1 dòng
  $user = action(RegisterUserAction::class, $data);
  // Triệt tiêu khối try-catch rườm rà
  $rate = rescue(fn() => fetchExchangeRate(), fallback: 25000);
  ```
* **Hàng đợi & Realtime:**
  ```php
  dispatch(new SendWelcomeEmailJob('user@example.com', 'Kiet'));
  broadcast('orders-channel', 'new-order', $payload);
  ```

### 5.2. Tận Dụng Triệt Để Cải Tiến PHP 8.4+
* **Property Hooks:** Thay thế hoàn toàn getter/setter boilerplate trong Model:
  ```php
  public string $title {
      get => ucfirst($this->attributes['title'] ?? '');
      set => strtolower($value);
  }
  ```
* **Asymmetric Visibility & Constructor Promotion:** Khai báo và phân quyền truy cập chỉ trong 1 dòng:
  ```php
  public function __construct(
      public private(set) UserRepository $users,
      public private(set) MailLogger $logger,
  ) {}
  ```
* **Method Chaining trực tiếp sau `new`:**
  ```php
  $status = (new UserStatusChecker)->check($user);
  ```

### 5.3. First-Class Callable Syntax & Arrow Functions
Khi xử lý mảng và collection, triệt tiêu closure dài dòng:
```php
// Ngắn gọn và thanh thoát
$activeUsers = array_filter($users, fn($u) => $u->isActive());

// First-Class Callable Syntax
$names = array_map($userTransformer->transform(...), $users);
```

### 5.4. Fluent Interface & Method Chaining Cho Actions
Mọi Action kế thừa từ `App\Actions\Action` đều hỗ trợ chạy nhanh qua static `run()` hoặc chain phương thức:
```php
// Cách 1: Chạy trực tiếp qua static run()
$order = CreateOrderAction::run($orderData);

// Cách 2: Fluent Builder Style
Pay::user($userId)
   ->amount(500_000)
   ->via('VNPAY')
   ->execute();
```

---

## 6. 💻 NODEX CLI COMMANDS (`php nodex`)
Sử dụng các lệnh CLI chuẩn hóa của NodeX Studio để quản lý và sinh code:
```bash
php nodex serve                  # Khởi chạy Local Development Server
php nodex migrate                # Thao tác database migrations
php nodex migrate:rollback       # Hoàn tác migration gần nhất
php nodex migrate:status         # Xem trạng thái migrations
php nodex make:action <Name>     # Tạo Action class mới trong app/Actions
php nodex make:migration <Name>  # Tạo file Migration mới trong database/migrations
php nodex make:model <Name>      # Tạo Eloquent Model trong app/Models
php nodex make:controller <Name> # Tạo Controller trong app/Controllers
php nodex make:job <Name>        # Tạo Queue Job ngầm trong app/Jobs
php nodex make:test <Name>       # Tạo Feature Test trong tests/Feature
php nodex make:rule <Name>       # Tạo Rule kiểm tra trong app/Rules
php nodex optimize:container     # Biên dịch Container tĩnh (0ms Reflection overhead)
php nodex docs:api               # Tự động sinh tài liệu OpenAPI 3.0 & Swagger UI
php nodex queue:work             # Khởi chạy Queue Worker (Exponential Backoff & Graceful Shutdown)
php nodex realtime:serve         # Khởi chạy máy chủ WebSocket Realtime (Micro-Reverb)
php nodex test                   # Chạy kiểm thử tự động toàn diện
php nodex cache:clear            # Xóa cache views & compiled container
php nodex route:list             # Xem danh sách routes
```

---

## 7. 🚨 CRITICAL EXECUTION RULE
- **Tập trung & Trực diện:** Khi triển khai mã nguồn, đi thẳng vào trọng tâm chức năng được yêu cầu. Không nhai lại lý thuyết dài dòng, trả về ngay lập tức các khối code chuẩn chỉnh, sạch sẽ và chạy được ngay.
