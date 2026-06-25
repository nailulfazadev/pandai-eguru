@extends('layouts.app')

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

@if($classrooms->count() == 0)
    <!-- EMPTY STATE (PENGGUNA BARU) -->
    <div class="max-w-2xl mx-auto mt-10">
        <div class="bg-snow-white rounded-3xl border-2 border-cloud-gray shadow-sm p-10 text-center relative overflow-hidden">
            <!-- Dekorasi background -->
            <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-duo-green-light rounded-full opacity-50 blur-xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-32 h-32 bg-sky-blue/20 rounded-full opacity-50 blur-xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="w-20 h-20 bg-duo-green/10 text-duo-green rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h2 class="text-2xl font-feather text-charcoal mb-3">Selamat Datang di Kelas Pintar!</h2>
                <p class="text-graphite mb-8 text-lg max-w-lg mx-auto">Mari mulai dengan membuat kelas pertama Anda. Setelah itu, Anda bisa mengatur daftar siswa, jadwal, dan mulai mengisi jurnal harian dengan sangat mudah.</p>

                <form action="{{ route('classrooms.store') }}" method="POST" class="bg-cloud-gray/10 p-6 rounded-2xl border border-cloud-gray text-left shadow-sm max-w-sm mx-auto">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-charcoal mb-1">Nama Kelas</label>
                            <input type="text" name="name" placeholder="Contoh: X IPA 1" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-charcoal mb-1">Mata Pelajaran</label>
                            <input type="text" name="subject" placeholder="Contoh: Biologi" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                        </div>
                        <button type="submit" class="btn-3d-primary w-full text-sm mt-2">Buat Kelas Pertama</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@else
    
    <!-- TAMPILAN NORMAL (SUDAH ADA KELAS) -->
    
    <div class="flex flex-col xl:flex-row gap-6 mb-8">
        <!-- Kiri: Widget Cetak Laporan (Di Depan) -->
        <div class="w-full xl:w-1/3">
            <div class="bg-[#fdfdfd] rounded-3xl border-2 border-cloud-gray shadow-sm overflow-hidden h-full flex flex-col" x-data="{ reportType: 'mingguan' }">
                <div class="p-4 border-b border-cloud-gray bg-duo-green-light/20 flex items-center justify-between">
                    <h2 class="font-bold text-lg text-charcoal flex items-center gap-2">
                        <svg class="w-5 h-5 text-duo-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Pusat Cetak Laporan
                    </h2>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <p class="text-xs text-graphite mb-4">Cetak seluruh rekap jurnal & presensi untuk semua kelas Anda di sini.</p>
                    <form action="{{ route('journals.rekap.print') }}" target="_blank" method="GET" class="flex flex-col h-full">
                        <div class="mb-4">
                            <select name="report_type" x-model="reportType" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:border-sky-blue focus:outline-none transition font-bold text-charcoal bg-snow-white">
                                <option value="harian">Laporan Harian</option>
                                <option value="mingguan">Laporan Mingguan</option>
                                <option value="bulanan">Laporan Bulanan</option>
                                <option value="semesteran">Laporan Semesteran</option>
                                <option value="tahunan">Laporan Tahunan</option>
                            </select>
                        </div>
                        
                        <div class="flex-1">
                            <!-- Harian -->
                            <div x-show="reportType === 'harian'">
                                <label class="block text-xs font-bold text-charcoal mb-1">Tanggal</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                            </div>

                            <!-- Mingguan -->
                            <div x-show="reportType === 'mingguan'" class="flex gap-2">
                                <div class="w-1/2">
                                    <label class="block text-xs font-bold text-charcoal mb-1">Mulai</label>
                                    <input type="date" name="start_date" value="{{ date('Y-m-d', strtotime('-7 days')) }}" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-xs font-bold text-charcoal mb-1">Akhir</label>
                                    <input type="date" name="end_date" value="{{ date('Y-m-d') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                                </div>
                            </div>

                            <!-- Bulanan -->
                            <div x-show="reportType === 'bulanan'" class="flex gap-2">
                                <div class="w-3/5">
                                    <label class="block text-xs font-bold text-charcoal mb-1">Bulan</label>
                                    <select name="month" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                                        @foreach(['1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'Mei','6'=>'Jun','7'=>'Jul','8'=>'Agu','9'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'] as $num => $name)
                                            <option value="{{ $num }}" {{ date('n') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-2/5">
                                    <label class="block text-xs font-bold text-charcoal mb-1">Tahun</label>
                                    <input type="number" name="year" value="{{ date('Y') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                                </div>
                            </div>

                            <!-- Semesteran -->
                            <div x-show="reportType === 'semesteran'" class="flex gap-2">
                                <div class="w-1/2">
                                    <label class="block text-xs font-bold text-charcoal mb-1">Semester</label>
                                    <select name="semester" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                                        <option value="ganjil">Ganjil</option>
                                        <option value="genap">Genap</option>
                                    </select>
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-xs font-bold text-charcoal mb-1">Thn Ajaran</label>
                                    <input type="number" name="school_year" value="{{ date('Y') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                                </div>
                            </div>

                            <!-- Tahunan -->
                            <div x-show="reportType === 'tahunan'">
                                <label class="block text-xs font-bold text-charcoal mb-1">Tahun Ajaran (Awal)</label>
                                <input type="number" name="school_year" value="{{ date('Y') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:border-sky-blue transition">
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-cloud-gray">
                            <button type="submit" class="btn-3d-primary w-full text-sm py-2">
                                Cetak Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kanan: Jadwal Mingguan -->
        <div class="w-full xl:w-2/3" x-data="{ activeDay: '{{ $today }}' }">
            <div class="bg-snow-white rounded-3xl border-2 border-cloud-gray shadow-sm overflow-hidden h-full flex flex-col">
                <div class="p-4 border-b border-cloud-gray bg-sky-blue/10 flex items-center justify-between">
                    <h2 class="font-bold text-lg text-charcoal flex items-center gap-2">
                        <svg class="w-5 h-5 text-sky-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Jadwal Mengajar Mingguan
                    </h2>
                </div>
                <!-- Tabs -->
                <div class="flex overflow-x-auto border-b border-cloud-gray scrollbar-hide">
                    <template x-for="day in ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']">
                        <button @click="activeDay = day" :class="activeDay === day ? 'border-b-2 border-sky-blue text-sky-blue font-bold bg-sky-blue/5' : 'text-graphite font-medium hover:bg-cloud-gray/20'" class="px-5 py-3 whitespace-nowrap text-sm flex-1 text-center transition">
                            <span x-text="day"></span>
                            <span x-show="day === '{{ $today }}'" class="ml-1 inline-block w-1.5 h-1.5 rounded-full bg-bubblegum-pink align-middle mb-1" title="Hari ini"></span>
                        </button>
                    </template>
                </div>
                <!-- Content per day -->
                <div class="p-5 flex-1 bg-[#f9f9f9]">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $dayName)
                        <div x-show="activeDay === '{{ $dayName }}'" style="display: none;" class="animate-[fadeIn_0.2s_ease-out] h-full">
                            @php
                                $schedules = $schedulesByDay->get($dayName, collect());
                            @endphp
                            @if($schedules->count() > 0)
                                <div class="flex flex-col gap-3">
                                    @foreach($schedules as $schedule)
                                        @php
                                            $themeColors = ['sky-blue', 'grape-soda', 'duo-green', 'bubblegum-pink', 'sunshine-yellow'];
                                            $colorClass = $themeColors[$schedule->classroom_id % count($themeColors)];
                                        @endphp
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border-2 border-cloud-gray rounded-2xl bg-snow-white shadow-sm hover:border-{{ $colorClass }} transition relative overflow-hidden">
                                            <!-- Aksens warna di kiri -->
                                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-{{ $colorClass }} opacity-80"></div>
                                            
                                            <div class="flex items-center gap-4 mb-3 sm:mb-0 pl-2">
                                                <div class="w-14 h-14 bg-{{ $colorClass }}/10 text-{{ $colorClass }} rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                                                    <span class="font-bold text-base leading-none">{{ explode(':', $schedule->start_time)[0] }}:{{ explode(':', $schedule->start_time)[1] }}</span>
                                                    <span class="text-[10px] font-bold mt-1 opacity-70">{{ explode(':', $schedule->end_time)[0] }}:{{ explode(':', $schedule->end_time)[1] }}</span>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-charcoal text-lg">{{ $schedule->classroom->name }}</div>
                                                    <div class="text-graphite text-sm mt-0.5">{{ $schedule->classroom->subject }}</div>
                                                </div>
                                            </div>
                                            <div class="w-full sm:w-auto">
                                                @if($schedule->today_journal && $dayName === $today)
                                                    <a href="{{ route('journals.edit', ['classroom' => $schedule->classroom->id, 'journal' => $schedule->today_journal->id]) }}" class="btn-3d-primary text-sm w-full sm:w-auto px-6 py-2 shadow-sm bg-sunshine-yellow text-charcoal border-none" style="box-shadow: 0 4px 0 #dca600;">Edit Jurnal</a>
                                                @elseif($dayName === $today)
                                                    <a href="{{ route('journals.create', ['classroom' => $schedule->classroom->id, 'schedule_id' => $schedule->id]) }}" class="btn-3d-primary text-sm w-full sm:w-auto px-6 py-2 shadow-sm">Isi Jurnal</a>
                                                @else
                                                    <div class="text-sm text-center sm:text-right text-silver font-medium px-4 py-2 bg-cloud-gray/20 rounded-xl">
                                                        Bukan hari ini
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-graphite py-8">
                                    <div class="w-12 h-12 bg-cloud-gray/30 rounded-full flex items-center justify-center text-silver mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                    </div>
                                    <p class="text-sm">Tidak ada jadwal mengajar pada hari {{ $dayName }}.</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Master Kelas Section -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end mb-4 mt-8 gap-3">
        <h2 class="font-bold text-xl text-charcoal">Daftar Kelas Anda</h2>
        
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($classrooms as $classroom)
        <a href="{{ route('classrooms.show', $classroom) }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray hover:border-sky-blue hover:shadow-md transition group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-cloud-gray/20 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-sky-blue/10"></div>
            
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-xl bg-cloud-gray/40 flex items-center justify-center text-graphite mb-4 group-hover:text-sky-blue group-hover:bg-sky-blue/20 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="font-bold text-charcoal text-xl mb-1">{{ $classroom->name }}</h3>
                <p class="text-graphite font-medium mb-4">{{ $classroom->subject }}</p>
                
                <div class="flex items-center gap-4 text-sm text-graphite">
                    <div class="flex items-center gap-1 bg-[#f9f9f9] px-2 py-1 rounded-lg border border-cloud-gray">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>{{ $classroom->students_count }} Siswa</span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach

        <!-- Kartu Tambah Kelas -->
        <div x-data="{ open: false }" class="h-full">
            <button @click="open = !open" x-show="!open" class="w-full h-full min-h-[200px] flex flex-col items-center justify-center bg-[#f9f9f9] border-2 border-dashed border-silver rounded-2xl hover:border-sky-blue hover:bg-sky-blue/5 transition text-graphite hover:text-sky-blue cursor-pointer">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span class="font-bold">Tambah Kelas Baru</span>
            </button>
            
            <div x-show="open" style="display: none;" class="bg-snow-white p-5 rounded-2xl border-2 border-sky-blue shadow-sm h-full flex flex-col justify-center relative">
                <button @click="open = false" class="absolute top-3 right-3 text-silver hover:text-charcoal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <form action="{{ route('classrooms.store') }}" method="POST" class="space-y-3 mt-2">
                    @csrf
                    <p class="text-sm font-bold text-sky-blue mb-2">Buat Kelas Baru</p>
                    <input type="text" name="name" placeholder="Nama Kelas (Contoh: X IPA 1)" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                    <input type="text" name="subject" placeholder="Mata Pelajaran" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                    <button type="submit" class="btn-3d-primary w-full text-sm mt-1">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- Pastikan Alpine JS termuat -->
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
