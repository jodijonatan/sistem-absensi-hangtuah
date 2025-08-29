# 🏫 Sistem Absensi Sekolah RFID

Sistem manajemen absensi berbasis teknologi RFID untuk sekolah yang dibangun dengan Laravel dan menggunakan modern admin template dengan Tailwind CSS.

## ✨ Fitur Utama

### 📊 Dashboard Modern
- **Dashboard interaktif** dengan statistik real-time
- **Grafik kehadiran** dan analisis data
- **Interface responsif** dengan design modern
- **Sidebar navigation** yang user-friendly

### 👥 Manajemen Pengguna
- **Multi-role system**: Admin dan Guru
- **Authentication** yang aman
- **Profile management** dengan foto
- **Login form** yang modern dan interaktif

### 🏫 Manajemen Kelas
- **CRUD kelas lengkap** dengan wali kelas
- **Detail kelas** dengan ringkasan siswa
- **Jadwal pelajaran** per kelas
- **Statistik kehadiran** per kelas

### 👨‍🎓 Manajemen Siswa
- **Database siswa lengkap** dengan foto
- **Integrasi RFID card** untuk setiap siswa
- **Import/export data** siswa
- **Tracking kehadiran** individual

### 📝 Sistem Absensi
- **Absensi otomatis** via RFID tap
- **Deteksi smart** masuk/pulang
- **Input manual** untuk koreksi
- **Status kehadiran**: Hadir, Terlambat, Pulang Awal

### 📊 Laporan & Analytics
- **Laporan harian, mingguan, bulanan**
- **Export ke Excel/PDF**
- **Statistik per kelas dan siswa**
- **Dashboard analytics** real-time

## 🛠 Teknologi yang Digunakan

### Backend
- **Laravel 12.0** - PHP Framework
- **PHP 8.2** - Programming Language
- **MySQL** - Database
- **Eloquent ORM** - Database Management

### Frontend
- **Tailwind CSS 4.0** - Modern CSS Framework
- **Vite 7.0** - Build Tool
- **Alpine.js** - JavaScript Framework
- **Inter Font** - Modern Typography

### Tools & Development
- **Composer** - PHP Dependency Manager
- **NPM** - Node Package Manager
- **Laravel Artisan** - Command Line Tool
- **PHPUnit** - Testing Framework

## 🚀 Instalasi

### Persyaratan Sistem
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js 18+ dan NPM
- MySQL/MariaDB
- Web Server (Apache/Nginx)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/azizt91/sistem-absensi-sekolah-rfid.git
   cd sistem-absensi-sekolah-rfid
   ```

2. **Install Dependencies**
   ```bash
   # Install PHP dependencies
   composer install
   
   # Install Node.js dependencies
   npm install
   ```

3. **Environment Setup**
   ```bash
   # Copy environment file
   cp .env.example .env
   
   # Generate application key
   php artisan key:generate
   ```

4. **Database Configuration**
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistem_absensi_rfid
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Database Migration & Seeding**
   ```bash
   # Create database tables
   php artisan migrate
   
   # Seed initial data (optional)
   php artisan db:seed
   ```

6. **Build Frontend Assets**
   ```bash
   # For development
   npm run dev
   
   # For production
   npm run build
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

   Aplikasi akan berjalan di `http://localhost:8000`

## 👨‍💻 Akun Demo

Untuk testing, gunakan akun berikut:

| Role  | Email              | Password |
|-------|-------------------|----------|
| Admin | admin@sekolah.com | admin123 |
| Guru  | budi@sekolah.com  | guru123  |

## 📱 API Endpoints

### RFID Hardware Integration
```
POST /api/rfid/tap
```
Parameter:
- `uid` (string): UID kartu RFID
- `timestamp` (optional): Waktu tap

Response:
```json
{
  "success": true,
  "message": "Absensi berhasil dicatat",
  "data": {
    "siswa": "Nama Siswa",
    "kelas": "10-A",
    "jenis_tap": "masuk",
    "waktu": "2025-01-15 07:30:00"
  }
}
```

## 🏗 Struktur Project

```
sistem-absensi-rfid/
├── app/
│   ├── Http/Controllers/          # Controllers
│   ├── Models/                    # Eloquent Models
│   └── Providers/                 # Service Providers
├── database/
│   ├── migrations/                # Database Migrations
│   └── seeders/                   # Database Seeders
├── public/                        # Public Assets
├── resources/
│   ├── views/                     # Blade Templates
│   ├── css/                       # Stylesheets
│   └── js/                        # JavaScript
├── routes/
│   ├── web.php                    # Web Routes
│   └── api.php                    # API Routes
└── storage/                       # File Storage
```

## 🎨 Fitur Design

### Modern Admin Template
- **Responsive sidebar navigation**
- **Card-based layout design**
- **Modern color schemes**
- **Interactive animations**
- **Mobile-first approach**

### UX Enhancements
- **Loading states** dan feedback visual
- **Form validation** real-time
- **Toast notifications**
- **Smooth transitions**
- **Accessibility features**

## 🔧 Konfigurasi RFID Hardware

### Setup Reader RFID
1. Pastikan RFID reader terhubung ke network
2. Konfigurasi endpoint API di reader: `http://your-domain.com/api/rfid/tap`
3. Set method POST dengan parameter `uid`
4. Test koneksi dengan tap kartu RFID

### Format Data RFID
```json
{
  "uid": "A1B2C3D4",
  "timestamp": "2025-01-15T07:30:00Z"
}
```

## 📊 Database Schema

### Tabel Utama
- **users**: Data pengguna (admin/guru)
- **kelas**: Data kelas sekolah
- **siswa**: Data siswa dengan UID RFID
- **jadwal_pelajaran**: Jadwal per kelas
- **absensi**: Record kehadiran siswa

### Relasi Database
```
users (1) -> (n) kelas (wali_kelas)
kelas (1) -> (n) siswa
kelas (1) -> (n) jadwal_pelajaran
siswa (1) -> (n) absensi
```

## 🚀 Deployment

### Production Setup
1. **Server Requirements**
   - PHP 8.2 dengan extensions: mbstring, openssl, PDO, tokenizer, XML, ctype, JSON
   - MySQL 8.0+
   - Nginx/Apache dengan rewrite module

2. **Environment Variables**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   ```

3. **Optimization Commands**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

## 🤝 Kontribusi

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b fitur/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin fitur/AmazingFeature`)
5. Buat Pull Request

## 📝 License

Project ini menggunakan lisensi [MIT License](LICENSE).

## 👨‍💻 Developer

**Aziz Taufik**
- GitHub: [@azizt91](https://github.com/azizt91)
- Email: aziz@example.com

## 📞 Support

Jika ada pertanyaan atau issue, silakan:
- Buat issue di [GitHub Issues](https://github.com/azizt91/sistem-absensi-sekolah-rfid/issues)
- Hubungi developer melalui email

---

⭐ **Jangan lupa berikan star jika project ini bermanfaat!**

🚀 **Built with ❤️ using Laravel & Tailwind CSS**