import re

file_path = 'resources/views/classrooms/index.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

new_header = """
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($schedules as $schedule)
                                        <div class="flex flex-col p-4 border-2 border-cloud-gray rounded-2xl bg-snow-white shadow-sm hover:border-sky-blue transition">
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="w-12 h-12 bg-sky-blue/10 text-sky-blue rounded-xl flex items-center justify-center font-bold text-sm">
                                                    {{ explode(':', $schedule->start_time)[0] }}:{{ explode(':', $schedule->start_time)[1] }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-charcoal">{{ $schedule->classroom->name }}</div>
                                                    <div class="text-graphite text-xs mt-0.5">{{ $schedule->classroom->subject }}</div>
                                                </div>
                                            </div>
                                            <div class="mt-auto">
                                                @if($schedule->today_journal && $dayName === $today)
                                                    <a href="{{ route('journals.edit', ['classroom' => $schedule->classroom->id, 'journal' => $schedule->today_journal->id]) }}" class="btn-3d-primary text-xs w-full py-2 shadow-sm bg-sunshine-yellow text-charcoal border-none" style="box-shadow: 0 4px 0 #dca600;">Edit Jurnal & Presensi</a>
                                                @elseif($dayName === $today)
                                                    <a href="{{ route('journals.create', ['classroom' => $schedule->classroom->id, 'schedule_id' => $schedule->id]) }}" class="btn-3d-primary text-xs w-full py-2 shadow-sm">Isi Jurnal & Presensi</a>
                                                @else
                                                    <div class="text-xs text-center text-silver font-medium border border-cloud-gray rounded-xl py-2 bg-[#f9f9f9]">
                                                        Bukan jadwal hari ini
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
"""

# Find the section to replace: from <!-- TAMPILAN NORMAL (SUDAH ADA KELAS) --> to right before <!-- Master Kelas Section -->
pattern = r"<!-- TAMPILAN NORMAL \(SUDAH ADA KELAS\) -->.*?<!-- Master Kelas Section -->"
content = re.sub(pattern, new_header + "\n    <!-- Master Kelas Section -->", content, flags=re.DOTALL)

# Remove the old "Cetak Rekap Semua Mapel" button
content = re.sub(
    r"<a href=\"\{\{ route\('journals\.rekap'\) \}\}\" class=\"btn-3d-primary.*?</a>",
    "",
    content,
    flags=re.DOTALL
)

with open(file_path, 'w') as f:
    f.write(content)

print("Updated index.blade.php with dashboard overhaul")
