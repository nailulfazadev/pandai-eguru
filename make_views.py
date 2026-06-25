import os

os.makedirs('resources/views/classrooms', exist_ok=True)
os.makedirs('resources/views/journals', exist_ok=True)

views = {}

views['resources/views/classrooms/index.blade.php'] = """@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-heading font-feather text-almost-black mb-2">Manajemen Kelas & Jurnal</h1>
    <p class="text-body text-graphite">Kelola jadwal mengajar, absen siswa, dan jurnal harian Anda.</p>
</div>

@if(session('success'))
<div class="bg-duo-green-light/30 border border-duo-green text-duo-green px-4 py-3 rounded-xl mb-6">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Jadwal Hari Ini -->
    <div class="md:col-span-2">
        <div class="bg-snow-white rounded-2xl border-2 border-cloud-gray shadow-sm overflow-hidden h-full">
            <div class="p-6 border-b border-cloud-gray flex justify-between items-center bg-duo-green-light/10">
                <h2 class="font-bold text-lg text-charcoal flex items-center gap-2">
                    <svg class="w-5 h-5 text-duo-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Jadwal Anda Hari Ini ({{ $today }})
                </h2>
            </div>
            <div class="p-6">
                @if($todaySchedules->count() > 0)
                    <div class="space-y-4">
                        @foreach($todaySchedules as $schedule)
                        <div class="flex items-center justify-between p-4 border border-cloud-gray rounded-xl hover:border-sky-blue transition bg-snow-white">
                            <div>
                                <div class="font-bold text-charcoal text-lg">{{ $schedule->classroom->name }}</div>
                                <div class="text-graphite text-sm flex items-center gap-2 mt-1">
                                    <span class="bg-cloud-gray/50 px-2 py-0.5 rounded font-medium">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                    <span>{{ $schedule->classroom->subject }}</span>
                                </div>
                            </div>
                            <a href="{{ route('journals.create', $schedule->classroom) }}" class="btn-primary text-sm px-4 py-2">Isi Jurnal & Presensi</a>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-graphite">
                        <svg class="w-12 h-12 mx-auto text-silver mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        <p>Tidak ada jadwal mengajar untuk hari ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Daftar Kelas -->
    <div>
        <div class="bg-snow-white rounded-2xl border-2 border-cloud-gray shadow-sm overflow-hidden h-full flex flex-col">
            <div class="p-6 border-b border-cloud-gray flex justify-between items-center bg-cloud-gray/10">
                <h2 class="font-bold text-lg text-charcoal">Master Kelas</h2>
            </div>
            <div class="p-6 flex-1 overflow-y-auto">
                <form action="{{ route('classrooms.store') }}" method="POST" class="mb-6 space-y-3 p-4 bg-cloud-gray/20 rounded-xl border border-cloud-gray">
                    @csrf
                    <p class="text-sm font-bold text-charcoal mb-2">Tambah Kelas Baru</p>
                    <input type="text" name="name" placeholder="Nama Kelas (Contoh: X IPA 1)" class="w-full form-input" required>
                    <input type="text" name="subject" placeholder="Mata Pelajaran" class="w-full form-input" required>
                    <button type="submit" class="btn-primary w-full text-sm">Simpan Kelas</button>
                </form>

                <div class="space-y-3">
                    @forelse($classrooms as $classroom)
                    <a href="{{ route('classrooms.show', $classroom) }}" class="block p-4 border border-cloud-gray rounded-xl hover:border-sky-blue hover:shadow-sm transition">
                        <div class="font-bold text-charcoal">{{ $classroom->name }}</div>
                        <div class="flex justify-between mt-2 text-xs text-graphite">
                            <span>{{ $classroom->subject }}</span>
                            <span class="bg-cloud-gray/50 px-2 py-0.5 rounded">{{ $classroom->students_count }} Siswa</span>
                        </div>
                    </a>
                    @empty
                    <p class="text-center text-sm text-graphite py-4">Belum ada kelas. Silakan tambah kelas pertama Anda.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
"""

