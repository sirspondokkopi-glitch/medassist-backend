# AuthorityController

**Controller:** App\Http\Controllers\Auth\AuthorityController  
**Base URL:** /api/master/authorities

> **Untuk Frontend:** Otoritas (Authority) adalah **peran/role** yang menentukan menu apa saja yang bisa diakses oleh seorang user.
> Alur umumnya: buat otoritas → lampirkan menu → assign otoritas ke user → saat user login, frontend membaca `authority.menus` untuk menentukan navigasi yang tampil.

---

## 1. index

**Method:** GET  
**Endpoint:** /api/master/authorities  
**Auth:** Bearer Token (wajib)

Mengambil daftar semua otoritas dengan dukungan pencarian dan paginasi.

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Query Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| search | string | Tidak | Filter berdasarkan nama otoritas |
| page | integer | Tidak | Nomor halaman (default: 1, per halaman: 20) |

### Response

#### Success (200)
```json
{
  "status": true,
  "message": "Berhasil mengambil data otoritas.",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Admin",
        "description": "Administrator sistem dengan akses penuh",
        "created_by": "superadmin",
        "updated_by": "superadmin",
        "deleted_at": null,
        "deleted_by": null,
        "created_at": "2026-05-23T00:00:00.000000Z",
        "updated_at": "2026-05-23T00:00:00.000000Z"
      }
    ],
    "last_page": 1,
    "per_page": 20,
    "total": 1
  }
}
```

> **Catatan:** Response `index` **tidak** menyertakan relasi `menus`. Gunakan endpoint `show` untuk mendapatkan detail beserta daftar menu.

#### Error (401)
```json
{
  "status": false,
  "message": "Unauthenticated."
}
```

---

## 2. store

**Method:** POST  
**Endpoint:** /api/master/authorities  
**Auth:** Bearer Token (wajib)

Membuat otoritas baru dan (opsional) langsung melampirkan menu-menu yang boleh diakses.

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |
| Content-Type | application/json | Ya |

### Body Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| name | string | Ya | Nama otoritas, unik, maks 100 karakter |
| description | string | Tidak | Deskripsi otoritas, maks 255 karakter |
| menu_ids | array of integer | Tidak | ID menu yang bisa diakses otoritas ini. Ambil dari endpoint `GET /api/master/menus` |

### Contoh Request Body
```json
{
  "name": "Perawat",
  "description": "Akses untuk perawat ruangan",
  "menu_ids": [1, 2, 3]
}
```

### Response

#### Success (201)
```json
{
  "status": true,
  "message": "Otoritas berhasil dibuat.",
  "data": {
    "id": 2,
    "name": "Perawat",
    "description": "Akses untuk perawat ruangan",
    "created_by": "superadmin",
    "updated_by": "superadmin",
    "deleted_at": null,
    "deleted_by": null,
    "created_at": "2026-05-23T00:00:00.000000Z",
    "updated_at": "2026-05-23T00:00:00.000000Z",
    "menus": [
      { "id": 1, "title_menu_id": 1, "name": "Dashboard", "url": "/dashboard", "sort_order": 1 },
      { "id": 2, "title_menu_id": 2, "name": "Authority", "url": "/master/otoritas", "sort_order": 1 }
    ]
  }
}
```

#### Error (422)
```json
{
  "status": false,
  "message": "Data yang dikirim tidak valid.",
  "errors": {
    "name": ["The name field is required."],
    "menu_ids.0": ["The selected menu_ids.0 is invalid."]
  }
}
```

#### Error (500)
```json
{
  "status": false,
  "message": "pesan error asli dari exception",
  "code": 0,
  "file": "/path/to/file.php",
  "line": 42
}
```

---

## 3. show

**Method:** GET  
**Endpoint:** /api/master/authorities/{id}  
**Auth:** Bearer Token (wajib)

Mengambil detail satu otoritas beserta **seluruh menu** yang terlampir padanya.

> **Untuk Frontend:** Gunakan endpoint ini saat membuka halaman edit otoritas, untuk mengetahui menu mana yang sudah dicentang (`menu_ids` yang aktif).

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Path Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| id | integer | Ya | ID otoritas |

### Response

#### Success (200)
```json
{
  "status": true,
  "message": "Berhasil mengambil detail otoritas.",
  "data": {
    "id": 1,
    "name": "Admin",
    "description": "Administrator sistem dengan akses penuh",
    "created_by": "superadmin",
    "updated_by": "superadmin",
    "deleted_at": null,
    "deleted_by": null,
    "created_at": "2026-05-23T00:00:00.000000Z",
    "updated_at": "2026-05-23T00:00:00.000000Z",
    "menus": [
      { "id": 1, "title_menu_id": 1, "name": "Dashboard", "url": "/dashboard", "sort_order": 1 },
      { "id": 2, "title_menu_id": 2, "name": "Authority", "url": "/master/otoritas", "sort_order": 1 },
      { "id": 3, "title_menu_id": 2, "name": "Menu", "url": "/master/menu", "sort_order": 2 }
    ]
  }
}
```

