@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-heading font-feather text-almost-black mb-2">Rekap Agenda Harian Guru</h1>
    <p class="text-body text-graphite">Cetak rekapitulasi jurnal mengajar seluruh kelas berdasarkan jenis laporan.</p>
</div>

<div class="bg-snow-white rounded-2xl border-2 border-cloud-gray shadow-sm overflow-hidden mb-8" x-data="{ reportType: '{{ $type }}' }">
    <div class="p-6 border-b border-cloud-gray bg-cloud-gray/10">
        <h2 class="font-bold text-lg text-charcoal">Filter Laporan</h2>
    </div>
    <div class="p-6">
        <form action="{{ route('journals.rekap') }}" method="GET" id="rekapForm">
            
            <div class="mb-6">
                <label class="block text-sm font-bold text-charcoal mb-2">Pilih Jenis Laporan</label>
                <div class="flex flex-wrap gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="report_type" value="harian" x-model="reportType" class="peer hidden">
                        <div class="px-4 py-2 border-2 rounded-xl text-sm font-bold transition peer-checked:border-duo-green peer-checked:bg-duo-green-light peer-checked:text-duo-green border-cloud-gray text-graphite hover:bg-cloud-gray/20">Harian</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="report_type" value="mingguan" x-model="reportType" class="peer hidden">
                        <div class="px-4 py-2 border-2 rounded-xl text-sm font-bold transition peer-checked:border-duo-green peer-checked:bg-duo-green-light peer-checked:text-duo-green border-cloud-gray text-graphite hover:bg-cloud-gray/20">Mingguan</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="report_type" value="bulanan" x-model="reportType" class="peer hidden">
                        <div class="px-4 py-2 border-2 rounded-xl text-sm font-bold transition peer-checked:border-duo-green peer-checked:bg-duo-green-light peer-checked:text-duo-green border-cloud-gray text-graphite hover:bg-cloud-gray/20">Bulanan</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="report_type" value="semesteran" x-model="reportType" class="peer hidden">
                        <div class="px-4 py-2 border-2 rounded-xl text-sm font-bold transition peer-checked:border-duo-green peer-checked:bg-duo-green-light peer-checked:text-duo-green border-cloud-gray text-graphite hover:bg-cloud-gray/20">Semesteran</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="report_type" value="tahunan" x-model="reportType" class="peer hidden">
                        <div class="px-4 py-2 border-2 rounded-xl text-sm font-bold transition peer-checked:border-duo-green peer-checked:bg-duo-green-light peer-checked:text-duo-green border-cloud-gray text-graphite hover:bg-cloud-gray/20">Tahunan</div>
                    </label>
                </div>
            </div>

            <!-- Dynamic Inputs based on reportType -->
            <div class="flex flex-col md:flex-row gap-4 items-end mb-6">
                
                <!-- Harian -->
                <div x-show="reportType === 'harian'" class="w-full md:w-1/3">
                    <label class="block text-sm font-bold text-charcoal mb-1">Pilih Tanggal</label>
                    <input type="date" name="date" value="{{ $request->date ?? date('Y-m-d') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 focus:border-sky-blue transition">
                </div>

                <!-- Mingguan -->
                <div x-show="reportType === 'mingguan'" class="w-full flex gap-4">
                    <div class="w-1/2 md:w-1/3">
                        <label class="block text-sm font-bold text-charcoal mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ $request->start_date ?? date('Y-m-d', strtotime('-7 days')) }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 focus:border-sky-blue transition">
                    </div>
                    <div class="w-1/2 md:w-1/3">
                        <label class="block text-sm font-bold text-charcoal mb-1">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ $request->end_date ?? date('Y-m-d') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 focus:border-sky-blue transition">
                    </div>
                </div>

                <!-- Bulanan -->
                <div x-show="reportType === 'bulanan'" class="w-full flex gap-4">
                    <div class="w-1/2 md:w-1/3">
                        <label class="block text-sm font-bold text-charcoal mb-1">Bulan</label>
                        <select name="month" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 focus:border-sky-blue transition">
                            @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $name)
                                <option value="{{ $num }}" {{ ($request->month ?? date('n')) == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-1/2 md:w-1/4">
                        <label class="block text-sm font-bold text-charcoal mb-1">Tahun</label>
                        <input type="number" name="year" value="{{ $request->year ?? date('Y') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 focus:border-sky-blue transition">
                    </div>
                </div>

                <!-- Semesteran -->
                <div x-show="reportType === 'semesteran'" class="w-full flex gap-4">
                    <div class="w-1/2 md:w-1/3">
                        <label class="block text-sm font-bold text-charcoal mb-1">Semester</label>
                        <select name="semester" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 focus:border-sky-blue transition">
                            <option value="ganjil" {{ ($request->semester ?? '') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ ($request->semester ?? '') == 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <div class="w-1/2 md:w-1/3">
                        <label class="block text-sm font-bold text-charcoal mb-1">Tahun Ajaran (Tahun Awal)</label>
                        <input type="number" name="school_year" value="{{ $request->school_year ?? date('Y') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 focus:border-sky-blue transition">
                    </div>
                </div>

                <!-- Tahunan -->
                <div x-show="reportType === 'tahunan'" class="w-full md:w-1/3">
                    <label class="block text-sm font-bold text-charcoal mb-1">Tahun Ajaran (Tahun Awal)</label>
                    <input type="number" name="school_year" value="{{ $request->school_year ?? date('Y') }}" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 focus:border-sky-blue transition">
                </div>

            </div>

            <div class="flex gap-2 border-t border-cloud-gray pt-6">
                <button type="submit" class="btn-3d-primary px-8 py-2.5">Tampilkan Preview</button>
                <button type="button" onclick="document.getElementById('rekapForm').action='{{ route('journals.rekap.print') }}'; document.getElementById('rekapForm').target='_blank'; document.getElementById('rekapForm').submit(); document.getElementById('rekapForm').action='{{ route('journals.rekap') }}'; document.getElementById('rekapForm').target='';" class="btn-3d-primary bg-sky-blue border-none px-6 py-2.5 flex items-center gap-2" style="box-shadow: 0 4px 0 #1899d6;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Landscape
                </button>
            </div>
        </form>
    </div>
</div>

<div class="bg-snow-white rounded-2xl border-2 border-cloud-gray shadow-sm overflow-hidden">
    <div class="p-6 border-b border-cloud-gray flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="font-bold text-lg text-charcoal">{{ $printTitle }}</h2>
            <p class="text-sm text-graphite">{{ $periodText }}</p>
        </div>
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
                    <td colspan="8" class="p-8 text-center text-graphite">Tidak ada data jurnal pada rentang ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
