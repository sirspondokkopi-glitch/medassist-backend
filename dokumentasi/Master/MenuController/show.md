# Show

**Method:** GET  
**Endpoint:** /api/master/menus/{id}  
**Controller:** App\Http\Controllers\Master\MenuController@show

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |

### Path Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| id | integer | Ya | ID menu |

## Response

### Success (200)
```json
{
  "status": true,
  "message": "Berhasil mengambil detail menu.",
  "data": {
    "id": 1,
    "title_menu_id": 1,
    "parent_id": null,
    "name": "Master Data",
    "url": null,
    "sort_order": 0,
    "title_menu": { "id": 1, "title": "Master Data" },
    "parent": null,
    "children": [
      { "id": 2, "name": "Instrument", "url": "/master/instrument", "sort_order": 1 }
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
