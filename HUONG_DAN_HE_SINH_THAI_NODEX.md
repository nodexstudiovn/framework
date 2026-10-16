# 📘 HƯỚNG DẪN CHI TIẾT HỆ SINH THÁI & CÁC PACKAGE TRONG NODEX STUDIO FRAMEWORK v1.1.0

Tài liệu này cung cấp hướng dẫn chuyên sâu về cách sử dụng toàn bộ các module và gói tính năng hạt nhân trong **NodeX Studio Framework v1.1.0**, bao gồm: **Realtime WebSockets (Micro-Reverb)**, **Hàng đợi ngầm (Queue Engine)**, **Bảo mật CSRF**, **Dependency Injection Container (DIC)**, **Bộ kiểm tra Vệ sinh View (Strict View Hygiene)** và **Công cụ dòng lệnh NodeX CLI**.

---

## 📌 MỤC LỤC
1. [Kiến Trúc Tổng Quan](#1-kiến-trúc-tổng-quan)
2. [Hệ Thống Realtime WebSockets & Broadcasting (Micro-Reverb)](#2-hệ-thống-realtime-websockets--broadcasting-micro-reverb)
3. [Hệ Thống Hàng Đợi Ngầm (Queue Engine & Worker)](#3-hệ-thống-hàng-đợi-ngầm-queue-engine--worker)
4. [Tầng Bảo Mật & CSRF Protection Tự Động](#4-tầng-bảo-mật--csrf-protection-tự-động)
5. [Dependency Injection Container (DIC) & Reflection Resolver](#5-dependency-injection-container-dic--reflection-resolver)
6. [Quy Chuẩn Strict View Hygiene & Bắt Lỗi Tầng Ứng Dụng](#6-quy-chuẩn-strict-view-hygiene--bắt-lỗi-tầng-ứng-dụng)
7. [Bảng Tra Cứu Lệnh Dòng Lệnh NodeX CLI (`php nodex`)](#7-bảng-tra-cứu-lệnh-dòng-lệnh-nodex-cli-php-nodex)

---

## 1. KIẾN TRÚC TỔNG QUAN

NodeX Studio Framework được thiết kế theo triết lý **Micro-Kernel** (Nhân siêu nhỏ). Thay vì ôm đồm hàng trăm package không cần thiết, framework chỉ tích hợp có chọn lọc các thành phần tốt nhất:

* **Backend Engine:** PHP 8.4+ với `declare(strict_types=1);` bắt buộc.
* **Database & ORM:** `Illuminate/Database v13` (Eloquent ORM Capsule).
* **View Template:** `Illuminate/View v13` (Blade Engine thuần HTML).
* **Frontend Pipeline:** Vite 6.x + Tailwind CSS v3.
* **Business Logic Pattern:** Hybrid Action Pattern (100% logic đặt trong `app/Actions/`).

---

## 2. HỆ THỐNG REALTIME WEBSOCKETS & BROADCASTING (MICRO-REVERB)

NodeX Studio Framework tích hợp sẵn cơ chế truyền phát sự kiện thời gian thực (Realtime Event Broadcasting) tương tự mô hình Laravel Reverb nhưng được tối ưu hóa siêu nhẹ, phân tách rõ ràng trách nhiệm giữa PHP và Node.js.

### 2.1. Mô Hình Hoạt Động 4 Bước:
```text
[Trình duyệt Client] ──(1) Gửi Request API (POST)──> [PHP NodeX Framework]
                                                             │
                                                     (2) Xử lý DB & Lưu dữ liệu
                                                             │
                                                     (3) Broadcaster::emit()
                                                             ▼
                                                    [Redis Pub/Sub Kênh]
                                                             │
[Trình duyệt Client] <──(4) WebSocket Broadcast────── [Node.js WS Server (Cổng 6001)]
```

### 2.2. Cách Phát Sự Kiện Từ PHP (Backend)
Để phát một sự kiện thời gian thực tới toàn bộ các client đang kết nối, bạn chỉ cần gọi 1 dòng lệnh duy nhất từ bất kỳ Action hay Controller nào thông qua lớp `NodeX\Broadcasting\Broadcaster`:

```php
<?php

declare(strict_types=1);

namespace App\Actions;

use NodeX\Broadcasting\Broadcaster;

class SendNotificationAction extends Action
{
    public function execute(mixed ...$args): void
    {
        $payload = [
            'id' => 101,
            'title' => 'Đơn hàng mới #101',
            'amount' => 500000,
            'time' => date('H:i:s d/m/Y')
        ];

        // Phát sự kiện lên kênh 'orders-channel' với tên sự kiện 'new-order'
        Broadcaster::emit('orders-channel', 'new-order', $payload);
    }
}
```

### 2.3. Khởi Chạy Máy Chủ WebSocket (Micro-Reverb Server)
Framework đã tích hợp sẵn lệnh dòng lệnh để khởi chạy máy chủ WebSocket:

```bash
php nodex realtime:serve
```

Máy chủ sẽ chạy ngầm ở cổng `ws://localhost:6001`, tự động lắng nghe các kênh Redis Pub/Sub và chuyển tiếp tức thì dữ liệu tới mọi trình duyệt đang mở kết nối.

### 2.4. Kết Nối & Lắng Nghe Sự Kiện Ở Frontend (JavaScript Module)
Tuân thủ tiêu chuẩn **Strict View Hygiene**, mã JavaScript lắng nghe sự kiện không được viết trực tiếp trong file Blade mà phải tách vào file module riêng (ví dụ `resources/js/modules/realtime.js`) và nạp qua Vite:

```javascript
// resources/js/modules/realtime.js
export function initRealtimeListener() {
    const ws = new WebSocket('ws://localhost:6001');

    ws.onopen = () => {
        console.log('🟢 Đã kết nối thành công tới NodeX WebSocket Server');
    };

    ws.onmessage = (event) => {
        try {
            const packet = JSON.parse(event.data);
            
            // Bắt sự kiện 'new-order'
            if (packet.event === 'new-order') {
                console.log('🔔 Nhận đơn hàng mới:', packet.data);
                // Cập nhật giao diện DOM tại đây
            }
        } catch (e) {
            console.error('Lỗi phân tích gói tin WebSocket:', e);
        }
    };

    ws.onclose = () => {
        console.warn('🟡 Mất kết nối WebSocket. Đang tự động kết nối lại sau 3 giây...');
        setTimeout(initRealtimeListener, 3000);
    };
}
```

Nạp module trong `resources/js/app.js`:
```javascript
import { initRealtimeListener } from './modules/realtime.js';

document.addEventListener('DOMContentLoaded', () => {
    initRealtimeListener();
});
```

---

## 3. HỆ THỐNG HÀNG ĐỢI NGẦM (QUEUE ENGINE & WORKER)

Để các tác vụ nặng (như gửi email, tạo hóa đơn PDF, gọi API bên thứ ba) không làm chậm thời gian tải trang của người dùng, NodeX Framework cung cấp sẵn hệ thống Hàng đợi (Queue) đa driver thông minh: **Redis**, **File Queue** (hoạt động độc lập không cần cài Redis), **Sync** (thực thi ngay lập tức) và chế độ **Auto** (tự động phát hiện và chuyển đổi dự phòng mượt mà).

Cấu hình driver trong file `.env`:
```ini
# Chế độ: auto (mặc định), redis, file, sync
QUEUE_CONNECTION=auto
```

### 3.1. Tạo Một Job Ngầm Mới
Mỗi Job ngầm được đặt tại `app/Jobs/` và bắt buộc phải triển khai interface `NodeX\Queue\ShouldQueue`:

```php
<?php

declare(strict_types=1);

namespace App\Jobs;

use NodeX\Queue\ShouldQueue;

class SendWelcomeEmailJob implements ShouldQueue
{
    /**
     * Tận dụng tính năng Constructor Property Promotion của PHP 8.4
     */
    public function __construct(
        public readonly string $email,
        public readonly string $name
    ) {}

    /**
     * Phương thức xử lý tác vụ ngầm khi Worker lấy job ra khỏi hàng đợi
     */
    public function handle(): void
    {
        // Thực thi logic gửi email hoặc xử lý nặng tại đây
        mail($this->email, "Chào mừng {$this->name}", "Cảm ơn bạn đã tham gia hệ thống!");
    }
}
```

### 3.2. Đẩy Công Việc Vào Hàng Đợi (Queue Dispatch)
Sử dụng lớp `NodeX\Queue\QueueManager` để đẩy Job vào hàng đợi:

```php
use App\Jobs\SendWelcomeEmailJob;
use NodeX\Queue\QueueManager;

// Đẩy job vào queue mặc định ('default')
$job = new SendWelcomeEmailJob('user@example.com', 'Nguyễn Văn A');
QueueManager::push($job);

// Hoặc đẩy vào queue chuyên biệt
QueueManager::push($job, 'emails');
```

*Cơ chế tự động:* Nếu Redis tạm thời chưa được bật, `QueueManager` sẽ tự động chuyển sang cơ chế **File Fallback**, ghi tạm job vào thư mục `storage/queue/` để không làm mất tác vụ của người dùng.

### 3.3. Khởi Chạy Tiến Trình Worker Lắng Nghe Hàng Đợi
Mở một cửa sổ Terminal và chạy lệnh:

```bash
# Lắng nghe hàng đợi mặc định 'default'
php nodex queue:work

# Hoặc lắng nghe hàng đợi chỉ định
php nodex queue:work emails
```

### 3.4. Cơ Chế Tự Động Thử Lại (Retry) & Nhật Ký Lỗi (Failed Jobs Log)
* Worker sử dụng lệnh `BRPOP` (blocking read) kết nối tới Redis, giúp **tiêu tốn gần như 0% CPU** khi hàng đợi đang trống.
* Nếu Job gặp ngoại lệ trong quá trình thực thi, Worker sẽ tự động thử lại tối đa 3 lần.
* Nếu sau 3 lần vẫn thất bại, Worker sẽ tự động đóng gói nguyên vẹn dữ liệu payload kèm Stack Trace chi tiết và ghi vào file `storage/logs/failed_jobs.log` bằng tiếng Việt rõ ràng để phục vụ việc kiểm tra và đối soát lỗi.

---

## 4. TẦNG BẢO MẬT & CSRF PROTECTION TỰ ĐỘNG

Mọi ứng dụng web hiện đại đều đối mặt với rủi ro tấn công giả mạo yêu cầu (CSRF). NodeX Framework kích hoạt cơ chế bảo vệ CSRF hoàn toàn tự động ở cấp độ Router Pipeline.

### 4.1. Quy Tắc Hoạt Động
* Các phương thức đọc dữ liệu an toàn (`GET`, `HEAD`, `OPTIONS`) và các endpoint tĩnh bắt đầu bằng `/api/` được bỏ qua kiểm tra token.
* Tất cả các request làm biến đổi dữ liệu (`POST`, `PUT`, `DELETE`, `PATCH`) trên giao diện web bắt buộc phải đính kèm CSRF Token hợp lệ.
* Nếu token bị thiếu hoặc không khớp, hệ thống sẽ lập tức trả về mã lỗi **`403 Forbidden`** (dưới dạng JSON nếu là AJAX request, hoặc màn hình lỗi bảo mật riêng biệt).

### 4.2. Nhúng CSRF Token Trong Form Blade
Sử dụng directive `@csrf` bên trong thẻ `<form>`:

```html
<form action="/profile/update" method="POST">
    @csrf
    <input type="text" name="display_name" value="Nguyễn Văn A">
    <button type="submit">Cập nhật</button>
</form>
```
*Directive `@csrf` sẽ tự động sinh ra thẻ ẩn: `<input type="hidden" name="_csrf_token" value="...">`.*

### 4.3. Nhúng CSRF Token Cho AJAX / Fetch API
Đặt directive `@csrf_meta` bên trong thẻ `<head>` của layout chính (`resources/views/layouts/app.blade.php`):

```html
<head>
    @csrf_meta
</head>
```
*Directive `@csrf_meta` sẽ tự sinh thẻ: `<meta name="csrf-token" content="...">`.*

Khi gửi request bằng JavaScript `fetch()`:
```javascript
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

fetch('/api/user/save', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({ name: 'Dữ liệu mới' })
});
```

---

## 5. DEPENDENCY INJECTION CONTAINER (DIC) & REFLECTION RESOLVER

Lớp hạt nhân `NodeX\Container\Container` chịu trách nhiệm quản lý vòng đời của đối tượng và tự động phân giải (auto-resolve) các tham số phụ thuộc mà lập trình viên không cần viết lệnh `new` thủ công.

### 5.1. Tự Động Tiêm Phụ Thuộc Vào Controller (Method Injection)
Khi một Controller method hoặc Closure route khai báo tham số có type-hint, Container sẽ dùng Reflection API để tự động phát hiện và khởi tạo đối tượng tương ứng:

```php
namespace App\Controllers;

use App\Actions\RegisterUserAction;
use NodeX\Http\Request;
use NodeX\Http\Response;

class AuthController extends Controller
{
    /**
     * $request và $action được tự động inject bởi Container
     */
    public function register(Request $request, RegisterUserAction $action): Response
    {
        $userData = $request->all();
        $user = $action->execute($userData);

        return $this->json(['status' => 'success', 'user' => $user]);
    }
}
```

---

## 6. QUY CHUẨN STRICT VIEW HYGIENE & BẮT LỖI TẦNG ỨNG DỤNG

Khác với nhiều framework cho phép trộn lẫn code tùy tiện, NodeX Framework áp dụng triết lý **Strict View Hygiene** nghiêm ngặt nhằm đảm bảo tính bảo mật và sạch sẽ tuyệt đối cho tầng giao diện.

### 6.1. Ba Điều Cấm Tuyệt Đối Trong View
1. ❌ **CẤM viết mã PHP thuần:** Cấm mọi thẻ `<?php`, `<?=`, `<%` hoặc directive `@php` trong View. 100% việc tính toán, truy vấn dữ liệu phải được hoàn tất tại Action trước khi đẩy ra View.
2. ❌ **CẤM viết thẻ `<style>` và thuộc tính inline `style="..."`:** Toàn bộ định dạng giao diện phải sử dụng các class tiện ích của Tailwind CSS hoặc file stylesheet độc lập trong `resources/css/`.
3. ❌ **CẤM viết thẻ `<script>` và các thuộc tính sự kiện inline (`onclick=`, `onload=`):** Mọi logic tương tác JavaScript phải được module hóa độc lập trong `resources/js/` và nạp thông qua `@vite`.

### 6.2. Cơ Chế Giám Sát Của `ViewHygieneChecker`
Lớp `NodeX\View\ViewHygieneChecker` sẽ tự động quét cú pháp của file View ngay khi gọi lệnh render. Nếu phát hiện vi phạm, hệ thống sẽ lập tức dừng chương trình và hiển thị chi tiết dòng lỗi vi phạm:

```text
🚨 VI PHẠM QUY CHUẨN VIEW HYGIENE:
Phát hiện mã PHP thuần ('<?php echo $user; ?>') tại dòng 15 trong file [resources/views/profile.blade.php].
NodeX Framework nghiêm cấm tuyệt đối viết PHP thuần trong View. 100% Business Logic phải xử lý ở Action/Controller.
```

---

## 7. BẢNG TRA CỨU LỆNH DÒNG LỆNH NODEX CLI (`php nodex`)

NodeX CLI cung cấp bộ lệnh đầy đủ để hỗ trợ quy trình phát triển từ giai đoạn khởi tạo đến bảo trì:

| Lệnh CLI | Ý Nghĩa Kỹ Thuật |
| :--- | :--- |
| `php nodex serve` *(hoặc `dev`)* | Khởi chạy máy chủ phát triển nội bộ PHP (Mặc định `http://127.0.0.1:8000`). |
| `php nodex queue:work [queue]` | Khởi chạy tiến trình Worker lắng nghe và xử lý tác vụ ngầm từ Redis/File Queue. |
| `php nodex realtime:serve` | Khởi chạy máy chủ Node.js WebSocket Server (Micro-Reverb Engine) ở cổng 6001. |
| `php nodex migrate` | Thực thi toàn bộ các file migration cơ sở dữ liệu chưa chạy trong `database/migrations/`. |
| `php nodex migrate:rollback` | Hoàn tác lại đợt migration gần nhất. |
| `php nodex migrate:status` | Kiểm tra trạng thái đã chạy / chưa chạy của các file migration. |
| `php nodex make:action <Tên>` | Sinh nhanh một lớp Action mới theo chuẩn Action Pattern tại `app/Actions/`. |
| `php nodex make:controller <Tên>` | Sinh nhanh một Controller kế thừa Base Controller tại `app/Controllers/`. |
| `php nodex make:model <Tên>` | Sinh nhanh một Eloquent Model tại `app/Models/`. |
| `php nodex make:migration <Tên>` | Sinh nhanh một file migration có gắn timestamp tại `database/migrations/`. |
| `php nodex make:rule <Tên>` | Sinh nhanh một lớp Validation Rule tùy chỉnh tại `app/Rules/`. |
| `php nodex route:list` | Hiển thị bảng tổng hợp toàn bộ các tuyến đường (Web & API) đã đăng ký trong hệ thống. |
| `php nodex cache:clear` | Dọn dẹp toàn bộ bộ nhớ đệm ứng dụng và file Blade template đã biên dịch trong `storage/cache/views/`. |
| `php nodex test` | Tự động chạy bộ kiểm thử toàn diện hệ thống (PHPUnit / Pest / NodeX Core Self-Check). |

---

## 8. BỘ CÀI ĐẶT DỰ ÁN TOÀN CẦU (`nodex new`)

Để khởi tạo một dự án mới sạch sẽ chỉ bằng 1 câu lệnh từ bất kỳ thư mục nào trên máy tính, sử dụng gói cài đặt toàn cầu đã xuất bản trên Packagist:

```bash
# 1. Cài đặt công cụ toàn cục (Chỉ cần chạy một lần)
composer global require nodexstudiovn/installer

# 2. Khởi tạo một dự án mới hoàn toàn rỗng
nodex new ten-du-an-cua-ban

# 3. Bắt đầu phát triển
cd ten-du-an-cua-ban
php nodex serve     # Máy chủ Backend (Cổng 8000)
npm run dev        # Máy chủ Frontend Vite (Cổng 5173)
```

---

## 9. CÁC PACKAGE TINH HOA ĐỘC LẬP TỪ ILLUMINATE & SYMFONY

Nhằm tối ưu hóa trải nghiệm lập trình (Developer Experience) mà không làm cồng kềnh Micro-Kernel, NodeX Framework tích hợp có chọn lọc các component độc lập tốt nhất từ hai hệ sinh thái Laravel & Symfony:

### 9.1. Động Cơ Xác Thực Độc Lập (`illuminate/validation`)
Tích hợp sẵn bộ từ điển tiếng Việt tự nhiên, cho phép validate nhanh dữ liệu từ Request:
```php
// Cách 1: Xác thực trực tiếp trên đối tượng Request trong Controller
public function store(Request $request): Response
{
    $validated = $request->validate([
        'title' => 'required|min:5|max:255',
        'email' => 'required|email|unique:users,email',
        'age'   => 'nullable|numeric|between:18,65'
    ]);

    // Dữ liệu hợp lệ đã được lọc an toàn
    return response()->json(['status' => 'success', 'data' => $validated]);
}

// Cách 2: Sử dụng helper validate() hoặc validator()
$cleanData = validate($payload, ['amount' => 'required|numeric|min:10000']);
```
*Tự động bắt lỗi:* Khi dữ liệu không hợp lệ, hệ thống tự ném `ValidationException` và `ExceptionHandler` sẽ tự động trả về HTTP `422 Unprocessable Entity` kèm danh sách lỗi chi tiết bằng tiếng Việt.

### 9.2. Phân Trang Tự Động Eloquent (`illuminate/pagination`)
Cung cấp phân trang chuẩn mực kết hợp với Eloquent ORM:
```php
// Phân trang truyền thống (LengthAwarePaginator)
$users = User::query()->where('is_active', true)->paginate(15);

// Phân trang tối ưu hiệu năng cao cho dữ liệu lớn (CursorPaginator)
$orders = Order::query()->cursorPaginate(20);
```

### 9.3. Tiện Ích Mảng, Chuỗi & Collections (`illuminate/support`)
* **Hàm `collect()`:** Thao tác trên mảng dữ liệu mượt mà như JavaScript/Python:
  ```php
  $activeEmails = collect($users)
      ->filter(fn($u) => $u->isActive())
      ->map(fn($u) => $u->email)
      ->values();
  ```
* **Các Helper Chuỗi & Mảng:** `Str::slug()`, `Str::uuid()`, `Arr::get()`, `tap()`, `value()`.

### 9.4. Tầng Đối Tượng HTTP (`symfony/http-foundation`)
Quản lý Header, Cookie, Session và tải lên tệp tin (File Upload) theo chuẩn quốc tế hướng đối tượng, bảo mật và tương thích tối đa.

---
*Tài liệu kỹ thuật hệ sinh thái — Đội ngũ Phát triển NodeX Studio.*
