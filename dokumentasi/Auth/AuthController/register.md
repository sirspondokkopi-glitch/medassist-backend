# register

**Method:** POST
**Endpoint:** `/api/auth/register`
**Controller:** `App\Http\Controllers\Auth\AuthController@register`
**Auth:** Tidak diperlukan

## Request

### Body (JSON)
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| name | string | Ya | Nama lengkap |
| username | string | Ya | Username unik |
| email | string | Ya | Email unik, format valid |
| password | string | Ya | Minimal 8 karakter |
| password_confirmation | string | Ya | Harus sama dengan `password` |

## Response

### Success (201)
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

### Error (422)
```json
{
  "status": false,
  "message": "Data yang dikirim tidak valid.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```
