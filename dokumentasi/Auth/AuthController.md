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
        "name": "Administrator",
        "description": "Akses penuh ke seluruh fitur sistem",
        "menus": [
          {
            "id": 1,
            "title_menu_id": 1,
            "parent_id": null,
            "name": "Dashboard",
            "url": "/dashboard",
            "sort_order": 1
          },
          {
            "id": 2,
            "title_menu_id": 2,
            "parent_id": null,
            "name": "Authority",
            "url": "/master/otoritas",
            "sort_order": 1
          },
          {
            "id": 3,
            "title_menu_id": 2,
            "parent_id": null,
            "name": "Menu",
            "url": "/master/menu",
            "sort_order": 2
          },
          {
            "id": 4,
            "title_menu_id": 2,
            "parent_id": null,
            "name": "User",
            "url": "/master/user",
            "sort_order": 3
          }
        ]
      }
    },
    "token": "1|xxxxxxxxxxxxxxxx"
  }
}
```

> **Catatan penting untuk Frontend:**
>
> Array `menus` dikembalikan dalam bentuk **flat list**. Setiap item memiliki field `title_menu_id` yang menunjuk ke group/section sidebar-nya (dari tabel `title_menuses`).
>
> Untuk membangun sidebar, kelompokkan menu berdasarkan `title_menu_id`. Ambil data title group dari endpoint `GET /api/master/title-menus`. Contoh fungsi JavaScript:
>
> ```js
> function groupMenusByTitle(titleMenus, menus) {
>   return titleMenus.map(title => ({
>     ...title,
>     menus: menus.filter(m => m.title_menu_id === title.id),
>   }));
> }
>
> // Penggunaan setelah login:
> const sidebar = groupMenusByTitle(titleMenus, data.user.authority.menus);
> // Simpan sidebar ke Redux store untuk render navigasi
> ```

#### Error (401) — Kredensial salah
```json
{
  "status": false,
  "message": "Email/username atau password salah."
}
```

#### Error (403) — Akun dinonaktifkan
```json
{
  "status": false,
  "message": "Akun Anda telah dinonaktifkan."
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
  "message": "Unauthenticated."
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
      "name": "Administrator",
      "description": "Akses penuh ke seluruh fitur sistem",
      "menus": [
        {
          "id": 1,
          "title_menu_id": 1,
          "parent_id": null,
          "name": "Dashboard",
          "url": "/dashboard",
          "sort_order": 1
        },
        {
          "id": 2,
          "title_menu_id": 2,
          "parent_id": null,
          "name": "Authority",
          "url": "/master/otoritas",
          "sort_order": 1
        },
        {
          "id": 3,
          "title_menu_id": 2,
          "parent_id": null,
          "name": "Menu",
          "url": "/master/menu",
          "sort_order": 2
        },
        {
          "id": 4,
          "title_menu_id": 2,
          "parent_id": null,
          "name": "User",
          "url": "/master/user",
          "sort_order": 3
        }
      ]
    },
    "deleted_by": null,
    "deleted_at": null,
    "created_at": "2026-05-22T08:00:00.000000Z",
    "updated_at": "2026-05-22T08:00:00.000000Z"
  }
}
```

> **Untuk Redux:** Panggil endpoint ini saat aplikasi pertama kali dimuat (page refresh) untuk merehydrate state. Struktur `menus` sama dengan response login — flat list dengan `title_menu_id`. Gunakan fungsi `groupMenusByTitle` yang sama untuk rebuild sidebar.

---

## 5. Update (Profile + Password)

**Method:** PUT  
**Endpoint:** /api/auth/update  
**Auth:** Bearer Token (wajib)

> Endpoint tunggal untuk memperbarui nama, username, email, dan password sekaligus. Field password bersifat opsional — jika tidak dikirim, password tidak akan berubah.

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
| password | string | Tidak | Password baru, minimal 8 karakter |
| password_confirmation | string | Kondisional | Wajib jika `password` diisi |

### Response

#### Success (200)
```json
{
  "status": true,
  "message": "Data berhasil diperbarui.",
  "data": {
    "id": 1,
    "name": "John Updated",
    "username": "johnupdated",
    "email": "johnupdated@example.com",
    "updated_at": "2026-05-26T09:00:00.000000Z"
  }
}
```

> Token lama **tetap aktif** setelah update melalui endpoint ini. Jika ingin mencabut semua sesi lain, gunakan endpoint `PUT /api/auth/change-password`.

#### Error (422)
```json
{
  "status": false,
  "message": "Data yang dikirim tidak valid.",
  "errors": {
    "username": ["The username has already been taken."]
  }
}
```

---

## 6. Update Profile

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

#### Error (422)
```json
{
  "status": false,
  "message": "Data yang dikirim tidak valid.",
  "errors": {
    "username": ["The username has already been taken."]
  }
}
```

---

## 7. Change Password

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
