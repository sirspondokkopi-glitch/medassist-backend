# changePassword

**Method:** PUT
**Endpoint:** `/api/auth/change-password`
**Controller:** `App\Http\Controllers\Auth\AuthController@changePassword`
**Auth:** Bearer Token (wajib)

## Request

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

## Response

### Success (200)
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

### Error (422) — Password lama salah
```json
{
  "status": false,
  "message": "Password saat ini tidak sesuai."
}
```
