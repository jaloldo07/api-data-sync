# 📚 QADAMMA-QADAM O'RNATISH VA ISHLATISH KO'RSATMASI

## ❗ MUHIM: Loyihani ishlatishdan oldin

### 1. Bepul MySQL Database yaratish

**Variantlar:**

#### A) FreeSQLDatabase.com (Tavsiya etiladi)
1. https://www.freesqldatabase.com/ saytiga kiring
2. "Create Free MySQL Database" tugmasini bosing
3. Forma to'ldiring (Email, Database nomi)
4. Email orqali kelgan ma'lumotlarni saqlang

#### B) FreeMySQLHosting.net
1. https://www.freemysqlhosting.net/ saytiga kiring
2. Ro'yxatdan o'ting
3. Database yarating
4. Ma'lumotlarni saqlang

### 2. Database ma'lumotlarini yozib oling:

```
Host: (masalan: sql12.freemysqlhosting.net)
Database: (masalan: sql12745123)
Username: (masalan: sql12745123)
Password: (sizga berilgan parol)
Port: 3306
```

---

## 🚀 LOYIHANI O'RNATISH

### QADAM 1: Loyihani yuklab olish

```bash
# GitHub dan clone qilish
git clone <repo-url>
cd api-data-sync
```

### QADAM 2: PHP va Composer o'rnatish

**Windows uchun:**
1. XAMPP yoki Laragon o'rnating (PHP + MySQL)
2. Composer yuklab oling: https://getcomposer.org/download/

**Linux/Mac uchun:**
```bash
# PHP o'rnatish
sudo apt install php php-mysql php-mbstring php-xml

# Composer o'rnatish
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### QADAM 3: Laravel dependencies o'rnatish

```bash
composer install
```

### QADAM 4: .env faylini sozlash

`.env` faylini oching va quyidagilarni o'zgartiring:

```env
# Database ma'lumotlaringizni kiriting
DB_HOST=sql12.freemysqlhosting.net
DB_PORT=3306
DB_DATABASE=sql12745123
DB_USERNAME=sql12745123
DB_PASSWORD=SIZNING_PAROLINGIZ

# API sozlamalari (o'zgartirish shart emas)
API_BASE_URL=http://109.73.206.144:6969
API_KEY=E6kUTYrYwZq2tN4QEtyzsbEBk3ie
```

### QADAM 5: Jadvallarni yaratish

```bash
php artisan migrate
```

**Natija:** Database da 4 ta jadval yaratiladi:
- sales
- orders  
- stocks
- incomes

---

## 🎯 ISHLATISH

### Ma'lumotlarni yuklash (oxirgi 30 kun)

```bash
php artisan api:sync
```

### Ma'lum davr uchun yuklash

```bash
php artisan api:sync --dateFrom=2024-11-01 --dateTo=2024-11-23
```

### Faqat bugungi kun uchun

```bash
php artisan api:sync --dateFrom=2024-11-23 --dateTo=2024-11-23
```

---

## 📊 MA'LUMOTLARNI TEKSHIRISH

### MySQL Workbench orqali

1. MySQL Workbench o'rnating
2. Yangi connection yarating:
   - Host: sizning DB hostingiz
   - Port: 3306
   - Username: sizning username
   - Password: sizning parol
3. Connect qiling
4. Jadvallarni ko'ring: sales, orders, stocks, incomes

### phpMyAdmin orqali

Ba'zi bepul hostinglar phpMyAdmin beradi:
1. Hosting control panelga kiring
2. phpMyAdmin ni oching
3. Database ni tanlang
4. Jadvallarni ko'ring

### Terminal orqali

```bash
mysql -h sql12.freemysqlhosting.net -u sql12745123 -p
# Parolni kiriting

USE sql12745123;
SHOW TABLES;
SELECT COUNT(*) FROM sales;
SELECT * FROM sales LIMIT 10;
```

---

## 🔧 MUAMMOLARNI YECHISH

### "SQLSTATE[HY000] [2002] Connection refused"

**Sabab:** Database ma'lumotlari noto'g'ri yoki internet yo'q

**Yechim:**
1. `.env` fayl dagi ma'lumotlarni tekshiring
2. Internet ulanishini tekshiring
3. Database hosting ishlab turibdimi tekshiring

```bash
# Konfiguratsiyani tozalash
php artisan config:clear
```

### "Class 'SyncApiData' not found"

**Sabab:** Composer autoload yangilanmagan

**Yechim:**
```bash
composer dump-autoload
```

### "cURL error"

**Sabab:** API serveriga ulanib bo'lmayapti

**Yechim:**
1. Internet ulanishini tekshiring
2. API server ishlab turibdimi tekshiring:
```bash
curl http://109.73.206.144:6969/api/sales?key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie
```

### Migration xatosi

```bash
# Barcha jadvallarni qayta yaratish
php artisan migrate:fresh
```

---

## 📈 QO'SHIMCHA IMKONIYATLAR

### Avtomatik yuklash (Cron)

Linux serverda har kuni ma'lumotlarni avtomatik yuklash:

```bash
# Crontab ni tahrirlash
crontab -e

# Quyidagi qatorni qo'shing (har kuni soat 23:00 da)
0 23 * * * cd /path/to/api-data-sync && php artisan api:sync
```

### Log fayllarni ko'rish

```bash
# Xatolarni ko'rish
tail -f storage/logs/laravel.log
```

---

## 📝 MUHIM ESLATMALAR

1. ✅ Bepul database limitlari:
   - Ko'p hosting 5MB gacha
   - Ba'zilari 1GB gacha beradi
   - Ma'lumot hajmini nazorat qiling

2. ✅ API cheklovlari:
   - Bir so'rovda maksimum 500 ta yozuv
   - Commandda 1 soniya kutish bor

3. ✅ Database backup:
   ```bash
   mysqldump -h HOST -u USERNAME -p DATABASE > backup.sql
   ```

4. ✅ Ma'lumotlarni tozalash:
   ```sql
   TRUNCATE TABLE sales;
   TRUNCATE TABLE orders;
   TRUNCATE TABLE stocks;
   TRUNCATE TABLE incomes;
   ```

---

## 🎓 LARAVEL HAQIDA QO'SHIMCHA

### Asosiy tushunchalar:

**Model** - Database jadval bilan bog'langan PHP klass
```php
// Masalan Sale.php modeli sales jadvaliga bog'langan
$sales = Sale::all(); // Barcha sotuvlarni olish
```

**Migration** - Database jadvallarini yaratish/o'zgartirish
```bash
php artisan make:migration create_sales_table
```

**Command** - Terminal orqali ishlaydigan dastur
```bash
php artisan make:command SyncApiData
```

### Foydali commandlar:

```bash
# Barcha commandlarni ko'rish
php artisan list

# Ma'lum commandni ko'rish
php artisan help api:sync

# Konfiguratsiyani tozalash
php artisan config:clear
php artisan cache:clear

# Jadvallarni qayta yaratish (barcha ma'lumotlar o'chadi!)
php artisan migrate:fresh
```

---

## 🎉 TAYYOR!

Agar hamma narsa to'g'ri bajarilgan bo'lsa:
1. ✅ Database yaratilgan va ulangan
2. ✅ Jadvallar yaratilgan (4 ta)
3. ✅ Ma'lumotlar API dan yuklanmoqda
4. ✅ Loyiha GitHub da

**Omad tilaymiz! 🚀**

---

Savollar bo'lsa, loyihaning Issues bo'limiga yozing!
