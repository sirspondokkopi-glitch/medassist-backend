# AuthController

**Controller:** App\Http\Controllers\Auth\AuthController  
**Base URL:** /api/auth

---

## 1. Register

**Method:** POST  
**Endpoint:** /api/auth/register  
**Auth:** Bearer Token (wajib)

> Hanya admin yang sudah login yang dapat mendaftarkan akun baru. Endpoint ini tidak bisa diakses tanpa token.

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Body (JSON)
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| name | string | Ya | Nama lengkap |
| username | string | Ya | Username unik |
| email | string | Ya | Email unik, format valid |
| password | string | Ya | Minimal 8 karakter |
| password_confirmation | string | Ya | Harus sama dengan `password` |

### Response

#### Success (201)
```json
{
  "status": true,
  "message": "Registrasi berhasil.",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "created_at": "2026-05-22T08:00:00.000000Z",
      "updated_at": "2026-05-22T08:00:00.000000Z"
    },
    "token": "1|xxxxxxxxxxxxxxxx"
  }
}
```

#### Error (422)
```json
{
  "status": false,
  "message": "Data yang dikirim tidak valid.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

---

## 2. Login

**Method:** POST  
**Endpoint:** /api/auth/login  
**Auth:** Tidak diperlukan

### Body (JSON)
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| username | string | Ya | Username akun |
| password | string | Ya | Password akun |

### Response

#### Success (200)
```json
{
  "status": true,
  "message": "Login berhasil.",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "authority_id": 1,
      "authority": {
        "id": 1,
        "name": "Admin",
        "description": "Administrator sistem",
        "menus": [
          { "id": 1, "name": "Dashboard", "url": "/dashboard", "icon": "home", "sort_order": 0 },
          { "id": 2, "name": "Master Data", "url": "/master", "icon": "database", "sort_order": 1 }
        ]
      }
    },
    "token": "1|xxxxxxxxxxxxxxxx"
  }
}
```

> **Untuk Redux:** Simpan seluruh objek `data` ke store. Field `data.user.authority.menus` digunakan untuk membangun navigasi sidebar secara dinamis.

#### Error (401) — Kredensial salah
```json
{
  "status": false,
  "message": "Email/username atau password salah."
}
```

---

## 3. Logout

**Method:** POST  
**Endpoint:** /api/auth/logout  
**Auth:** Bearer Token (wajib)

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Response

#### Success (200)
```json
{
  "status": true,
  "message": "Logout berhasil."
}
```

#### Error (401)
```json
{
  "status": false,
  "message": "Unauthenticated. Silakan login terlebih dahulu."
}
```

---

## 4. Me

**Method:** GET  
**Endpoint:** /api/auth/me  
**Auth:** Bearer Token (wajib)

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Response

#### Success (200)
```json
{
  "status": true,
  "message": "Data user berhasil diambil.",
  "data": {
    "id": 1,
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "authority_id": 1,
    "authority": {
      "id": 1,
      "name": "Admin",
      "description": "Administrator sistem",
      "menus": [
        { "id": 1, "name": "Dashboard", "url": "/dashboard", "icon": "home", "sort_order": 0 },
        { "id": 2, "name": "Master Data", "url": "/master", "icon": "database", "sort_order": 1 }
      ]
    },
    "deleted_by": null,
    "deleted_at": null,
    "created_at": "2026-05-22T08:00:00.000000Z",
    "updated_at": "2026-05-22T08:00:00.000000Z"
  }
}
```

> **Untuk Redux:** Panggil endpoint ini saat aplikasi pertama kali dimuat (page refresh) untuk merehydrate state. Simpan hasilnya ke Redux store yang sama dengan saat login.

---

## 5. Update Profile

**Method:** PUT  
**Endpoint:** /api/auth/profile  
**Auth:** Bearer Token (wajib)

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Body (JSON)
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| name | string | Ya | Nama lengkap |
| username | string | Ya | Username unik (kecuali milik sendiri) |
| email | string | Ya | Email unik (kecuali milik sendiri) |

### Response

#### Success (200)
```json
{
  "status": true,
  "message": "Profil berhasil diperbarui.",
  "data": {
    "id": 1,
    "name": "John Updated",
    "username": "johnupdated",
    "email": "johnupdated@example.com",
    "updated_at": "2026-05-22T09:00:00.000000Z"
  }
}
```

---

## 6. Change Password

**Method:** PUT  
**Endpoint:** /api/auth/change-password  
**Auth:** Bearer Token (wajib)

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Body (JSON)
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| current_password | string | Ya | Password saat ini |
| password | string | Ya | Password baru, minimal 8 karakter |
| password_confirmation | string | Ya | Harus sama dengan `password` |

### Response

#### Success (200)
```json
{
  "status": true,
  "message": "Password berhasil diubah. Silakan login ulang.",
  "data": {
    "token": "2|xxxxxxxxxxxxxxxx"
  }
}
```

> Semua token lama dihapus. Token baru langsung dikembalikan agar tidak perlu login ulang manual.

#### Error (422) — Password lama salah
```json
{
  "status": false,
  "message": "Password saat ini tidak sesuai."
}
```
