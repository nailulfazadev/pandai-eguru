import re

with open('resources/views/welcome.blade.php', 'r') as f:
    content = f.read()

# 1. Reduce bold text
content = content.replace('font-black', 'font-extrabold')
content = content.replace('font-bold', 'font-medium')

# 2. Expand feature explanations
f1_old = "Hanya dengan mengetikkan topik dan kelas, AI kami menyusun Tujuan Pembelajaran, Langkah-Langkah, hingga Asesmen dalam satu klik."
f1_new = "Hanya dengan mengetikkan topik dan kelas, AI kami menyusun Tujuan Pembelajaran, Langkah-Langkah Kegiatan, Profil Pelajar Pancasila, hingga Asesmen secara komprehensif dalam satu klik. Hemat waktu berjam-jam untuk administrasi!"

f2_old = "Perlu soal dadakan? PandAI bisa membuat soal Pilihan Ganda dan Esai lengkap dengan kunci jawaban, anti repot!"
f2_new = "Perlu evaluasi harian atau ujian dadakan? PandAI secara cerdas membuat paket soal Pilihan Ganda dan Esai yang disesuaikan dengan tingkat kesulitan siswa, lengkap dengan kunci jawaban dan rubrik penilaian mendetail."

f3_old = "Sediakan materi pendukung yang interaktif. PandAI meracik Lembar Kerja Peserta Didik yang merangsang keaktifan siswa."
f3_new = "Sediakan materi pendukung yang interaktif dan menyenangkan. PandAI meracik Lembar Kerja Peserta Didik (LKPD), Bahan Bacaan, hingga Rubrik Penilaian Presentasi yang dirancang khusus untuk merangsang keaktifan dan kolaborasi siswa."

content = content.replace(f1_old, f1_new)
content = content.replace(f2_old, f2_new)
content = content.replace(f3_old, f3_new)

# 3. Change "Semester Plan" color
sem_old = """<div class="bg-snow-white p-8 rounded-3xl border-2 border-cloud-gray shadow-xl relative overflow-hidden transform md:-translate-y-2">"""
sem_new = """<div class="bg-[#f0f9ff] p-8 rounded-3xl border-2 border-accent shadow-xl shadow-accent/20 relative overflow-hidden transform md:-translate-y-2">"""
content = content.replace(sem_old, sem_new)

# Also update the text in Semester Plan to fit the accent color
content = content.replace('<h3 class="text-2xl font-extrabold text-almost-black mb-2">Paket Semester</h3>', '<h3 class="text-2xl font-extrabold text-accent mb-2">Paket Semester</h3>')
content = content.replace('<a href="https://solusiedu.myr.id/pl/PandAI-by-e-Guru?email={{ auth()->check() ? urlencode(auth()->user()->email) : \'\' }}&name={{ auth()->check() ? urlencode(auth()->user()->name) : \'\' }}&mobile={{ auth()->check() && auth()->user()->phone ? urlencode(auth()->user()->phone) : \'\' }}" target="_blank" class="block w-full py-4 px-6 bg-cloud-gray hover:bg-silver text-almost-black font-medium rounded-2xl text-center uppercase tracking-wider transition">Beli Paket Semester</a>', '<a href="https://solusiedu.myr.id/pl/PandAI-by-e-Guru?email={{ auth()->check() ? urlencode(auth()->user()->email) : \'\' }}&name={{ auth()->check() ? urlencode(auth()->user()->name) : \'\' }}&mobile={{ auth()->check() && auth()->user()->phone ? urlencode(auth()->user()->phone) : \'\' }}" target="_blank" class="block w-full py-4 px-6 bg-accent hover:bg-[#189ce0] text-snow-white font-medium rounded-2xl text-center uppercase tracking-wider transition" style="box-shadow: 0 4px 0 #1583bc;">Beli Paket Semester</a>')


with open('resources/views/welcome.blade.php', 'w') as f:
    f.write(content)

print("Updates applied to welcome.blade.php")
