# 📋 BÁO CÁO PHÂN TÍCH KIẾN TRÚC & CODEBASE DỰ ÁN NODEX STUDIO

---

## 📌 1. BẢN CHẤT DỰ ÁN

Dự án này là **NodeX Studio Framework v1.1.0** — một nền tảng PHP Micro-Framework hiện đại được thiết kế và phát triển bởi **Nguyễn Anh Kiệt** (NodeX Studio).

Mục tiêu cốt lõi của dự án là giải quyết bài toán hiệu năng cao và tính chuẩn mực trong lập trình:
1. **Kiến trúc Tinh gọn (Micro-Kernel):** Loại bỏ toàn bộ gánh nặng cồng kềnh của các framework full-stack truyền thống, chỉ tích hợp có chọn lọc các thư viện hạt nhân xuất sắc nhất từ hệ sinh thái Laravel (`Illuminate/Database v13 Eloquent ORM`, `Illuminate/View v13 Blade Engine`, `Illuminate/Filesystem`, `Illuminate/Events`).
2. **Hệ sinh thái Đồng bộ:** Tích hợp sẵn bộ đóng gói Frontend tốc độ cao **Vite 6.x**, công cụ dòng lệnh riêng **NodeX CLI (`php nodex`)**, bộ cài đặt toàn cầu **NodeX Installer (`nodex/installer`)**, hệ thống quản lý hàng đợi ngầm **Queue Engine**, hạt nhân truyền phát thời gian thực **Broadcaster WebSockets (Micro-Reverb)** và cơ chế phân giải phụ thuộc tự động **DIC Container**.
3. **Triết lý Thiết kế Clean Code:** Áp dụng nghiêm ngặt mô hình **Hybrid Action Pattern** và tiêu chuẩn **Strict View Hygiene** ở tầng ứng dụng, giúp mã nguồn luôn sạch sẽ, bảo mật và dễ mở rộng khi dự án phát triển lên quy mô lớn.

---

## 📐 2. BỘ QUY CHUẨN KIẾN TRÚC & MÃ NGUỒN

Hệ thống quy chuẩn của framework được thiết lập đồng bộ tại file `.cursorrules` và thư mục `rules/` với các trụ cột kỹ thuật:

### 2.1. Môi trường & Quy ước Lập trình
* **PHP 8.4+ Bắt buộc:** Tận dụng tối đa các cải tiến cú pháp mới nhất: *Asymmetric Visibility `public private(set)`*, *Property Hooks*, *Match Expressions*, *Typed Properties* và *Constructor Property Promotion*.
* **Strict Types:** Khai báo `declare(strict_types=1);` ở dòng đầu tiên của tất cả các file PHP nhằm triệt tiêu lỗi ép kiểu ngầm.
* **Múi giờ Chuẩn:** Thiết lập cố định `Asia/Ho_Chi_Minh`.
* **Chữ ký Bản quyền & Comment:** Mỗi file đều gắn DocBlock chữ ký của NodeX Studio (`@nodexstudio`). Toàn bộ comment giải thích nghiệp vụ sử dụng 100% tiếng Việt rõ ràng, mạch lạc.

### 2.2. Kiến trúc Backend: Hybrid Action Pattern
* **Phân lập Nghiệp vụ (Logic Isolation):** 100% nghiệp vụ xử lý dữ liệu và tính toán phải nằm trong các lớp Action tại `app/Actions/`.
* **Controller Siêu Mỏng (Thin Controller):** Controller tuyệt đối không chứa logic phức tạp. Vai trò duy nhất của Controller là nhận Request, kiểm tra tính hợp lệ dữ liệu, tiêm và gọi Action thực thi, sau đó trả về Response (JSON hoặc View).
* **Tự Động Tiêm Phụ Thuộc (Dependency Injection):** Hệ thống DIC Container tự phân giải và inject các Action, Model, Service vào hàm thông qua PHP Reflection API.
* **Tối Ưu Eloquent Models:** Các model lớn chứa nhiều quan hệ phải phân rã thành các Traits nhỏ trong `app/Models/Traits/`. Khống chế dung lượng file Model không vượt quá 300 dòng.

### 2.3. An Toàn Dữ Liệu & Bảo Mật
* **Bảo Vệ CSRF Mặc Định:** Bộ lọc `CsrfMiddleware` tự động giám sát toàn bộ các request thay đổi trạng thái dữ liệu (`POST`, `PUT`, `DELETE`, `PATCH`). Chỉ thị `@csrf` và `@csrf_meta` được tích hợp sẵn trong Blade Engine.
* **Kiểm Soát Tranh Chấp Số Dư (Concurrency):** Mọi thao tác biến động tài chính, số dư ví, điểm thưởng hoặc tranh chấp đơn hàng bắt buộc phải được bọc trong `DB::transaction()` và kích hoạt khóa bi quan `lockForUpdate()` (Pessimistic Locking) để loại trừ triệt để lỗi Race Condition.
* **Triệt Tiêu N+1 Query:** Luôn áp dụng Eager Loading (`with()`) khi truy vấn quan hệ và đánh chỉ mục (Index) các trường tra cứu thường xuyên.

