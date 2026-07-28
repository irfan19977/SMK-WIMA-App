<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi In/Out</title>
    <style>
        @page { size: landscape; margin: 12mm; }
        body { font-family: Arial, sans-serif; color: #1f2937; }
        h1 { margin: 0 0 6px; font-size: 18px; text-align: center; }
        p { margin: 0 0 16px; text-align: center; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th, td { border: 1px solid #9ca3af; padding: 7px; text-align: left; }
        th { background: #0f766e; color: #fff; }
        .text-center { text-align: center; }
        .print-button { margin-bottom: 16px; padding: 8px 14px; border: 0; background: #0f766e; color: #fff; cursor: pointer; }
        @media print { .print-button { display: none; } }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">Cetak PDF</button>
    <h1>LAPORAN ABSENSI IN/OUT</h1>
    <p>Dicetak pada {{ now()->format('d-m-Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Status Masuk</th>
                <th>Jam Keluar</th>
                <th>Status Keluar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $index => $attendance)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $attendance->student_nisn ?? '-' }}</td>
                    <td>{{ $attendance->student_name }}</td>
                    <td>{{ $attendance->class_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}</td>
                    <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $attendance->check_in_status)) }}</td>
                    <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->check_out_status ? ucfirst(str_replace('_', ' ', $attendance->check_out_status)) : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center">Tidak ada data absensi.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
