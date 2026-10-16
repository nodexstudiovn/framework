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

* **Nhà sáng lập & Kiến trúc sư trưởng (Founder & Lead Architect):** **Nguyễn Anh Kiệt (Founder của NodeX Studio)**
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
6. **Tự làm chủ công nghệ lõi (Native Realtime & Async Queue):**
   * Tự phát triển động cơ WebSockets thời gian thực (**Micro-Reverb**) và hàng đợi xử lý ngầm (**Queue Worker**) chuẩn hóa tại tầng core lõi, không dựa dẫm vào các giải pháp cloud bên ngoài hay các dịch vụ SaaS bên thứ ba đắt đỏ.

---

## 💎 4. CÁC TINH HOA CÔNG NGHỆ CỐT LÕI (CORE ARCHITECTURAL MASTERPIECES)

Dự án NodeX Studio Framework là sự kết tinh của tư duy kỹ thuật đỉnh cao, tập trung giải quyết triệt để các bài toán khó nhất trong phát triển backend hiện đại:

### 1. ⚡ Tự Làm Chủ Công Nghệ Realtime WebSocket Core Lõi (Micro-Reverb Native Engine)
* **Không phụ thuộc thư viện ngoài:** Thay vì phải trả phí đắt đỏ cho Pusher/Ably hay triển khai các cụm server cồng kềnh như Soketi, NodeX Studio tự nghiên cứu và lập trình toàn bộ máy chủ WebSocket thuần chạy trực tiếp trên tầng Socket của PHP (`socket_create`, `socket_bind`, `socket_listen`, `socket_select`).
* **Handshake chuẩn RFC 6455:** Tự code thuật toán bắt tay HTTP Upgrade, băm SHA-1 và mã hóa Base64 cho header `Sec-WebSocket-Accept`.
* **Bit-level Frame Processing:** Tự cài đặt thuật toán giải mã mặt nạ (Unmasking Payload) và đóng gói Frame dữ liệu nhị phân chuẩn xác từng bit.
* **Tích hợp Redis Pub/Sub đa luồng:** Ứng dụng chính phát tín hiệu bằng hàm ngắn `broadcast($channel, $event, $data)` đẩy vào kênh Redis; máy chủ WebSocket (`php nodex realtime:serve`) đón nhận và phân phối tức thì đến hàng chục nghìn client với độ trễ sub-millisecond (< 1ms).
* **Quản lý kênh đa dạng:** Hỗ trợ đầy đủ Kênh Công khai (Public), Kênh Riêng tư (Private) và Kênh Hiện diện (Presence).

### 2. 🛡️ Động Cơ Hàng Đợi Bất Đồng Bộ Chuẩn Doanh Nghiệp (Async Queue Engine)
* **Xử lý ngầm tốc độ cao:** Sử dụng Redis làm message broker, hỗ trợ phân chia nhiều hàng đợi ưu tiên (`default`, `emails`, `payments`).
* **Thuật toán Exponential Backoff:** Khi tác vụ gặp sự cố (mạng chập chờn, bên thứ 3 gián đoạn), Worker tự động tính toán thời gian chờ tăng theo hàm mũ (`delay = base * 2^(attempts-1)`), ngăn chặn triệt để tình trạng bão request đánh sập hệ thống.
* **Dead Letter Queue (DLQ):** Khi vượt quá ngưỡng thử lại tối đa (`maxRetries`), Job tự động được chuyển sang kho lưu trữ thất bại kèm toàn bộ stack trace chi tiết để kỹ sư đối soát.
* **Graceful Shutdown (Bắt tín hiệu POSIX):** Lắng nghe các tín hiệu hệ điều hành `SIGTERM`, `SIGINT`, `SIGHUP`. Khi hệ thống reboot hoặc deploy phiên bản mới, Worker không ngắt đột ngột mà chờ hoàn thành dứt điểm Job đang chạy dang dở rồi mới thoát an toàn.

