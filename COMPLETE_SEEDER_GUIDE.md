# 🎓 Complete Academic Seeder 2025/2026 - Semester Ganjil

## 📋 Deskripsi
Seeder lengkap yang mengisi **SEMUA TABEL** database untuk tahun ajaran 2025/2026 Semester Ganjil.

## ✅ Tabel yang Diisi

### 1. **Users** (Admin, Teachers, Students, Parents)
- ✅ 1 Super Administrator
- ✅ 10 Teachers (Guru)
- ✅ 50 Students (Siswa)
- ✅ 50 Parents (Orang Tua)
- **Total: 111 users**

### 2. **Administrator**
- ✅ 1 Administrator record

### 3. **Teachers**
- ✅ 10 Teachers dengan NIP dan jurusan
- Jurusan: TKRO, TBSM, TKJ, TKI

### 4. **Parents**
- ✅ 50 Parents dengan NIK dan alamat

### 5. **Students**
- ✅ 50 Students dengan NISN, NIS
- Terdistribusi di 4 jurusan: TKRO, TBSM, TKJ, TKI
- Terdistribusi di 3 tingkat: Kelas X, XI, XII

### 6. **Subjects** (Mata Pelajaran)
- ✅ 8 Mata Pelajaran Umum
- ✅ 4 Mata Pelajaran Kejuruan TKRO
- ✅ 3 Mata Pelajaran Kejuruan TBSM
- ✅ 4 Mata Pelajaran Kejuruan TKJ
- ✅ 3 Mata Pelajaran Kejuruan TKI
- **Total: 22 subjects**

### 7. **Classes** (Kelas)
- ✅ 12 Kelas (X, XI, XII untuk 4 jurusan)
- Academic Year: 2025/2026

### 8. **Student Classes** (Penempatan Siswa)
- ✅ 50 Students assigned ke kelas sesuai jurusan

### 9. **Schedules** (Jadwal Pelajaran)
- ✅ Jadwal lengkap untuk semua kelas
- ✅ Senin - Jumat
- ✅ 5 Slot waktu per hari (07:00 - 15:30)
- ✅ Semester: Ganjil
- ✅ Academic Year: 2025/2026

### 10. **Setting Schedule**
- ✅ Jam masuk: 07:00
- ✅ Jam pulang: 15:30

### 11. **Attendances** (Absensi In/Out)
- ✅ Data absensi 30 hari terakhir
- ✅ Check in & check out
- ✅ Status: Hadir / Terlambat
- ✅ Attendance rate: 90%

### 12. **Lesson Attendances** (Absensi Per Mata Pelajaran)
- ✅ Absensi per jadwal pelajaran
- ✅ 30 hari terakhir
- ✅ Status: Hadir / Sakit / Izin / Alpa
- ✅ Attendance rate: 85%

### 13. **Student Grades** (Nilai Siswa)
- ✅ Nilai untuk setiap siswa per mata pelajaran
- ✅ Komponen: Tugas, UTS, UAS, Praktik
- ✅ Final Grade (otomatis dihitung)
- ✅ Semester: Ganjil
- ✅ Academic Year: 2025/2026

### 14. **News** (Berita)
- ✅ 3 Artikel berita
- ✅ Kategori: Pengumuman, Prestasi, Kegiatan

## 🚀 Cara Menjalankan

### Option 1: Fresh Migration + Seed (RECOMMENDED)
```bash
php artisan migrate:fresh --seed
```

### Option 2: Seed Saja (Jika sudah migrate)
```bash
php artisan db:seed
```

### Option 3: Seed Spesifik
```bash
php artisan db:seed --class=CompleteAcademicSeeder
```

## 📊 Data yang Dihasilkan

### Users & Credentials
| Type | Email | Password | Count |
|------|-------|----------|-------|
| Admin | superadmin@gmail.com | password | 1 |
| Teacher | teacher1@gmail.com - teacher10@gmail.com | password | 10 |
| Student | student1@gmail.com - student50@gmail.com | password | 50 |
| Parent | parent1@gmail.com - parent50@gmail.com | password | 50 |

### Mata Pelajaran Umum
1. Pendidikan Agama Islam (PAI)
2. Pendidikan Pancasila dan Kewarganegaraan (PPKn)
3. Bahasa Indonesia (BIND)
4. Matematika (MTK)
5. Sejarah Indonesia (SEJ)
6. Bahasa Inggris (BING)
7. Seni Budaya (SBUD)
8. Pendidikan Jasmani, Olahraga dan Kesehatan (PJOK)

