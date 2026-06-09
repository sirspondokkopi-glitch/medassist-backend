# show

**Method:** GET  
**Endpoint:** `/api/master/orders/{order}`  
**Controller:** `App\Http\Controllers\Transaction\OrderController@show`  
**Auth:** Bearer Token (wajib)

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
  "message": "Detail peminjaman berhasil diambil.",
  "data": {
    "id": 1,
    "code": "ORD-001",
    "room_id": 1,
    "user_id": 1,
    "order_date": "2026-06-08",
    "return_plan_date": "2026-06-10",
    "return_actual_date": null,
    "status": "dipinjam",
    "note": "Untuk operasi minor",
    "room": { "id": 1, "code": "JWGL", "name": "poli umum" },
    "user": { "id": 1, "name": "Administrator", "username": "administrator" },
    "items": [
      {
        "id": 1,
        "order_id": 1,
        "instrument_stock_id": 1,
        "condition_out_id": 1,
        "condition_in_id": null,
        "is_returned": false,
        "instrument_stock": {
          "id": 1, "code": "ZHVQ-001", "status": "dipinjam",
          "instrument": { "id": 1, "code": "ZHVQ", "name": "stetoskop" }
        },
        "condition_out": { "id": 1, "name": "Baik" },
        "condition_in": null
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
