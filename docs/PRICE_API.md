# Price Validation API

`GET /api/v1/price-check` — read-only, public, rate limited (60 requests/minute per IP).
Does not change any data. All admin operations require an administrator login.

## Query parameters
| Name | Type | Rules |
|---|---|---|
| `category_id` | integer | required, must exist in `categories` |
| `material_id` | integer | required, must exist in `materials` |
| `price` | integer (Rupiah) | required, >= 0 |

## Responses
Fields: `status`, `valid` (true / false / null), `price`, `rule` (`min_price`, `max_price` or null), `message`.

**200** — price evaluated against an active rule
```json
{"status":"valid","valid":true,"price":200000,"rule":{"min_price":150000,"max_price":250000},"message":"Harga Rp 200.000 berada dalam rentang harga adil (Rp 150.000 – Rp 250.000)."}
```
```json
{"status":"below_minimum","valid":false,"price":100000,"rule":{"min_price":150000,"max_price":250000},"message":"Harga Rp 100.000 berada di bawah batas minimum harga adil Rp 150.000 (kurang Rp 50.000)."}
```
`status` can also be `above_maximum` (valid = false).

**404** — no applicable active rule (`valid` is null, `rule` is null)
`status` is `no_rule` (no rule for this combination) or `rule_inactive` (rule exists but is deactivated).

**422** — invalid or missing parameters
```json
{"status":"validation_error","valid":null,"message":"Parameter tidak valid.","errors":{"price":["The price field is required."]}}
```

Example: `GET /api/v1/price-check?category_id=1&material_id=2&price=200000`
