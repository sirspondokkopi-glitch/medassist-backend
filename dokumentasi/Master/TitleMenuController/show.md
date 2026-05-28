# Show

**Method:** GET  
**Endpoint:** /api/master/title-menus/{id}  
**Controller:** App\Http\Controllers\Master\TitleMenuController@show  
**Auth:** Bearer Token (wajib)

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Path Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| id | integer | Ya | ID title menu |

## Response

### Success (200)
```json
{
  "status": true,
  "message": "Berhasil mengambil detail title menu.",
  "data": {
    "id": 1,
    "title": "Master Data",
    "icon": "database",
    "sort_order": 1,
    "is_active": true,
    "menus": [
      { "id": 1, "name": "Authority", "url": "/master/otoritas", "sort_order": 1 },
      { "id": 2, "name": "Menu",      "url": "/master/menu",     "sort_order": 2 },
      { "id": 3, "name": "User",      "url": "/master/user",     "sort_order": 3 }
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
