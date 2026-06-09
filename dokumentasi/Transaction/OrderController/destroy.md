# destroy

**Method:** DELETE  
**Endpoint:** `/api/master/orders/{order}`  
**Controller:** `App\Http\Controllers\Transaction\OrderController@destroy`  
**Auth:** Bearer Token (wajib)

Soft delete order (mengisi `deleted_at` + `deleted_by`). Data tidak hilang permanen.

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Path Parameters
| Parameter | Type | Keterangan |
|-----------|------|------------|
| order | integer | ID order |

## Response

### Success (200)
```json
{
  "status": true,
  "message": "Peminjaman berhasil dihapus."
}
```

### Error (404)
```json
{
  "status": false,
  "message": "Data tidak ditemukan."
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
