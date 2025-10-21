# Instalasi PhpSpreadsheet untuk Export Excel

## Langkah Instalasi

Untuk menggunakan fitur **Export Excel**, Anda perlu menginstall library PhpSpreadsheet terlebih dahulu.

### 1. Install via Composer

Jalankan command berikut di terminal/command prompt:

```bash
composer require phpoffice/phpspreadsheet
```

### 2. Verifikasi Instalasi

Setelah instalasi selesai, pastikan library sudah terdaftar di `composer.json`:

```json
"require": {
    "phpoffice/phpspreadsheet": "^1.29"
}
```

### 3. Clear Cache (Opsional)

Jika diperlukan, clear cache Laravel:

```bash
php artisan config:clear
php artisan cache:clear
```

## Fitur yang Tersedia

Setelah instalasi berhasil, Anda dapat menggunakan:

- **Export Excel** - Format .xlsx dengan styling profesional
- **Export CSV** - Format .csv sederhana (sudah tersedia tanpa library tambahan)
- **Cetak PDF** - Print ke PDF via browser

## Catatan

- Library PhpSpreadsheet memerlukan PHP 7.4 atau lebih tinggi
- File Excel yang dihasilkan akan memiliki:
  - Header dengan judul dan informasi sekolah
  - Styling profesional (border, warna, alignment)
  - Auto-filter untuk memudahkan sorting
  - Freeze panes agar header tetap terlihat saat scroll
  - Format tanggal Indonesia (dd/mm/yyyy)

## Troubleshooting

Jika terjadi error saat instalasi:

1. Pastikan Composer sudah terinstall dan up-to-date
2. Cek versi PHP: `php -v` (minimal 7.4)
3. Pastikan extension PHP yang diperlukan sudah aktif:
   - ext-gd
   - ext-zip
   - ext-xml
