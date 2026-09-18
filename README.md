# Airline E-Ticketing Laravel

Aplikasi web pemesanan tiket pesawat berbasis Laravel. Sistem ini membantu pengguna mencari penerbangan, melakukan pemesanan, mengelola data penumpang, serta memproses pembayaran secara online.

## Biodata Pengembang

| Data | Keterangan |
| --- | --- |
| Nama | Naufal Ardra Anabil |
| Email | [naufalanabil73@gmail.com](mailto:naufalanabil73@gmail.com) |
| LinkedIn | [linkedin.com/in/naufal-ardra-anabil-9b4974283](https://linkedin.com/in/naufal-ardra-anabil-9b4974283/) |

## Tentang Project

Airline E-Ticketing adalah sistem informasi pemesanan tiket pesawat yang menyediakan alur pemesanan dari pencarian penerbangan sampai penerbitan e-ticket. Project ini dibuat sebagai aplikasi web menggunakan Laravel dengan database relasional dan integrasi pembayaran Midtrans.

## Fitur Utama

- Registrasi dan login pengguna
- Pencarian penerbangan berdasarkan bandara dan jadwal
- Informasi maskapai, bandara, dan detail penerbangan
- Pemesanan tiket dan pengisian data penumpang
- Penggunaan voucher atau kode promo
- Pembayaran melalui Midtrans
- Pembuatan e-ticket dalam format PDF
- QR code untuk validasi tiket
- Riwayat dan detail pemesanan
- Pengelolaan data penerbangan dan maskapai

## Teknologi

- PHP 8.3+
- Laravel 13
- MySQL atau SQLite
- Laravel Breeze
- Tailwind CSS
- Vite
- Midtrans Payment Gateway
- Dompdf
- Simple Software IO QR Code

## Persyaratan

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- MySQL atau SQLite

## Instalasi

Clone repository dan masuk ke folder project:

```bash
git clone https://github.com/naufalanabil/airline-eticketing-laravel.git
cd airline-eticketing-laravel
```

Install dependency:

```bash
composer install
npm install
```

Salin file environment dan buat application key:

```bash
copy .env.example .env
php artisan key:generate
```

Atur koneksi database dan konfigurasi Midtrans di file `.env`, kemudian jalankan migration dan seeder:

```bash
php artisan migrate --seed
```

Build asset frontend dan jalankan aplikasi:

```bash
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser.

## Konfigurasi Midtrans

Isi konfigurasi berikut di `.env` menggunakan credential dari akun Midtrans kamu:

```env
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_IS_PRODUCTION=false
```

Jangan commit file `.env` atau credential asli ke repository publik.

## Menjalankan Test

```bash
php artisan test
```

## Struktur Folder Penting

```text
app/                 Logika aplikasi, model, controller, dan mail
database/            Migration, factory, dan seeder
resources/           View, JavaScript, dan stylesheet
routes/              Route web, API, dan autentikasi
public/              Entry point dan asset publik
tests/               Feature test dan unit test
```

## Lisensi

Project ini menggunakan lisensi MIT.
