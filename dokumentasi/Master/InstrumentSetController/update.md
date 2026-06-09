# update

**Method:** PUT / PATCH  
**Endpoint:** `/api/master/instrument-sets/{instrument_set}`  
**Controller:** `App\Http\Controllers\Master\InstrumentSetController@update`  
**Auth:** Bearer Token (wajib)

Memperbarui data set. Bila `items` dikirim, **keanggotaan unit disinkronkan** dengan daftar tersebut:
unit yang tidak lagi ada akan dilepas (soft delete), unit baru ditambahkan. Bila `items` tidak dikirim, keanggotaan tidak diubah.

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |
| Content-Type | application/json | Ya |

### Body Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| name | string | Tidak | Nama set |
| room_id | integer | Tidak | Ruangan lokasi |
| status | string | Tidak | `tersedia`, `dipinjam`, `sterilisasi`, `dikembalikan` |
| note | string | Tidak | Catatan |
| items | array | Tidak | Daftar lengkap unit anggota (sinkronisasi) |
| items[].instrument_stock_id | integer | Ya (jika `items` ada) | Unit fisik (unik dalam set) |

### Contoh — Ubah anggota set
```json
{
  "items": [
    { "instrument_stock_id": 1 },
    { "instrument_stock_id": 3 }
  ]
}
```

## Response

### Success (200)
```json
{
  "status": true,
  "message": "Set instrumen berhasil diperbarui.",
  "data": {
    "id": 1,
    "code": "SET-001",
    "name": "Set Bedah Minor",
    "status": "tersedia",
    "items": [
      { "id": 1, "instrument_stock_id": 1, "instrument_stock": { "id": 1, "code": "ZHVQ-001" } },
      { "id": 3, "instrument_stock_id": 3, "instrument_stock": { "id": 3, "code": "ZHVQ-003" } }
    ]
  }
}
```

### Error (422)
```json
{
  "status": false,
  "message": "Data yang dikirim tidak valid.",
  "errors": { "items.0.instrument_stock_id": ["The selected instrument stock id is invalid."] }
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
