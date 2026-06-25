import os

views = {}

views['resources/views/journals/create.blade.php'] = """@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('classrooms.show', $classroom) }}" class="text-sky-blue hover:underline text-sm mb-2 inline-block">&larr; Batal & Kembali</a>
        <h1 class="text-heading font-feather text-almost-black">Isi Jurnal & Presensi</h1>
        <p class="text-body text-graphite">Kelas: {{ $classroom->name }} | Mapel: {{ $classroom->subject }}</p>
    </div>
</div>

<form action="{{ route('journals.store', $classroom) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @csrf
    
    @if($schedule)
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
    @endif

    <!-- Kolom Kiri: Jurnal -->
    <div class="md:col-span-1 space-y-4">
        <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray shadow-sm">
            <div class="flex justify-between items-center mb-4 border-b border-cloud-gray pb-2">
                <h2 class="font-bold text-lg text-charcoal">Jurnal Mengajar</h2>
                @if($schedule)
                <span class="text-xs bg-duo-green-light text-duo-green px-2 py-1 rounded font-bold">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                @endif
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Pertemuan Ke-</label>
                    <input type="number" name="meeting_number" placeholder="Contoh: 1" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Topik/Konten Materi</label>
                    <input type="text" name="title" placeholder="Contoh: Pengenalan Sel Hewan" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Capaian Kompetensi</label>
                    <textarea name="competency" rows="2" class="w-full border-2 border-cloud-gray rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition resize-y" placeholder="Cth: Siswa dapat menyebutkan bagian sel..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Kegiatan Belajar Mengajar (KBM)</label>
                    <textarea name="activity" rows="3" class="w-full border-2 border-cloud-gray rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition resize-y" placeholder="Cth: Penyampaian materi, diskusi kelompok..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Uraian / Ringkasan Tambahan</label>
                    <textarea name="content" rows="2" class="w-full border-2 border-cloud-gray rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition resize-y" placeholder="Opsional..." required>-</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Keterangan / Catatan Khusus</label>
                    <input type="text" name="notes" placeholder="Cth: 14 siswa izin PKL" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Presensi -->
    <div class="md:col-span-2">
        <div class="bg-snow-white p-0 rounded-2xl border-2 border-cloud-gray shadow-sm overflow-hidden sticky top-6">
            <div class="p-6 border-b border-cloud-gray flex justify-between items-center bg-cloud-gray/10">
                <h2 class="font-bold text-lg text-charcoal">Presensi Siswa</h2>
            </div>
            
            <div class="overflow-x-auto max-h-[60vh] overflow-y-auto">
                <table class="w-full text-left">
                    <thead class="bg-cloud-gray/30 text-charcoal text-sm uppercase sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="p-4 w-10">No</th>
                            <th class="p-4">Nama Siswa</th>
                            <th class="p-4 text-center">Hadir</th>
                            <th class="p-4 text-center">Sakit</th>
                            <th class="p-4 text-center">Izin</th>
                            <th class="p-4 text-center">Alpa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cloud-gray text-sm">
                        @foreach($classroom->students as $index => $student)
                        <tr class="hover:bg-cloud-gray/10 transition">
                            <td class="p-4 text-graphite">{{ $index + 1 }}</td>
                            <td class="p-4 font-medium text-charcoal">{{ $student->name }}</td>
                            <td class="p-4 text-center">
                                <input type="radio" name="attendances[{{ $student->id }}][status]" value="hadir" checked class="w-4 h-4 text-duo-green focus:ring-duo-green cursor-pointer">
                            </td>
                            <td class="p-4 text-center">
                                <input type="radio" name="attendances[{{ $student->id }}][status]" value="sakit" class="w-4 h-4 text-sunshine-yellow focus:ring-sunshine-yellow cursor-pointer">
                            </td>
                            <td class="p-4 text-center">
                                <input type="radio" name="attendances[{{ $student->id }}][status]" value="izin" class="w-4 h-4 text-sky-blue focus:ring-sky-blue cursor-pointer">
                            </td>
                            <td class="p-4 text-center">
                                <input type="radio" name="attendances[{{ $student->id }}][status]" value="alpa" class="w-4 h-4 text-bubblegum-pink focus:ring-bubblegum-pink cursor-pointer">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($classroom->students->count() == 0)
                <div class="p-8 text-center text-graphite">
                    Belum ada siswa di kelas ini. Silakan tambah siswa di menu kelas terlebih dahulu.
                </div>
                @endif
            </div>
            
            <div class="p-6 border-t border-cloud-gray bg-cloud-gray/5 flex justify-end">
                <button type="submit" class="btn-3d-primary px-8" {{ $classroom->students->count() == 0 ? 'disabled' : '' }}>Simpan Jurnal & Presensi</button>
            </div>
        </div>
    </div>
</form>
@endsection
"""

