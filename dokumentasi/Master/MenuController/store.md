# Store

**Method:** POST  
**Endpoint:** /api/master/menus  
**Controller:** App\Http\Controllers\Master\MenuController@store

## Request

### Headers
| Key | Value | Required |
|-----|-------|----------|
| Authorization | Bearer {token} | Ya |
| Content-Type | application/json | Ya |

### Body Parameters
| Parameter | Type | Required | Keterangan |
|-----------|------|----------|------------|
| parent_id | integer | Tidak | ID menu parent (untuk sub-menu) |
| name | string | Ya | Nama menu, maks 100 karakter |
| url | string | Tidak | URL / route menu, maks 255 karakter |
| icon | string | Tidak | Nama icon, maks 100 karakter |
| sort_order | integer | Tidak | Urutan tampil (default: 0) |

## Response

### Success (201)
```json
{
  "status": true,
  "message": "Menu berhasil dibuat.",
  "data": {
    "id": 2,
    "parent_id": 1,
    "name": "Instrument",
    "url": "/master/instrument",
    "icon": "tool",
    "sort_order": 1,
    "parent": { "id": 1, "name": "Master Data" }
  }
}
```

### Error (422)
```json
{
  "status": false,
  "message": "Data yang dikirim tidak valid.",
  "errors": {
    "name": ["The name field is required."]
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
