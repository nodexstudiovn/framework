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

## 5. 💻 NODEX CLI COMMANDS (`php nodex`)
Sử dụng các lệnh CLI chuẩn hóa của NodeX Studio để quản lý và sinh code:
```bash
php nodex serve                  # Khởi chạy Local Server
php nodex migrate                # Thao tác database migrations
php nodex migrate:rollback       # Hoàn tác migration gần nhất
php nodex migrate:status         # Xem trạng thái migrations
php nodex make:action <Name>     # Tạo Action class mới trong app/Actions
php nodex make:migration <Name>  # Tạo file Migration mới trong database/migrations
php nodex make:model <Name>      # Tạo Eloquent Model trong app/Models
php nodex make:controller <Name> # Tạo Controller trong app/Controllers
php nodex make:rule <Name>       # Tạo Rule trong app/Rules
php nodex queue:work             # Khởi chạy Queue Worker xử lý hàng đợi ngầm
php nodex realtime:serve         # Khởi chạy máy chủ WebSocket Realtime (Micro-Reverb)
php nodex cache:clear            # Xóa cache views & app
php nodex route:list             # Xem danh sách routes
```

---

## 6. 🚨 CRITICAL EXECUTION RULE
- **Tập trung & Trực diện:** Khi triển khai mã nguồn, đi thẳng vào trọng tâm chức năng được yêu cầu. Không nhai lại lý thuyết dài dòng, trả về ngay lập tức các khối code chuẩn chỉnh, sạch sẽ và chạy được ngay.
