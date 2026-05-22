# login

**Method:** POST
**Endpoint:** `/api/auth/login`
**Controller:** `App\Http\Controllers\Auth\AuthController@login`
**Auth:** Tidak diperlukan

## Request

### Body (JSON)
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| username | string | Ya | Username akun |
| password | string | Ya | Password akun |

## Response

### Success (200)
```json
{
  "status": true,
  "message": "Login berhasil.",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com"
    },
    "token": "1|xxxxxxxxxxxxxxxx"
  }
}
```

### Error (401) — Kredensial salah
```json
{
  "status": false,
  "message": "Email/username atau password salah."
}
```

### Error (403) — Akun dinonaktifkan
```json
{
  "status": false,
  "message": "Akun Anda telah dinonaktifkan."
}
```
