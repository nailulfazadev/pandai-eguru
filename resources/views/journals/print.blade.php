<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jurnal & Presensi - {{ $classroom->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            margin-bottom: 30px;
        }
        .info-grid div {
            padding: 5px 0;
        }
        .info-grid .label {
            font-weight: bold;
        }
        h2 {
            font-size: 18px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .signature p {
            margin: 0;
        }
        .signature .name {
            margin-top: 80px;
            font-weight: bold;
            text-decoration: underline;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .no-print button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Cetak Dokumen Ini</button>
    </div>

    <div class="container">
        <div class="header">
            <h1>JURNAL MENGAJAR DAN PRESENSI SISWA</h1>
        </div>

        <div class="info-grid">
            <div class="label">Nama Guru</div>
            <div>: {{ auth()->user()->name }}</div>
            <div class="label">Mata Pelajaran</div>
            <div>: {{ $classroom->subject }}</div>
            <div class="label">Kelas</div>
            <div>: {{ $classroom->name }}</div>
            <div class="label">Tanggal / Pertemuan</div>
            <div>: {{ \Carbon\Carbon::parse($journal->date)->format('d F Y') }} (Ke-{{ $journal->meeting_number }})</div>
        </div>

        <h2>A. Jurnal Materi</h2>
        <table>
            <tr>
                <th style="width: 30%;">Topik / Konten</th>
                <td>{{ $journal->title }}</td>
            </tr>
            <tr>
                <th>Capaian Kompetensi</th>
                <td>{{ $journal->competency }}</td>
            </tr>
            <tr>
                <th>Kegiatan Belajar Mengajar</th>
                <td style="white-space: pre-line;">{{ $journal->activity }}</td>
            </tr>
            <tr>
                <th>Ringkasan Tambahan</th>
                <td style="white-space: pre-line;">{{ $journal->content }}</td>
            </tr>
            <tr>
                <th>Catatan Khusus (KBM)</th>
                <td>{{ $journal->notes }}</td>
            </tr>
        </table>

        <h2>B. Presensi Siswa</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Nama Siswa</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($journal->attendances as $index => $attendance)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $attendance->student->name }}</td>
                    <td class="text-center uppercase">{{ strtoupper($attendance->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $hadir = $journal->attendances->where('status', 'hadir')->count();
            $sakit = $journal->attendances->where('status', 'sakit')->count();
            $izin = $journal->attendances->where('status', 'izin')->count();
            $alpa = $journal->attendances->where('status', 'alpa')->count();
        @endphp
        
        <p><strong>Rekapitulasi:</strong> Hadir ({{ $hadir }}), Sakit ({{ $sakit }}), Izin ({{ $izin }}), Alpa ({{ $alpa }})</p>

        <div class="signature">
            <p>Mengetahui,</p>
            <p>Guru Mata Pelajaran</p>
            <p class="name">{{ auth()->user()->name }}</p>
            @if(auth()->user()->nip)
            <p>NIP. {{ auth()->user()->nip }}</p>
            @endif
        </div>
    </div>
</body>
</html>