#### Error (404)
```json
{
  "status": false,
  "message": "Data tidak ditemukan."
}
```

---

## 4. update

**Method:** PUT / PATCH  
**Endpoint:** /api/master/authorities/{id}  
**Auth:** Bearer Token (wajib)

Memperbarui data otoritas. Field `menu_ids` bersifat **replace-all** (sync): daftar menu yang dikirim akan menggantikan seluruh menu yang sebelumnya terlampir.

> **Untuk Frontend:**
> - Kirim semua `menu_ids` yang dicentang user (bukan hanya yang berubah).
> - Kirim `menu_ids: []` (array kosong) untuk mencabut **semua** akses menu dari otoritas ini.
> - Jika tidak ingin mengubah menu sama sekali, **jangan sertakan** field `menu_ids` dalam request.

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |
| Content-Type | application/json | Ya |

### Path Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| id | integer | Ya | ID otoritas |

### Body Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| name | string | Tidak | Nama otoritas baru, unik |
| description | string | Tidak | Deskripsi otoritas |
| menu_ids | array of integer | Tidak | Daftar lengkap ID menu yang aktif. Menggantikan seluruh relasi menu sebelumnya |

### Contoh Request Body
```json
{
  "name": "Perawat Senior",
  "menu_ids": [1, 2, 3, 4]
}
```

### Response

#### Success (200)
```json
{
  "status": true,
  "message": "Otoritas berhasil diperbarui.",
  "data": {
    "id": 2,
    "name": "Perawat Senior",
    "description": "Akses untuk perawat ruangan",
    "menus": [
      { "id": 1, "title_menu_id": 1, "name": "Dashboard", "url": "/dashboard", "sort_order": 1 },
      { "id": 2, "title_menu_id": 2, "name": "Authority", "url": "/master/otoritas", "sort_order": 1 },
      { "id": 3, "title_menu_id": 2, "name": "Menu", "url": "/master/menu", "sort_order": 2 },
      { "id": 4, "title_menu_id": 2, "name": "User", "url": "/master/user", "sort_order": 3 }
    ]
  }
}
```

#### Error (404)
```json
{
  "status": false,
  "message": "Data tidak ditemukan."
}
```

#### Error (422)
```json
{
  "status": false,
  "message": "Data yang dikirim tidak valid.",
  "errors": {
    "name": ["The name has already been taken."]
  }
}
```

#### Error (500)
```json
{
  "status": false,
  "message": "pesan error asli dari exception",
  "code": 0,
  "file": "/path/to/file.php",
  "line": 42
}
```

---

## 5. destroy

**Method:** DELETE  
**Endpoint:** /api/master/authorities/{id}  
**Auth:** Bearer Token (wajib)

Menghapus otoritas secara soft delete. Data tidak benar-benar dihapus dari database — hanya ditandai sebagai dihapus (`deleted_at` dan `deleted_by` akan terisi).

> **Untuk Frontend:** Setelah berhasil hapus, otoritas ini tidak akan muncul lagi di daftar. User yang sebelumnya memiliki otoritas ini **tidak otomatis** kehilangan akses sampai token mereka expired atau mereka login ulang.

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Path Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| id | integer | Ya | ID otoritas |

### Response

#### Success (200)
```json
{
  "status": true,
  "message": "Otoritas berhasil dihapus.",
  "data": null
}
```

#### Error (404)
```json
{
  "status": false,
  "message": "Data tidak ditemukan."
}
```

#### Error (500)
```json
{
  "status": false,
  "message": "pesan error asli dari exception",
  "code": 0,
  "file": "/path/to/file.php",
  "line": 42
}
```

---

## Alur Penggunaan untuk Frontend

### 1. Inisialisasi halaman "Kelola Otoritas"

```
GET /api/master/authorities          → tampilkan daftar otoritas
GET /api/master/title-menus          → ambil semua title menu (untuk grouping checkbox)
GET /api/master/menus                → ambil semua menu (untuk form checkbox)
```

### 2. Buat otoritas baru

```
POST /api/master/authorities
Body: { name, description, menu_ids: [id yang dicentang] }
```

### 3. Edit otoritas

```
GET  /api/master/authorities/{id}    → prefill form, ketahui menu yang sudah aktif
PUT  /api/master/authorities/{id}    → simpan perubahan dengan seluruh menu_ids yang dicentang
```

### 4. Hapus otoritas

```
DELETE /api/master/authorities/{id}
```

### 5. Gunakan otoritas di User

Saat membuat/edit user, assign `authority_id` ke user melalui endpoint UserController.  
Setelah user login, frontend bisa membaca `user.authority.menus` dari respons `GET /api/auth/me` untuk membangun navigasi dinamis berdasarkan `title_menu_id`.
