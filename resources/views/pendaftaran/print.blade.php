<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Pendaftar - {{ $selectedYear ?? '' }}</title>
  <style>
    @page { size: landscape; margin: 15mm; }
    /* Instruksi layar agar pengguna mematikan header/footer browser saat cetak */
    .print-instruction { background:#fff3cd; border:1px solid #ffeeba; color:#856404; padding:8px 12px; border-radius:4px; margin-bottom:10px; font-size:13px; }
    @media print {
      .print-instruction { display: none; }
    }
    body { font-family: Arial, Helvetica, sans-serif; color: #111; margin: 0; padding: 0; }
    .container { max-width: 100%; margin: 0 auto; padding: 20px; }
    h2 { margin: 0 0 8px 0; font-size: 18px; }
    .subtitle { margin: 0 0 16px 0; font-size: 13px; color: #444; }
    table { width: 100%; border-collapse: collapse; font-size: 10px; }
    th, td { border: 1px solid #999; padding: 6px 4px; }
    th { background: #f2f2f2; text-align: left; font-weight: bold; }
    .text-center { text-align: center; }
    .actions { margin: 12px 0 24px 0; }
    .btn { display: inline-block; padding: 8px 12px; font-size: 12px; border: 1px solid #666; background: #fff; cursor: pointer; }
    @media print {
      .actions { display: none; }
      body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
  </style>
</head>
<body>
  <div style="padding:12px;">
    <div class="print-instruction">
      <strong>Catatan:</strong> Jika PDF menampilkan tanggal/waktu di header (<span id="printTime"></span>), ikuti langkah berikut:
      <br><br>
      <strong>Chrome/Edge:</strong>
      <br>1. Saat dialog print muncul
      <br>2. Klik "More settings"
      <br>3. Nonaktifkan opsi "Headers and footers"
    </div>
  </div>
  <div class="container">
    <!-- Kop Surat -->
    <div class="header">
      <table style="width: 100%; border: none; margin-bottom: 4px;">
        <tr>

          <td style="width: 80px; border: none; text-align: center; vertical-align: middle; padding: 0;">
            <img src="{{ asset('backend/assets/img/logo 1.png') }}" alt="Logo" style="width: 65px; height: auto;">
          </td>
          <td style="border: none; text-align: center; vertical-align: middle; padding: 0 5px;">
            <div style="margin: 0; padding: 0;">
              <p style="margin: 0; padding: 0; font-size: 9px; font-weight: normal; line-height: 1.1;">YAYASAN PEMBINA LEMBAGA PENDIDIKAN PERSATUAN GURU</p>
              <p style="margin: 0; padding: 0; font-size: 9px; font-weight: normal; line-height: 1.1;">REPUBLIK INDONESIA (YP LP PGRI)</p>
              <h2 style="margin: 2px 0 1px 0; padding: 0; font-size: 20px; font-weight: bold; letter-spacing: 1px;">SMK PGRI LAWANG</h2>
              <p style="margin: 0; padding: 0; font-size: 9px; font-weight: normal; letter-spacing: 0.5px; line-height: 1.1;">SEKOLAH MENENGAH KEJURUAN</p>
              <p style="margin: 1px 0 0 0; padding: 0; font-size: 8px; font-weight: bold; line-height: 1.2;">Kompetensi Keahlian dan Sesuai Molior Waktu</p>
              <p style="margin: 0; padding: 0; font-size: 8px; font-weight: normal; line-height: 1.2;">Kimia Industri, Teknik Komputer & Jaringan</p>
              <p style="margin: 0; padding: 0; font-size: 8px; font-weight: normal; line-height: 1.2;">Jl. Dr. Mardijil 71 Lawang - Telp. (0341) 4365505 Fax. (0341) 4365505</p>
              <p style="margin: 0; padding: 0; font-size: 8px; font-weight: normal; line-height: 1.2;">E-mail: smkpgri@smkpgrilawang.sch.id &nbsp; Website : www.smkpgrilawang.sch.id</p>
            </div>
          </td>
          <td style="width: 80px; border: none; text-align: center; vertical-align: middle; padding: 0;">
            <img src="{{ asset('backend/assets/img/logo 1.png') }}" alt="Logo" style="width: 65px; height: auto;">
          </td>
        </tr>
      </table>
      <hr style="border: 1.5px solid #000; margin: 2px 0 1px 0;">
      <hr style="border: 0.5px solid #000; margin: 0 0 10px 0;">
    </div>

    <!-- Judul Dokumen -->
    <div style="text-align: center; margin-bottom: 20px;">
      <h2 style="margin: 0 0 5px 0; font-size: 16px; font-weight: bold; text-decoration: underline;">DAFTAR PENDAFTAR BARU</h2>
      <p style="margin: 0; font-size: 12px;">Tahun Akademik: <strong>{{ $selectedYear ?? '-' }}</strong></p>
    </div>

    <div class="actions">
      <button class="btn" onclick="window.print()">Cetak</button>
    </div>

    <table>
      <thead>
        <tr>
          <th class="text-center" style="width:30px">No.</th>
          <th style="width:140px">Nama</th>
          <th style="width:90px">NISN</th>
          <th style="width:110px">NIK</th>
          <th style="width:100px">Jurusan Utama</th>
          <th style="width:100px">Jurusan Cadangan</th>
          <th style="width:70px">Jenis Kelamin</th>
          <th style="width:90px">Tempat Lahir</th>
          <th style="width:80px">Tanggal Lahir</th>
          <th style="width:90px">Nomor HP</th>
        </tr>
      </thead>
      <tbody>
        @forelse($students as $i => $student)
          <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $student->name }}</td>
            <td>{{ $student->nisn ?? '-' }}</td>
            <td>{{ $student->nik ?? '-' }}</td>
            <td>{{ $student->jurusan_utama ?? '-' }}</td>
            <td>{{ $student->jurusan_cadangan ?? '-' }}</td>
            <td>{{ $student->gender ?? '-' }}</td>
            <td>{{ $student->birth_place ?? '-' }}</td>
            <td>{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '-' }}</td>
            <td>{{ $student->user->phone ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td class="text-center" colspan="11">Tidak ada data</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <script>
    window.addEventListener('load', function() { setTimeout(function(){ window.print(); }, 200); });
  </script>
  <script>
    function updatePrintTime() {
      const now = new Date();
      const formatted = now.toLocaleString('id-ID', {
        day: '2-digit',
        month: '2-digit', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Asia/Jakarta'
      });
      document.getElementById('printTime').textContent = formatted;
    }
    
    updatePrintTime();
    
    window.addEventListener('load', function() { 
      setTimeout(function(){ 
        window.print(); 
      }, 200); 
    });
  </script>
</body>
</html>
