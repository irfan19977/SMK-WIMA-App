<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Akademik - {{ $academicYear }} - {{ strtoupper($semester) }}</title>
  <style>
    @page { size: A4 portrait; margin: 15mm; }
    .print-instruction { background:#fff3cd; border:1px solid #ffeeba; color:#856404; padding:8px 12px; border-radius:4px; margin-bottom:10px; font-size:13px; }
    @media print { .print-instruction { display:none; } body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } .actions{display:none;} }
    body { font-family: 'Times New Roman', Times, serif; color:#111; margin:0; padding:0; }
    .container { max-width:100%; margin:0 auto; padding:20px; }
    h2 { margin: 0 0 8px 0; font-size: 18px; }
    table { width:100%; border-collapse:collapse; font-size: 11px; }
    th, td { border:1px solid #999; padding:6px 4px; }
    th { background:#f2f2f2; text-align:left; font-weight:bold; }
    .text-center { text-align:center; }
  </style>
</head>
<body>
  <div style="padding:12px;">
    <div class="print-instruction">
      <strong>Catatan:</strong> Nonaktifkan header/footer saat mencetak PDF.
    </div>
  </div>
  <div class="container">
    <!-- Kop Surat -->
    <div class="header">
      <table style="width: 100%; border: none; margin-bottom: 4px;">
        <tr>
          <td style="width: 80px; border: none; text-align: center; vertical-align: middle; padding: 0;">
            <img src="{{ asset('backend/assets/img/logo yayasan.png') }}" alt="Logo" style="width: 100px; height: auto;">
          </td>
          <td style="border: none; text-align: center; vertical-align: middle; padding: 0 5px;">
            <div style="margin: 0; padding: 0;">
              <p style="margin: 0; padding: 0; font-size: 12px; font-weight: normal; line-height: 1.1;">YAYASAN PEMBINA LEMBAGA PENDIDIKAN </p>
              <p style="margin: 0; padding: 0; font-size: 12px; font-weight: normal; line-height: 1.1;">PERSATUAN GURU REPUBLIK INDONESIA (YP LP PGRI)</p>
              <p style="margin: 0; padding: 0; font-size: 12px; font-weight: normal; line-height: 1.1;">KABUPATEN MALANG</p>
              <h2 style="margin: 2px 0 1px 0; padding: 0; font-size: 20px; font-weight: bold; letter-spacing: 1px;">SMK PGRI LAWANG</h2>
              <p style="margin: 0; padding: 0; font-size: 12px; font-weight: normal; letter-spacing: 0.5px; line-height: 1.1;">Kompetensi Keahlian:</p>
              <p style="margin: 1px 0 0 0; padding: 0; font-size: 12px; font-weight: bold; line-height: 1.2;">Teknik Kendaraan Ringan Otomotif,Teknik Bisnis dan Sepeda Motor</p>
              <p style="margin: 0; padding: 0; font-size: 12px; font-weight: bold; line-height: 1.2;">Teknik Kimia Industri, Teknik Komputer & Jaringan</p>
              <p style="margin: 0; padding: 0; font-size: 12px; font-weight: normal; line-height: 1.2;">JL. Dr. Wahidin 17 Lawang Telp. (0341) 4395005 Fax. (0341) 4395005 Lawang</p>
              <p style="margin: 0; padding: 0; font-size: 12px; font-weight: normal; line-height: 1.2;">Website : www.smkpgri-lawang.sch.id &nbsp; E-mail: www.cssmkpgrilawang@gmail.com</p>
            </div>
          </td>
          <td style="width: 80px; border: none; text-align: center; vertical-align: middle; padding: 0;">
            <img src="{{ asset('backend/assets/img/logo 1.png') }}" alt="Logo" style="width: 100px; height: auto;">
          </td>
        </tr>
      </table>
      <hr style="border: 1.5px solid #000; margin: 2px 0 1px 0;">
      <hr style="border: 0.5px solid #000; margin: 0 0 10px 0;">
    </div>

    <!-- Judul Dokumen -->
    <div style="text-align: center; margin-bottom: 12px;">
      <h2 style="margin: 0 0 5px 0; font-size: 16px; font-weight: bold; text-decoration: underline;">LAPORAN NILAI AKADEMIK</h2>
      <p style="margin: 0; font-size: 12px;">Kelas: <strong>{{ $class->name ?? '-' }}</strong> &nbsp; | &nbsp; Mapel: <strong>{{ $subject->name ?? '-' }}</strong></p>
      <p style="margin: 0; font-size: 12px;">Semester: <strong>{{ strtoupper($semester) }}</strong> &nbsp; | &nbsp; Tahun Akademik: <strong>{{ $academicYear }}</strong></p>
    </div>

    <div class="actions" style="margin-bottom: 10px;">
      <button class="btn" onclick="window.print()" style="display:inline-block; padding:8px 12px; font-size:12px; border:1px solid #666; background:#fff; cursor:pointer;">Cetak</button>
    </div>

    <table>
      <thead>
        <tr>
          <th class="text-center" style="width:40px">No.</th>
          <th style="width:140px">Nama</th>
          <th style="width:90px">NISN</th>
          <th style="width:60px">No Absen</th>
          <th style="width:60px">UTS</th>
          <th style="width:60px">UAS</th>
          <th style="width:70px">Tugas</th>
          <th style="width:70px">Sikap</th>
          <th style="width:80px">Nilai Akhir</th>
          <th style="width:70px">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $i => $row)
          <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $row['student_name'] ?? '-' }}</td>
            <td>{{ $row['student_nisn'] ?? '-' }}</td>
            <td class="text-center">{{ $row['no_absen'] ?? '-' }}</td>
            <td class="text-center">{{ isset($row['uts']) ? number_format($row['uts'], 2) : '-' }}</td>
            <td class="text-center">{{ isset($row['uas']) ? number_format($row['uas'], 2) : '-' }}</td>
            <td class="text-center">{{ isset($row['tugas']) ? number_format($row['tugas'], 2) : '-' }}</td>
            <td class="text-center">{{ isset($row['sikap']) ? number_format($row['sikap'], 2) : '-' }}</td>
            <td class="text-center"><strong>{{ isset($row['final']) ? number_format($row['final'], 2) : '-' }}</strong></td>
            <td class="text-center">{{ $row['status'] ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td class="text-center" colspan="10">Tidak ada data</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <script>
    window.addEventListener('load', function(){ setTimeout(function(){ window.print(); }, 300); });
  </script>
</body>
</html>
