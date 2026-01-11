# Panduan Seeder Tahun Akademik 2025/2026

## Deskripsi
Seeder ini akan membuat data lengkap untuk tahun akademik 2025/2026 termasuk:
- ✅ Mata Pelajaran (Umum + Kejuruan untuk semua jurusan)
- ✅ Kelas (X, XI, XII untuk TKRO, TBSM, TKJ, TKI)
- ✅ Jadwal Pelajaran (Senin-Jumat dengan 5 slot waktu)

## Mata Pelajaran yang Dibuat

### Mata Pelajaran Umum (Semua Jurusan)
1. Pendidikan Agama Islam (PAI)
2. Pendidikan Pancasila dan Kewarganegaraan (PPKn)
3. Bahasa Indonesia (BIND)
4. Matematika (MTK)
5. Sejarah Indonesia (SEJ)
6. Bahasa Inggris (BING)
7. Seni Budaya (SBUD)
8. Pendidikan Jasmani, Olahraga dan Kesehatan (PJOK)

### Mata Pelajaran Kejuruan TKRO
1. Teknologi Dasar Otomotif (TDO)
2. Pekerjaan Dasar Teknik Otomotif (PDTO)
3. Pemeliharaan Mesin Kendaraan Ringan (PMKR)
4. Pemeliharaan Sasis dan Pemindah Tenaga (PSPT)
5. Pemeliharaan Kelistrikan Kendaraan Ringan (PKKR)

### Mata Pelajaran Kejuruan TBSM
1. Teknologi Dasar Sepeda Motor (TDSM)
2. Pemeliharaan Mesin Sepeda Motor (PMSM)
3. Pemeliharaan Sasis Sepeda Motor (PSSM)
4. Pemeliharaan Kelistrikan Sepeda Motor (PKSM)

### Mata Pelajaran Kejuruan TKJ
1. Sistem Komputer (SISKOM)
2. Komputer dan Jaringan Dasar (KJD)
3. Pemrograman Dasar (PD)
4. Dasar Design Grafis (DDG)
5. Administrasi Infrastruktur Jaringan (AIJ)
6. Administrasi Sistem Jaringan (ASJ)
7. Teknologi Layanan Jaringan (TLJ)

### Mata Pelajaran Kejuruan TKI
1. Dasar-Dasar Kimia Industri (DDKI)
2. Kimia Dasar (KD)
3. Mikrobiologi Industri (MI)
4. Kimia Analitik (KA)
5. Kimia Organik (KO)

## Kelas yang Dibuat

### TKRO (Teknik Kendaraan Ringan Otomotif)
- X-TKRO (Kelas 10)
- XI-TKRO (Kelas 11)
- XII-TKRO (Kelas 12)

### TBSM (Teknik Bisnis Sepeda Motor)
- X-TBSM (Kelas 10)
- XI-TBSM (Kelas 11)
- XII-TBSM (Kelas 12)

### TKJ (Teknik Komputer & Jaringan)
- X-TKJ (Kelas 10)
- XI-TKJ (Kelas 11)
- XII-TKJ (Kelas 12)

### TKI (Teknik Kimia Industri)
- X-TKI (Kelas 10)
- XI-TKI (Kelas 11)
- XII-TKI (Kelas 12)

## Jadwal Pelajaran

### Hari
- Senin
- Selasa
- Rabu
- Kamis
- Jumat

### Slot Waktu
1. 07:00 - 08:30
2. 08:30 - 10:00
3. 10:15 - 11:45 (setelah istirahat)
4. 12:30 - 14:00 (setelah istirahat siang)
5. 14:00 - 15:30

### Semester & Tahun Akademik
- Semester: Ganjil
- Tahun Akademik: 2025/2026

## Cara Menjalankan Seeder

### 1. Jalankan Seeder Spesifik
```bash
php artisan db:seed --class=AcademicYear2025Seeder
```

### 2. Atau Tambahkan ke DatabaseSeeder
Edit file `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call([
        UserSeeder::class,
        AdministratorSeeder::class,
        TeacherSeeder::class,
        AcademicYear2025Seeder::class,  // Tambahkan ini
        // ... seeder lainnya
    ]);
}
```

Lalu jalankan:
```bash
php artisan db:seed
```

### 3. Reset Database dan Seed Ulang (HATI-HATI!)
```bash
php artisan migrate:fresh --seed
```

## Prasyarat

Pastikan sudah ada data berikut sebelum menjalankan seeder:
1. ✅ User Admin (superadmin@gmail.com)
2. ✅ Teacher/Guru (minimal 1 guru)

Jika belum ada, jalankan seeder berikut terlebih dahulu:
```bash
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=TeacherSeeder
```

## Fitur Seeder

### 1. Tidak Duplikat
- Seeder akan mengecek data yang sudah ada
- Jika data sudah ada, tidak akan dibuat ulang
- Aman untuk dijalankan berkali-kali

### 2. Otomatis Assign Guru
- Setiap jadwal akan di-assign ke guru secara random
- Guru dipilih dari data teacher yang ada di database

### 3. Mata Pelajaran Sesuai Jurusan
- Setiap kelas akan mendapat mata pelajaran umum
- Plus mata pelajaran kejuruan sesuai jurusannya

## Setelah Seeder Berhasil

Anda bisa langsung:
1. ✅ **Input Nilai Siswa** per mata pelajaran
2. ✅ **Lihat Jadwal** per kelas
3. ✅ **Catat Kehadiran** (attendance) per mata pelajaran
4. ✅ **Generate Laporan** nilai dan kehadiran

## Troubleshooting

### Error: Admin user not found
```bash
php artisan db:seed --class=UserSeeder
```

### Error: No teachers found
```bash
php artisan db:seed --class=TeacherSeeder
```

### Error: Duplicate entry
Seeder sudah pernah dijalankan. Tidak masalah, data tidak akan duplikat.

## Struktur Database

### Tabel yang Terisi
1. `subjects` - Mata pelajaran
2. `classes` - Kelas
3. `schedules` - Jadwal pelajaran

### Relasi
- Schedule → Class (many to one)
- Schedule → Subject (many to one)
- Schedule → Teacher (many to one)

## Contoh Query Setelah Seeder

### Lihat Semua Jadwal Kelas X-TKJ
```sql
SELECT s.*, c.name as class_name, sub.name as subject_name, t.name as teacher_name
FROM schedules s
JOIN classes c ON s.class_id = c.id
JOIN subjects sub ON s.subject_id = sub.id
JOIN teachers t ON s.teacher_id = t.id
WHERE c.code = 'X-TKJ' AND s.academic_year = '2025/2026'
ORDER BY s.day, s.start_time;
```

### Lihat Mata Pelajaran per Jurusan
```sql
SELECT * FROM subjects WHERE description LIKE '%TKJ%';
```

## Support

Jika ada masalah, cek log:
```bash
tail -f storage/logs/laravel.log
```

---
**Created for SMK PGRI Lawang**
**Academic Year: 2025/2026**
