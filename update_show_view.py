import re

file_path = 'resources/views/classrooms/show.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

# Add Paste UI inside Tab Siswa, next to the normal Add Student form
# Look for the section where "Kiri: Form Tambah" ends and "Kanan: Tabel" begins.
# Actually, let's just make the left side a bit more complex, with two Alpine tabs: Manual / Paste.

new_left_side = """
                <!-- Kiri: Form Tambah -->
                <div class="w-full md:w-1/3" x-data="{ addMode: 'manual' }">
                    <div class="sticky top-6">
                        <!-- Toggle Manual / Paste -->
                        <div class="flex gap-2 p-1 bg-cloud-gray/30 rounded-xl mb-4">
                            <button @click="addMode = 'manual'" :class="{ 'bg-snow-white text-charcoal shadow-sm': addMode === 'manual', 'text-graphite': addMode !== 'manual' }" class="flex-1 py-1.5 rounded-lg font-bold text-xs transition-all">Manual</button>
                            <button @click="addMode = 'paste'" :class="{ 'bg-snow-white text-charcoal shadow-sm': addMode === 'paste', 'text-graphite': addMode !== 'paste' }" class="flex-1 py-1.5 rounded-lg font-bold text-xs transition-all">Paste dari Excel</button>
                        </div>
                        
                        <!-- Mode Manual -->
                        <form x-show="addMode === 'manual'" action="{{ route('classrooms.students.store', $classroom) }}" method="POST" class="p-6 bg-[#fdfdfd] rounded-2xl border-2 border-cloud-gray space-y-4 shadow-sm animate-[fadeIn_0.3s_ease-out]">
                            @csrf
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 bg-duo-green/20 text-duo-green rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                </div>
                                <h3 class="font-bold text-lg text-charcoal">Tambah Siswa</h3>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-1">Nama Lengkap</label>
                                <input type="text" name="name" placeholder="Cth: Budi Santoso" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-1">NIS/NISN (Opsional)</label>
                                <input type="text" name="nisn" placeholder="Cth: 12345" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-1">Jenis Kelamin</label>
                                <select name="gender" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                                    <option value="">- Pilih Jenis -</option>
                                    <option value="L">Laki-laki (L)</option>
                                    <option value="P">Perempuan (P)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-3d-primary w-full text-sm py-2.5 mt-2">Simpan Siswa</button>
                        </form>

                        <!-- Mode Paste Excel -->
                        <form x-show="addMode === 'paste'" style="display: none;" action="{{ route('classrooms.students.import', $classroom) }}" method="POST" class="p-6 bg-[#fdfdfd] rounded-2xl border-2 border-cloud-gray space-y-4 shadow-sm animate-[fadeIn_0.3s_ease-out]">
                            @csrf
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 bg-sky-blue/20 text-sky-blue rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <h3 class="font-bold text-lg text-charcoal">Paste dari Excel</h3>
                            </div>
                            
                            <div class="bg-cloud-gray/30 p-3 rounded-xl border border-cloud-gray/50 text-xs text-graphite space-y-1">
                                <p class="font-bold text-charcoal">Cara pakai:</p>
                                <p>1. Buka file Excel Anda.</p>
                                <p>2. Copy kolom: <b class="text-charcoal">Nama | NISN | L/P</b> (urutan harus begini).</p>
                                <p>3. Paste (Ctrl+V) ke dalam kotak di bawah.</p>
                            </div>

                            <div>
                                <textarea name="paste_data" rows="8" class="w-full border-2 border-cloud-gray rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition resize-y font-mono whitespace-pre" placeholder="Budi Santoso&#9;1234&#9;L&#10;Siti Aminah&#9;1235&#9;P&#10;..."></textarea>
                            </div>
                            <button type="submit" class="btn-3d-primary w-full text-sm py-2.5 mt-2 bg-sky-blue hover:bg-sky-blue/90 text-white border-0" style="box-shadow: 0 4px 0 #1899d6;">Import Data Siswa</button>
                        </form>
                    </div>
                </div>
"""

# Regex to replace the old "Kiri: Form Tambah" block
old_pattern = r'<!-- Kiri: Form Tambah -->\s*<div class="w-full md:w-1/3">.*?</form>\s*</div>'
content = re.sub(old_pattern, new_left_side, content, flags=re.DOTALL)

with open(file_path, 'w') as f:
    f.write(content)

print("Updated show.blade.php with Paste from Excel UI")
