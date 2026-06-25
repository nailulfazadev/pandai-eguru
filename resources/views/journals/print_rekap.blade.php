<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $printTitle }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            line-height: 1.3;
            margin: 0;
            padding: 20px;
            font-size: 11px;
        }
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        .header-title {
            text-align: center;
            background-color: #f9cb9c;
            padding: 8px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid #000;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border: none;
        }
        .info-table td {
            padding: 2px 5px;
            border: none;
            vertical-align: top;
            font-weight: bold;
        }
        .info-table td:nth-child(1), .info-table td:nth-child(4) {
            width: 15%;
        }
        .info-table td:nth-child(2), .info-table td:nth-child(5) {
            width: 2%;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }
        .main-table th {
            background-color: #d9ead3;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .signature {
            width: 100%;
            margin-top: 30px;
        }
        .signature td {
            width: 50%;
            border: none;
            text-align: center;
        }
        .signature .name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .no-print button {
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Cetak Dokumen Ini</button>
    </div>

    <div class="header-title">
        {{ $printTitle }}
    </div>

    <table class="info-table">
        <tr>
            <td>Nama Sekolah</td><td>:</td><td>{{ $user->school_name ?? '...........................' }}</td>
            <td>Kurikulum</td><td>:</td><td>Merdeka</td>
        </tr>
        <tr>
            <td>Mata Pelajaran</td><td>:</td><td>Sesuai Jadwal</td>
            <td>Nama Guru</td><td>:</td><td>{{ $user->name }}</td>
        </tr>
        <tr>
            <td>Semester</td><td>:</td><td>...........................</td>
            <td>NIP</td><td>:</td><td>{{ $user->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tahun Pelajaran</td><td>:</td><td>...........................</td>
            <td>Rentang</td><td>:</td><td>{{ $periodText }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 8%;">Hari/Tanggal</th>
                <th rowspan="2" style="width: 7%;">Kelas</th>
                <th rowspan="2" style="width: 5%;">Perte<br>muan<br>Ke-</th>
                <th rowspan="2" style="width: 18%;">Capaian Kompetensi</th>
                <th rowspan="2" style="width: 18%;">Konten</th>
                <th rowspan="2" style="width: 18%;">Kegiatan Belajar Mengajar</th>
                <th rowspan="2" style="width: 5%;">Kehadiran<br>Siswa</th>
                <th colspan="3">Absensi Siswa</th>
                <th rowspan="2" style="width: 15%;">Keterangan dalam proses KBM</th>
            </tr>
            <tr>
                <th style="width: 2%;">S</th>
                <th style="width: 2%;">I</th>
                <th style="width: 2%;">A</th>
            </tr>
        </thead>
        <tbody>
            @if($groupedJournals->isEmpty())
            <tr>
                <td colspan="11" class="text-center" style="padding: 20px;">Tidak ada data jurnal pada rentang tanggal ini.</td>
            </tr>
            @else
                @foreach($groupedJournals as $date => $journals)
                    @php 
                        $first = true;
                        $rowspan = $journals->count();
                        $formattedDate = \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd') . ',<br>' . \Carbon\Carbon::parse($date)->format('d/m/Y');
                    @endphp
                    @foreach($journals as $journal)
                        @php
                            $hadir = $journal->attendances->where('status', 'hadir')->count();
                            $sakit = $journal->attendances->where('status', 'sakit')->count();
                            $izin = $journal->attendances->where('status', 'izin')->count();
                            $alpa = $journal->attendances->where('status', 'alpa')->count();
                        @endphp
                        <tr>
                            @if($first)
                                <td rowspan="{{ $rowspan }}" style="vertical-align: top;">{!! $formattedDate !!}</td>
                                @php $first = false; @endphp
                            @endif
                            <td class="text-center">{{ $journal->classroom->name }}</td>
                            <td class="text-center">{{ $journal->meeting_number }}</td>
                            <td>{{ $journal->competency }}</td>
                            <td>{{ $journal->title }}</td>
                            <td>{{ $journal->activity }}</td>
                            <td class="text-center">{{ $hadir }}</td>
                            <td class="text-center">{{ $sakit }}</td>
                            <td class="text-center">{{ $izin }}</td>
                            <td class="text-center">{{ $alpa }}</td>
                            <td>{{ $journal->notes }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endif
        </tbody>
    </table>

    <table class="signature">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala Sekolah
                <div class="name">{{ $user->principal_name ?? '.......................................' }}</div>
                <div>NIP. {{ $user->principal_nip ?? '.........................' }}</div>
            </td>
            <td>
                ......................., ...........................<br>
                Guru Mata Pelajaran
                <div class="name">{{ $user->name }}</div>
                <div>NIP. {{ $user->nip ?? '.........................' }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
