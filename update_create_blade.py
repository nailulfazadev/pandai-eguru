import re

file_path = 'resources/views/journals/create.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

# Replace the form tag with Alpine x-data
content = content.replace(
    '<form action="{{ route(\'journals.store\', $classroom) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">',
    '<form action="{{ route(\'journals.store\', $classroom) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data="journalForm()">'
)

# Replace the Date input to use x-model="selectedDate"
date_input_pattern = r'<input type="date" name="date" value="\{\{ date\(\'Y-m-d\'\) \}\}" class="w-full border-2 border-cloud-gray rounded-xl py-2\.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-\[\#f9f9f9\] transition" required>'
new_date_input = '<input type="date" name="date" x-model="selectedDate" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>'
content = re.sub(date_input_pattern, new_date_input, content)

# Replace the select dropdown
old_select = """                    <select name="schedule_id" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                        <option value="">-- Pilih Jam Mengajar --</option>
                        @foreach($schedules as $sch)
                            <option value="{{ $sch->id }}">{{ $sch->day_of_week }}, {{ $sch->start_time }} - {{ $sch->end_time }}</option>
                        @endforeach
                    </select>"""
new_select = """                    <select name="schedule_id" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                        <option value="">-- Pilih Jam Mengajar --</option>
                        <template x-for="sch in filteredSchedules" :key="sch.id">
                            <option :value="sch.id" x-text="sch.start_time + ' - ' + sch.end_time"></option>
                        </template>
                    </select>
                    <p class="text-xs text-graphite mt-1" x-show="filteredSchedules.length === 0">Tidak ada jadwal kelas ini di hari <span class="font-bold text-bubblegum-pink" x-text="dayOfWeek"></span>.</p>
                    <p class="text-xs text-sky-blue font-bold mt-1" x-show="filteredSchedules.length > 0">Terdapat <span x-text="filteredSchedules.length"></span> jadwal di hari <span x-text="dayOfWeek"></span>.</p>"""
content = content.replace(old_select, new_select)

# If $schedule is set, we also want to display something or restrict it
# Actually, if $schedule is set, the date might still be changeable, but we should make sure the user knows it's tied to that schedule.
# Or if $schedule is set, make the date readonly.

if_schedule = """    @if($schedule)
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
        <div class="md:col-span-1 space-y-4">
            <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray shadow-sm">
                <div class="flex justify-between items-center mb-4 border-b border-cloud-gray pb-2">
                    <h2 class="font-bold text-lg text-charcoal">Jurnal Mengajar</h2>
                    <span class="text-xs bg-duo-green-light text-duo-green px-2 py-1 rounded font-bold">{{ $schedule->day_of_week }}, {{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                </div>"""

new_if_schedule = """    @if($schedule)
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
        <div class="md:col-span-1 space-y-4">
            <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray shadow-sm">
                <div class="flex justify-between items-center mb-4 border-b border-cloud-gray pb-2">
                    <h2 class="font-bold text-lg text-charcoal">Jurnal Mengajar</h2>
                    <span class="text-xs bg-duo-green-light text-duo-green px-2 py-1 rounded font-bold">{{ $schedule->day_of_week }}, {{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                </div>
                <div class="bg-cloud-gray/20 border border-cloud-gray text-graphite p-3 rounded-xl text-xs mb-4">
                    Jurnal ini sudah dikunci untuk jadwal <b>{{ $schedule->day_of_week }}</b>. Pastikan tanggal yang Anda pilih jatuh pada hari tersebut.
                </div>"""
content = content.replace(if_schedule, new_if_schedule)

# Add alpine script at the end before @endsection
script_block = """
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('journalForm', () => ({
        selectedDate: '{{ date('Y-m-d') }}',
        schedules: @json($schedules),
        
        get dayOfWeek() {
            if (!this.selectedDate) return '';
            const date = new Date(this.selectedDate);
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            return days[date.getDay()];
        },
        
        get filteredSchedules() {
            return this.schedules.filter(s => s.day_of_week === this.dayOfWeek);
        }
    }))
})
</script>
"""
content = content.replace("@endsection", script_block + "\n@endsection")

with open(file_path, 'w') as f:
    f.write(content)

print("Updated create.blade.php with AlpineJS smart date-schedule linking")
