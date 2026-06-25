import re

file_path = 'resources/views/classrooms/index.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

old_block = """                            @if($schedules->count() > 0)
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
                                </div>"""

new_block = """                            @if($schedules->count() > 0)
                                <div class="flex flex-col gap-3">
                                    @foreach($schedules as $schedule)
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border-2 border-cloud-gray rounded-2xl bg-snow-white shadow-sm hover:border-sky-blue transition relative overflow-hidden">
                                            <!-- Aksens warna di kiri -->
                                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-sky-blue opacity-80"></div>
                                            
                                            <div class="flex items-center gap-4 mb-3 sm:mb-0 pl-2">
                                                <div class="w-14 h-14 bg-sky-blue/10 text-sky-blue rounded-xl flex flex-col items-center justify-center flex-shrink-0">
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
                                </div>"""

if old_block in content:
    content = content.replace(old_block, new_block)
    with open(file_path, 'w') as f:
        f.write(content)
    print("Updated layout to vertical list successfully.")
else:
    print("Could not find block to replace.")
