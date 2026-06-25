@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
    <div>
        <a href="{{ route('classrooms.index') }}" class="text-sky-blue hover:underline text-sm mb-2 inline-flex items-center gap-1 font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Kelas
        </a>
        <h1 class="text-heading font-feather text-almost-black">{{ $classroom->name }}</h1>
        <p class="text-body text-graphite font-medium">Mata Pelajaran: {{ $classroom->subject }} &bull; {{ $classroom->students->count() }} Siswa</p>
    </div>
    <a href="{{ route('journals.create', $classroom) }}" class="btn-3d-primary shadow-sm text-sm px-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
        Isi Jurnal Khusus / Rapel
    </a>
</div>

@if(session('success'))
<div class="bg-duo-green-light/30 border border-duo-green text-duo-green px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    {{ session('success') }}
</div>
@endif

@if($classroom->students->count() == 0)
<div class="bg-sunshine-yellow/10 border-2 border-sunshine-yellow text-charcoal px-6 py-5 rounded-2xl mb-8 flex items-start gap-4">
    <div class="bg-sunshine-yellow text-white p-2 rounded-full mt-1">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
    </div>
    <div>
        <h3 class="font-bold text-lg mb-1">Aksi Diperlukan: Tambahkan Siswa</h3>
        <p class="text-sm">Kelas ini belum memiliki siswa. Anda tidak bisa mengisi jurnal dan melakukan presensi sebelum menambahkan minimal 1 orang siswa ke dalam kelas ini.</p>
    </div>
</div>
@endif

