# 📘 HƯỚNG DẪN CHI TIẾT ĐƯA GÓI `nodexstudiovn/installer` LÊN PACKAGIST VÀ CÀI ĐẶT GLOBAL

Tài liệu này hướng dẫn chi tiết quy trình đưa gói công cụ dòng lệnh **NodeX Studio Installer** lên kho **Packagist.org**, cấu hình biến môi trường `PATH` và sử dụng lệnh `nodex new` từ bất kỳ thư mục nào trên máy tính.

---

## 📌 BƯỚC 1: QUẢN LÝ MÃ NGUỒN TRÊN GITHUB

Gói installer được lưu trữ tại kho lưu trữ công khai trên GitHub:

* **Repository:** `https://github.com/nodexstudiovn/installer.git`
* **Nhánh chính:** `main`
* **Phiên bản phát hành:** `v1.0.2`

Lệnh đồng bộ và gắn thẻ phiên bản mới:
```bash
cd installer
git add .
git commit -m "feat: release installer version v1.0.2"
git push origin main
git tag -a v1.0.2 -m "Release version 1.0.2"
git push origin v1.0.2
```

---

## 📌 BƯỚC 2: ĐĂNG KÝ VÀ QUẢN LÝ TRÊN PACKAGIST.ORG

1. Truy cập: [https://packagist.org/packages/submit](https://packagist.org/packages/submit).
2. Nhập URL: `https://github.com/nodexstudiovn/installer`
3. Nhấn **Check** để hệ thống tự động phân tích file `composer.json` và xác nhận định danh gói: **`nodexstudiovn/installer`**.
4. Nhấn **Submit** để hoàn tất công bố gói trên phạm vi toàn cầu.

---

## 📌 BƯỚC 3: CẤU HÌNH BIẾN MÔI TRƯỜNG PATH (WINDOWS)

Để hệ điều hành nhận diện được lệnh `nodex` từ mọi cửa sổ dòng lệnh:
1. Đảm bảo đường dẫn sau đã nằm trong biến môi trường `Path` của người dùng:
   ```text
   %USERPROFILE%\AppData\Roaming\Composer\vendor\bin
   ```
2. Nếu mở PowerShell mới mà chưa nhận diện được lệnh, chạy một lần:
   ```powershell
   $env:Path += ";$env:APPDATA\Composer\vendor\bin"
   ```

---

## 📌 BƯỚC 4: CÀI ĐẶT TOÀN CỤC & TẠO DỰ ÁN MỚI

### 4.1. Cài đặt toàn cục (Global Require):
```bash
composer global require nodexstudiovn/installer
```

### 4.2. Khởi tạo dự án mới:
Mở Terminal ở thư mục bất kỳ và gõ:
```bash
nodex new my-new-project
```

Tiến trình tự động thực hiện:
* Tải bản sao bộ khung sạch mới nhất từ `https://github.com/nodexstudiovn/framework.git`.
* Dọn dẹp cấu hình Git và tự động khởi tạo file môi trường `.env`.
* Chạy `composer install` cài đặt đầy đủ các phụ thuộc hạt nhân.

---
*Tài liệu kỹ thuật — Đội ngũ Phát triển NodeX Studio.*
