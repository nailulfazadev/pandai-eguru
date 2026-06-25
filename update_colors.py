import re

file_path = 'resources/views/classrooms/index.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

old_block = """                                    @foreach($schedules as $schedule)
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border-2 border-cloud-gray rounded-2xl bg-snow-white shadow-sm hover:border-sky-blue transition relative overflow-hidden">
                                            <!-- Aksens warna di kiri -->
                                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-sky-blue opacity-80"></div>
                                            
                                            <div class="flex items-center gap-4 mb-3 sm:mb-0 pl-2">
                                                <div class="w-14 h-14 bg-sky-blue/10 text-sky-blue rounded-xl flex flex-col items-center justify-center flex-shrink-0">"""

new_block = """                                    @foreach($schedules as $schedule)
                                        @php
                                            $themeColors = ['sky-blue', 'grape-soda', 'duo-green', 'bubblegum-pink', 'sunshine-yellow'];
                                            $colorClass = $themeColors[$schedule->classroom_id % count($themeColors)];
                                        @endphp
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border-2 border-cloud-gray rounded-2xl bg-snow-white shadow-sm hover:border-{{ $colorClass }} transition relative overflow-hidden">
                                            <!-- Aksens warna di kiri -->
                                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-{{ $colorClass }} opacity-80"></div>
                                            
                                            <div class="flex items-center gap-4 mb-3 sm:mb-0 pl-2">
                                                <div class="w-14 h-14 bg-{{ $colorClass }}/10 text-{{ $colorClass }} rounded-xl flex flex-col items-center justify-center flex-shrink-0">"""

content = content.replace(old_block, new_block)

with open(file_path, 'w') as f:
    f.write(content)

print("Updated colors successfully")
