<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Akademik - Semua Mapel</title>
  <style>
    @page { size: A4 portrait; margin: 15mm; }
    body { font-family: 'Times New Roman', Times, serif; color:#111; margin:0; padding:0; }
    .page { page-break-after: always; padding: 20px; }
    .page:last-child { page-break-after: auto; }
    table { width:100%; border-collapse:collapse; font-size: 11px; }
    th, td { border:1px solid #999; padding:6px 4px; }
    th { background:#f2f2f2; text-align:left; font-weight:bold; }
    .text-center { text-align:center; }
    .kop td { border: none !important; }
  </style>
</head>
<body>
  @foreach($pages as $page)
    <div class="page">
      <!-- Kop Surat -->
      <table class="kop" style="width: 100%; margin-bottom: 4px;">
        <tr>
          <td style="width: 80px; text-align: center; vertical-align: middle; padding: 0;">
            <img src="{{ asset('backend/assets/img/logo yayasan.png') }}" alt="Logo" style="width: 100px; height: auto;">
          </td>
          <td style="text-align: center; vertical-align: middle; padding: 0 5px;">
            <div style="margin: 0; padding: 0;">
              <p style="margin: 0; padding: 0; font-size: 12px; line-height: 1.1;">YAYASAN PEMBINA LEMBAGA PENDIDIKAN</p>
              <p style="margin: 0; padding: 0; font-size: 12px; line-height: 1.1;">PERSATUAN GURU REPUBLIK INDONESIA (YP LP PGRI)</p>
              <p style="margin: 0; padding: 0; font-size: 12px; line-height: 1.1;">KABUPATEN MALANG</p>
              <h2 style="margin: 2px 0 1px 0; padding: 0; font-size: 20px; font-weight: bold; letter-spacing: 1px;">SMK PGRI LAWANG</h2>
              <p style="margin: 0; padding: 0; font-size: 12px; line-height: 1.2;">Kompetensi Keahlian:</p>
              <p style="margin: 1px 0 0 0; padding: 0; font-size: 12px; font-weight: bold; line-height: 1.2;">Teknik Kendaraan Ringan Otomotif,Teknik Bisnis dan Sepeda Motor</p>
              <p style="margin: 0; padding: 0; font-size: 12px; font-weight: bold; line-height: 1.2;">Teknik Kimia Industri, Teknik Komputer & Jaringan</p>
              <p style="margin: 0; padding: 0; font-size: 12px; line-height: 1.2;">JL. Dr. Wahidin 17 Lawang Telp. (0341) 4395005 Fax. (0341) 4395005 Lawang</p>
              <p style="margin: 0; padding: 0; font-size: 12px; line-height: 1.2;">Website : www.smkpgri-lawang.sch.id &nbsp; E-mail: www.cssmkpgrilawang@gmail.com</p>
            </div>
          </td>
          <td style="width: 80px; text-align: center; vertical-align: middle; padding: 0;">
            <img src="{{ asset('backend/assets/img/logo 1.png') }}" alt="Logo" style="width: 100px; height: auto;">
          </td>
        </tr>
      </table>
      <hr style="border: 1.5px solid #000; margin: 2px 0 1px 0;">
      <hr style="border: 0.5px solid #000; margin: 0 0 10px 0;">

      <!-- Header Halaman -->
      <div style="text-align: center; margin-bottom: 12px;">
        <h2 style="margin: 0 0 5px 0; font-size: 16px; font-weight: bold; text-decoration: underline;">LAPORAN NILAI SEMESTER - SEMUA MAPEL</h2>
        <p style="margin: 0; font-size: 12px;">Kelas: <strong>{{ $class->name ?? '-' }}</strong> &nbsp; | &nbsp; Semester: <strong>{{ strtoupper($semester) }}</strong> &nbsp; | &nbsp; Tahun Akademik: <strong>{{ $academicYear }}</strong></p>
        <p style="margin: 0; font-size: 12px;">Nama: <strong>{{ $page['student']['name'] }}</strong> &nbsp; | &nbsp; NISN: <strong>{{ $page['student']['nisn'] ?? '-' }}</strong> &nbsp; | &nbsp; No Absen: <strong>{{ $page['student']['no_absen'] ?? '-' }}</strong></p>
      </div>

      <table>
        <thead>
          <tr>
            <th style="width:40px">No.</th>
            <th>Mapel</th>
            <th style="width:70px" class="text-center">UTS</th>
            <th style="width:70px" class="text-center">UAS</th>
            <th style="width:80px" class="text-center">Tugas</th>
            <th style="width:90px" class="text-center">Nilai Akhir</th>
            <th style="width:80px" class="text-center">Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($page['subjects'] as $i => $sub)
            <tr>
              <td class="text-center">{{ $i + 1 }}</td>
              <td>{{ $sub['subject_name'] }}</td>
              <td class="text-center">{{ isset($sub['uts']) ? number_format($sub['uts'], 2) : '-' }}</td>
              <td class="text-center">{{ isset($sub['uas']) ? number_format($sub['uas'], 2) : '-' }}</td>
              <td class="text-center">{{ isset($sub['tugas']) ? number_format($sub['tugas'], 2) : '-' }}</td>
              <td class="text-center"><strong>{{ isset($sub['final']) ? number_format($sub['final'], 2) : '-' }}</strong></td>
              <td class="text-center">{{ $sub['status'] }}</td>
            </tr>
          @empty
            <tr>
              <td class="text-center" colspan="7">Tidak ada data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  @endforeach
  <script>
    window.addEventListener('load', function(){ setTimeout(function(){ window.print(); }, 300); });
  </script>
</body>
</html>
