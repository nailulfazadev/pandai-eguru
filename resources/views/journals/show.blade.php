@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('classrooms.show', $classroom) }}" class="text-sky-blue hover:underline text-sm mb-2 inline-block">&larr; Kembali ke Kelas</a>
        <h1 class="text-heading font-feather text-almost-black">Detail Jurnal</h1>
        <p class="text-body text-graphite">{{ \Carbon\Carbon::parse($journal->date)->format('l, d M Y') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('journals.edit', ['classroom' => $classroom->id, 'journal' => $journal->id]) }}" class="btn-3d-primary flex items-center gap-2 bg-sunshine-yellow text-charcoal border-none" style="box-shadow: 0 4px 0 #dca600;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Jurnal
        </a>
        <a href="{{ route('journals.print', $journal) }}" target="_blank" class="btn-3d-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Laporan
        </a>
    </div>
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
