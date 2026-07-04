# Testing Sistem Izin dan Checkout Status

## 📋 **Deskripsi Fitur**
Sistem sekarang secara otomatis menyesuaikan status checkout berdasarkan izin yang aktif pada siswa untuk hari yang sama.

## 🎯 **Logic Yang Diimplementasikan:**

### **1. Status Checkout Berdasarkan Jenis Izin:**
- **Izin Sakit** (`sakit`) → Status checkout: `sakit`
- **Izin Pulang Awal** (`pulang_awal`) → Status checkout: `izin`
- **Izin Ada Agenda** (`agenda`) → Status checkout: `izin`
- **Tidak Ada Izin** → Logic normal (tepat/lebih_awal)

### **2. Kondisi Izin Aktif:**
- Status izin: `approved`
- Tanggal izin mencakup tanggal absensi hari ini
- Support izin single date dan date range

## 🧪 **Skenario Testing:**

### **Skenario 1: Izin Sakit**
1. **Setup:**
   - Buat izin sakit untuk siswa A pada tanggal 2025-06-25
   - Status: approved
   - Type: sakit

2. **Test:**
   - Siswa A check-in pada 2025-06-25 pagi (status: tepat/terlambat)
   - Siswa A check-out pada 2025-06-25 sore

3. **Expected Result:**
   - Status check-out harus: `sakit`
   - Bukan: `tepat` atau `lebih_awal`

### **Skenario 2: Izin Pulang Awal**
1. **Setup:**
   - Buat izin pulang awal untuk siswa B pada tanggal 2025-06-25
   - Status: approved
   - Type: pulang_awal

2. **Test:**
   - Siswa B check-in pada 2025-06-25 pagi
   - Siswa B check-out pada 2025-06-25 sore (sebelum jam pulang)

3. **Expected Result:**
   - Status check-out harus: `izin`
   - Bukan: `lebih_awal`

### **Skenario 3: Izin Ada Agenda**
1. **Setup:**
   - Buat izin agenda untuk siswa C pada tanggal 2025-06-25
   - Status: approved
   - Type: agenda

2. **Test:**
   - Siswa C check-in pada 2025-06-25 pagi
   - Siswa C check-out pada 2025-06-25 sore

3. **Expected Result:**
   - Status check-out harus: `izin`

### **Skenario 4: Izin Date Range**
1. **Setup:**
   - Buat izin sakit untuk siswa D dari 2025-06-25 sampai 2025-06-27
   - Status: approved
   - Type: sakit

2. **Test:**
   - Siswa D check-in/check-out pada 2025-06-25 → status: `sakit`
   - Siswa D check-in/check-out pada 2025-06-26 → status: `sakit`
   - Siswa D check-in/check-out pada 2025-06-27 → status: `sakit`
   - Siswa D check-in/check-out pada 2025-06-28 → status normal

### **Skenario 5: Tanpa Izin**
1. **Setup:**
   - Tidak ada izin untuk siswa E

2. **Test:**
   - Siswa E check-in pada 2025-06-25 pagi
   - Siswa E check-out sebelum jam pulang → status: `lebih_awal`
   - Siswa E check-out tepat waktu → status: `tepat`

### **Skenario 6: RFID Auto Checkout**
1. **Setup:**
   - Buat izin sakit untuk siswa F pada tanggal 2025-06-25
   - Status: approved

2. **Test:**
   - Siswa F tap kartu RFID pagi → check-in normal
   - Siswa F tap kartu RFID sore → auto checkout

3. **Expected Result:**
   - Status check-out otomatis: `sakit`

### **Skenario 7: Manual Override**
1. **Setup:**
   - Buat izin sakit untuk siswa G
   - Admin input manual checkout dengan status: `tepat`

2. **Test:**
   - Admin input checkout manual

3. **Expected Result:**
   - Status check-out: `tepat` (manual override diutamakan)

## 🔧 **Cara Testing:**

### **Via Web Interface:**
1. Login sebagai admin/guru
2. Buka halaman `/student-permissions` atau `/izin`
3. Buat izin untuk siswa tertentu
4. Buka halaman attendance
5. Input check-in dan check-out manual
6. Verifikasi status checkout

### **Via RFID:**
1. Setup izin untuk siswa
2. Tap kartu RFID untuk check-in
3. Tap kartu RFID lagi untuk check-out
4. Verifikasi status checkout otomatis

### **Via Database:**
```sql
-- Cek izin aktif
SELECT * FROM student_permissions 
WHERE student_id = 'uuid_siswa' 
AND status = 'approved' 
AND start_date <= '2025-06-25'
AND (end_date IS NULL OR end_date >= '2025-06-25');

-- Cek hasil attendance
SELECT * FROM attendance 
WHERE student_id = 'uuid_siswa' 
AND date = '2025-06-25';
```

## 📁 **File Yang Diubah:**

### **Models:**
- `app/Models/StudentPermission.php` (methods: `isActiveOnDate`, `getActivePermission`, `getCheckoutStatusAttribute`)

### **Controllers:**
- `app/Http/Controllers/Backend/AttendanceController.php` (logic checkout dengan izin)
- `app/Http/Controllers/API/RFIDController.php` (auto checkout RFID dengan izin)

## ✅ **Success Criteria:**
- [ ] Status checkout otomatis menyesuaikan dengan jenis izin
- [ ] Logic berlaku untuk manual dan RFID checkout
- [ ] Support date range untuk izin multi-hari
- [ ] Manual override masih berfungsi
- [ ] Tidak meng affect logic normal untuk siswa tanpa izin
- [ ] Error handling untuk izin pending/rejected

## 🐛 **Known Issues & Solutions:**
1. **Issue:** Status tidak berubah meskipun ada izin
   **Solution:** Pastikan izin status = 'approved' dan tanggal cover

2. **Issue:** Logic fallback tidak berjalan
   **Solution:** Cek jadwal setting_schedule untuk hari tersebut

3. **Issue:** RFID tidak detect izin
   **Solution:** Verify import StudentPermission model di RFIDController
