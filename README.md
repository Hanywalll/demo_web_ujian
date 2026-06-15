# 🎓 Online Exam System

Sistem ujian online berbasis web yang modern, aman, dan responsif. Dibangun menggunakan **CodeIgniter 4**, **Bootstrap 5**, dan **MySQL** dengan dukungan timer server-side, auto-save jawaban, monitoring peserta secara real-time, serta mekanisme pemulihan koneksi yang menjaga integritas data selama ujian berlangsung.

---

## 🔗 Links

* Repository: https://github.com/Hanywalll/demo_web_ujian
* Dokumentasi Tangkapan Layar: https://drive.google.com/drive/folders/1jyIg9MB_9ZzqtyJqRJu0oFBZ16oII-da
* Database SQL: tersedia pada folder `database/demowebujian.sql`

---

## ✨ Features

### 👨‍💼 Admin

* Dashboard real-time
* Manajemen ujian (Create, Publish, Unpublish)
* Manajemen soal dengan dukungan MathJax
* Upload gambar soal
* Monitoring peserta ujian
* Penambahan waktu ujian secara real-time
* Statistik dan histori ujian peserta
* Manajemen user
* Manajemen kategori ujian
* Pengaturan status ujian

### 👨‍🎓 User

* Registrasi & Login
* Pendaftaran ujian
* Timer ujian server-side
* Auto-save jawaban (AJAX)
* Backup jawaban menggunakan LocalStorage
* Resume ujian yang belum selesai
* Review hasil dan detail jawaban
* Riwayat ujian
* Responsive design (Desktop, Tablet, Mobile)

---

## 🔐 Security Features

* CSRF Protection
* Password Hashing (Bcrypt)
* SQL Injection Prevention
* XSS Protection
* Session-Based Authentication
* Role-Based Access Control
* Server-Side Timer Validation
* Input Validation & Sanitization
* Secure File Upload
* Safe Error Handling

---

## 🌐 Network Recovery Mechanism

Aplikasi dirancang tetap aman ketika koneksi internet terputus:

* Jawaban otomatis tersimpan ke database
* Backup jawaban ke LocalStorage
* Auto-sync saat koneksi kembali
* Session tetap aktif
* Resume ujian setelah login ulang
* Timer tetap berjalan di server
* Sinkronisasi jawaban secara otomatis setelah jaringan pulih

---

## 📸 Application Preview

### 👨‍💼 Admin Panel

#### Dashboard

<img src="screenshots/admin/Tampilan Dashboard.png" width="100%">

Dashboard admin menampilkan statistik ujian, aktivitas peserta, dan monitoring sistem secara real-time.

#### Kelola Ujian

<img src="screenshots/admin/Kelola Ujian.png" width="100%">

Fitur untuk membuat, mengedit, mempublikasikan, dan mengelola ujian beserta soal-soalnya.

#### Riwayat Ujian Peserta

<img src="screenshots/admin/riwayat ujian peserta.png" width="100%">

Menampilkan riwayat pengerjaan ujian peserta lengkap dengan nilai dan statistik hasil ujian.

---

### 👨‍🎓 User Panel

#### Tampilan Saat Ujian

<img src="screenshots/user/tampilan saat ujian.png" width="100%">

Peserta dapat mengerjakan ujian dengan navigasi soal yang mudah, auto-save jawaban, dan timer yang tersinkronisasi dengan server.

#### Tampilan Saat Ujian + Extra Time

<img src="screenshots/user/tampilan saat ujian + ekstra time.png" width="100%">

Admin dapat menambahkan waktu ujian secara real-time dan timer peserta akan otomatis diperbarui tanpa perlu me-refresh halaman.

#### Review Ujian

<img src="screenshots/user/review ujian.png" width="100%">

Halaman review menampilkan nilai, statistik jawaban, serta detail jawaban peserta dan jawaban yang benar.

---

## 👤 Demo Accounts

> Akun berikut otomatis tersedia setelah mengimpor file `database/demowebujian.sql`

| Role  | Email                                         | Password |
| ----- | --------------------------------------------- | -------- |
| Admin | [admin@example.com](mailto:admin@example.com) | admin123 |
| User  | [user1@example.com](mailto:user1@example.com) | user123  |
| User  | [user2@example.com](mailto:user2@example.com) | user123  |

---

## ℹ️ Penggunaan Demo Account

Untuk menguji fitur Admin dan User secara bersamaan, gunakan salah satu metode berikut:

### Opsi 1 (Direkomendasikan)

