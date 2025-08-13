# API Quản lý Môn học (MonHoc)

## Tổng quan

API này cung cấp các chức năng CRUD đầy đủ cho việc quản lý môn học trong hệ thống AcademicService.

## Base URL

```
http://localhost:8000/api
```

## Authentication

Tất cả các API endpoints đều yêu cầu JWT token trong header:

```
Authorization: Bearer {JWT_TOKEN}
```

## Endpoints

### 1. Lấy danh sách tất cả môn học

```http
GET /monhocs
```

**Response:**

```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "ten_mon": "Lập trình C",
            "so_tin_chi": 3,
            "khoa_id": 1,
            "giao_vien_id": 1,
            "created_at": "2025-01-15T10:00:00.000000Z",
            "updated_at": "2025-01-15T10:00:00.000000Z",
            "khoa": {
                "id": 1,
                "ten_khoa": "Công nghệ thông tin"
            },
            "giao_vien": {
                "id": 1,
                "ho_ten": "Nguyễn Văn A"
            }
        }
    ]
}
```

### 2. Lấy thông tin môn học theo ID

```http
GET /monhocs/{id}
```

**Response:**

```json
{
    "status": "success",
    "data": {
        "id": 1,
        "ten_mon": "Lập trình C",
        "so_tin_chi": 3,
        "khoa_id": 1,
        "giao_vien_id": 1,
        "khoa": {...},
        "giao_vien": {...}
    }
}
```

### 3. Tạo môn học mới

```http
POST /monhocs
```

**Request Body:**

```json
{
    "ten_mon": "Lập trình Java",
    "so_tin_chi": 4,
    "khoa_id": 1,
    "giao_vien_id": 1
}
```

**Response:**

```json
{
    "status": "success",
    "message": "Tạo môn học thành công",
    "data": {
        "id": 2,
        "ten_mon": "Lập trình Java",
        "so_tin_chi": 4,
        "khoa_id": 1,
        "giao_vien_id": 1
    }
}
```

### 4. Cập nhật môn học

```http
PUT /monhocs/{id}
```

**Request Body:**

```json
{
    "ten_mon": "Lập trình Java nâng cao",
    "so_tin_chi": 4,
    "khoa_id": 1,
    "giao_vien_id": 1
}
```

**Response:**

```json
{
    "status": "success",
    "message": "Cập nhật môn học thành công",
    "data": {...}
}
```

### 5. Xóa môn học

```http
DELETE /monhocs/{id}
```

**Response:**

```json
{
    "status": "success",
    "message": "Xóa môn học thành công"
}
```

### 6. Lấy môn học theo khoa

```http
GET /monhocs-khoa/{khoaId}
```

**Response:**

```json
{
    "status": "success",
    "data": [...]
}
```

### 7. Lấy môn học theo giáo viên

```http
GET /monhocs-giaovien/{giaoVienId}
```

**Response:**

```json
{
    "status": "success",
    "data": [...]
}
```

### 8. Tìm kiếm môn học

```http
GET /monhocs-search?keyword={keyword}
```

**Response:**

```json
{
    "status": "success",
    "data": [...]
}
```

## Validation Rules

### Tạo/Cập nhật môn học:

-   `ten_mon`: Bắt buộc, string, max 255 ký tự, unique
-   `so_tin_chi`: Bắt buộc, integer, min 1, max 20
-   `khoa_id`: Bắt buộc, phải tồn tại trong bảng khoas
-   `giao_vien_id`: Bắt buộc, phải tồn tại trong bảng giao_viens

## Error Responses

### 404 - Không tìm thấy

```json
{
    "status": "error",
    "message": "Không tìm thấy môn học"
}
```

### 400 - Lỗi validation hoặc business logic

```json
{
    "status": "error",
    "message": "Không thể xóa môn học đang được sử dụng"
}
```

### 422 - Validation errors

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "ten_mon": ["Tên môn học không được để trống"],
        "so_tin_chi": ["Số tín chỉ phải là số nguyên"]
    }
}
```

## Business Rules

1. **Xóa môn học**: Chỉ có thể xóa khi môn học không có lịch học hoặc điểm nào
2. **Tên môn học**: Phải unique trong toàn bộ hệ thống
3. **Số tín chỉ**: Phải từ 1-20 tín chỉ
4. **Khoa và Giáo viên**: Phải tồn tại trước khi tạo môn học

## Relationships

-   **Khoa**: Mỗi môn học thuộc về một khoa
-   **Giáo viên**: Mỗi môn học có một giáo viên phụ trách
-   **Lịch học**: Một môn học có thể có nhiều lịch học
-   **Điểm**: Một môn học có thể có nhiều điểm của sinh viên

## Testing

Chạy test:

```bash
php artisan test tests/Feature/MonHocTest.php
```

## Seeding

Tạo dữ liệu mẫu:

```bash
php artisan db:seed --class=MonHocSeeder
```