### 2.4. Tiêu Chuẩn Strict View Hygiene (Vệ Sinh View Khắt Khe)
* **100% HTML Thuần:** File View Blade chỉ đóng vai trò hiển thị và chứa các cấu trúc điều khiển cơ bản (`@if`, `@foreach`, `@extends`, `@section`).
* **Cấm Mã PHP Trong View:** Nghiêm cấm hoàn toàn thẻ `<?php`, `<?=`, `<%` hoặc directive `@php` bên trong View. Mọi dữ liệu phải được chuẩn bị sẵn từ Controller hoặc Action.
* **Cấm CSS / JS Inline:** Không viết thẻ `<style>`, `<script>` trong sub-views; không sử dụng các thuộc tính inline `style=`, `onclick=`. Tất cả CSS và JS phải được module hóa độc lập và nạp thông qua Vite.
* **Cơ Chế Bắt Lỗi Tầng Ứng Dụng:** Bộ kiểm tra `NodeX\View\ViewHygieneChecker` sẽ quét nội dung trước khi render. Nếu phát hiện vi phạm, hệ thống sẽ lập tức dừng thực thi và ném ngoại lệ `ViewHygieneViolationException`.

---

## 🏗️ 3. PHÂN TÍCH CẤU TRÚC CODEBASE THỰC TẾ

```text
nodex-framework/
├── .cursorrules               # Bộ quy chuẩn mã nguồn cho toàn dự án
├── .env / .env.example        # Cấu hình môi trường (MySQL, Redis, App Key)
├── composer.json              # Khai báo dependency Illuminate v13 & PSR-4 Autoload
├── package.json               # Cấu hình đóng gói Vite, Tailwind CSS v3, PostCSS
├── vite.config.js             # Cấu hình đóng gói tài nguyên Frontend
├── nodex                      # CLI Executable Script (tương đương Artisan)
├── public/                    # Thư mục Web Root công khai
│   ├── index.php              # Front Controller entrypoint của ứng dụng
│   ├── assets/                # Chứa logo SVG và tài nguyên tĩnh
│   └── build/                 # Chứa asset CSS/JS đã được Vite biên dịch
├── src/                       # NHÂN LÕI FRAMEWORK (NodeX Core Engine)
│   ├── Application.php        # Khởi tạo App Container, nạp môi trường & xử lý lỗi
│   ├── Broadcasting/          # Core Broadcaster phát sự kiện Realtime qua Redis/WS
│   ├── Console/               # Kernel CLI, Generators sinh code và Migrator
│   ├── Container/             # Dependency Injection Container & Reflection Resolver
│   ├── Database/              # Khởi tạo Eloquent Capsule Manager (MySQL/SQLite)
│   ├── Http/                  # Router điều hướng, Request parser, Response builder
│   │   └── Middleware/        # Middleware Pipeline & bộ lọc bảo mật CsrfMiddleware
│   ├── Queue/                 # QueueManager, Worker chạy ngầm và ShouldQueue Contract
│   ├── Redis/                 # RedisClient kết nối socket TCP siêu nhẹ (RESP protocol)
│   ├── Security/              # Quản lý CSRF Token và phiên làm việc Session
│   └── View/                  # Engine Blade, Vite helper & ViewHygieneChecker
├── app/                       # MÃ NGUỒN ỨNG DỤNG CỦA LẬP TRÌNH VIÊN
│   ├── Actions/               # Chứa các lớp nghiệp vụ (Action Pattern)
│   ├── Controllers/           # Chứa các Controller điều phối request
│   ├── Models/                # Chứa các Eloquent Model
│   ├── Rules/                 # Chứa các Rule kiểm tra tính hợp lệ dữ liệu
│   └── Jobs/                  # Chứa các công việc xử lý ngầm (Queue Jobs)
├── routes/                    # ĐỊNH NGHĨA ROUTE
│   ├── web.php                # Tuyến đường giao diện Web (render Blade)
│   └── api.php                # Tuyến đường API RESTful (trả về JSON)
├── resources/                 # TÀI NGUYÊN GIAO DIỆN
│   ├── css/ & js/             # Mã nguồn CSS Tailwind và JavaScript module
│   └── views/                 # Blade Templates chuẩn HTML thuần
└── storage/                   # THƯ MỤC LƯU TRỮ NỘI BỘ
    ├── cache/views/           # Cache các view Blade đã biên dịch
    ├── logs/                  # Nhật ký lỗi và failed_jobs.log
    └── queue/                 # Hàng đợi dự phòng khi Redis offline
```

---

## 🎯 4. ĐÁNH GIÁ & ĐỊNH HƯỚNG PHÁT TRIỂN

1. **Khả Năng Vận Hành:** NodeX Studio Framework v1.1.0 đã hoàn thiện đầy đủ các tính năng của một framework hiện đại: từ quản lý Database Eloquent, bảo mật CSRF, hàng đợi Queue, truyền phát Realtime WebSockets đến công cụ CLI sinh code tự động.
2. **Độ Tin Cậy & Tốc Độ:** Nhờ áp dụng kiến trúc Micro-Kernel và loại bỏ các tầng trừu tượng không cần thiết, tốc độ phản hồi của hệ thống đạt mức tối ưu, sẵn sàng phục vụ cho các ứng dụng thực tế đòi hỏi hiệu năng cao.
3. **Tính Đóng Gói Cao:** Với gói cài đặt toàn cầu `nodexstudiovn/installer` trên Packagist, lập trình viên có thể tạo mới một dự án sạch chỉ với 1 câu lệnh duy nhất từ bất kỳ đâu trên hệ thống.

---
*Tài liệu phân tích kiến trúc — NodeX Studio Engineering Team.*
