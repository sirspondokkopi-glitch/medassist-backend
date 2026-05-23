# Store

**Method:** POST  
**Endpoint:** /api/master/users  
**Controller:** App\Http\Controllers\Master\UserController@store

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |
| Content-Type | application/json | Ya |

### Body Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| name | string | Ya | Nama lengkap user |
| username | string | Ya | Username unik, maks 100 karakter |
| email | string | Ya | Email unik |
| no_telephone | string | Tidak | Nomor telepon, maks 20 karakter |
| authority_id | integer | Tidak | ID otoritas user |
| password | string | Ya | Minimal 8 karakter |
| password_confirmation | string | Ya | Konfirmasi password |

## Response

### Success (201)
```json
{
  "status": true,
  "message": "User berhasil dibuat.",
  "data": {
    "id": 1,
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "no_telephone": "081234567890",
    "authority_id": 1,
    "authority": { "id": 1, "name": "Admin" }
  }
}
```

### Error (422)
```json
{
  "status": false,
  "message": "Data yang dikirim tidak valid.",
  "errors": {
    "username": ["The username has already been taken."]
  }
}
```

### Error (500)
```json
{
  "status": false,
  "message": "pesan error asli dari exception",
  "code": 0,
  "file": "/path/to/file.php",
  "line": 42
}
```