<!-- Tab Navigation UI -->
<div x-data="{ tab: 'siswa' }" class="space-y-6">
    <!-- Pilihan Tab Ala Pil -->
    <div class="flex gap-2 p-1 bg-cloud-gray/30 rounded-xl max-w-max">
        <button @click="tab = 'siswa'" :class="{ 'bg-snow-white text-charcoal shadow-sm border-cloud-gray': tab === 'siswa', 'text-graphite border-transparent hover:bg-cloud-gray/50': tab !== 'siswa' }" class="px-6 py-2 rounded-lg font-bold text-sm border-2 transition-all">
            Daftar Siswa
        </button>
        <button @click="tab = 'jadwal'" :class="{ 'bg-snow-white text-charcoal shadow-sm border-cloud-gray': tab === 'jadwal', 'text-graphite border-transparent hover:bg-cloud-gray/50': tab !== 'jadwal' }" class="px-6 py-2 rounded-lg font-bold text-sm border-2 transition-all">
            Jadwal Mengajar
        </button>
        <button @click="tab = 'jurnal'" :class="{ 'bg-snow-white text-charcoal shadow-sm border-cloud-gray': tab === 'jurnal', 'text-graphite border-transparent hover:bg-cloud-gray/50': tab !== 'jurnal' }" class="px-6 py-2 rounded-lg font-bold text-sm border-2 transition-all">
            Riwayat Jurnal
        </button>
    </div>

    <div class="bg-snow-white rounded-3xl border-2 border-cloud-gray shadow-sm overflow-hidden p-6 md:p-8">
        
        <!-- Tab: Siswa -->
        <div x-show="tab === 'siswa'" class="animate-[fadeIn_0.3s_ease-out]">
            <div class="flex flex-col md:flex-row gap-8">
                
                <!-- Kiri: Form Tambah -->
                <div class="w-full md:w-1/3" x-data="{ addMode: 'manual' }">
                    <div class="sticky top-6">
                        <!-- Toggle Manual / Paste -->
                        <div class="flex gap-2 p-1 bg-cloud-gray/30 rounded-xl mb-4">
                            <button @click="addMode = 'manual'" :class="{ 'bg-snow-white text-charcoal shadow-sm': addMode === 'manual', 'text-graphite': addMode !== 'manual' }" class="flex-1 py-1.5 rounded-lg font-bold text-xs transition-all">Manual</button>
                            <button @click="addMode = 'paste'" :class="{ 'bg-snow-white text-charcoal shadow-sm': addMode === 'paste', 'text-graphite': addMode !== 'paste' }" class="flex-1 py-1.5 rounded-lg font-bold text-xs transition-all">Paste dari Excel</button>
                        </div>
                        
                        <!-- Mode Manual -->
                        <form x-show="addMode === 'manual'" action="{{ route('classrooms.students.store', $classroom) }}" method="POST" class="p-6 bg-[#fdfdfd] rounded-2xl border-2 border-cloud-gray space-y-4 shadow-sm animate-[fadeIn_0.3s_ease-out]">
                            @csrf
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 bg-duo-green/20 text-duo-green rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                </div>
                                <h3 class="font-bold text-lg text-charcoal">Tambah Siswa</h3>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-1">Nama Lengkap</label>
                                <input type="text" name="name" placeholder="Cth: Budi Santoso" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-1">NIS/NISN (Opsional)</label>
                                <input type="text" name="nisn" placeholder="Cth: 12345" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-1">Jenis Kelamin</label>
                                <select name="gender" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                                    <option value="">- Pilih Jenis -</option>
                                    <option value="L">Laki-laki (L)</option>
                                    <option value="P">Perempuan (P)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-3d-primary w-full text-sm py-2.5 mt-2">Simpan Siswa</button>
                        </form>

                        <!-- Mode Paste Excel -->
                        <form x-show="addMode === 'paste'" style="display: none;" action="{{ route('classrooms.students.import', $classroom) }}" method="POST" class="p-6 bg-[#fdfdfd] rounded-2xl border-2 border-cloud-gray space-y-4 shadow-sm animate-[fadeIn_0.3s_ease-out]">
                            @csrf
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 bg-sky-blue/20 text-sky-blue rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <h3 class="font-bold text-lg text-charcoal">Paste dari Excel</h3>
                            </div>
                            
                            <div class="bg-cloud-gray/30 p-3 rounded-xl border border-cloud-gray/50 text-xs text-graphite space-y-1">
                                <p class="font-bold text-charcoal">Cara pakai:</p>
                                <p>1. Buka file Excel Anda.</p>
                                <p>2. Copy kolom: <b class="text-charcoal">Nama | NISN | L/P</b> (urutan harus begini).</p>
                                <p>3. Paste (Ctrl+V) ke dalam kotak di bawah.</p>
                            </div>

                            <div>
                                <textarea name="paste_data" rows="8" class="w-full border-2 border-cloud-gray rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition resize-y font-mono whitespace-pre" placeholder="Budi Santoso&#9;1234&#9;L&#10;Siti Aminah&#9;1235&#9;P&#10;..."></textarea>
                            </div>
                            <button type="submit" class="btn-3d-primary w-full text-sm py-2.5 mt-2 bg-sky-blue hover:bg-sky-blue/90 text-white border-0" style="box-shadow: 0 4px 0 #1899d6;">Import Data Siswa</button>
                        </form>
                    </div>
                </div>

                
                <!-- Kanan: Tabel -->
                <div class="w-full md:w-2/3">
                    <div class="border-2 border-cloud-gray rounded-2xl overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-cloud-gray/20 text-silver text-xs uppercase tracking-wider font-bold">
                                <tr>
                                    <th class="p-4 border-b-2 border-cloud-gray">Nama Siswa</th>
                                    <th class="p-4 border-b-2 border-cloud-gray">NIS</th>
                                    <th class="p-4 border-b-2 border-cloud-gray">L/P</th>
                                    <th class="p-4 border-b-2 border-cloud-gray text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-cloud-gray bg-snow-white text-sm">
                                @forelse($classroom->students as $student)
                                <tr class="hover:bg-[#f9f9f9] transition">
                                    <td class="p-4 font-bold text-charcoal">{{ $student->name }}</td>
                                    <td class="p-4 text-graphite">{{ $student->nisn ?? '-' }}</td>
                                    <td class="p-4">
                                        @if($student->gender == 'L')
                                            <span class="bg-sky-blue/20 text-sky-blue px-2 py-1 rounded font-bold text-xs">Laki-laki</span>
                                        @elseif($student->gender == 'P')
                                            <span class="bg-bubblegum-pink/20 text-bubblegum-pink px-2 py-1 rounded font-bold text-xs">Perempuan</span>
                                        @else
                                            <span class="text-graphite">-</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right">
                                        <form action="{{ route('classrooms.students.destroy', $student) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')" class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-silver hover:text-bubblegum-pink p-1 transition" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-10 text-center text-graphite">
                                        <div class="inline-flex w-16 h-16 bg-cloud-gray/30 rounded-full items-center justify-center text-silver mb-3">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <p class="font-medium">Belum ada siswa terdaftar.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Jadwal -->
        <div x-show="tab === 'jadwal'" style="display: none;" class="animate-[fadeIn_0.3s_ease-out]">
            <div class="flex flex-col md:flex-row gap-8">
                <div class="w-full md:w-1/3">
                    <form action="{{ route('classrooms.schedules.store', $classroom) }}" method="POST" class="p-6 bg-[#fdfdfd] rounded-2xl border-2 border-cloud-gray space-y-4 sticky top-6 shadow-sm">
                        @csrf
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 bg-sunshine-yellow/20 text-sunshine-yellow rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="font-bold text-lg text-charcoal">Tambah Jadwal</h3>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-charcoal mb-1">Hari</label>
                            <select name="day_of_week" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div class="flex gap-3">
                            <div class="w-1/2">
                                <label class="block text-sm font-bold text-charcoal mb-1">Jam Mulai</label>
                                <input type="time" name="start_time" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                            </div>
                            <div class="w-1/2">
                                <label class="block text-sm font-bold text-charcoal mb-1">Selesai</label>
                                <input type="time" name="end_time" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-3d-primary w-full text-sm py-2.5 mt-2">Simpan Jadwal</button>
                    </form>
                </div>
                
                <div class="w-full md:w-2/3">
                    <div class="space-y-4">
                        @forelse($classroom->schedules as $schedule)
                        <div class="flex justify-between items-center p-5 border-2 border-cloud-gray rounded-2xl hover:border-sunshine-yellow transition bg-[#fdfdfd]">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-cloud-gray/30 rounded-xl flex items-center justify-center font-bold text-charcoal">
                                    {{ substr($schedule->day_of_week, 0, 3) }}
                                </div>
                                <div>
                                    <span class="font-bold text-charcoal text-lg block">{{ $schedule->day_of_week }}</span>
                                    <span class="text-graphite text-sm flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                    </span>
                                </div>
                            </div>
                            <form action="{{ route('classrooms.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-bubblegum-pink/10 text-bubblegum-pink hover:bg-bubblegum-pink hover:text-white px-3 py-1.5 rounded-lg text-sm font-bold transition">Hapus</button>
                            </form>
                        </div>
                        @empty
                        <div class="border-2 border-dashed border-cloud-gray rounded-2xl p-10 text-center text-graphite">
                            Jadwal belum diatur. Tambahkan jadwal di form samping.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Tab: Jurnal -->
        <div x-show="tab === 'jurnal'" style="display: none;" class="animate-[fadeIn_0.3s_ease-out]">
            
            <!-- Kotak Filter Cetak Rekap Kelas -->
            <div class="bg-[#fdfdfd] rounded-2xl border-2 border-cloud-gray shadow-sm overflow-hidden mb-6" x-data="{ reportType: 'mingguan' }">
                <div class="p-4 border-b border-cloud-gray bg-cloud-gray/10 flex items-center gap-2">
                    <svg class="w-5 h-5 text-duo-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <h3 class="font-bold text-lg text-charcoal">Cetak Laporan / Rekap Jurnal Kelas Ini</h3>
                </div>
                <div class="p-5">
                    <form action="{{ route('journals.rekap.print') }}" target="_blank" method="GET">
                        <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-charcoal mb-2">Pilih Jenis Laporan</label>
                            <div class="flex flex-wrap gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="report_type" value="harian" x-model="reportType" class="peer hidden">
                                    <div class="px-3 py-1.5 border-2 rounded-xl text-xs font-bold transition peer-checked:border-duo-green peer-checked:bg-duo-green-light peer-checked:text-duo-green border-cloud-gray text-graphite hover:bg-cloud-gray/20">Harian</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="report_type" value="mingguan" x-model="reportType" class="peer hidden">
                                    <div class="px-3 py-1.5 border-2 rounded-xl text-xs font-bold transition peer-checked:border-duo-green peer-checked:bg-duo-green-light peer-checked:text-duo-green border-cloud-gray text-graphite hover:bg-cloud-gray/20">Mingguan</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="report_type" value="bulanan" x-model="reportType" class="peer hidden">
                                    <div class="px-3 py-1.5 border-2 rounded-xl text-xs font-bold transition peer-checked:border-duo-green peer-checked:bg-duo-green-light peer-checked:text-duo-green border-cloud-gray text-graphite hover:bg-cloud-gray/20">Bulanan</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="report_type" value="semesteran" x-model="reportType" class="peer hidden">
                                    <div class="px-3 py-1.5 border-2 rounded-xl text-xs font-bold transition peer-checked:border-duo-green peer-checked:bg-duo-green-light peer-checked:text-duo-green border-cloud-gray text-graphite hover:bg-cloud-gray/20">Semesteran</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="report_type" value="tahunan" x-model="reportType" class="peer hidden">
                                    <div class="px-3 py-1.5 border-2 rounded-xl text-xs font-bold transition peer-checked:border-duo-green peer-checked:bg-duo-green-light peer-checked:text-duo-green border-cloud-gray text-graphite hover:bg-cloud-gray/20">Tahunan</div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-4 items-end mb-4">
                            <!-- Harian -->
                            <div x-show="reportType === 'harian'" class="w-full md:w-1/3">
                                <label class="block text-xs font-bold text-charcoal mb-1">Pilih Tanggal</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                            </div>

                            <!-- Mingguan -->
                            <div x-show="reportType === 'mingguan'" class="w-full flex gap-3">
                                <div class="w-1/2 md:w-1/3">
                                    <label class="block text-xs font-bold text-charcoal mb-1">Tgl Mulai</label>
                                    <input type="date" name="start_date" value="{{ date('Y-m-d', strtotime('-7 days')) }}" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                                </div>
                                <div class="w-1/2 md:w-1/3">
                                    <label class="block text-xs font-bold text-charcoal mb-1">Tgl Akhir</label>
                                    <input type="date" name="end_date" value="{{ date('Y-m-d') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                                </div>
                            </div>

                            <!-- Bulanan -->
                            <div x-show="reportType === 'bulanan'" class="w-full flex gap-3">
                                <div class="w-1/2 md:w-1/3">
                                    <label class="block text-xs font-bold text-charcoal mb-1">Bulan</label>
                                    <select name="month" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                                        @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $name)
                                            <option value="{{ $num }}" {{ date('n') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-1/2 md:w-1/4">
                                    <label class="block text-xs font-bold text-charcoal mb-1">Tahun</label>
                                    <input type="number" name="year" value="{{ date('Y') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                                </div>
                            </div>

                            <!-- Semesteran -->
                            <div x-show="reportType === 'semesteran'" class="w-full flex gap-3">
                                <div class="w-1/2 md:w-1/3">
                                    <label class="block text-xs font-bold text-charcoal mb-1">Semester</label>
                                    <select name="semester" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                                        <option value="ganjil">Ganjil</option>
                                        <option value="genap">Genap</option>
                                    </select>
                                </div>
                                <div class="w-1/2 md:w-1/3">
                                    <label class="block text-xs font-bold text-charcoal mb-1">Tahun Ajaran</label>
                                    <input type="number" name="school_year" value="{{ date('Y') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                                </div>
                            </div>

                            <!-- Tahunan -->
                            <div x-show="reportType === 'tahunan'" class="w-full md:w-1/3">
                                <label class="block text-xs font-bold text-charcoal mb-1">Tahun Ajaran (Awal)</label>
                                <input type="number" name="school_year" value="{{ date('Y') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn-3d-primary w-full md:w-auto text-sm py-2 px-6">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak Laporan (Format Resmi)
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List Jurnal Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse($classroom->journals as $journal)
                <div class="border-2 border-cloud-gray bg-[#fdfdfd] rounded-2xl p-5 hover:border-sky-blue hover:shadow-md transition group flex flex-col h-full relative">
                    <!-- Garis Hijau kalau hadir semua (opsional, visual flair) -->
                    <div class="absolute top-0 left-0 w-full h-1 bg-sky-blue opacity-50 group-hover:opacity-100 transition"></div>
                    
                    <div class="flex justify-between items-start mb-3 mt-1">
                        <span class="text-xs font-bold text-graphite bg-cloud-gray/50 px-2.5 py-1 rounded-md">{{ \Carbon\Carbon::parse($journal->date)->format('d M Y') }}</span>
                    </div>
                    <h3 class="font-bold text-lg text-charcoal mb-2 leading-tight">{{ $journal->title }}</h3>
                    <p class="text-graphite text-sm mb-5 line-clamp-3 flex-1">{{ $journal->content }}</p>
                    
                    <div class="pt-4 border-t border-cloud-gray/50 flex gap-2">
                        <a href="{{ route('journals.show', $journal) }}" class="btn-3d-primary py-1.5 px-4 text-xs flex-1 text-center">Detail Presensi</a>
                        
                    </div>
                </div>
                @empty
                <div class="md:col-span-2 lg:col-span-3 border-2 border-dashed border-cloud-gray rounded-2xl p-12 text-center text-graphite">
                    <svg class="w-12 h-12 text-silver mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <p class="font-medium text-lg mb-1">Belum ada jurnal mengajar.</p>
                    <p class="text-sm">Silakan buat jurnal pertama Anda hari ini dengan mengklik tombol "Isi Jurnal Hari Ini" di atas.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(5px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
