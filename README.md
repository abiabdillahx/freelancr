# Freelancr
> Marketplace jasa freelance untuk mahasiswa - temukan talent, posting jasa, dan berkolaborasi di dalam ekosistem kampus.

---

## 📌 Deskripsi Proyek

**Freelancr** adalah platform marketplace jasa freelance berbasis kampus yang menghubungkan mahasiswa sebagai freelancer dengan klien yang membutuhkan jasa mereka. Mulai dari desain grafis, penerjemahan, pemrograman, hingga jasa akademik lainnya, semua bisa ditawarkan dan dipesan di satu platform.

Proyek ini dikembangkan sebagai tugas akhir mata kuliah dengan mengintegrasikan teknologi modern seperti Laravel REST API, JWT Authentication, API Gateway, dan integrasi API pihak ketiga.

---

## 👥 Tim Pengembang

| Nama | NIM | Peran |
|------|-----|-------|
| Ahmad Thoriq Hafidzurrohman | 245150701111026 | Auth, API Gateway, Dokumentasi Swagger |
| [Nama 2] | [NIM] | Modul Jasa & Kategori, Imgbb Integration |
| Muhammad Kensya Kussyahputra Hidayatullah | 245150707111047 | Modul Order & Manajemen Status |
| Muhammad Abi Abdillah | 245150701111027 | Modul Review, Currency API, Frontend |

---

## ✨ Fitur Utama

### Untuk Freelancer
- Registrasi & login dengan JWT
- Posting, edit, dan hapus jasa
- Upload foto portofolio via Imgbb
- Kelola dan update status order yang masuk
- Lihat riwayat order dan rating

### Untuk Client
- Browse dan search jasa berdasarkan kategori
- Order jasa freelancer
- Lihat harga dalam IDR & USD (via Currency API)
- Tulis review setelah order selesai
- Riwayat order pribadi

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 11, MySQL |
| Authentication | JWT (tymon/jwt-auth) |
| API Gateway | Laravel (custom middleware routing) |
| Frontend | HTML, CSS, JavaScript, Axios |
| Dokumentasi API | Swagger / OpenAPI (L5-Swagger) |
| File Upload | Imgbb API |
| Konversi Mata Uang | Currency API (currencyapi.com) |

---

## 🗄️ Struktur Database

```
users
├── id, name, email, password, role (freelancer | client), avatar, bio
│
categories
├── id, name, slug
│
services
├── id, user_id, category_id, title, description, price, image_url, status
│
orders
├── id, service_id, client_id, status (pending | in_progress | completed | cancelled), note, created_at
│
reviews
└── id, order_id, rating (1-5), comment, created_at
```

---

## 🔐 Role & Hak Akses

| Fitur | Freelancer | Client |
|-------|-----------|--------|
| Posting jasa | ✅ | ❌ |
| Edit/hapus jasa milik sendiri | ✅ | ❌ |
| Order jasa | ❌ | ✅ |
| Update status order | ✅ | ❌ |
| Cancel order | ❌ | ✅ (hanya status pending) |
| Tulis review | ❌ | ✅ (hanya order completed) |
| Lihat semua jasa | ✅ | ✅ |
| Lihat profil sendiri | ✅ | ✅ |

---

## 📡 API Endpoints

### Auth
```
POST   /api/auth/register
POST   /api/auth/login
GET    /api/auth/profile
POST   /api/auth/logout
```

### Services (Jasa)
```
GET    /api/services              → list semua jasa
GET    /api/services/{id}         → detail jasa
POST   /api/services              → buat jasa [freelancer]
PUT    /api/services/{id}         → edit jasa [freelancer]
DELETE /api/services/{id}         → hapus jasa [freelancer]
```

### Orders
```
GET    /api/orders                → list order milik user
POST   /api/orders                → buat order [client]
PUT    /api/orders/{id}/status    → update status [freelancer]
PUT    /api/orders/{id}/cancel    → cancel order [client]
```

### Reviews
```
POST   /api/reviews               → tulis review [client, hanya completed]
GET    /api/services/{id}/reviews → review per jasa
```

### Categories
```
GET    /api/categories            → list kategori
```

### Utility (via Gateway)
```
GET    /api/currency?amount=50000 → konversi IDR → USD
```

---

## 🌐 Integrasi API Pihak Ketiga

### 1. Imgbb
Digunakan untuk upload dan hosting gambar portofolio jasa freelancer.
- Endpoint upload gambar terhubung ke Imgbb via Laravel backend
- Mengembalikan URL gambar yang disimpan di database
- Free tier: unlimited upload, gak perlu kartu kredit

### 2. Currency API (currencyapi.com)
Digunakan untuk menampilkan harga jasa dalam USD di sisi client.
- Free tier: 300 request/bulan (cukup untuk keperluan demo)
- Dipanggil melalui API Gateway

---

## 🚪 API Gateway

Semua request dari client melewati API Gateway di prefix `/api/gateway/` yang bertugas:
- Validasi JWT token
- Rate limiting
- Routing ke service yang sesuai
- Logging request

---

## 👾 Cara Menjalankan

### Requirements
- PHP >= 8.2
- Composer
- MySQL
- Node.js (opsional, untuk asset)

### Instalasi

```bash
# Clone repository
git clone https://github.com/[username]/freelancr.git
cd freelancr

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret

# Setup database
php artisan migrate --seed

# Jalankan server
php artisan serve
```

### Konfigurasi `.env`

```env
DB_DATABASE=freelancr
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=your_jwt_secret

IMGBB_API_KEY=your_imgbb_api_key
CURRENCY_API_KEY=your_currency_api_key
```

---

## 📁 Struktur Folder

```
freelancr/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── ServiceController.php
│   │   │   ├── OrderController.php
│   │   │   ├── ReviewController.php
│   │   │   └── GatewayController.php
│   │   └── Middleware/
│   │       ├── JwtMiddleware.php
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Service.php
│       ├── Order.php
│       └── Review.php
├── routes/
│   └── api.php
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── client/          ← frontend HTML/JS/CSS
│       ├── index.html
│       ├── login.html
│       ├── dashboard.html
│       └── js/
│           └── app.js
└── storage/
```

---

## 📄 Dokumentasi API

Dokumentasi lengkap tersedia via Swagger UI setelah server berjalan:

```
http://localhost:8000/api/documentation
```

---

## 🎯 Alur Demo

1. **Register & Login** → dapat JWT token
2. **Akses via Gateway** → semua request lewat `/api/gateway/`
3. **Role restriction** → client coba POST jasa → `403 Forbidden`
4. **Validasi gagal** → order tanpa note → response error JSON konsisten
5. **Relasi data** → order terhubung ke jasa, review terhubung ke order
6. **API 3rd party** → tampilkan harga jasa dalam USD via Currency API
7. **Upload gambar** → freelancer upload foto portofolio via Imgbb

---

## 📝 Lisensi

Proyek ini dibuat untuk keperluan akademik.

---
