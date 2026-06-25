import re

file_path = 'resources/views/classrooms/show.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

filter_html = """
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
"""

content = re.sub(
    r"<!-- Tab: Jurnal -->\s*<div x-show=\"tab === 'jurnal'\" style=\"display: none;\" class=\"animate-\[fadeIn_0\.3s_ease-out\]\">\s*<div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6\">",
    filter_html,
    content,
    flags=re.MULTILINE
)

# Remove the individual print button
content = re.sub(
    r"<a href=\"\{\{ route\('journals\.print', \$journal\) \}\}\" target=\"_blank\".*?</a>",
    "",
    content,
    flags=re.DOTALL
)

with open(file_path, 'w') as f:
    f.write(content)

print("Updated show.blade.php with filter UI")