### 3. 🚀 Compiled Dependency Injection Container (0ms Reflection Overhead)
* **Môi trường Development:** Container tự động resolve phụ thuộc dựa trên Reflection API với tính linh hoạt tuyệt đối.
* **Môi trường Production:** Với lệnh `php nodex optimize:container`, Container tự động duyệt cây phụ thuộc của toàn bộ Actions, Controllers, Models và biên dịch thành một file Closure Map tĩnh (`storage/cache/container.php`).
* **Hiệu năng cực đại:** Xóa bỏ hoàn toàn chi phí Reflection khi chạy trên môi trường thực tế, đưa tốc độ khởi tạo dependency về **0ms**, tương đương tốc độ khởi tạo thủ công bằng PHP thuần.

### 4. 🧩 Tinh Hoa Decoupled Từ Hệ Sinh Thái Laravel & Symfony
* **Standalone Eloquent ORM v13+:** Tách rời hoàn toàn khỏi Laravel core cồng kềnh nhưng vẫn sở hữu trọn vẹn: Eager Loading chống N+1 Query, Mutators, Accessors, Model Events, Query Builder và Migrations.
* **Laravel Blade Engine Độc Lập:** Tận dụng công nghệ biên dịch template nhanh nhất của Laravel mà không cần kéo theo full-stack framework.
* **Vite Asset Bundler Tích Hợp:** Hỗ trợ Hot Module Replacement (HMR) cực nhanh, tự động nhận diện môi trường qua cổng 5173 và fallback mượt mà về `manifest.json` trên Production.

### 5. 🧹 Bộ Thanh Tra Vệ Sinh View Khắt Khe (Strict View Hygiene)
* **Bắt lỗi tầng ứng dụng:** Bộ quét `ViewHygieneChecker` tự động kiểm tra cú pháp ngay khi View được gọi render.
* **Cấm triệt để mã bẩn:** Nghiêm cấm hoàn toàn mã PHP thuần (`<?php`, `@php`), CSS inline (`<style>`, `style=`), và JS inline (`<script>`, `onclick=`) trong sub-views.
* **Bảo mật & Dễ bảo trì:** Đảm bảo mã nguồn frontend đạt chuẩn Clean Code 100%, miễn nhiễm với lỗi rò rỉ logic nghiệp vụ ra giao diện hoặc lỗ hổng XSS tiềm ẩn.

### 6. 🏛️ Kiến Trúc Hybrid Action Pattern
* **Cô lập 100% Business Logic:** Nghiệp vụ chỉ được viết trong `app/Actions/`.
* **Controller Siêu Mỏng (Skinny Controllers):** Controller chỉ làm đúng 3 việc: Nhận Request, Validate và gọi Action.
* **Clean Models:** Các Model lớn được phân tách thành các Traits nhỏ (`app/Models/Traits/`) không bao giờ vượt quá 300 dòng/file.

### 7. ✨ Cú Pháp PHP 8.4+ Thanh Thoát & Global Fluent Helpers
* **Tiên phong áp dụng PHP 8.4+:** Property Hooks thay thế getter/setter rườm rà, Asymmetric Visibility (`public private(set)`), Constructor Promotion, First-Class Callables.
* **Hệ thống Fluent Helpers:**
  * `response()->json(...)`, `response()->redirect(...)`, `view(...)`
  * `action(SomeAction::class, ...$args)`
  * `rescue(fn() => ..., fallback: ...)` — triệt tiêu khối try-catch dài dòng
  * `dispatch($job)` — đẩy việc vào hàng đợi ngầm
  * `broadcast($channel, $event, $data)` — phát sóng realtime tức thời
  * `validator($data, $rules)` & `validate($data, $rules)`

### 8. 🛠️ Bộ Công Cụ Dòng Lệnh NodeX CLI (`php nodex`)
* Tự động sinh mã nguồn chuẩn kiến trúc (Action, Model, Controller, Job, Test, Rule, Migration).
* Tự động quét và phát sinh tài liệu API theo chuẩn OpenAPI 3.0 & Swagger UI (`php nodex docs:api`).
* Bộ kiểm thử tự động toàn diện tích hợp sẵn (`php nodex test`).

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
* **Trưởng nhóm kiến trúc & Sáng lập:** **Nguyễn Anh Kiệt (Founder của NodeX Studio)** (Telegram: [@nodexstudio](https://t.me/nodexstudio))
* **Giấy phép bản quyền:** Dự án được phát hành theo giấy phép mã nguồn mở **[MIT License](LICENSE)**.
