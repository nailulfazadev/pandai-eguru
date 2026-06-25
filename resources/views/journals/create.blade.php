@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('classrooms.show', $classroom) }}" class="text-sky-blue hover:underline text-sm mb-2 inline-block">&larr; Batal & Kembali</a>
        <h1 class="text-heading font-feather text-almost-black">Isi Jurnal & Presensi</h1>
        <p class="text-body text-graphite">Kelas: {{ $classroom->name }} | Mapel: {{ $classroom->subject }}</p>
    </div>
</div>

<form action="{{ route('journals.store', $classroom) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data="journalForm()">
    @csrf
    
    @if($schedule)
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
        <div class="md:col-span-1 space-y-4">
            <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray shadow-sm">
                <div class="flex justify-between items-center mb-4 border-b border-cloud-gray pb-2">
                    <h2 class="font-bold text-lg text-charcoal">Jurnal Mengajar</h2>
                    <span class="text-xs bg-duo-green-light text-duo-green px-2 py-1 rounded font-bold">{{ $schedule->day_of_week }}, {{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                </div>
                <div class="bg-cloud-gray/20 border border-cloud-gray text-graphite p-3 rounded-xl text-sm mb-4">
                    <p class="mb-1">Jadwal: <b>{{ $schedule->day_of_week }}, {{ $schedule->start_time }} - {{ $schedule->end_time }}</b></p>
                    <p>Tanggal: <b>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</b> (Hari Ini)</p>
                </div>
    @else
        <div class="md:col-span-1 space-y-4">
            <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray shadow-sm">
                <div class="flex justify-between items-center mb-4 border-b border-cloud-gray pb-2">
                    <h2 class="font-bold text-lg text-charcoal">Jurnal Mengajar</h2>
                </div>
                
                @if($schedules->isEmpty())
                <div class="mb-4 bg-sunshine-yellow/20 border-2 border-sunshine-yellow text-charcoal p-4 rounded-xl text-sm">
                    <b>Perhatian:</b> Anda belum membuat jadwal untuk kelas ini. Jurnal wajib dikaitkan dengan jam mengajar. Silakan kembali dan buat jadwal terlebih dahulu.
                </div>
                @endif
                
                <div class="mb-4">
                    <label class="block text-sm font-bold text-charcoal mb-1">Pilih Jadwal (Wajib)</label>
                    <select name="schedule_id" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                        <option value="">-- Pilih Jam Mengajar --</option>
                        <template x-for="sch in filteredSchedules" :key="sch.id">
                            <option :value="sch.id" x-text="sch.start_time + ' - ' + sch.end_time"></option>
                        </template>
                    </select>
                    <p class="text-xs text-graphite mt-1" x-show="filteredSchedules.length === 0">Tidak ada jadwal kelas ini di hari <span class="font-bold text-bubblegum-pink" x-text="dayOfWeek"></span>.</p>
                    <p class="text-xs text-sky-blue font-bold mt-1" x-show="filteredSchedules.length > 0">Terdapat <span x-text="filteredSchedules.length"></span> jadwal di hari <span x-text="dayOfWeek"></span>.</p>
                </div>
    @endif
            
            <div class="space-y-4">
                @if($schedule)
                    <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                @else
                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Tanggal <span class="text-xs text-graphite font-normal">(Ganti jika ingin merapel jurnal lampau)</span></label>
                    <input type="date" name="date" x-model="selectedDate" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                </div>
                @endif
                
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

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('journalForm', () => ({
        selectedDate: '{{ date('Y-m-d') }}',
        schedules: @json($schedules),
        
        get dayOfWeek() {
            if (!this.selectedDate) return '';
            // Parse YYYY-MM-DD locally to prevent UTC timezone shift issues
            const parts = this.selectedDate.split('-');
            if(parts.length !== 3) return '';
            const date = new Date(parts[0], parts[1] - 1, parts[2]);
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            return days[date.getDay()];
        },
        
        get filteredSchedules() {
            return this.schedules.filter(s => s.day_of_week === this.dayOfWeek);
        }
    }))
})
</script>

<!-- Pastikan Alpine JS termuat -->
<script src="//unpkg.com/alpinejs" defer></script>

@endsection
