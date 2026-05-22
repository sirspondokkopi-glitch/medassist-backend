# logout

**Method:** POST
**Endpoint:** `/api/auth/logout`
**Controller:** `App\Http\Controllers\Auth\AuthController@logout`
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
  "message": "Logout berhasil."
}
```

### Error (401)
```json
{
  "status": false,
  "message": "Unauthenticated. Silakan login terlebih dahulu."
}
```