views['resources/views/classrooms/show.blade.php'] = """@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('classrooms.index') }}" class="text-sky-blue hover:underline text-sm mb-2 inline-block">&larr; Kembali ke Daftar Kelas</a>
        <h1 class="text-heading font-feather text-almost-black">{{ $classroom->name }}</h1>
        <p class="text-body text-graphite">Mata Pelajaran: {{ $classroom->subject }}</p>
    </div>
    <a href="{{ route('journals.create', $classroom) }}" class="btn-3d-primary">Tulis Jurnal Hari Ini</a>
</div>

@if(session('success'))
<div class="bg-duo-green-light/30 border border-duo-green text-duo-green px-4 py-3 rounded-xl mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Tab Navigation (Simple UI logic with Alpine or just plain HTML structure) -->
<div x-data="{ tab: 'siswa' }" class="bg-snow-white rounded-2xl border-2 border-cloud-gray shadow-sm overflow-hidden">
    <div class="flex border-b border-cloud-gray bg-cloud-gray/10">
        <button @click="tab = 'siswa'" :class="{ 'border-b-2 border-sky-blue text-sky-blue font-bold': tab === 'siswa', 'text-graphite font-medium hover:text-charcoal': tab !== 'siswa' }" class="px-6 py-4 transition">Daftar Siswa</button>
        <button @click="tab = 'jadwal'" :class="{ 'border-b-2 border-sky-blue text-sky-blue font-bold': tab === 'jadwal', 'text-graphite font-medium hover:text-charcoal': tab !== 'jadwal' }" class="px-6 py-4 transition">Jadwal Mengajar</button>
        <button @click="tab = 'jurnal'" :class="{ 'border-b-2 border-sky-blue text-sky-blue font-bold': tab === 'jurnal', 'text-graphite font-medium hover:text-charcoal': tab !== 'jurnal' }" class="px-6 py-4 transition">Riwayat Jurnal & Presensi</button>
    </div>

    <div class="p-6">
        <!-- Tab: Siswa -->
        <div x-show="tab === 'siswa'">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-lg text-charcoal">Data Siswa ({{ $classroom->students->count() }})</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <form action="{{ route('classrooms.students.store', $classroom) }}" method="POST" class="p-4 bg-cloud-gray/20 rounded-xl border border-cloud-gray space-y-3">
                        @csrf
                        <p class="text-sm font-bold text-charcoal mb-2">Tambah Siswa</p>
                        <input type="text" name="name" placeholder="Nama Lengkap" class="w-full form-input" required>
                        <input type="text" name="nisn" placeholder="NIS/NISN (Opsional)" class="w-full form-input">
                        <select name="gender" class="w-full form-input">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <button type="submit" class="btn-primary w-full text-sm">Tambah Siswa</button>
                    </form>
                </div>
                <div class="md:col-span-2">
                    <div class="border border-cloud-gray rounded-xl overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-cloud-gray/30 text-charcoal text-sm uppercase">
                                <tr>
                                    <th class="p-3">Nama</th>
                                    <th class="p-3">NIS/NISN</th>
                                    <th class="p-3">L/P</th>
                                    <th class="p-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-cloud-gray">
                                @forelse($classroom->students as $student)
                                <tr>
                                    <td class="p-3">{{ $student->name }}</td>
                                    <td class="p-3 text-graphite">{{ $student->nisn ?? '-' }}</td>
                                    <td class="p-3">{{ $student->gender ?? '-' }}</td>
                                    <td class="p-3">
                                        <form action="{{ route('classrooms.students.destroy', $student) }}" method="POST" onsubmit="return confirm('Hapus siswa ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline text-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-graphite">Belum ada data siswa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Jadwal -->
        <div x-show="tab === 'jadwal'" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <form action="{{ route('classrooms.schedules.store', $classroom) }}" method="POST" class="p-4 bg-cloud-gray/20 rounded-xl border border-cloud-gray space-y-3">
                        @csrf
                        <p class="text-sm font-bold text-charcoal mb-2">Tambah Jadwal</p>
                        <select name="day_of_week" class="w-full form-input" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                        <div class="flex gap-2">
                            <input type="time" name="start_time" class="w-full form-input" required>
                            <input type="time" name="end_time" class="w-full form-input" required>
                        </div>
                        <button type="submit" class="btn-primary w-full text-sm">Tambah Jadwal</button>
                    </form>
                </div>
                <div class="md:col-span-2">
                    <div class="space-y-3">
                        @forelse($classroom->schedules as $schedule)
                        <div class="flex justify-between items-center p-4 border border-cloud-gray rounded-xl">
                            <div>
                                <span class="font-bold text-charcoal">{{ $schedule->day_of_week }}</span>
                                <span class="text-graphite ml-2">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                            </div>
                            <form action="{{ route('classrooms.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-sm">Hapus</button>
                            </form>
                        </div>
                        @empty
                        <p class="text-center text-graphite py-4">Belum ada jadwal diatur.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Jurnal -->
        <div x-show="tab === 'jurnal'" style="display: none;">
            <div class="space-y-4">
                @forelse($classroom->journals as $journal)
                <div class="border border-cloud-gray rounded-xl p-4 hover:border-sky-blue transition">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-charcoal">{{ $journal->title }}</h3>
                        <span class="text-sm text-graphite bg-cloud-gray/30 px-2 py-1 rounded">{{ \Carbon\Carbon::parse($journal->date)->format('d M Y') }}</span>
                    </div>
                    <p class="text-graphite text-sm mb-4 line-clamp-2">{{ $journal->content }}</p>
                    <div class="flex gap-3">
                        <a href="{{ route('journals.show', $journal) }}" class="text-sky-blue hover:underline text-sm font-medium">Lihat Detail & Presensi</a>
                        <a href="{{ route('journals.print', $journal) }}" target="_blank" class="text-graphite hover:text-charcoal hover:underline text-sm font-medium">Cetak/PDF</a>
                    </div>
                </div>
                @empty
                <p class="text-center text-graphite py-8">Belum ada riwayat jurnal.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Add Alpine.js for Tabs if not present -->
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
"""