* Login sebagai **Admin** pada browser utama (Chrome/Firefox)
* Login sebagai **User** pada **Incognito/Private Window**

### Opsi 2

Gunakan browser yang berbeda:

* Chrome → Admin
* Firefox → User

### Opsi 3

Gunakan profile browser yang berbeda.

| Browser/Profile  | Akun  |
| ---------------- | ----- |
| Chrome           | Admin |
| Chrome Incognito | User  |
| Firefox          | User  |

> Karena aplikasi menggunakan session login, satu browser hanya dapat menggunakan satu akun aktif dalam satu sesi. Untuk simulasi monitoring ujian secara real-time, gunakan browser atau profile yang berbeda.

---

## ⚙️ Requirements

### Server

* PHP 8.0+
* Composer 2+
* MySQL 5.7+ / MariaDB 10.3+
* Apache / Nginx

### PHP Extensions

```text
intl
curl
mbstring
openssl
pdo_mysql
xml
json
fileinfo
```

---

## 🚀 Installation

### 1. Clone Repository

```bash
git clone https://github.com/Hanywalll/demo_web_ujian.git
cd demo_web_ujian
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Salin file `env` menjadi `.env`

```bash
cp env .env
```

Edit konfigurasi database pada file `.env`

```env
database.default.hostname = localhost
database.default.database = demowebujian
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 4. Create Database

```sql
CREATE DATABASE demowebujian;
```

### 5. Import Database

File database tersedia pada:

```text
database/demowebujian.sql
```

#### Menggunakan phpMyAdmin

1. Buka `http://localhost/phpmyadmin`
2. Buat database `demowebujian`
3. Klik tab **Import**
4. Pilih file `database/demowebujian.sql`
5. Klik **Go**

#### Menggunakan MySQL CLI

```bash
mysql -u root -p demowebujian < database/demowebujian.sql
```

### 6. Set Folder Permissions (Linux/MacOS)

```bash
chmod -R 775 writable/
chmod -R 775 public/uploads/
```

Untuk Windows, pastikan folder berikut memiliki izin baca dan tulis:

```text
writable/
public/uploads/
```

### 7. Start Server

```bash
php spark serve
```

Akses aplikasi melalui:

```text
http://localhost:8080
```

---

## 🛠 Tech Stack

### Backend

* PHP 8+
* CodeIgniter 4

### Frontend

* HTML5
* CSS3
* JavaScript (ES6+)
* Bootstrap 5
* Bootstrap Icons

### Database

* MySQL
* MariaDB

### Libraries

* MathJax
* Google Fonts
* jQuery
* AJAX

---

## 📁 Project Structure

```text
demo_web_ujian/
├── app/
│   ├── Config/
│   ├── Controllers/
│   ├── Filters/
│   ├── Models/
│   └── Views/
├── public/
│   └── uploads/
├── writable/
├── database/
│   └── demowebujian.sql
├── screenshots/
│   ├── admin/
│   │   ├── Kelola Ujian.png
│   │   ├── Tampilan Dashboard.png
│   │   └── riwayat ujian peserta.png
│   └── user/
│       ├── review ujian.png
│       ├── tampilan saat ujian.png
│       └── tampilan saat ujian + ekstra time.png
├── .env
├── composer.json
└── README.md
```

### Struktur Folder Penting

| Folder/File               | Deskripsi                        |
| ------------------------- | -------------------------------- |
| app/Controllers           | Logic aplikasi                   |
| app/Models                | Interaksi database               |
| app/Views                 | Tampilan aplikasi                |
| app/Filters               | Authentication & Authorization   |
| public/uploads            | Penyimpanan gambar soal          |
| writable                  | Cache, session, dan log aplikasi |
| database/demowebujian.sql | Database instalasi               |
| screenshots               | Dokumentasi tampilan aplikasi    |
| .env                      | Konfigurasi aplikasi             |

---

## 🤝 Contributing

1. Fork repository

2. Buat branch baru

```bash
git checkout -b feature/new-feature
```

3. Commit perubahan

```bash
git commit -m "Add new feature"
```

4. Push ke repository

```bash
git push origin feature/new-feature
```

5. Buat Pull Request

---

## 👨‍💻 Developer

**Muhammad Burhanudin**

GitHub:
https://github.com/Hanywalll

LinkedIn:
https://www.linkedin.com/in/m-burhanudin

---

## 📄 License

Project ini dibuat untuk keperluan pembelajaran, portfolio, dan mini project.

---

⭐ Jika project ini bermanfaat, jangan lupa memberikan **Star** pada repository.