views['resources/views/journals/edit.blade.php'] = """@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('classrooms.index') }}" class="text-sky-blue hover:underline text-sm mb-2 inline-block">&larr; Batal & Kembali</a>
        <h1 class="text-heading font-feather text-almost-black">Edit Jurnal & Presensi</h1>
        <p class="text-body text-graphite">Kelas: {{ $classroom->name }} | Mapel: {{ $classroom->subject }}</p>
    </div>
</div>

<form action="{{ route('journals.update', [$classroom, $journal]) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @csrf
    @method('PUT')
    
    <!-- Kolom Kiri: Jurnal -->
    <div class="md:col-span-1 space-y-4">
        <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray shadow-sm">
            <div class="flex justify-between items-center mb-4 border-b border-cloud-gray pb-2">
                <h2 class="font-bold text-lg text-charcoal">Jurnal Mengajar</h2>
                @if($schedule)
                <span class="text-xs bg-duo-green-light text-duo-green px-2 py-1 rounded font-bold">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                @endif
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ $journal->date }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Pertemuan Ke-</label>
                    <input type="number" name="meeting_number" value="{{ $journal->meeting_number }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Topik/Konten Materi</label>
                    <input type="text" name="title" value="{{ $journal->title }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Capaian Kompetensi</label>
                    <textarea name="competency" rows="2" class="w-full border-2 border-cloud-gray rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition resize-y">{{ $journal->competency }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Kegiatan Belajar Mengajar (KBM)</label>
                    <textarea name="activity" rows="3" class="w-full border-2 border-cloud-gray rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition resize-y">{{ $journal->activity }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Uraian / Ringkasan Tambahan</label>
                    <textarea name="content" rows="2" class="w-full border-2 border-cloud-gray rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition resize-y" required>{{ $journal->content }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Keterangan / Catatan Khusus</label>
                    <input type="text" name="notes" value="{{ $journal->notes }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Presensi -->
    <div class="md:col-span-2">
        <div class="bg-snow-white p-0 rounded-2xl border-2 border-cloud-gray shadow-sm overflow-hidden sticky top-6">
            <div class="p-6 border-b border-cloud-gray flex justify-between items-center bg-cloud-gray/10">
                <h2 class="font-bold text-lg text-charcoal">Presensi Siswa</h2>
            </div>
            
            <div class="overflow-x-auto max-h-[60vh] overflow-y-auto">
                <table class="w-full text-left">
                    <thead class="bg-cloud-gray/30 text-charcoal text-sm uppercase sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="p-4 w-10">No</th>
                            <th class="p-4">Nama Siswa</th>
                            <th class="p-4 text-center">Hadir</th>
                            <th class="p-4 text-center">Sakit</th>
                            <th class="p-4 text-center">Izin</th>
                            <th class="p-4 text-center">Alpa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cloud-gray text-sm">
                        @foreach($classroom->students as $index => $student)
                        @php
                            $att = $journal->attendances->where('student_id', $student->id)->first();
                            $status = $att ? $att->status : 'hadir';
                        @endphp
                        <tr class="hover:bg-cloud-gray/10 transition">
                            <td class="p-4 text-graphite">{{ $index + 1 }}</td>
                            <td class="p-4 font-medium text-charcoal">{{ $student->name }}</td>
                            <td class="p-4 text-center">
                                <input type="radio" name="attendances[{{ $student->id }}][status]" value="hadir" {{ $status == 'hadir' ? 'checked' : '' }} class="w-4 h-4 text-duo-green focus:ring-duo-green cursor-pointer">
                            </td>
                            <td class="p-4 text-center">
                                <input type="radio" name="attendances[{{ $student->id }}][status]" value="sakit" {{ $status == 'sakit' ? 'checked' : '' }} class="w-4 h-4 text-sunshine-yellow focus:ring-sunshine-yellow cursor-pointer">
                            </td>
                            <td class="p-4 text-center">
                                <input type="radio" name="attendances[{{ $student->id }}][status]" value="izin" {{ $status == 'izin' ? 'checked' : '' }} class="w-4 h-4 text-sky-blue focus:ring-sky-blue cursor-pointer">
                            </td>
                            <td class="p-4 text-center">
                                <input type="radio" name="attendances[{{ $student->id }}][status]" value="alpa" {{ $status == 'alpa' ? 'checked' : '' }} class="w-4 h-4 text-bubblegum-pink focus:ring-bubblegum-pink cursor-pointer">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 border-t border-cloud-gray bg-cloud-gray/5 flex justify-end gap-3">
                <button type="submit" class="btn-3d-primary px-8 bg-sunshine-yellow text-charcoal border-none" style="box-shadow: 0 4px 0 #dca600;">Perbarui Jurnal</button>
            </div>
        </div>
    </div>
</form>
@endsection
"""

