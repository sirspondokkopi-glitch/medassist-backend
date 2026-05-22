# store

**Method:** POST
**Endpoint:** `/api/master/rooms`
**Controller:** `App\Http\Controllers\Master\RoomController@store`

## Request

### Body (JSON)
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| name | string | Ya | Nama ruangan, harus unik |

## Response

### Success (201)
```json
{
  "status": true,
  "message": "Ruangan berhasil ditambahkan.",
  "data": {
    "id": 1,
    "name": "Ruang CSSD",
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
  "message": "The name has already been taken.",
  "errors": {
    "name": ["The name has already been taken."]
  }
}
```
