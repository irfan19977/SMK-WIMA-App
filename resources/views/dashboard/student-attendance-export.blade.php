@php
    // ====== PREPARE DATA ======
    // Index attendance by date
    $attByDate = $attendances->keyBy(fn($a) => \Carbon\Carbon::parse($a->date)->format('Y-m-d'));

    // Semua tanggal unik (gabungan attendance + lesson)
    $allDatesRaw = collect();
    foreach ($attendances as $a) { $allDatesRaw->push(\Carbon\Carbon::parse($a->date)->format('Y-m-d')); }
    foreach ($lessonAttendances as $la) { $allDatesRaw->push(\Carbon\Carbon::parse($la->date)->format('Y-m-d')); }
    $allDates = $allDatesRaw->unique()->sort()->values();

    // Index lessons by date -> subject
    $lessonByDate = [];
    foreach ($lessonAttendances as $la) {
        $d = \Carbon\Carbon::parse($la->date)->format('Y-m-d');
        $lessonByDate[$d][$la->subject_name] = $la;
    }

    // Semua mata pelajaran unik
    $allSubjects = $lessonAttendances->pluck('subject_name')->unique()->sort()->values();

    // Ringkasan in/out (terlambat = hadir)
    $ioHadir    = $attendances->whereIn('check_in_status', ['tepat','terlambat'])->count();
    $ioSakit    = $attendances->where('check_in_status', 'sakit')->count();
    $ioIzin     = $attendances->where('check_in_status', 'izin')->count();
    $ioAlpha    = $attendances->whereNotIn('check_in_status', ['tepat','terlambat','izin','sakit'])->count();

    // Ringkasan per subject
    $subjectStats = [];
    foreach ($allSubjects as $subj) {
        $rows = $lessonAttendances->where('subject_name', $subj);
        $subjectStats[$subj] = [
            'H' => $rows->whereIn('check_in_status',['hadir','terlambat'])->count(),
            'S' => $rows->where('check_in_status','sakit')->count(),
            'I' => $rows->where('check_in_status','izin')->count(),
            'A' => $rows->where('check_in_status','alpha')->count(),
        ];
    }

    $totalDates = $allDates->count();
    $periodeLabel = ($startDate && $endDate) ? $startDate . ' s/d ' . $endDate : 'Semua Data';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kehadiran - {{ $student->name }}</title>
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
        .status-letter-h { color: #27ae60; font-weight: bold; }
        .status-letter-s { color: #2980b9; font-weight: bold; }
        .status-letter-i { color: #f39c12; font-weight: bold; }
        .status-letter-a { color: #e74c3c; font-weight: bold; }
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
        .section-divider {
            margin-top: 30px;
            margin-bottom: 5px;
            font-size: 13px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            <h1>Laporan Kehadiran Siswa</h1>
            <h2>{{ $student->name }}</h2>
            <h2>NISN: {{ $student->nisn }} &nbsp;|&nbsp; Kelas: {{ $studentClass ? $studentClass->name : '-' }}</h2>
        </div>

        <div class="info-bar">
            <div class="info-item">
                <strong>📅 Periode:</strong> {{ $periodeLabel }}
            </div>
            <div class="info-item">
                <strong>📊 Total Hari:</strong> {{ $attendances->count() }} hari
            </div>
            <div class="info-item">
                <strong>📚 Total Pertemuan:</strong> {{ $lessonAttendances->count() }} pertemuan
            </div>
        </div>

        {{-- ====== TABEL 1: ABSENSI IN/OUT ====== --}}
        <div class="section-divider">📋 Absensi Masuk / Keluar</div>

        @php
            $ioDates = $attendances->map(fn($a) => \Carbon\Carbon::parse($a->date)->format('Y-m-d'))->unique()->sort()->values();
        @endphp

        @if($ioDates->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Keterangan</th>
                    @foreach($ioDates as $date)
                        <th style="width: 2%;">{{ \Carbon\Carbon::parse($date)->format('d') }}</th>
                    @endforeach
                    <th style="width: 4%;">Hadir</th>
                    <th style="width: 4%;">Sakit</th>
                    <th style="width: 4%;">Izin</th>
                    <th style="width: 4%;">Alpha</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td class="text-left">Masuk (Check In)</td>
                    @foreach($ioDates as $date)
                        @php $att = $attByDate->get($date); $s = $att ? $att->check_in_status : null; @endphp
                        <td>
                            @if($s=='tepat') <span class="status-letter-h">H</span>
                            @elseif($s=='terlambat') <span class="status-letter-i">H*</span>
                            @elseif($s=='izin') <span class="status-letter-i">I</span>
                            @elseif($s=='sakit') <span class="status-letter-s">S</span>
                            @elseif($s) <span class="status-letter-a">A</span>
                            @else -
                            @endif
                        </td>
                    @endforeach
                    <td class="status-h">{{ $ioHadir }}</td>
                    <td class="status-s">{{ $ioSakit }}</td>
                    <td class="status-i">{{ $ioIzin }}</td>
                    <td class="status-a">{{ $ioAlpha }}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td class="text-left">Pulang (Check Out)</td>
                    @foreach($ioDates as $date)
                        @php $att = $attByDate->get($date); $so = $att ? $att->check_out_status : null; @endphp
                        <td>
                            @if($so=='tepat') <span class="status-letter-h">H</span>
                            @elseif($so=='terlambat') <span class="status-letter-i">H*</span>
                            @elseif($so=='izin') <span class="status-letter-i">I</span>
                            @elseif($so=='sakit') <span class="status-letter-s">S</span>
                            @elseif($so) <span class="status-letter-a">A</span>
                            @else -
                            @endif
                        </td>
                    @endforeach
                    <td colspan="4"></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td class="text-left">Jam Masuk</td>
                    @foreach($ioDates as $date)
                        @php $att = $attByDate->get($date); @endphp
                        <td>{{ $att && $att->check_in ? substr($att->check_in, 0, 5) : '-' }}</td>
                    @endforeach
                    <td colspan="4"></td>
                </tr>
            </tbody>
        </table>
        @else
            <p style="text-align:center;color:#999;font-size:11px;padding:15px 0;">Tidak ada data kehadiran in/out.</p>
        @endif

        {{-- ====== TABEL 2: ABSENSI PEMBELAJARAN ====== --}}
        <div class="section-divider" style="margin-top:30px;">📚 Absensi Pembelajaran</div>

        @php
            $lessonDates = $lessonAttendances->map(fn($la) => \Carbon\Carbon::parse($la->date)->format('Y-m-d'))->unique()->sort()->values();
        @endphp

        @if($lessonDates->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Mata Pelajaran</th>
                    @foreach($lessonDates as $date)
                        <th style="width: 2%;">{{ \Carbon\Carbon::parse($date)->format('d') }}</th>
                    @endforeach
                    <th style="width: 4%;">Hadir</th>
                    <th style="width: 4%;">Sakit</th>
                    <th style="width: 4%;">Izin</th>
                    <th style="width: 4%;">Alpha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allSubjects as $index => $subj)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $subj }}</td>
                    @foreach($lessonDates as $date)
                        @php $ls = $lessonByDate[$date][$subj]->check_in_status ?? null; @endphp
                        <td>
                            @if($ls=='hadir') <span class="status-letter-h">H</span>
                            @elseif($ls=='terlambat') <span class="status-letter-i">H*</span>
                            @elseif($ls=='izin') <span class="status-letter-i">I</span>
                            @elseif($ls=='sakit') <span class="status-letter-s">S</span>
                            @elseif($ls=='alpha') <span class="status-letter-a">A</span>
                            @else -
                            @endif
                        </td>
                    @endforeach
                    <td class="status-h">{{ $subjectStats[$subj]['H'] }}</td>
                    <td class="status-s">{{ $subjectStats[$subj]['S'] }}</td>
                    <td class="status-i">{{ $subjectStats[$subj]['I'] }}</td>
                    <td class="status-a">{{ $subjectStats[$subj]['A'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p style="text-align:center;color:#999;font-size:11px;padding:15px 0;">Tidak ada data absensi pembelajaran.</p>
        @endif

        <div class="legend">
            <div class="legend-item">
                <span class="legend-color legend-h"></span>
                <span>Hadir (H)</span>
            </div>
            <div class="legend-item">
                <span class="legend-color legend-i"></span>
                <span>Hadir Terlambat (H*)</span>
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
            <p style="margin-top: 10px; font-style: italic;">Laporan Kehadiran Siswa - SMK WIMA</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
