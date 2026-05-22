# me

**Method:** GET
**Endpoint:** `/api/auth/me`
**Controller:** `App\Http\Controllers\Auth\AuthController@me`
**Auth:** Bearer Token (wajib)

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

## Response

### Success (200)
```json
{
  "status": true,
  "message": "Data user berhasil diambil.",
  "data": {
    "id": 1,
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "email_verified_at": null,
    "deleted_by": null,
    "deleted_at": null,
    "created_at": "2026-05-22T08:00:00.000000Z",
    "updated_at": "2026-05-22T08:00:00.000000Z"
  }
}
```
