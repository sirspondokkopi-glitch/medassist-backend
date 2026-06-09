# store

**Method:** POST  
**Endpoint:** `/api/master/instrument-sets`  
**Controller:** `App\Http\Controllers\Master\InstrumentSetController@store`  
**Auth:** Bearer Token (wajib)

Membuat set/tray instrumen baru. Daftar unit (`items`) opsional dan bisa ditambahkan/diubah kemudian via update.

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |
| Content-Type | application/json | Ya |

### Body Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| name | string | Ya | Nama set, mis. "Set Bedah Minor" |
| room_id | integer | Tidak | Ruangan lokasi set |
| status | string | Tidak | `tersedia` (default), `dipinjam`, `sterilisasi`, `dikembalikan` |
| note | string | Tidak | Catatan |
| items | array | Tidak | Daftar unit anggota set |
| items[].instrument_stock_id | integer | Ya (jika `items` ada) | Unit fisik (unik dalam set), harus ada di instrument_stocks |

### Contoh Body
```json
{
  "name": "Set Bedah Minor",
  "room_id": 1,
  "items": [
    { "instrument_stock_id": 1 },
    { "instrument_stock_id": 2 }
  ]
}
```

## Response

### Success (201)
```json
{
  "status": true,
  "message": "Set instrumen berhasil dibuat.",
  "data": {
    "id": 1,
    "code": "SET-001",
    "name": "Set Bedah Minor",
    "room_id": 1,
    "status": "tersedia",
    "room": { "id": 1, "code": "JWGL", "name": "poli umum" },
    "items": [
      {
        "id": 1,
        "instrument_set_id": 1,
        "instrument_stock_id": 1,
        "instrument_stock": {
          "id": 1, "code": "ZHVQ-001", "status": "tersedia",
          "instrument": { "id": 1, "code": "ZHVQ", "name": "stetoskop" }
        }
      }
    ]
  }
}
```

### Error (422)
```json
{
  "status": false,
  "message": "Data yang dikirim tidak valid.",
  "errors": { "name": ["The name field is required."] }
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
