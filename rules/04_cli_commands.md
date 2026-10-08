# 💻 04. HƯỚNG DẪN SỬ DỤNG NODEX CLI (`php nodex`)

## 📌 Tổng quan
NodeX CLI (`php nodex`) là bộ công cụ dòng lệnh được phát triển riêng cho **NodeX Studio Framework v1.1.0**.

---

## 1. 🗄️ Database & Migrations
| Lệnh CLI | Mô tả |
| :--- | :--- |
| `php nodex migrate` | Khởi chạy tất cả các file migration chưa được thực thi trong `database/migrations/`. |
| `php nodex migrate:rollback` | Hoàn tác (rollback) đợt (batch) migration gần nhất. |
| `php nodex migrate:status` | Hiển thị bảng trạng thái các file migration (Đã chạy / Chưa chạy). |

---

## 2. ⚡ Sinh Mã Nguồn Tự Động (Code Generators)
| Lệnh CLI | Thư mục đầu ra | Mô tả |
| :--- | :--- | :--- |
| `php nodex make:action <Name>` | `app/Actions/` | Tạo một Action Class mới tuân thủ Action Pattern (tự động gắn hậu tố `Action` & chữ ký bản quyền NodeX Studio). |
| `php nodex make:migration <Name>` | `database/migrations/` | Tạo file migration mới kèm mốc thời gian timestamp. |
| `php nodex make:model <Name>` | `app/Models/` | Tạo Eloquent Model class kế thừa Base Model NodeX Studio. |
| `php nodex make:controller <Name>` | `app/Controllers/` | Tạo Controller class kế thừa Base Controller NodeX Studio. |
| `php nodex make:rule <Name>` | `app/Rules/` | Tạo Validation Rule class triển khai Rule Interface. |

### Ví dụ sử dụng:
```bash
# Tạo Action xử lý thanh toán
php nodex make:action ProcessOrderPayment

# Tạo Migration bảng sản phẩm
php nodex make:migration create_products_table

# Tạo Model Product
php nodex make:model Product

# Tạo Controller quản lý sản phẩm
php nodex make:controller ProductController

# Tạo Rule kiểm tra mã giảm giá
php nodex make:rule ValidCoupon
```

---

## 3. ⚙️ Lệnh Hệ Thống, Queue Worker & Testing
| Lệnh CLI | Mô tả |
| :--- | :--- |
| `php nodex serve` *(hoặc `php nodex dev`)* | Khởi chạy máy chủ local phát triển (Mặc định: `http://127.0.0.1:8000`). |
| `php nodex queue:work` | Khởi chạy tiến trình Queue Worker xử lý công việc ngầm từ Redis/File Queue. |
| `php nodex test` | Khởi chạy bộ kiểm thử tự động (PHPUnit / Pest / NodeX Core Self-Check). |
| `php nodex cache:clear` | Xóa toàn bộ bộ nhớ đệm ứng dụng và file Blade template đã biên dịch trong `storage/cache/views`. |
| `php nodex route:list` *(hoặc `php nodex routes`)* | Liệt kê tất cả các route đã đăng ký (Web & API) dạng bảng trực quan. |
| `php nodex version` *(hoặc `-v`)* | Hiển thị thông tin phiên bản Framework & PHP. |
| `php nodex help` | Hiển thị trợ giúp danh sách lệnh. |
