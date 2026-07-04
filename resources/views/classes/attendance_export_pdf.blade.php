<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi Kelas</title>
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 10mm;
            }
            body {
                margin: 0;
                padding: 20px;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            .header {
                background-color: #f8f9fa !important;
                border-bottom: 3px solid #2c3e50 !important;
            }
            .info-bar {
                background-color: #ecf0f1 !important;
            }
            th {
                background-color: #3498db !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            tr:nth-child(even) {
                background-color: #f8f9fa !important;
            }
            .status-h {
                background-color: #e8f8f0 !important;
                color: #27ae60 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .status-s {
                background-color: #e8f0f8 !important;
                color: #2980b9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .status-i {
                background-color: #fef5e6 !important;
                color: #f39c12 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .status-a {
                background-color: #fce8e6 !important;
                color: #e74c3c !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .legend-color {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            table {
                box-shadow: none !important;
            }
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            background-color: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
            background: linear-gradient(to bottom, #f8f9fa, #ffffff);
            padding: 20px;
            border-radius: 8px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 1px;
        }
        .header h2 {
            font-size: 14px;
            margin: 8px 0 0 0;
            font-weight: normal;
            color: #7f8c8d;
        }
        .header .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 5px;
        }
        .info-bar {
            display: flex;
            justify-content: space-between;
            background-color: #ecf0f1;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .info-item strong {
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #bdc3c7;
            padding: 5px;
            text-align: center;
        }
        th {
            background: linear-gradient(to bottom, #3498db, #2980b9);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e8f4f8;
        }
        .text-left {
            text-align: left;
        }
        .status-letter-h {
            color: #000;
            font-weight: bold;
        }
        .status-letter-s {
            color: #000;
            font-weight: bold;
        }
        .status-letter-i {
            color: #000;
            font-weight: bold;
        }
        .status-letter-a {
            color: #000;
            font-weight: bold;
        }
        .status-h {
            color: #27ae60;
            font-weight: bold;
            background-color: #e8f8f0;
        }
        .status-s {
            color: #2980b9;
            font-weight: bold;
            background-color: #e8f0f8;
        }
        .status-i {
            color: #f39c12;
            font-weight: bold;
            background-color: #fef5e6;
        }
        .status-a {
            color: #e74c3c;
            font-weight: bold;
            background-color: #fce8e6;
        }
        .summary-row {
            background-color: #d5dbdb;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #bdc3c7;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: linear-gradient(to bottom, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .print-btn:hover {
            background: linear-gradient(to bottom, #2980b9, #1c5980);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }
        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
            font-size: 10px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
            display: inline-block;
        }
        .legend-h { background-color: #27ae60; }
        .legend-s { background-color: #2980b9; }
        .legend-i { background-color: #f39c12; }
        .legend-a { background-color: #e74c3c; }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">
        <i style="margin-right: 5px;">🖨️</i> Cetak PDF
    </button>

    <div class="container">
        <div class="header">
            <div class="school-name">SMK WIMA</div>
            <h1>Laporan Absensi Kelas</h1>
            <h2>{{ strtoupper($class->name) }}</h2>
            <h2>Tahun Akademik: {{ $class->academic_year ?? '-' }}</h2>
        </div>

        <div class="info-bar">
            <div class="info-item">
                <strong>📅 Bulan:</strong> {{ $monthName }}
            </div>
            <div class="info-item">
                <strong>👥 Jumlah Siswa:</strong> {{ $studentsWithAttendance->count() }}
            </div>
            <div class="info-item">
                <strong>📊 Hari Efektif:</strong> {{ $daysInMonth }} hari
            </div>
        </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">NISN</th>
                <th style="width: 25%;">Nama Siswa</th>
                @for($day = 1; $day <= $daysInMonth; $day++)
                    <th style="width: 2%;">{{ $day }}</th>
                @endfor
                <th style="width: 4%;">Hadir</th>
                <th style="width: 4%;">Sakit</th>
                <th style="width: 4%;">Izin</th>
                <th style="width: 4%;">Alpha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentsWithAttendance as $index => $studentData)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $studentData['student']->nisn ?? '-' }}</td>
                    <td class="text-left">{{ $studentData['student']->name }}</td>
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        <td>
                            @php
                                $status = $studentData['daily_attendance'][$day] ?? '-';
                                $statusClass = '';
                                if ($status == 'H') $statusClass = 'status-letter-h';
                                elseif ($status == 'S') $statusClass = 'status-letter-s';
                                elseif ($status == 'I') $statusClass = 'status-letter-i';
                                elseif ($status == 'A') $statusClass = 'status-letter-a';
                            @endphp
                            <span class="{{ $statusClass }}">{{ $status }}</span>
                        </td>
                    @endfor
                    <td class="status-h">{{ $studentData['present_count'] }}</td>
                    <td class="status-s">{{ $studentData['sick_count'] }}</td>
                    <td class="status-i">{{ $studentData['permit_count'] }}</td>
                    <td class="status-a">{{ $studentData['absent_count'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="legend">
        <div class="legend-item">
            <span class="legend-color legend-h"></span>
            <span>Hadir (H)</span>
        </div>
        <div class="legend-item">
            <span class="legend-color legend-s"></span>
            <span>Sakit (S)</span>
        </div>
        <div class="legend-item">
            <span class="legend-color legend-i"></span>
            <span>Izin (I)</span>
        </div>
        <div class="legend-item">
            <span class="legend-color legend-a"></span>
            <span>Alpha (A)</span>
        </div>
    </div>

    <div class="footer">
        <p><strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}</p>
        <p><strong>Oleh:</strong> {{ auth()->user()->name ?? 'System' }}</p>
        <p style="margin-top: 10px; font-style: italic;">Laporan Absensi Kelas - SMK WIMA</p>
    </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </div>
</body>
</html>
