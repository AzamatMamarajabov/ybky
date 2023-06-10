# YBKY saralash bosqichi topshirig'i

```
 Mavjud xonalarni olish uchun API
```
```
GET /api
```

```json
{
  "page": 1,
  "count": 8,
  "page_size": 10,
  "results": [
    {
      "id": 1,
      "name": "azik-1",
      "type": "conference",
      "capacity": 15
    },
    {
      "id": 2,
      "name": "azik-2",
      "type": "focus",
      "capacity": 1
    },
    {
      "id": 3,
      "name": "azik-3",
      "type": "conference",
      "capacity": 15
    },
    {
      "id": 4,
      "name": "azik-4",
      "type": "focus",
      "capacity": 1
    },
    {
      "id": 5,
      "name": "azik-5",
      "type": "team",
      "capacity": 5
    },
    {
      "id": 6,
      "name": "azik-6",
      "type": "team",
      "capacity": 5
    },
    {
      "id": 7,
      "name": "azik-7",
      "type": "focus",
      "capacity": 1
    },
    {
      "id": 8,
      "name": "azik-8",
      "type": "team",
      "capacity": 5
    }
  ]
}
```

---

## Xonani id orqali olish uchun API

```
GET /api/rooms/{id}
```

```json
{
  "id": 3,
  "name": "azik-1",
  "type": "conference",
  "capacity": 15
}
```

HTTP 404

```json
{
  "error": "Xona topilmadi!"
}
```

---

## Xonaning bo'sh vaqtlarini olish uchun API

```
GET /api/rooms/{id}/availability
```



```json
[
    {
        "start": "2023-06-10 00:00:00",
        "end": "2023-06-10 12:00:00"
    },
    {
        "start": "2023-06-10 14:00:00",
        "end": "2023-06-10 23:59:59"
    }
]
```

---

## Xonani band qilish uchun API

```
POST /api/rooms/{id}/book
```

```json
{
    "resident": {
        "name": "Azamat Mamarajabov"
    },
    "start": "2023-06-10 09:00:00",
    "end": "2023-06-20 10:00:00"
}
```

---

HTTP 201: Xona muvaffaqiyatli band qilinganda

```json
{
    "message": "Xona band qilindi"
}
```

HTTP 410: Tanlangan vaqtda xona band bo'lganda

```json
{
  "error": "Xona berilgan vaqt oralig'ida band qilingan"
}

