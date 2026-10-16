<div align="center">

# ⚡ NodeX Studio Framework

### Framework PHP 8.4+ Thế Hệ Mới — Siêu Nhẹ, Tối Đa Hiệu Năng & Chuẩn Kiến Trúc Sạch

[![PHP Version](https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Release Version](https://img.shields.io/badge/version-1.2.0-6366f1?style=for-the-badge)](https://github.com/nodexstudiovn/framework)
[![License: MIT](https://img.shields.io/badge/License-MIT-emerald?style=for-the-badge)](LICENSE)
[![Telegram](https://img.shields.io/badge/Telegram-@nodexstudio-229ED9?style=for-the-badge&logo=telegram&logoColor=white)](https://t.me/nodexstudio)

</div>

---

## 📖 1. GIỚI THIỆU TỔNG QUAN

**NodeX Studio Framework** là một Micro-Framework PHP thế hệ mới được xây dựng trên nền tảng **PHP 8.4+**, kết hợp giữa tốc độ xử lý thô vượt trội của PHP thuần và trải nghiệm lập trình hiện đại (Developer Experience - DX) thanh thoát, tinh tế tương tự như TypeScript, Kotlin hay Python.

Framework được thiết kế với triết lý **Decoupled & Ultra-Fast**: tích hợp chọn lọc những tinh hoa độc lập tốt nhất từ hệ sinh thái Laravel & Symfony (Eloquent ORM v13+, Blade Engine, Symfony Components) nhưng loại bỏ hoàn toàn sự cồng kềnh, nặng nề của các monolithic full-stack framework truyền thống.

---

## 👨‍💻 2. TÁC GIẢ & THÔNG TIN PHÁT TRIỂN

* **Nhà phát triển chính (Lead Developer & Architect):** **Nguyễn Anh Kiệt** (Kiet Nguyen)
* **Năm sinh:** **2006** (Kỹ sư phần mềm Gen Z)
* **Đơn vị phát triển:** **NodeX Studio** ([@nodexstudiovn](https://github.com/nodexstudiovn))
* **Kênh liên hệ chính thức:**
  * **Email:** [nanhkiet2006.dev@gmail.com](mailto:nanhkiet2006.dev@gmail.com) / [kiet.nguyen@nodex.studio](mailto:kiet.nguyen@nodex.studio)
  * **Telegram:** [@nodexstudio](https://t.me/nodexstudio)
  * **GitHub:** [https://github.com/nodexstudiovn](https://github.com/nodexstudiovn)

---

## 🎯 3. MỤC ĐÍCH PHÁT TRIỂN (VISION & MISSION)

Dự án **NodeX Studio Framework** được Nguyễn Anh Kiệt khởi xướng và phát triển với các mục tiêu chiến lược rõ ràng:

1. **Giải phóng sự cồng kềnh của Monolithic Framework:**
   * Trong nhiều dự án thực tế, các API Microservice, Web Service hoặc hệ thống xử lý tác vụ ngầm không cần tới hàng trăm MB dependency của toàn bộ Laravel hay Symfony full-stack. NodeX Studio giữ lại những gì tinh túy nhất (ORM, Blade, Validation, Event) nhưng tối ưu dung lượng siêu nhẹ và khởi động trong tích tắc.
2. **Tối ưu hóa hiệu năng tối đa (Zero Reflection Overhead):**
   * Trang bị cơ chế **Compiled Container** (`php nodex optimize:container`), phân giải cây phụ thuộc tĩnh giúp triệt tiêu hoàn toàn chi phí Reflection khi chạy trên môi trường Production (0ms Reflection overhead).
3. **Thiết lập chuẩn kiến trúc sạch (Clean Architecture & Action Pattern):**
   * Định hình mô hình **Hybrid Action Pattern**: 100% nghiệp vụ (Business Logic) được cô lập trong từng lớp Action chuyên biệt (`app/Actions/`). Controllers chỉ làm nhiệm vụ tiếp nhận Request, Validate và trả về Response, chấm dứt hoàn toàn tình trạng Fat Controller / Fat Model.
4. **Chuẩn hóa vệ sinh giao diện tuyệt đối (Strict View Hygiene):**
   * Tích hợp bộ quét tự động `ViewHygieneChecker` ở tầng ứng dụng, ngăn chặn triệt để việc viết mã PHP thuần (`<?php`, `@php`), CSS inline (`<style>`, `style=`) và JS inline trong file view, đảm bảo khả năng tái cấu trúc và bảo mật dài hạn.
5. **Trải nghiệm lập trình PHP 8.4+ đẳng cấp (Modern DX):**
   * Khai thác triệt để các tính năng tối tân nhất của PHP 8.4+: *Property Hooks*, *Asymmetric Visibility (`public private(set)`)*, *First-Class Callables*, kết hợp cùng bộ **Global Fluent Helpers** (`response()`, `action()`, `rescue()`, `validator()`, `dispatch()`, `broadcast()`) giúp mã nguồn thanh thoát, tinh gọn và dễ bảo trì.
6. **Tích hợp sẵn hệ thống Async & Realtime doanh nghiệp:**
   * Tích hợp sẵn động cơ Hàng đợi **Queue Worker** (Redis, Exponential Backoff, Graceful Shutdown qua tín hiệu POSIX `SIGTERM`) và máy chủ Realtime WebSocket (**Micro-Reverb Broadcaster**) phục vụ ngay lập tức các ứng dụng thời gian thực.

---

## 🚀 4. CÁC TÍNH NĂNG CỐT LÕI (KEY FEATURES)

| Tính Năng | Mô Tả Chi Tiết |
| :--- | :--- |
| **PHP 8.4+ Native** | Tận dụng triệt để Property Hooks, Asymmetric Visibility, Typed Properties, Constructor Promotion. |
| **Standalone Eloquent v13+** | Toàn bộ sức mạnh của Eloquent ORM: Eager Loading, Query Builder, Model Events, Mutators/Accessors. |
| **Laravel Blade & Vite** | Blade Engine hiện đại kết hợp Asset Bundler Vite (Hot Module Replacement - HMR). |
| **Strict View Hygiene** | Bộ kiểm tra ứng dụng tự động chặn PHP/CSS/JS inline vi phạm chuẩn mực trong Blade Views. |
| **Compiled DIC Container** | Container tự động phân giải Dependency Injection, hỗ trợ biên dịch sơ đồ phụ thuộc thành mảng Closure tĩnh (0ms). |
| **Async Queue Engine** | Worker xử lý ngầm qua Redis với cơ chế Exponential Backoff, Dead Letter Queue và Graceful Shutdown. |
| **Realtime WebSockets** | Động cơ phát tán sự kiện (Broadcaster) tích hợp Redis Pub/Sub và WebSocket Micro-Reverb. |
| **CLI Toolkit Mạnh Mẽ** | Bộ công cụ dòng lệnh `php nodex` quản lý migration, sinh mã tự động (action, model, rule, controller, job). |
| **OpenAPI & Swagger** | Tự động quét và phát sinh tài liệu API chuẩn OpenAPI 3.0 (`php nodex docs:api`). |

---

## 💻 5. YÊU CẦU HỆ THỐNG & CÀI ĐẶT

### Yêu cầu môi trường:
* **PHP:** phiên bản `>= 8.4` (bật extension `pdo`, `mbstring`, `openssl`, `sockets`)
* **Composer:** phiên bản `>= 2.2`
* **Node.js & npm:** phục vụ biên dịch tài nguyên frontend qua Vite
* **Redis Server:** (khuyến nghị cho hàng đợi Queue & Realtime)

### Cài đặt nhanh:

```bash
# 1. Clone mã nguồn dự án
git clone https://github.com/nodexstudiovn/framework.git my-app
cd my-app

# 2. Cài đặt các thư viện PHP qua Composer
composer install

# 3. Tạo file cấu hình môi trường
cp .env.example .env

# 4. Cài đặt và build tài nguyên Vite
npm install
npm run build

# 5. Khởi chạy máy chủ phát triển NodeX
php nodex serve
```

Truy cập ứng dụng tại địa chỉ: `http://localhost:8000`

---

## 🛠️ 6. DANH MỤC LỆNH CLI (`php nodex`)

NodeX Studio cung cấp bộ lệnh CLI chuẩn mực phục vụ toàn bộ vòng đời phát triển dự án:

```bash
php nodex serve                  # Khởi chạy Local Development Server
php nodex migrate                # Chạy Database Migrations
php nodex migrate:rollback       # Rollback đợt migration gần nhất
php nodex migrate:status         # Kiểm tra trạng thái migrations
php nodex make:action <Name>     # Tạo Action Class mới (app/Actions/)
php nodex make:controller <Name> # Tạo Controller mới (app/Controllers/)
php nodex make:model <Name>      # Tạo Eloquent Model (app/Models/)
php nodex make:migration <Name>  # Tạo file Migration mới (database/migrations/)
php nodex make:job <Name>        # Tạo Queue Job ngầm (app/Jobs/)
php nodex make:rule <Name>       # Tạo Validation Rule (app/Rules/)
php nodex make:test <Name>       # Tạo Test tự động (tests/Feature/)
php nodex optimize:container     # Biên dịch Container phụ thuộc tĩnh (0ms Reflection)
php nodex docs:api               # Tự động sinh tài liệu OpenAPI 3.0 & Swagger UI
php nodex queue:work             # Khởi chạy Queue Worker (Redis Worker)
php nodex realtime:serve         # Khởi chạy WebSocket Server (Micro-Reverb)
php nodex test                   # Chạy bộ kiểm thử tự động toàn diện
php nodex cache:clear            # Dọn dẹp cache Views và Compiled Container
php nodex route:list             # Liệt kê danh sách Routes
```

---

## 🌟 7. VÍ DỤ CÚ PHÁP HIỆN ĐẠI (CODE EXAMPLES)

### 7.1. Global Fluent Helpers:
```php
// Trả về JSON Response hoặc View cực gọn
return response()->json(['status' => 'success', 'data' => $user]);
return view('index', compact('stats'));

// Thực thi Action một dòng duy nhất
$user = action(RegisterUserAction::class, $request->all());

// An toàn ngoại lệ với rescue() triệt tiêu try-catch rườm rà
$exchangeRate = rescue(fn() => fetchForeignExchangeRate(), fallback: 25400);

// Đẩy Job vào hàng đợi ngầm
dispatch(new SendWelcomeEmailJob('user@example.com', 'Kiet'));

// Phát tín hiệu Realtime qua WebSocket
broadcast('orders-channel', 'order-placed', ['order_id' => 1024]);
```

### 7.2. Hybrid Action Pattern:
```php
namespace App\Actions;

use App\Models\User;

class CreateUserAction
{
    public static function run(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_ARGON2ID),
        ]);
    }
}
```

---

## 📜 8. BẢN QUYỀN & GIẤY PHÉP

* **Đơn vị thiết kế & phát triển:** **NodeX Studio**
* **Trưởng nhóm kiến trúc:** **Nguyễn Anh Kiệt** (Telegram: [@nodexstudio](https://t.me/nodexstudio))
* **Giấy phép bản quyền:** Dự án được phát hành theo giấy phép mã nguồn mở **[MIT License](LICENSE)**.
