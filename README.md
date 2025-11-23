# 🚀 API Data Sync - Laravel loyihasi

Bu loyiha API dan ma'lumotlarni tortib olib MySQL bazasiga saqlaydigan Laravel dastur.

---

## 📋 Loyiha haqida

API endpointlari:
- **Sotuvlar** (Sales): `/api/sales`
- **Buyurtmalar** (Orders): `/api/orders`  
- **Omborlar** (Stocks): `/api/stocks`
- **Daromadlar** (Incomes): `/api/incomes`

API manzili: `http://109.73.206.144:6969`

---

## 🗄️ DATABASE SOZLAMALARI

### Bepul MySQL Hosting

**Xizmat:** FreeMySQLHosting.net yoki FreeSQLDatabase.com

### Database ma'lumotlari:

```
Host: sql12.freemysqlhosting.net
Port: 3306
Database nomi: sql12745123
Username: sql12745123
Password: <sizning parolingiz>
```

### 📊 Jadvallar ro'yxati:

| Jadval nomi | Tavsif | Asosiy maydonlar |
|-------------|--------|------------------|
| `sales` | Sotuvlar ma'lumotlari | id, data, sale_date, external_id, created_at, updated_at |
| `orders` | Buyurtmalar ma'lumotlari | id, data, order_date, external_id, created_at, updated_at |
| `stocks` | Ombor ma'lumotlari | id, data, stock_date, external_id, created_at, updated_at |
| `incomes` | Daromad ma'lumotlari | id, data, income_date, external_id, created_at, updated_at |

**ESLATMA:** Barcha jadvallar `data` maydonida API dan kelgan to'liq JSON ma'lumotini saqlaydi.

---

## ⚙️ O'rnatish (Setup)

### 1. Loyihani yuklab olish

```bash
git clone <sizning-repo-url>
cd api-data-sync
```

### 2. Composer dependencies o'rnatish

```bash
composer install
```

### 3. .env faylini sozlash

`.env` faylini tahrirlang va database ma'lumotlarini kiriting:

```env
DB_CONNECTION=mysql
DB_HOST=sql12.freemysqlhosting.net
DB_PORT=3306
DB_DATABASE=sql12745123
DB_USERNAME=sql12745123
DB_PASSWORD=<sizning-parolingiz>

API_BASE_URL=http://109.73.206.144:6969
API_KEY=E6kUTYrYwZq2tN4QEtyzsbEBk3ie
```

### 4. Jadvallarni yaratish (Migration)

```bash
php artisan migrate
```

Bu buyruq database da 4 ta jadval yaratadi: `sales`, `orders`, `stocks`, `incomes`

---

## 🎯 Ishlatish (Usage)

### API dan ma'lumotlarni yuklash

```bash
php artisan api:sync
```

Bu buyruq oxirgi 30 kun uchun barcha ma'lumotlarni yuklaydi.

### Ma'lum sana oralig'i uchun yuklash

```bash
php artisan api:sync --dateFrom=2024-01-01 --dateTo=2024-01-31
```

### Nima sodir bo'ladi?

1. Command ishga tushadi
2. API ga so'rov yuboriladi (har bir endpoint uchun)
3. Ma'lumotlar sahifalab yuklanadi (har safar 500 ta yozuv)
4. Har bir yozuv database ga saqlanadi
5. Dublikatlar avtomatik yangilanadi (updateOrCreate orqali)

---

## 📁 Loyiha tuzilmasi

```
api-data-sync/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── SyncApiData.php      # Asosiy sync command
│   └── Models/
│       ├── Sale.php                 # Sale modeli
│       ├── Order.php                # Order modeli
│       ├── Stock.php                # Stock modeli
│       └── Income.php               # Income modeli
├── database/
│   └── migrations/
│       ├── 2024_01_01_000001_create_sales_table.php
│       ├── 2024_01_01_000002_create_orders_table.php
│       ├── 2024_01_01_000003_create_stocks_table.php
│       └── 2024_01_01_000004_create_incomes_table.php
├── config/
│   └── database.php                 # Database konfiguratsiya
├── .env                             # Environment sozlamalari
└── README.md                        # Bu fayl
```

---

## 🔧 Texnik tafsilotlar

### Database jadval tuzilishi

Har bir jadvalda quyidagi maydonlar bor:

- **id** - Avtomatik ID (Primary Key)
- **data** - JSON format (API dan kelgan to'liq ma'lumot)
- **{type}_date** - Sana (masalan: sale_date, order_date)
- **external_id** - API dagi ID (dublikatlarni oldini olish uchun)
- **created_at** - Yaratilgan vaqt
- **updated_at** - Yangilangan vaqt

### API so'rov formati

```
GET /api/sales?dateFrom=2024-01-01&dateTo=2024-01-31&page=1&limit=500&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie
```

### Xususiyatlar

✅ Avtomatik pagination (sahifalash)  
✅ Dublikatlarni oldini olish  
✅ Xatoliklarni qayd qilish (logging)  
✅ Timeout bilan himoyalangan so'rovlar  
✅ JSON formatda ma'lumot saqlash  

---

## 🐛 Muammolar va yechimlar

### "Connection refused" xatosi

Database sozlamalarini tekshiring:
```bash
php artisan config:clear
```

### "Too many requests" xatosi

Command ichida `sleep(1)` qo'shilgan, API ga ortiqcha yuklanmaslik uchun.

### Ma'lumotlar yuklanmayapti

1. API Key ni tekshiring (.env fayl)
2. Internet ulanishini tekshiring
3. API manzilini tekshiring

---

## 📞 Yordam

Agar savollar bo'lsa:
- Issues yozing GitHub da
- Email: <sizning-emailingiz>

---

## 📜 Litsenziya

Bu test loyiha, o'quv maqsadida yaratilgan.

---

## 🎓 Laravel haqida qisqa ma'lumot

### Asosiy tushunchalar:

1. **Migration** - Database jadvallarini yaratish/o'zgartirish
2. **Model** - Database bilan ishlash uchun klass
3. **Command** - Console orqali ishlaydigan dastur
4. **Artisan** - Laravel ning CLI (command line) vositasi

### Foydali Artisan buyruqlari:

```bash
php artisan migrate              # Jadvallarni yaratish
php artisan migrate:rollback     # Oxirgi migrationni bekor qilish
php artisan migrate:fresh        # Barcha jadvallarni qayta yaratish
php artisan list                 # Barcha commandlarni ko'rish
```

---

**Muvaffaqiyatlar tilaymiz! 🎉**
