# 📘 HƯỚNG DẪN CHI TIẾT ĐƯA GÓI `nodexstudiovn/installer` LÊN PACKAGIST VÀ CÀI ĐẶT GLOBAL

Tài liệu này hướng dẫn từng bước chi tiết từ việc chuẩn bị mã nguồn trên GitHub, đưa gói lên **Packagist.org**, cấu hình biến môi trường `PATH` đến việc sử dụng lệnh `nodex new` từ bất kỳ đâu trên hệ thống.

---

## 📌 BƯỚC 1: CHUẨN BỊ MÃ NGUỒN VÀ ĐẨY LÊN GITHUB

Để Composer và Packagist có thể nhận diện gói installer của bạn, thư mục `installer/` cần được lưu trữ trên một **Public Repository** trên GitHub.

### 1.1. Tạo Repository mới trên GitHub:
1. Đăng nhập tài khoản GitHub **`nodexstudiovn`**.
2. Tạo một Repository mới đặt tên là `installer`:
   * **Owner:** `nodexstudiovn`
   * **Repository name:** `installer`
   * **Visibility:** `Public`
   * **Add README / .gitignore / license:** Giữ nguyên Off/None.
3. Đường dẫn Repository nhận được: `https://github.com/nodexstudiovn/installer.git`

### 1.2. Đẩy thư mục `installer` lên Git:
Mở cửa sổ Terminal tại thư mục `installer` của dự án và chạy các lệnh sau:

```bash
cd installer

# Commit cập nhật tên gói nodexstudiovn/installer
git add .
git commit -m "fix: update package name to nodexstudiovn/installer"

# Đẩy code và tag v1.0.1 lên GitHub
git push origin main
git tag -a v1.0.1 -m "Release version 1.0.1"
git push origin v1.0.1
```

---

## 📌 BƯỚC 2: ĐĂNG KÝ VÀ NỘP GÓI LÊN PACKAGIST.ORG

### 2.1. Submit Package:
1. Truy cập trang web: [https://packagist.org/packages/submit](https://packagist.org/packages/submit).
2. Dán đường dẫn GitHub Repository của bạn: `https://github.com/nodexstudiovn/installer`
3. Nhấn nút **Check**. Packagist sẽ nhận diện thành công tên gói độc quyền là **`nodexstudiovn/installer`**.
4. Nhấn nút **Submit** để hoàn tất đăng ký.

---

## 📌 BƯỚC 3: THỰC THI CÀI ĐẶT GLOBAL VÀ KHỞI TẠO DỰ ÁN

Bây giờ bạn hoặc bất kỳ ai cũng có thể cài đặt và sử dụng:

```bash
# Cài đặt gói Global
composer global require nodexstudiovn/installer

# Khởi tạo dự án mới ở bất kỳ thư mục nào
nodex new my-portfolio-app
```

---
*Tài liệu được cập nhật tự động bởi Antigravity AI — NodeX Studio Installer Guide.*
