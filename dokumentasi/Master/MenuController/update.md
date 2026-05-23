# Update

**Method:** PUT / PATCH  
**Endpoint:** /api/master/menus/{id}  
**Controller:** App\Http\Controllers\Master\MenuController@update

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |
| Content-Type | application/json | Ya |

### Path Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| id | integer | Ya | ID menu |

### Body Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| parent_id | integer | Tidak | ID menu parent |
| name | string | Tidak | Nama menu |
| url | string | Tidak | URL / route menu |
| icon | string | Tidak | Nama icon |
| sort_order | integer | Tidak | Urutan tampil |

## Response

### Success (200)
```json
{
  "status": true,
  "message": "Menu berhasil diperbarui.",
  "data": {
    "id": 2,
    "parent_id": 1,
    "name": "Instrument",
    "url": "/master/instrument",
    "icon": "tool",
    "sort_order": 1,
    "parent": { "id": 1, "name": "Master Data" },
    "children": []
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

### Error (422)
```json
{
  "status": false,
  "message": "Data yang dikirim tidak valid.",
  "errors": {}
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
