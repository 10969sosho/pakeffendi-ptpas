# Guest API

Base URL: `/api/guest`

## Sync
`GET /api/guest/sync`

Response:
```json
{
  "version": 1700000000,
  "changed_at": "2026-02-10T00:00:00.000000Z"
}
```

Dipakai oleh guest frontend untuk polling perubahan data. Jika `version` berubah, UI melakukan reload.

## Home Data
`GET /api/guest/home`

Response (ringkas):
```json
{
  "version": 1700000000,
  "categories": [{ "id": 1, "name": "POWER TOOL" }],
  "brands": [{ "id": 1, "brand_name": "BOSCH", "is_favorite": true }],
  "broadcasts": [{ "id": 1, "image_path": "https://...", "description": "..." }],
  "featured_products": [{ "id": 1, "sku": "SKU-00001", "name": "...", "price_1": 25000 }],
  "about": { "content": "<h3>...</h3>" }
}
```

Sumber data:
- Admin mengubah data via CRUD (brands, products, broadcasts, about, dll)
- Guest membaca data ini via API dan menampilkan di halaman `/`

## Produk List
`GET /api/guest/products`

Query params:
- `q` (string): cari berdasarkan `name` atau `sku`
- `category_id` (int)
- `brand_id` (int)
- `per_page` (1-50)

Response: JSON pagination Laravel.

## Produk Detail
`GET /api/guest/products/{product}`

Response berisi detail + images + tier harga.

## Create Order (Guest → Admin)
`POST /api/guest/orders`

Request:
```json
{
  "customer": {
    "full_name": "Budi",
    "email": "budi@example.com",
    "phone": "08123456789",
    "address": "Jl. ..."
  },
  "delivery_to": "Budi",
  "delivery_phone": "08123456789",
  "delivery_address": "Jl. ...",
  "notes": "Catatan",
  "items": [
    { "product_id": 1, "quantity": 2 }
  ]
}
```

Response (201):
```json
{
  "order_id": 1,
  "order_no": "W2602100001",
  "status": "Payment Pending",
  "grand_total": 50000
}
```

Data flow:
- Guest membuat order melalui endpoint ini (tersimpan ke `sales_orders` + `sales_order_items`)
- Admin melihat order dari menu Sales Orders

