# store

**Method:** POST
**Endpoint:** `/api/master/instruments`
**Controller:** `App\Http\Controllers\Master\InstrumentController@store`

## Request

### Body (JSON)
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| name | string | Ya | Nama instrumen |

> `code` di-generate otomatis oleh backend (4 huruf kapital acak, unik). Tidak perlu dikirim dari client.

## Response

### Success (201)
```json
{
  "status": true,
  "message": "Instrumen berhasil ditambahkan.",
  "data": {
    "id": 1,
    "code": "ABCD",
    "name": "Stetoskop",
    "created_by": "Admin",
    "updated_by": "Admin",
    "deleted_at": null,
    "deleted_by": null,
    "created_at": "2026-05-21T09:00:00.000000Z",
    "updated_at": "2026-05-21T09:00:00.000000Z"
  }
}
```

### Error (422)
```json
{
  "message": "The code has already been taken.",
  "errors": {
    "code": ["The code has already been taken."]
  }
}
```
