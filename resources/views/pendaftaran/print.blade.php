<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Pendaftar - {{ $selectedYear ?? '' }}</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; color: #111; }
    .container { max-width: 1000px; margin: 0 auto; padding: 24px; }
    h2 { margin: 0 0 8px 0; }
    .subtitle { margin: 0 0 16px 0; font-size: 14px; color: #444; }
    table { width: 100%; border-collapse: collapse; font-size: 12px; }
    th, td { border: 1px solid #999; padding: 8px; }
    th { background: #f2f2f2; text-align: left; }
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
  <div class="container">
    <h2>Daftar Pendaftar Baru</h2>
    <p class="subtitle">Tahun Akademik: <strong>{{ $selectedYear ?? '-' }}</strong></p>

    <div class="actions">
      <button class="btn" onclick="window.print()">Cetak</button>
    </div>

    <table>
      <thead>
        <tr>
          <th class="text-center" style="width:60px">No.</th>
          <th>Nama</th>
          <th style="width:140px">NISN</th>
          <th style="width:220px">Email</th>
          <th style="width:140px">Phone</th>
          <th style="width:120px">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($students as $i => $student)
          <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $student->name }}</td>
            <td>{{ $student->nisn ?? '-' }}</td>
            <td>{{ $student->email ?? ($student->user->email ?? '-') }}</td>
            <td>{{ $student->phone ?? ($student->user->phone ?? '-') }}</td>
            <td>{{ $student->status ?? 'calon siswa' }}</td>
          </tr>
        @empty
          <tr>
            <td class="text-center" colspan="6">Tidak ada data</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <script>
    window.addEventListener('load', function() { setTimeout(function(){ window.print(); }, 200); });
  </script>
</body>
</html>
