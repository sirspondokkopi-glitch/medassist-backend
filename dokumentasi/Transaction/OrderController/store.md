# store

**Method:** POST  
**Endpoint:** `/api/master/orders`  
**Controller:** `App\Http\Controllers\Transaction\OrderController@store`  
**Auth:** Bearer Token (wajib)

Membuat order peminjaman baru (header + daftar unit). Status awal selalu `diajukan`.
Semua item dibuat dalam satu transaksi DB.

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |
| Content-Type | application/json | Ya |

### Body Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| room_id | integer | Ya | Ruangan tujuan, harus ada di tabel rooms |
| user_id | integer | Tidak | Petugas/penanggung jawab, harus ada di tabel users |
| order_date | date | Ya | Tanggal pengajuan/pinjam (format `YYYY-MM-DD`) |
| return_plan_date | date | Tidak | Rencana tanggal kembali |
| note | string | Tidak | Catatan/keperluan |
| items | array | Ya | Minimal 1 unit yang dipinjam |
| items[].instrument_stock_id | integer | Ya | Unit fisik instrumen (unik per order), harus ada di instrument_stocks |
| items[].condition_out_id | integer | Tidak | Kondisi unit saat keluar/dipinjam |

### Contoh Body
```json
{
  "room_id": 1,
  "user_id": 1,
  "order_date": "2026-06-08",
  "return_plan_date": "2026-06-10",
  "note": "Untuk operasi minor",
  "items": [
    { "instrument_stock_id": 1, "condition_out_id": 1 },
    { "instrument_stock_id": 2, "condition_out_id": 1 }
  ]
}
```

## Response

### Success (201)
```json
{
  "status": true,
  "message": "Peminjaman berhasil dibuat.",
  "data": {
    "id": 1,
    "code": "ORD-001",
    "room_id": 1,
    "user_id": 1,
    "order_date": "2026-06-08",
    "return_plan_date": "2026-06-10",
    "return_actual_date": null,
    "status": "diajukan",
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
          "id": 1, "code": "ZHVQ-001", "status": "tersedia",
          "instrument": { "id": 1, "code": "ZHVQ", "name": "stetoskop" }
        },
        "condition_out": { "id": 1, "name": "Baik" },
        "condition_in": null
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
  "errors": {
    "room_id": ["The room id field is required."],
    "items": ["The items field is required."]
  }
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
