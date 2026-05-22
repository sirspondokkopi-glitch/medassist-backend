# updateProfile

**Method:** PUT
**Endpoint:** `/api/auth/profile`
**Controller:** `App\Http\Controllers\Auth\AuthController@updateProfile`
**Auth:** Bearer Token (wajib)

## Request

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

## Response

### Success (200)
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
