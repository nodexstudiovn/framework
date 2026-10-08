# 🏛️ 02. KIẾN TRÚC BACKEND & ACTION PATTERN (NODEX STUDIO v1.1.0)

## 1. Hybrid Action Pattern
Trong NodeX Studio Framework, Controller không trực tiếp xử lý nghiệp vụ phức tạp mà ủy quyền toàn bộ cho các **Action Classes**.

### Cấu trúc Thư mục:
- `app/Controllers/`: Tiếp nhận HTTP Request, gọi Action, trả về HTTP Response.
- `app/Actions/`: Chứa 100% Business Logic của hệ thống. Mỗi Action thực hiện DUY NHẤT một nhiệm vụ nghiệp vụ (Single Responsibility Principle).

### Mẫu minh họa Action Class:
```php
<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Action xử lý giao nạp/nhận đơn hàng an toàn với Pessimistic Locking.
 */
class ProcessOrderAssignmentAction
{
    public function execute(User $user, int $orderId): Order
    {
        return DB::transaction(function () use ($user, $orderId) {
            $order = Order::where('id', $orderId)->lockForUpdate()->firstOrFail();

            if ($order->status !== 'pending') {
                throw new \DomainException('Đơn hàng đã được xử lý bởi nhân sự khác.');
            }

            $order->update([
                'assigned_to' => $user->id,
                'status' => 'processing',
                'assigned_at' => date('Y-m-d H:i:s'),
            ]);

            return $order;
        });
    }
}
```

## 2. Validation Standard
- Sử dụng các lớp Rule tại `app/Rules/` hoặc Validator Helper để xác thực đầu vào.
- Tuyệt đối không viết mã validation thủ công kéo dài rối rắm trong Controller.
