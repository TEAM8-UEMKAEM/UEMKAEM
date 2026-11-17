# API Validasi Harga Adil

Memeriksa apakah sebuah harga berada dalam rentang harga adil untuk kombinasi kategori + material.
Publik, **hanya-baca** (tidak mengubah data), dibatasi 60 permintaan per menit per alamat IP.

## Endpoint

`GET /api/price-check`

## Parameter (query string, semua wajib)

| Nama | Tipe | Keterangan |
|---|---|---|
| `category_id` | integer | ID kategori. Harus aktif. |
| `material_id` | integer | ID material. Harus aktif. |
| `price` | integer | Harga yang diperiksa, rupiah, >= 0. |

## Respons 200 (pemeriksaan berhasil dijalankan)

`data.status` menjelaskan hasilnya:

| status | valid | Arti |
|---|---|---|
| `within_range` | `true` | Harga di dalam rentang. |
| `below_minimum` | `false` | Di bawah minimum. `difference` = kekurangannya. |
| `above_maximum` | `false` | Di atas maksimum. `difference` = kelebihannya. |
| `no_rule` | `null` | Belum ada aturan untuk kombinasi ini. |
| `rule_inactive` | `null` | Aturan ada tetapi sedang dinonaktifkan. |

Contoh (`/api/price-check?category_id=1&material_id=2&price=50000`):

```json
{
  "data": {
    "valid": false,
    "status": "below_minimum",
    "message": "Harga di bawah batas minimum harga adil (minimum Rp 80.000). Kurang Rp 30.000.",
    "submitted_price": 50000,
    "rule": { "min_price": 80000, "max_price": 120000 },
    "difference": 30000,
    "category": { "id": 1, "name": "Tenun" },
    "material": { "id": 2, "name": "Benang Katun" }
  }
}
```

Harga sesuai: `"valid": true, "status": "within_range", "difference": null`.
Tanpa aturan: `"valid": null, "status": "no_rule", "rule": null`.

## Respons 422 (input tidak valid)

Parameter hilang, bukan angka bulat, harga negatif, atau kategori/material tidak aktif/tidak ditemukan.

```json
{
  "message": "Data yang dikirim tidak valid.",
  "errors": { "price": ["price tidak boleh negatif."] }
}
```

## Respons 429

Terlalu banyak permintaan (batas 60/menit).

## Contoh PowerShell

```powershell
Invoke-RestMethod "http://127.0.0.1:8000/api/price-check?category_id=1&material_id=2&price=50000"
```