views['resources/views/journals/create.blade.php'] = """@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('classrooms.show', $classroom) }}" class="text-sky-blue hover:underline text-sm mb-2 inline-block">&larr; Batal & Kembali</a>
    <h1 class="text-heading font-feather text-almost-black">Isi Jurnal & Presensi</h1>
    <p class="text-body text-graphite">Kelas: {{ $classroom->name }} | Mapel: {{ $classroom->subject }}</p>
</div>

<form action="{{ route('journals.store', $classroom) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @csrf
    
    <!-- Kolom Kiri: Jurnal -->
    <div class="md:col-span-1 space-y-4">
        <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray shadow-sm">
            <h2 class="font-bold text-lg text-charcoal mb-4 border-b border-cloud-gray pb-2">Jurnal Mengajar</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full form-input" required>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Topik/Judul Materi</label>
                    <input type="text" name="title" placeholder="Contoh: Pengenalan Sel Hewan" class="w-full form-input" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Catatan/Uraian Jurnal</label>
                    <textarea name="content" rows="6" class="w-full form-textarea" placeholder="Tuliskan materi yang dibahas, aktivitas siswa, atau kendala yang dihadapi hari ini..." required></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Presensi -->
    <div class="md:col-span-2">
        <div class="bg-snow-white p-0 rounded-2xl border-2 border-cloud-gray shadow-sm overflow-hidden">
            <div class="p-6 border-b border-cloud-gray flex justify-between items-center bg-cloud-gray/10">
                <h2 class="font-bold text-lg text-charcoal">Presensi Siswa</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-cloud-gray/30 text-charcoal text-sm uppercase">
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

views['resources/views/journals/show.blade.php'] = """@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('classrooms.show', $classroom) }}" class="text-sky-blue hover:underline text-sm mb-2 inline-block">&larr; Kembali ke Kelas</a>
        <h1 class="text-heading font-feather text-almost-black">Detail Jurnal</h1>
        <p class="text-body text-graphite">{{ \Carbon\Carbon::parse($journal->date)->format('l, d M Y') }}</p>
    </div>
    <a href="{{ route('journals.print', $journal) }}" target="_blank" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Laporan
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
        <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray shadow-sm">
            <h3 class="font-bold text-silver uppercase text-xs tracking-wider mb-2">Mata Pelajaran</h3>
            <p class="font-bold text-charcoal mb-6">{{ $classroom->subject }} ({{ $classroom->name }})</p>

            <h3 class="font-bold text-silver uppercase text-xs tracking-wider mb-2">Topik/Judul</h3>
            <p class="font-bold text-charcoal mb-6">{{ $journal->title }}</p>

            <h3 class="font-bold text-silver uppercase text-xs tracking-wider mb-2">Uraian Jurnal</h3>
            <p class="text-graphite whitespace-pre-line">{{ $journal->content }}</p>
        </div>
    </div>

    <div class="md:col-span-2">
        <div class="bg-snow-white rounded-2xl border-2 border-cloud-gray shadow-sm overflow-hidden">
            <div class="p-6 border-b border-cloud-gray bg-cloud-gray/10 flex justify-between items-center">
                <h2 class="font-bold text-lg text-charcoal">Rekap Presensi</h2>
                
                @php
                    $hadir = $journal->attendances->where('status', 'hadir')->count();
                    $sakit = $journal->attendances->where('status', 'sakit')->count();
                    $izin = $journal->attendances->where('status', 'izin')->count();
                    $alpa = $journal->attendances->where('status', 'alpa')->count();
                @endphp
                
                <div class="flex gap-3 text-sm">
                    <span class="text-duo-green font-bold">Hadir: {{ $hadir }}</span>
                    <span class="text-sunshine-yellow font-bold">Sakit: {{ $sakit }}</span>
                    <span class="text-sky-blue font-bold">Izin: {{ $izin }}</span>
                    <span class="text-bubblegum-pink font-bold">Alpa: {{ $alpa }}</span>
                </div>
            </div>
            
            <table class="w-full text-left">
                <thead class="bg-cloud-gray/30 text-charcoal text-sm uppercase">
                    <tr>
                        <th class="p-4 w-10">No</th>
                        <th class="p-4">Nama Siswa</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cloud-gray text-sm">
                    @foreach($journal->attendances as $index => $attendance)
                    <tr>
                        <td class="p-4 text-graphite">{{ $index + 1 }}</td>
                        <td class="p-4 font-medium text-charcoal">{{ $attendance->student->name }}</td>
                        <td class="p-4">
                            @if($attendance->status == 'hadir')
                                <span class="bg-duo-green-light text-duo-green px-2 py-1 rounded font-bold uppercase text-xs">Hadir</span>
                            @elseif($attendance->status == 'sakit')
                                <span class="bg-sunshine-yellow/20 text-sunshine-yellow px-2 py-1 rounded font-bold uppercase text-xs">Sakit</span>
                            @elseif($attendance->status == 'izin')
                                <span class="bg-sky-blue/20 text-sky-blue px-2 py-1 rounded font-bold uppercase text-xs">Izin</span>
                            @else
                                <span class="bg-bubblegum-pink/20 text-bubblegum-pink px-2 py-1 rounded font-bold uppercase text-xs">Alpa</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
"""

views['resources/views/journals/print.blade.php'] = """<!DOCTYPE html>
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
            <div class="label">Tanggal</div>
            <div>: {{ \Carbon\Carbon::parse($journal->date)->format('d F Y') }}</div>
        </div>

        <h2>A. Jurnal Materi</h2>
        <table>
            <tr>
                <th style="width: 30%;">Topik / Judul</th>
                <td>{{ $journal->title }}</td>
            </tr>
            <tr>
                <th>Uraian / Kegiatan</th>
                <td style="white-space: pre-line;">{{ $journal->content }}</td>
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
"""

for path, content in views.items():
    with open(path, 'w') as f:
        f.write(content)
    print(f"Created {path}")
