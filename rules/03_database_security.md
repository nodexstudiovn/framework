# 💾 03. DATABASE & BẢO MẬT GIAO DỊCH (NODEX STUDIO v1.1.0)

## 1. Eloquent ORM & Query Optimization
- Tất cả các truy vấn danh sách có quan hệ con BẮT BUỘC dùng Eager Loading `with()` để chống N+1 Query:
  ```php
  // ĐÚNG:
  $orders = Order::with(['user', 'items'])->where('status', 'completed')->get();

  // SAI:
  $orders = Order::where('status', 'completed')->get();
  // foreach ($orders as $order) { $order->user->name; } // N+1 Bug!
  ```

## 2. Bảo mật Tài chính & Giao dịch Số dư (Concurrency)
- **Database Transactions:** Mọi hành động làm thay đổi ví, nạp/trừ tiền, hoàn tiền, chiết khấu hoa hồng BẮT BUỘC bọc trong `DB::transaction()`.
- **Pessimistic Locking (`lockForUpdate()`):** Khi lấy bản ghi người dùng để trừ/cộng tiền hoặc lấy bản ghi đơn hàng để tranh chấp nhận đơn, BẮT BUỘC gọi `lockForUpdate()` để chống lỗi **Race Condition**.

```php
use Illuminate\Database\Capsule\Manager as DB;

DB::transaction(function () use ($userId, $amount) {
    $user = User::where('id', $userId)->lockForUpdate()->firstOrFail();
    
    if ($user->balance < $amount) {
        throw new \Exception('Số dư không đủ để thực hiện giao dịch.');
    }

    $user->decrement('balance', $amount);
});
```

## 3. Quản lý Models & Traits
- Nếu file Model vượt quá **300 dòng**, phải phân rã logic thành các Traits đặt tại `app/Models/Traits/` (Ví dụ: `UserRelations.php`, `UserAttributes.php`, `UserScopes.php`).
