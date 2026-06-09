# index

**Method:** GET  
**Endpoint:** `/api/master/instrument-sets`  
**Controller:** `App\Http\Controllers\Master\InstrumentSetController@index`  
**Auth:** Bearer Token (wajib)

Daftar set/tray instrumen — kumpulan unit fisik yang dikelola sebagai satu paket (mis. "Set Bedah Minor").

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Query Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| search | string | Tidak | Filter berdasarkan `name` atau `code` (like) |
| status | string | Tidak | Filter status: `tersedia`, `dipinjam`, `sterilisasi`, `dikembalikan` |
| room_id | integer | Tidak | Filter berdasarkan ruangan |
| page | integer | Tidak | Nomor halaman (default: 1) |

## Response

### Success (200)
```json
{
  "status": true,
  "message": "Data set instrumen berhasil diambil.",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "code": "SET-001",
        "name": "Set Bedah Minor",
        "room_id": 1,
        "status": "tersedia",
        "note": null,
        "items_count": 5,
        "created_by": "Administrator",
        "updated_by": "Administrator",
        "deleted_at": null,
        "deleted_by": null,
        "created_at": "2026-06-08T08:00:00.000000Z",
        "updated_at": "2026-06-08T08:00:00.000000Z",
        "room": { "id": 1, "code": "JWGL", "name": "poli umum" }
      }
    ],
    "per_page": 20,
    "total": 1,
    "last_page": 1
  }
}
```