### Mata Pelajaran Kejuruan

#### TKRO (Teknik Kendaraan Ringan Otomotif)
- Teknologi Dasar Otomotif (TDO)
- Pemeliharaan Mesin Kendaraan Ringan (PMKR)
- Pemeliharaan Sasis dan Pemindah Tenaga (PSPT)
- Pemeliharaan Kelistrikan Kendaraan Ringan (PKKR)

#### TBSM (Teknik Bisnis Sepeda Motor)
- Teknologi Dasar Sepeda Motor (TDSM)
- Pemeliharaan Mesin Sepeda Motor (PMSM)
- Pemeliharaan Sasis Sepeda Motor (PSSM)

#### TKJ (Teknik Komputer & Jaringan)
- Sistem Komputer (SISKOM)
- Komputer dan Jaringan Dasar (KJD)
- Pemrograman Dasar (PD)
- Administrasi Infrastruktur Jaringan (AIJ)

#### TKI (Teknik Kimia Industri)
- Dasar-Dasar Kimia Industri (DDKI)
- Kimia Dasar (KD)
- Kimia Analitik (KA)

### Kelas
| Code | Name | Grade | Major |
|------|------|-------|-------|
| X-TKRO | Kelas X TKRO | 10 | Teknik Kendaraan Ringan Otomotif |
| XI-TKRO | Kelas XI TKRO | 11 | Teknik Kendaraan Ringan Otomotif |
| XII-TKRO | Kelas XII TKRO | 12 | Teknik Kendaraan Ringan Otomotif |
| X-TBSM | Kelas X TBSM | 10 | Teknik Bisnis Sepeda Motor |
| XI-TBSM | Kelas XI TBSM | 11 | Teknik Bisnis Sepeda Motor |
| XII-TBSM | Kelas XII TBSM | 12 | Teknik Bisnis Sepeda Motor |
| X-TKJ | Kelas X TKJ | 10 | Teknik Komputer & Jaringan |
| XI-TKJ | Kelas XI TKJ | 11 | Teknik Komputer & Jaringan |
| XII-TKJ | Kelas XII TKJ | 12 | Teknik Komputer & Jaringan |
| X-TKI | Kelas X TKI | 10 | Teknik Kimia Industri |
| XI-TKI | Kelas XI TKI | 11 | Teknik Kimia Industri |
| XII-TKI | Kelas XII TKI | 12 | Teknik Kimia Industri |

### Jadwal Pelajaran
**Hari:** Senin - Jumat

**Slot Waktu:**
1. 07:00 - 08:30
2. 08:30 - 10:00
3. 10:15 - 11:45 (Setelah istirahat)
4. 12:30 - 14:00 (Setelah istirahat siang)
5. 14:00 - 15:30

## 🎯 Fitur Seeder

### 1. ✅ Tidak Duplikat
- Seeder bisa dijalankan berkali-kali
- Menggunakan `migrate:fresh` untuk reset database

### 2. ✅ Data Realistis
- Nama guru Indonesia
- NISN, NIS, NIP format valid
- Alamat di Lawang, Malang
- Nilai random 70-100
- Attendance rate realistis (85-90%)

### 3. ✅ Relasi Lengkap
- Student → Parent
- Student → Class
- Schedule → Class, Subject, Teacher
- Attendance → Student
- LessonAttendance → Schedule, Student
- StudentGrade → Student, Schedule

### 4. ✅ Data Historis
- Absensi 30 hari terakhir
- Nilai semester ganjil
- Skip weekend untuk attendance

## 📈 Statistik Data

Setelah seeder berhasil, Anda akan memiliki:
- ✅ **111 Users** (1 admin + 10 teachers + 50 students + 50 parents)
- ✅ **22 Subjects** (8 umum + 14 kejuruan)
- ✅ **12 Classes** (4 jurusan x 3 tingkat)
- ✅ **50 Student-Class assignments**
- ✅ **~150 Schedules** (12 classes x ~12 subjects)
- ✅ **~1000 Attendance records** (50 students x 20 working days)
- ✅ **~15,000 Lesson Attendance records** (50 students x ~150 schedules x 2 days)
- ✅ **~7,500 Student Grades** (50 students x ~150 schedules)
- ✅ **3 News articles**

## 🔍 Query Contoh

