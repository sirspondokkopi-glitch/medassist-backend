# Index

**Method:** GET  
**Endpoint:** /api/master/title-menus  
**Controller:** App\Http\Controllers\Master\TitleMenuController@index  
**Auth:** Bearer Token (wajib)

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Query Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| search | string | Tidak | Filter berdasarkan title |
| page | integer | Tidak | Halaman (default: 1) |

## Response

### Success (200)
```json
{
  "status": true,
  "message": "Berhasil mengambil data title menu.",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Master Data",
        "icon": "database",
        "sort_order": 1,
        "is_active": true,
        "menus": [
          { "id": 1, "name": "Authority", "url": "/master/otoritas", "sort_order": 1 }
        ]
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
