# 📄 01. CHUẨN MỰC CHUNG & MÔI TRƯỜNG (NODEX STUDIO v1.1.0)

## 1. Môi trường Thực thi
- **PHP Version:** PHP 8.4+
- **Strict Mode:** `declare(strict_types=1);` bắt buộc ở dòng đầu tiên của mọi file `.php`.
- **Múi giờ:** `Asia/Ho_Chi_Minh`
- **Framework Version:** NodeX Studio v1.1.0

## 2. Bản quyền & Signature
Mọi file PHP do nhân sự hoặc AI tạo mới/chỉnh sửa đều phải gắn DocBlock bản quyền ở đầu file:

```php
<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Module: [Tên Module / Action / Class]
 */

namespace App\Actions;

// Code implementation...
```

## 3. Strict View Hygiene (Vệ sinh View Khắt khe)
- **100% HTML Thuần:** File View Blade chỉ chứa cấu trúc HTML và các chỉ thị rẽ nhánh/vòng lặp Blade cơ bản.
- **CẤM TUYỆT ĐỐI:**
  - Cấm viết mã PHP thuần (`<?php`, `<?=`, `<%`, `@php`). 100% Business Logic phải nằm trong các Action class (`app/Actions/`).
  - Cấm viết inline `<style>` hoặc `<script>` trong sub-views.
  - Cấm sử dụng các thuộc tính inline handler (`onclick=`, `onload=`, `style=`).
- **Xử lý vi phạm:** Bộ quét `NodeX\View\ViewHygieneChecker` ở tầng ứng dụng sẽ tự động chặn và ném ngoại lệ `ViewHygieneViolationException` dừng chương trình lập tức nếu phát hiện vi phạm.

## 4. Quy định Comment
- **Ngôn ngữ:** 100% bằng Tiếng Việt.
- **Mục đích:** Giải thích lý do ("Tại sao làm vậy") đối với các logic tính toán số dư, xử lý hoa hồng, khoá hàng, phân chia doanh thu.
- **DocBlock:** Bắt buộc viết DocBlock cho hàm/hành động chính để IDE gợi ý type-hint chuẩn xác.