### Lihat Jadwal Kelas X-TKJ
```sql
SELECT 
    c.name as kelas,
    s.name as mata_pelajaran,
    t.name as guru,
    sch.day as hari,
    sch.start_time as mulai,
    sch.end_time as selesai
FROM schedules sch
JOIN classes c ON sch.class_id = c.id
JOIN subjects s ON sch.subject_id = s.id
JOIN teachers t ON sch.teacher_id = t.id
WHERE c.code = 'X-TKJ' 
  AND sch.academic_year = '2025/2026'
  AND sch.semester = 'Ganjil'
ORDER BY 
    FIELD(sch.day, 'senin', 'selasa', 'rabu', 'kamis', 'jumat'),
    sch.start_time;
```

### Lihat Nilai Siswa
```sql
SELECT 
    u.name as siswa,
    s.name as mata_pelajaran,
    sg.tugas,
    sg.uts,
    sg.uas,
    sg.praktik,
    sg.final_grade as nilai_akhir
FROM student_grades sg
JOIN students st ON sg.student_id = st.id
JOIN users u ON st.user_id = u.id
JOIN schedules sch ON sg.schedule_id = sch.id
JOIN subjects s ON sch.subject_id = s.id
WHERE sg.academic_year = '2025/2026'
  AND sg.semester = 'Ganjil'
ORDER BY u.name, s.name;
```

### Lihat Absensi Siswa
```sql
SELECT 
    u.name as siswa,
    a.date as tanggal,
    a.check_in as masuk,
    a.check_out as pulang,
    a.status
FROM attendances a
JOIN students st ON a.student_id = st.id
JOIN users u ON st.user_id = u.id
WHERE a.date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
ORDER BY a.date DESC, u.name;
```

### Rekap Kehadiran Per Mata Pelajaran
```sql
SELECT 
    u.name as siswa,
    s.name as mata_pelajaran,
    COUNT(*) as total_pertemuan,
    SUM(CASE WHEN la.status = 'Hadir' THEN 1 ELSE 0 END) as hadir,
    SUM(CASE WHEN la.status = 'Sakit' THEN 1 ELSE 0 END) as sakit,
    SUM(CASE WHEN la.status = 'Izin' THEN 1 ELSE 0 END) as izin,
    SUM(CASE WHEN la.status = 'Alpa' THEN 1 ELSE 0 END) as alpa,
    ROUND(SUM(CASE WHEN la.status = 'Hadir' THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) as persentase_hadir
FROM lesson_attendances la
JOIN students st ON la.student_id = st.id
JOIN users u ON st.user_id = u.id
JOIN schedules sch ON la.schedule_id = sch.id
JOIN subjects s ON sch.subject_id = s.id
GROUP BY st.id, s.id
ORDER BY u.name, s.name;
```

## 🎓 Kegunaan Data

Setelah seeder berhasil, Anda bisa langsung:

1. ✅ **Login** sebagai admin/teacher/student/parent
2. ✅ **Lihat Jadwal** per kelas dan per guru
3. ✅ **Input Nilai** siswa per mata pelajaran
4. ✅ **Catat Kehadiran** (attendance in/out)
5. ✅ **Catat Kehadiran Per Pelajaran** (lesson attendance)
6. ✅ **Generate Laporan** nilai dan kehadiran
7. ✅ **Lihat Statistik** per kelas, per siswa, per mata pelajaran
8. ✅ **Testing** fitur-fitur aplikasi dengan data lengkap

## ⚠️ Catatan Penting

1. **Password Default:** Semua user menggunakan password `password`
2. **Data Dummy:** Data ini untuk development/testing
3. **Fresh Migration:** Gunakan `migrate:fresh` untuk reset database
4. **Backup:** Backup database production sebelum seed
5. **Production:** Jangan gunakan seeder ini di production!

## 🐛 Troubleshooting

### Error: SQLSTATE[23000]: Integrity constraint violation
```bash
# Reset database dan seed ulang
php artisan migrate:fresh --seed
```

### Seeder terlalu lama
Seeder ini akan membuat banyak data (>20,000 records), tunggu hingga selesai (1-3 menit).

### Memory limit exceeded
Edit `php.ini`:
```ini
memory_limit = 512M
```

## 📞 Support

Jika ada error, cek log:
```bash
tail -f storage/logs/laravel.log
```

---
**🏫 SMK PGRI Lawang**  
**📅 Tahun Ajaran: 2025/2026**  
**📚 Semester: Ganjil**
