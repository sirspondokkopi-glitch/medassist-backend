# destroy

**Method:** DELETE  
**Endpoint:** `/api/master/instrument-sets/{instrument_set}`  
**Controller:** `App\Http\Controllers\Master\InstrumentSetController@destroy`  
**Auth:** Bearer Token (wajib)

Soft delete set instrumen (mengisi `deleted_at` + `deleted_by`). Unit anggota tidak ikut terhapus.

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
  "message": "Set instrumen berhasil dihapus."
}
```

### Error (404)
```json
{
  "status": false,
  "message": "Data tidak ditemukan."
}
```
