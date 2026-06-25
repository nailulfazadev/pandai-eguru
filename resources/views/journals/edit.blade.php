@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('classrooms.index') }}" class="text-sky-blue hover:underline text-sm mb-2 inline-block">&larr; Batal & Kembali</a>
        <h1 class="text-heading font-feather text-almost-black">Edit Jurnal & Presensi</h1>
        <p class="text-body text-graphite">Kelas: {{ $classroom->name }} | Mapel: {{ $classroom->subject }}</p>
    </div>
</div>

@if(session('success'))
<div class="bg-duo-green-light/30 border border-duo-green text-duo-green px-4 py-3 rounded-xl mb-6 flex justify-between items-center">
    <span>{{ session('success') }}</span>
    <a href="{{ route('classrooms.index') }}" class="btn-3d-primary text-xs px-4 py-1.5 shadow-sm">Kembali ke Dashboard</a>
</div>
@endif

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