views['resources/views/journals/rekap.blade.php'] = """@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-heading font-feather text-almost-black mb-2">Rekap Agenda Harian Guru</h1>
    <p class="text-body text-graphite">Cetak rekapitulasi jurnal mengajar seluruh kelas berdasarkan rentang tanggal.</p>
</div>

<div class="bg-snow-white rounded-2xl border-2 border-cloud-gray shadow-sm overflow-hidden mb-8">
    <div class="p-6 border-b border-cloud-gray bg-cloud-gray/10">
        <h2 class="font-bold text-lg text-charcoal">Filter Tanggal</h2>
    </div>
    <div class="p-6">
        <form action="{{ route('journals.rekap') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-bold text-charcoal mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 focus:border-sky-blue transition">
            </div>
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-bold text-charcoal mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 focus:border-sky-blue transition">
            </div>
            <div class="w-full md:w-auto flex gap-2">
                <button type="submit" class="btn-3d-primary px-6 py-2.5">Tampilkan</button>
                <a href="{{ route('journals.rekap.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn-3d-primary bg-sky-blue border-none px-6 py-2.5 flex items-center gap-2" style="box-shadow: 0 4px 0 #1899d6;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Landscape
                </a>
            </div>
        </form>
    </div>
</div>

<div class="bg-snow-white rounded-2xl border-2 border-cloud-gray shadow-sm overflow-hidden">
    <div class="p-6 border-b border-cloud-gray">
        <h2 class="font-bold text-lg text-charcoal">Preview Laporan ({{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-cloud-gray/30 text-charcoal uppercase border-b-2 border-cloud-gray">
                <tr>
                    <th class="p-3 whitespace-nowrap">Hari/Tanggal</th>
                    <th class="p-3">Kelas</th>
                    <th class="p-3">Ke-</th>
                    <th class="p-3 w-1/4">Konten / Topik</th>
                    <th class="p-3 text-center">Hadir</th>
                    <th class="p-3 text-center">S</th>
                    <th class="p-3 text-center">I</th>
                    <th class="p-3 text-center">A</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cloud-gray">
                @forelse($journals as $journal)
                @php
                    $hadir = $journal->attendances->where('status', 'hadir')->count();
                    $sakit = $journal->attendances->where('status', 'sakit')->count();
                    $izin = $journal->attendances->where('status', 'izin')->count();
                    $alpa = $journal->attendances->where('status', 'alpa')->count();
                @endphp
                <tr class="hover:bg-cloud-gray/10">
                    <td class="p-3 whitespace-nowrap">
                        <span class="font-bold block">{{ \Carbon\Carbon::parse($journal->date)->locale('id')->isoFormat('dddd') }}</span>
                        <span class="text-xs text-graphite">{{ \Carbon\Carbon::parse($journal->date)->format('d/m/Y') }}</span>
                    </td>
                    <td class="p-3 font-bold text-charcoal">{{ $journal->classroom->name }}</td>
                    <td class="p-3 text-center">{{ $journal->meeting_number }}</td>
                    <td class="p-3">{{ $journal->title }}</td>
                    <td class="p-3 text-center text-duo-green font-bold">{{ $hadir }}</td>
                    <td class="p-3 text-center text-sunshine-yellow font-bold">{{ $sakit }}</td>
                    <td class="p-3 text-center text-sky-blue font-bold">{{ $izin }}</td>
                    <td class="p-3 text-center text-bubblegum-pink font-bold">{{ $alpa }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-8 text-center text-graphite">Tidak ada data jurnal pada rentang tanggal ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
"""

views['resources/views/journals/print_rekap.blade.php'] = """<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda / Jurnal Harian Guru</title>
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
        AGENDA / JURNAL HARIAN GURU
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
            <td>Rentang</td><td>:</td><td>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</td>
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
"""

for path, content in views.items():
    with open(path, 'w') as f:
        f.write(content)
    print(f"Created/Updated {path}")
