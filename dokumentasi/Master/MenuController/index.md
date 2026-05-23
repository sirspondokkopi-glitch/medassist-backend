# Index

**Method:** GET  
**Endpoint:** /api/master/menus  
**Controller:** App\Http\Controllers\Master\MenuController@index

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Query Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| search | string | Tidak | Filter berdasarkan nama menu |
| page | integer | Tidak | Halaman (default: 1) |

## Response

### Success (200)
```json
{
  "status": true,
  "message": "Berhasil mengambil data menu.",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "parent_id": null,
        "name": "Dashboard",
        "url": "/dashboard",
        "icon": "home",
        "sort_order": 0,
        "parent": null
      }
    ],
    "last_page": 1,
    "per_page": 20,
    "total": 1
  }
}
```

### Error (401)
```json
{
  "status": false,
  "message": "Unauthenticated."
}
```
