with open('resources/views/welcome.blade.php', 'r') as f:
    content = f.read()

# Locate the features section
start_marker = "<!-- Feature 3 -->"
end_marker = "</section>"

start_idx = content.find(start_marker)
end_idx = content.find(end_marker, start_idx)

if start_idx != -1 and end_idx != -1:
    section_to_replace = content[start_idx:end_idx]
    
    new_features = """<!-- Feature 3 -->
                <div class="glass-card hover:-translate-y-2 transition duration-300">
                    <div class="w-14 h-14 bg-accent text-snow-white rounded-2xl flex items-center justify-center mb-6 shadow-md shadow-accent/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-3">Bahan Ajar Utama</h3>
                    <p class="font-medium text-graphite text-sm leading-relaxed mb-4">Sediakan materi pendukung yang padat dan komprehensif. PandAI menyusun ringkasan bacaan yang siap dipelajari siswa maupun guru.</p>
                    <ul class="space-y-2">
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Konten Mendalam</li>
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Format Moduler</li>
                    </ul>
                </div>
                
                <!-- Feature 4 -->
                <div class="glass-card hover:-translate-y-2 transition duration-300">
                    <div class="w-14 h-14 bg-[#f87171] text-snow-white rounded-2xl flex items-center justify-center mb-6 shadow-md shadow-red-500/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-3">LKPD Interaktif</h3>
                    <p class="font-medium text-graphite text-sm leading-relaxed mb-4">Merangsang keaktifan kelas dengan Lembar Kerja Peserta Didik (LKPD) yang menarik. Lengkap dengan instruksi eksperimen atau aktivitas kolaboratif.</p>
                    <ul class="space-y-2">
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Aktivitas Terstruktur</li>
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Siap Cetak</li>
                    </ul>
                </div>

                <!-- Feature 5 -->
                <div class="glass-card hover:-translate-y-2 transition duration-300">
                    <div class="w-14 h-14 bg-[#a78bfa] text-snow-white rounded-2xl flex items-center justify-center mb-6 shadow-md shadow-purple-500/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-3">Rubrik Penilaian</h3>
                    <p class="font-medium text-graphite text-sm leading-relaxed mb-4">Ucapkan selamat tinggal pada kesulitan menilai presentasi atau proyek. Dapatkan matriks rubrik penilaian yang adil dan objektif secara otomatis.</p>
                    <ul class="space-y-2">
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Kriteria Jelas</li>
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Skor Berjenjang</li>
                    </ul>
                </div>

                <!-- Feature 6 -->
                <div class="glass-card hover:-translate-y-2 transition duration-300">
                    <div class="w-14 h-14 bg-[#fb923c] text-snow-white rounded-2xl flex items-center justify-center mb-6 shadow-md shadow-orange-500/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-3">Jurnal & Presensi</h3>
                    <p class="font-medium text-graphite text-sm leading-relaxed mb-4">Bukan cuma konten, kelola kelas harian Anda. Catat jurnal mengajar, rekap kehadiran siswa, hingga cetak laporan bulanan dengan satu klik.</p>
                    <ul class="space-y-2">
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Laporan Terintegrasi</li>
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Pencatatan Real-time</li>
                    </ul>
                </div>
            </div>
        </div>
    """
    
    content = content.replace(section_to_replace, new_features)

    # Let's fix the grid from md:grid-cols-3 to lg:grid-cols-3 and md:grid-cols-2 so it looks good for 6 cards.
    content = content.replace('class="grid md:grid-cols-3 gap-8"', 'class="grid md:grid-cols-2 lg:grid-cols-3 gap-8"')

    with open('resources/views/welcome.blade.php', 'w') as f:
        f.write(content)
        
    print("Added all 6 feature cards!")
else:
    print("Could not find the section to replace.")
