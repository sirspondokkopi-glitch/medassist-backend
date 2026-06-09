# show

**Method:** GET  
**Endpoint:** `/api/master/instrument-sets/{instrument_set}`  
**Controller:** `App\Http\Controllers\Master\InstrumentSetController@show`  
**Auth:** Bearer Token (wajib)

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Path Parameters
| Parameter | Type | Keterangan |
|-----------|------|------------|
| instrument_set | integer | ID set instrumen |

## Response

### Success (200)
```json
{
  "status": true,
  "message": "Detail set instrumen berhasil diambil.",
  "data": {
    "id": 1,
    "code": "SET-001",
    "name": "Set Bedah Minor",
    "room_id": 1,
    "status": "tersedia",
    "note": null,
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

### Error (404)
```json
{
  "status": false,
  "message": "Data tidak ditemukan."
}
```
