# 📖 TÀI LIỆU KỸ THUẬT & HƯỚNG DẪN SỬ DỤNG NODEX STUDIO FRAMEWORK (v1.2.0)

> **NodeX Studio Framework** là nền tảng PHP Micro-Framework hiện đại được thiết kế theo triết lý **Micro-Kernel Tinh Gọn**, tối ưu hiệu năng tối đa cho môi trường **PHP 8.4+**, tích hợp sẵn hệ sinh thái Frontend Vite, ORM Eloquent v13, View Engine Blade v13, Queue Engine đa driver với Graceful Shutdown, WebSocket Micro-Reverb thời gian thực, Compiled DIC Container và công cụ tự động sinh tài liệu OpenAPI 3.0 / Swagger UI.

---

## 📌 MỤC LỤC TỔNG QUAN

1. [Chương 1: Getting Started (Bắt Đầu)](#chương-1-getting-started-bắt-đầu)
   * 1.1. Giới thiệu triết lý Micro-Kernel
   * 1.2. Yêu cầu hệ thống
   * 1.3. Cài đặt toàn cục qua `nodexstudiovn/installer`
   * 1.4. Cấu hình biến môi trường `.env`
2. [Chương 2: Architecture Concepts (Khái Niệm Kiến Trúc Cốt Lõi)](#chương-2-architecture-concepts-khái-niệm-kiến-trúc-cốt-lõi)
   * 2.1. Vòng đời Request (Request Lifecycle)
   * 2.2. Mô hình Hybrid Action Pattern
   * 2.3. Quy chuẩn Strict View Hygiene & Cơ chế Bắt lỗi Tầng Ứng dụng
3. [Chương 3: Core Components (Các Thành Phần Cốt Lõi)](#chương-3-core-components-các-thành-phần-cốt-lõi)
   * 3.1. Điều hướng Routing & Middleware Pipeline
   * 3.2. Cơ sở dữ liệu Eloquent ORM & Chống N+1 Query
   * 3.3. An toàn bảo mật CSRF & Chống tranh chấp số dư (Concurrency)
   * 3.4. Dependency Injection Container (DIC) & Compiled Container
4. [Chương 4: Advanced Features (Tính Năng Nâng Cao)](#chương-4-advanced-features-tính-năng-nâng-cao)
   * 4.1. Hệ thống Hàng đợi Ngầm (Queue Engine, Backoff & Graceful Shutdown)
   * 4.2. Truyền phát thời gian thực WebSockets (Micro-Reverb Engine)
   * 4.3. Bộ Kiểm Thử Tự Động (Testing Suite)
   * 4.4. Tự động sinh tài liệu OpenAPI 3.0 & Swagger UI
   * 4.5. Bảng tra cứu toàn bộ lệnh dòng lệnh NodeX CLI (`php nodex`)
5. [Chương 5: Deployment & Optimization (Triển Khai & Tối Ưu Production)](#chương-5-deployment--optimization-triển-khai--tối-ưu-production)
   * 5.1. Triển khai trên aaPanel / Ubuntu VPS Linux
   * 5.2. Cấu hình Nginx VirtualHost tối ưu
   * 5.3. Thiết lập Supervisor quản lý Worker & WebSocket Server
   * 5.4. Lệnh tối ưu hóa biên dịch Production

---

## CHƯƠNG 1: GETTING STARTED (BẮT ĐẦU)

### 1.1. Giới Thiệu Triết Lý Micro-Kernel
Các framework full-stack cồng kềnh truyền thống thường nạp hàng trăm service providers, file cấu hình và package phụ trợ ngay cả khi ứng dụng chỉ là một RESTful API hoặc ứng dụng monolith vừa và nhỏ. Điều này tiêu tốn dung lượng RAM và chu kỳ CPU không cần thiết.

**NodeX Studio Framework** được xây dựng với triết lý:
* **Hạt nhân siêu gọn:** Chỉ tích hợp những gì xuất sắc nhất (`Illuminate/Database v13`, `Illuminate/View v13`, `Illuminate/Filesystem`, `Illuminate/Events`).
* **Không overhead:** Tự xây dựng Router, Container, Queue, Broadcaster, CSRF Protector bằng PHP thuần tốc độ cao.
* **Chuẩn mực hiện đại:** Khai thác toàn diện cú pháp của **PHP 8.4+** (`declare(strict_types=1);`, Asymmetric Visibility `public private(set)`, Property Hooks, Constructor Property Promotion).

### 1.2. Yêu Cầu Hệ Thống
* **PHP:** $\ge 8.4.0$ (Bật các extension: `pdo`, `pdo_mysql` hoặc `pdo_sqlite`, `mbstring`, `openssl`, `sockets`, `fileinfo`).
* **Composer:** $\ge 2.5.0$.
* **Node.js & npm:** Node.js $\ge 18.0.0$ (Dùng cho Vite & WebSocket Server).
* **Redis Server:** Khuyến nghị cho Queue & WebSocket (Mặc định cổng 6379; nếu không có Redis, framework sẽ tự động kích hoạt **File Queue Fallback**).

### 1.3. Cài Đặt Toàn Cục Qua `nodexstudiovn/installer`
Bộ công cụ cài đặt đã được công bố chính thức trên **Packagist**:

```bash
# Cài đặt công cụ dòng lệnh toàn cầu (chỉ cần chạy một lần)
composer global require nodexstudiovn/installer

# Tạo mới một dự án sạch
nodex new my-app

# Bắt đầu phát triển
cd my-app
php nodex serve     # Máy chủ PHP (Cổng 8000)
npm run dev        # Đóng gói Frontend Vite (Cổng 5173)
```

### 1.4. Cấu Hình Biến Môi Trường `.env`
Khi tạo dự án, file `.env` được tạo tự động từ `.env.example`:

```ini
APP_NAME="NodeX Studio"
APP_ENV=development
APP_DEBUG=true
APP_VERSION=1.2.0
APP_URL=http://localhost:8000

# Cấu hình Cơ sở dữ liệu (MySQL hoặc SQLite)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nodexvn
DB_USERNAME=root
DB_PASSWORD=

# Cấu hình Redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null

# Cấu hình Hàng đợi Queue (Tùy chọn: auto, redis, file, sync)
QUEUE_CONNECTION=auto
```

---

## CHƯƠNG 2: ARCHITECTURE CONCEPTS (KHÁI NIỆM KIẾN TRÚC CỐT LÕI)

### 2.1. Vòng Đời Request (Request Lifecycle)
Mọi request từ trình duyệt/client đều tuân thủ luồng luân chuyển sau:

```text
[Trình duyệt / API Client]
           │
           ▼
[public/index.php]  ─── Khởi tạo Application Container, nạp .env & Database
           │
           ▼
[CsrfMiddleware]    ─── Kiểm tra Token CSRF đối với POST/PUT/DELETE/PATCH
           │
           ▼
[Router Engine]     ─── Đối soát URL và phương thức HTTP (routes/web.php hoặc routes/api.php)
           │
           ▼
[DIC Container]     ─── Phân giải Method Injection (tự động nạp Action / Model / Request)
           │
           ▼
[Controller]        ─── Tiếp nhận request, gọi Action thực thi nghiệp vụ
           │
           ▼
[Action Class]      ─── 100% Logic nghiệp vụ, giao dịch Database, phát sự kiện
           │
           ▼
[ViewHygieneChecker]─── Quét vệ sinh View (Chặn PHP/CSS/JS inline nếu trả về Blade View)
           │
           ▼
[Response Output]   ─── Gửi HTML hoặc JSON về cho Client
```

### 2.2. Mô Hình Hybrid Action Pattern
Trong các dự án lâu năm, "Fat Controller" (Controller chứa hàng trăm dòng code nghiệp vụ) là nguyên nhân hàng đầu gây khó khăn cho việc bảo trì và test.

NodeX áp dụng quy chuẩn **Hybrid Action Pattern**:
* **Controller Siêu Mỏng (Thin Controller):** Controller chỉ làm 3 việc: Nhận dữ liệu từ Request, gọi Action, trả về Response.
* **Action Đơn Trách Nhiệm (Single Action):** Mỗi lớp Action tại `app/Actions/` đại diện cho đúng một hành vi nghiệp vụ duy nhất (ví dụ: `RegisterUserAction`, `ProcessOrderPaymentAction`).

```php
// app/Actions/ProcessOrderPaymentAction.php
namespace App\Actions;

use App\Models\Order;
use Illuminate\Database\Capsule\Manager as DB;
use RuntimeException;

class ProcessOrderPaymentAction extends Action
{
    public function execute(int $orderId, float $amount): bool
    {
        return DB::transaction(function () use ($orderId, $amount): bool {
            $order = Order::query()->where('id', $orderId)->lockForUpdate()->first();

            if (!$order || $order->is_paid) {
                throw new RuntimeException("Đơn hàng không hợp lệ hoặc đã thanh toán.");
            }

            $order->update([
                'is_paid' => true,
                'paid_at' => date('Y-m-d H:i:s')
            ]);

            return true;
        });
    }
}
```

Gọi Action trong Controller siêu gọn:
```php
namespace App\Controllers;

use App\Actions\ProcessOrderPaymentAction;
use NodeX\Http\Request;
use NodeX\Http\Response;

class OrderController extends Controller
{
    public function pay(Request $request, ProcessOrderPaymentAction $action): Response
    {
        $orderId = (int)$request->input('order_id');
        $amount = (float)$request->input('amount');

        $result = $action->execute($orderId, $amount);

        return $this->json(['status' => 'success', 'paid' => $result]);
    }
}
```

### 2.3. Quy Chuẩn Strict View Hygiene & Cơ Chế Bắt Lỗi Tầng Ứng Dụng
Để tầng giao diện đạt tiêu chuẩn bảo mật và sạch sẽ cao nhất:
1. ❌ **CẤM viết mã PHP thuần:** Cấm thẻ `<?php`, `<?=`, `<%` hoặc directive `@php` trong View Blade.
2. ❌ **CẤM viết style inline:** Cấm thẻ `<style>` và thuộc tính inline `style="..."`. Toàn bộ giao diện dùng class Tailwind CSS hoặc CSS module.
3. ❌ **CẤM viết script inline:** Cấm thẻ `<script>` trong sub-views và các thuộc tính sự kiện `onclick=`, `onload=`. Toàn bộ tương tác JavaScript viết trong `resources/js/modules/` và nạp qua Vite.

**Cơ chế bắt lỗi:** Trước khi render bất kỳ template nào, `NodeX\View\ViewHygieneChecker` sẽ quét qua toàn bộ mã nguồn template. Nếu vi phạm, hệ thống ném ra `ViewHygieneViolationException` lập tức dừng chương trình kèm số dòng và tên file vi phạm.

---

## CHƯƠNG 3: CORE COMPONENTS (CÁC THÀNH PHẦN CỐT LÕI)

### 3.1. Điều Hướng Routing & Middleware Pipeline
Các tuyến đường được phân tách độc lập:
* `routes/web.php`: Dành cho trang giao diện hiển thị Blade.
* `routes/api.php`: Dành cho các endpoint RESTful trả về JSON.

```php
// routes/api.php
use App\Controllers\UserController;

$router->get('/api/users', [UserController::class, 'index']);
$router->post('/api/users', [UserController::class, 'store']);
```

### 3.2. Cơ Sở Dữ Liệu Eloquent ORM & Chống N+1 Query
NodeX tích hợp `Illuminate/Database v13` (Eloquent ORM) với đầy đủ sức mạnh của Eloquent Model, Query Builder, Pagination và Migration.

* **Nguyên tắc chống N+1 Query:** Khi truy vấn dữ liệu có quan hệ, luôn sử dụng Eager Loading `with()`:
```php
// Đúng: Chỉ sinh ra 2 câu truy vấn SQL tối ưu
$users = User::with('posts')->get();

// Sai: Gây ra N+1 câu truy vấn làm chậm hệ thống
$users = User::all();
foreach ($users as $user) {
    echo $user->posts;
}
```

* **Quy chuẩn Model nhỏ gọn:** Tách các quan hệ và scope phức tạp thành các Traits trong `app/Models/Traits/`. Mỗi file Model không vượt quá 300 dòng code.

### 3.3. An Toàn Bảo Mật CSRF & Chống Tranh Chấp Số Dư (Concurrency)
* **CSRF Token:** `CsrfMiddleware` tự động giám sát toàn bộ request làm thay đổi trạng thái (`POST`, `PUT`, `DELETE`).
  * Trong Form HTML: Thêm `@csrf`.
  * Trong Thẻ `<head>`: Thêm `@csrf_meta` và gửi kèm header `X-CSRF-TOKEN` khi gọi `fetch()`.
* **Kiểm soát tranh chấp số dư (Race Condition):** Bắt buộc sử dụng `lockForUpdate()` bên trong `DB::transaction()`:
```php
DB::transaction(function () use ($userId, $amount) {
    $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();
    if ($wallet->balance < $amount) {
        throw new RuntimeException("Số dư không đủ.");
    }
    $wallet->decrement('balance', $amount);
});
```

### 3.4. Dependency Injection Container (DIC) & Compiled Container
Lớp hạt nhân `NodeX\Container\Container` mang lại hiệu năng vượt trội nhờ kiến trúc 3 cấp độ phân giải:

```text
[Yêu cầu make(Class)]
        │
        ├─► (1) In-Memory Cache ($instances) ────► Trả về ngay lập tức (0ms)
        │
        ├─► (2) Compiled Container File       ────► Nạp closure tĩnh biên dịch sẵn (0ms Reflection)
        │
        └─► (3) Reflection & Param Cache      ────► Phân giải động khi đang ở môi trường Dev
```

#### Lệnh Biên Dịch Container (Compiled Container):
Trước khi đưa ứng dụng lên môi trường Production, chạy lệnh:
```bash
php nodex optimize:container
```
Hệ thống sẽ quét toàn bộ Actions, Controllers, Jobs và sinh ra file map closure tĩnh tại `storage/cache/container.php`, giúp triệt tiêu hoàn toàn chi phí Reflection API trong mọi request tiếp theo!

---

## CHƯƠNG 4: ADVANCED FEATURES (TÍNH NĂNG NÂNG CAO)

### 4.1. Hệ Thống Hàng Đợi Ngầm (Queue Engine)
Hỗ trợ 4 chế độ Driver linh hoạt: `auto`, `redis`, `file`, `sync`.

#### 1. Tạo Một Job Mới Bằng CLI:
```bash
php nodex make:job SendWelcomeEmail
```
File được sinh tự động tại `app/Jobs/SendWelcomeEmailJob.php`:
```php
namespace App\Jobs;

use NodeX\Queue\ShouldQueue;

class SendWelcomeEmailJob implements ShouldQueue
{
    public function __construct(
        public readonly string $email,
        public readonly string $fullName
    ) {}

    public function handle(): void
    {
        // Thực thi gửi mail ngầm
    }
}
```

#### 2. Đẩy Job Vào Hàng Đợi:
```php
use App\Jobs\SendWelcomeEmailJob;
use NodeX\Queue\QueueManager;

QueueManager::push(new SendWelcomeEmailJob('user@example.com', 'Nguyễn Văn A'));
```

#### 3. Chạy Tiến Trình Lắng Nghe Worker:
```bash
php nodex queue:work
```

#### 4. Cơ Chế Exponential Backoff & Jitter:
Khi Job gặp sự cố ngoại lệ (ví dụ mạng bên thứ 3 gián đoạn), Worker không retry liên tục làm quá tải hệ thống. Thay vào đó, thời gian chờ sẽ tăng dần theo cấp số nhân:
$$\text{Delay} = \min(2^{\text{attempts}} + \text{jitter}, 60\text{s})$$

#### 5. Graceful Shutdown (Dừng An Toàn):
Khi nhận tín hiệu dừng hệ thống (`Ctrl+C` hoặc lệnh dừng từ Supervisor `SIGTERM`), Worker sẽ **không ngắt ngang**. Thay vào đó, Worker đợi Job hiện tại hoàn tất thành công rồi mới đóng tài nguyên và thoát an toàn.

---

### 4.2. Truyền Phát Thời Gian Thực WebSockets (Micro-Reverb Engine)
* **Backend:** Phát sự kiện lên Redis Pub/Sub:
```php
use NodeX\Broadcasting\Broadcaster;

Broadcaster::emit('orders-channel', 'new-order', [
    'order_id' => 101,
    'total' => 250000
]);
```

* **Khởi chạy máy chủ WebSocket:**
```bash
php nodex realtime:serve
```

* **Frontend:** Lắng nghe bằng module JS (`resources/js/modules/realtime.js`):
```javascript
export function initRealtime() {
    const ws = new WebSocket('ws://localhost:6001');
    ws.onmessage = (e) => {
        const payload = JSON.parse(e.data);
        if (payload.event === 'new-order') {
            console.log('Đơn hàng mới:', payload.data);
        }
    };
}
```

---

### 4.3. Bộ Kiểm Thử Tự Động (Testing Suite)
NodeX hỗ trợ sinh test case và chạy kiểm thử tự động:

```bash
# Sinh file Feature Test mới
php nodex make:test UserCheckoutTest

# Chạy toàn bộ bộ kiểm thử
php nodex test
```
Hệ thống tự động nhận diện Pest hoặc PHPUnit nếu đã cài đặt trong `vendor/bin/`, hoặc chạy bộ Core Self-Check tích hợp sẵn của framework.

---

### 4.4. Tự Động Sinh Tài Liệu OpenAPI 3.0 & Swagger UI
Chỉ cần 1 lệnh duy nhất:
```bash
php nodex docs:api
```
Hệ thống sẽ:
1. Tự động quét toàn bộ các route trong `routes/api.php`.
2. Phân tích các endpoint, method và sinh chuẩn file JSON: `public/docs/swagger.json`.
3. Sinh giao diện tương tác **Swagger UI** tại `public/docs/index.html`.

Lập trình viên có thể mở trình duyệt truy cập ngay tại:
`http://127.0.0.1:8000/docs/index.html` để kiểm thử và tài liệu hóa toàn bộ API của dự án.

---

### 4.5. Bảng Tra Cứu Toàn Bộ Lệnh NodeX CLI (`php nodex`)

| Câu Lệnh | Chức Năng Kỹ Thuật |
| :--- | :--- |
| `php nodex serve [host] [port]` | Khởi chạy máy chủ phát triển nội bộ PHP (Mặc định `127.0.0.1:8000`). |
| `php nodex migrate` | Chạy toàn bộ các migration cơ sở dữ liệu chưa thực thi. |
| `php nodex migrate:rollback` | Hoàn tác lại batch migration gần nhất. |
| `php nodex migrate:status` | Xem bảng trạng thái các migration đã chạy. |
| `php nodex make:action <Name>` | Sinh lớp Action theo chuẩn Action Pattern tại `app/Actions/`. |
| `php nodex make:controller <Name>` | Sinh lớp Controller tại `app/Controllers/`. |
| `php nodex make:model <Name>` | Sinh Eloquent Model tại `app/Models/`. |
| `php nodex make:migration <Name>` | Sinh file migration có timestamp tại `database/migrations/`. |
| `php nodex make:job <Name>` | Sinh lớp Queue Job ngầm tại `app/Jobs/`. |
| `php nodex make:test <Name>` | Sinh file Feature Test tại `tests/Feature/`. |
| `php nodex make:rule <Name>` | Sinh lớp Validation Rule tùy chỉnh tại `app/Rules/`. |
| `php nodex optimize:container` | Biên dịch sơ đồ phụ thuộc DIC Container vào `storage/cache/container.php`. |
| `php nodex docs:api` | Tự động sinh file OpenAPI 3.0 và giao diện Swagger UI tài liệu hóa API. |
| `php nodex cache:clear` | Dọn dẹp cache Blade Views và Compiled Container cache. |
| `php nodex route:list` | Hiển thị bảng tổng hợp toàn bộ các route Web & API trong hệ thống. |
| `php nodex queue:work [queue]` | Khởi chạy tiến trình Queue Worker lắng nghe xử lý Job ngầm. |
| `php nodex realtime:serve` | Khởi chạy máy chủ WebSockets (Micro-Reverb) cổng 6001. |
| `php nodex test` | Thực thi toàn bộ bộ kiểm thử của dự án. |
| `php nodex version` | Hiển thị phiên bản hiện tại của NodeX Studio Framework. |

---

## CHƯƠNG 5: DEPLOYMENT & OPTIMIZATION (TRIỂN KHAI & TỐI ƯU PRODUCTION)

### 5.1. Triển Khai Trên aaPanel / Ubuntu VPS Linux
1. Cài đặt **PHP 8.4** và **Composer** trên server.
2. Clone mã nguồn vào `/www/wwwroot/your-domain.com`.
3. Cấp quyền ghi cho thư mục lưu trữ:
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www:www /www/wwwroot/your-domain.com
   ```
4. Cài đặt các gói phụ thuộc PHP và đóng gói Frontend:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install && npm run build
   ```

### 5.2. Cấu Hình Nginx VirtualHost Tối Ưu
Chỉ định thư mục gốc của trang web trỏ vào thư mục `public`:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /www/wwwroot/your-domain.com/public;
    index index.php index.html;

    # Tối ưu gzip nén tài nguyên tĩnh
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/tmp/php-cgi-84.sock;
        fastcgi_index index.php;
        include fastcgi.conf;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    # Chặn truy cập trực tiếp vào các file ẩn và thư mục nhạy cảm
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 5.3. Thiết Lập Supervisor Quản Lý Queue Worker & WebSocket
Tạo cấu hình Supervisor tại `/etc/supervisor/conf.d/nodex-worker.conf`:

```ini
[program:nodex-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /www/wwwroot/your-domain.com/nodex queue:work default
autostart=true
autorestart=true
user=www
numprocs=2
redirect_stderr=true
stdout_logfile=/www/wwwroot/your-domain.com/storage/logs/worker.log
stopwaitsecs=3600
stopsignal=SIGTERM
```

Khởi động lại Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start nodex-worker:*
```

### 5.4. Lệnh Tối Ưu Hóa Production
Mỗi khi deploy phiên bản mới lên production, chạy chuỗi lệnh tối ưu:
```bash
php nodex optimize:container  # Biên dịch Container tĩnh (0ms Reflection)
php nodex cache:clear          # Làm mới view cache
php nodex migrate              # Cập nhật migration cơ sở dữ liệu
php nodex docs:api             # Cập nhật tài liệu API mới nhất
```

---
*Tài liệu kiến trúc và hướng dẫn phát triển — NodeX Studio Engineering Team.*
