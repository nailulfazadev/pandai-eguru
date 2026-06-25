import re

file_path = 'resources/views/journals/create.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

# Replace the info block when $schedule is present
old_info = """                <div class="bg-cloud-gray/20 border border-cloud-gray text-graphite p-3 rounded-xl text-xs mb-4">
                    Jurnal ini sudah dikunci untuk jadwal <b>{{ $schedule->day_of_week }}</b>. Pastikan tanggal yang Anda pilih jatuh pada hari tersebut.
                </div>"""
new_info = """                <div class="bg-cloud-gray/20 border border-cloud-gray text-graphite p-3 rounded-xl text-sm mb-4">
                    <p class="mb-1">Jadwal: <b>{{ $schedule->day_of_week }}, {{ $schedule->start_time }} - {{ $schedule->end_time }}</b></p>
                    <p>Tanggal: <b>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</b> (Hari Ini)</p>
                </div>"""
content = content.replace(old_info, new_info)

# Replace the Date input to handle if $schedule is present
old_date = """                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Tanggal</label>
                    <input type="date" name="date" x-model="selectedDate" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                </div>"""

new_date = """                @if($schedule)
                    <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                @else
                <div>
                    <label class="block text-sm font-bold text-charcoal mb-1">Tanggal <span class="text-xs text-graphite font-normal">(Ganti jika ingin merapel jurnal lampau)</span></label>
                    <input type="date" name="date" x-model="selectedDate" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                </div>
                @endif"""
content = content.replace(old_date, new_date)

with open(file_path, 'w') as f:
    f.write(content)

print("Updated create.blade.php to hide date when schedule is set")
