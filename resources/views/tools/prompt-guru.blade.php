@extends('layouts.app')

@section('title', 'Prompt Guru AI - PandAI')

@section('content')
    <!-- Header Page -->
    <section class="select-none mb-6 print:hidden">
        <h2 class="text-heading font-feather text-almost-black">Prompt Guru AI (Super Prompt Builder)</h2>
        <p class="text-body text-graphite">Rakit instruksi prompt terstruktur dan kaya konteks secara instan untuk disalin ke Google Gemini, ChatGPT, atau DeepSeek.</p>
    </section>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Options Form -->
        <div class="col-span-1 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm space-y-5 print:hidden">
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-3 select-none">
                <h3 class="text-heading-sm font-feather text-almost-black">Konteks & Parameter</h3>
                <button type="button" id="fill-demo-btn" class="text-xs font-bold text-grape-soda hover:underline">Isi Contoh</button>
            </div>
            
            <form id="generate-form" class="space-y-4">
                @csrf

                <!-- Jenis Produk / Output -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Jenis Produk Pendidikan</label>
                    <select name="prompt_type" id="prompt_type" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                        <option value="modul_ajar" selected>Modul Ajar Kurikulum Merdeka (RPP)</option>
                        <option value="rpl_bk">Rencana Layanan BK (RPL BK)</option>
                        <option value="soal_ujian">Bank Soal & Asesmen</option>
                        <option value="proyek_p5">Modul Proyek P5</option>
                    </select>
                </div>

                <!-- Collapsible Identitas Sekolah & Guru (Opsional) -->
                <div class="border-2 border-cloud-gray rounded-xl p-3 bg-white space-y-2 select-none">
                    <button type="button" onclick="document.getElementById('school-identity-fields').classList.toggle('hidden')" class="w-full flex justify-between items-center text-xs font-bold text-charcoal focus:outline-none">
                        <span>Identitas Sekolah & Guru (Opsional)</span>
                        <svg class="w-4 h-4 text-graphite" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div id="school-identity-fields" class="hidden space-y-3 pt-2 border-t border-cloud-gray">
                        <!-- Nama Guru -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-graphite">Nama Guru</label>
                            <input type="text" name="teacher_name" placeholder="Nama Lengkap Guru" value="{{ auth()->user()->name }}"
                                   class="w-full border-2 border-cloud-gray rounded-lg py-1.5 px-3 text-xs focus:outline-none focus:border-grape-soda bg-[#f9f9f9]">
                        </div>
                        
                        <!-- NIP Guru -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-graphite">NIP Guru</label>
                            <input type="text" name="teacher_nip" placeholder="NIP Guru (jika ada)" value="{{ auth()->user()->nip }}"
                                   class="w-full border-2 border-cloud-gray rounded-lg py-1.5 px-3 text-xs focus:outline-none focus:border-grape-soda bg-[#f9f9f9]">
                        </div>

                        <!-- Nama Sekolah -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-graphite">Nama Sekolah / Instansi</label>
                            <input type="text" name="school_name" placeholder="Contoh: SMA Negeri 1 Jakarta" value="{{ auth()->user()->school_name }}"
                                   class="w-full border-2 border-cloud-gray rounded-lg py-1.5 px-3 text-xs focus:outline-none focus:border-grape-soda bg-[#f9f9f9]">
                        </div>

                        <!-- Nama Kepala Sekolah -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-graphite">Nama Kepala Sekolah</label>
                            <input type="text" name="principal_name" placeholder="Nama Kepala Sekolah" value="{{ auth()->user()->principal_name }}"
                                   class="w-full border-2 border-cloud-gray rounded-lg py-1.5 px-3 text-xs focus:outline-none focus:border-grape-soda bg-[#f9f9f9]">
                        </div>

                        <!-- NIP Kepala Sekolah -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-graphite">NIP Kepala Sekolah</label>
                            <input type="text" name="principal_nip" placeholder="NIP Kepala Sekolah" value="{{ auth()->user()->principal_nip }}"
                                   class="w-full border-2 border-cloud-gray rounded-lg py-1.5 px-3 text-xs focus:outline-none focus:border-grape-soda bg-[#f9f9f9]">
                        </div>
                    </div>
                </div>

                <!-- Theme / Tema P5 (Only for P5) -->
                <div class="space-y-1 hidden" id="theme-group">
                    <label class="block text-sm font-bold text-charcoal select-none">Tema Proyek P5</label>
                    <select name="theme" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                        <option value="Gaya Hidup Berkelanjutan">Gaya Hidup Berkelanjutan</option>
                        <option value="Kearifan Lokal">Kearifan Lokal</option>
                        <option value="Bhinneka Tunggal Ika">Bhinneka Tunggal Ika</option>
                        <option value="Bangunlah Jiwa dan Raganya">Bangunlah Jiwa dan Raganya</option>
                        <option value="Suara Demokrasi">Suara Demokrasi</option>
                        <option value="Rekayasa dan Teknologi">Rekayasa dan Teknologi</option>
                        <option value="Kewirausahaan">Kewirausahaan</option>
                    </select>
                </div>

                <!-- Bidang Layanan (Only for BK) -->
                <div class="space-y-1 hidden" id="bk-field-group">
                    <label class="block text-sm font-bold text-charcoal select-none">Bidang Layanan BK</label>
                    <select name="bk_field" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                        <option value="Pribadi">Pribadi</option>
                        <option value="Sosial">Sosial</option>
                        <option value="Belajar">Belajar</option>
                        <option value="Karir">Karir</option>
                    </select>
                </div>

                <!-- Subject (hidden for BK and P5) -->
                <div class="space-y-1" id="subject-group">
                    <label class="block text-sm font-bold text-charcoal select-none">Mata Pelajaran</label>
                    <input type="text" name="subject" placeholder="Contoh: IPAS, Matematika, Biologi" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                </div>

                <!-- Topic -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none" id="topic-label">Materi / Topik Pembelajaran</label>
                    <input type="text" name="topic" id="topic" placeholder="Contoh: Fotosintesis pada Tumbuhan" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                </div>

                <!-- Grade / Kelas & Semester -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none" id="grade-label">Kelas / Jenjang Sasaran</label>
                    <input type="text" name="grade" id="grade" placeholder="Contoh: Kelas IV SD, Kelas IX SMP, Kelas 12" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                </div>

                <!-- Time Allocation -->
                <div class="space-y-1" id="time-allocation-group">
                    <label class="block text-sm font-bold text-charcoal select-none">Alokasi Waktu</label>
                    <input type="text" name="time_allocation" id="time_allocation" placeholder="Contoh: 2 x 45 Menit, 3 JP" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition" value="2 x 45 Menit">
                </div>

                <!-- Learning Model Dropdown -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none" id="model-label">Model / Metode Pembelajaran</label>
                    <select name="learning_model" id="learning_model" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                        <option value="Project-Based Learning (PjBL)" selected>Project-Based Learning (PjBL)</option>
                        <option value="Problem-Based Learning (PBL)">Problem-Based Learning (PBL)</option>
                        <option value="Discovery Learning">Discovery Learning</option>
                        <option value="Inquiry Learning">Inquiry Learning</option>
                        <option value="Cooperative Learning">Cooperative Learning</option>
                        <option value="Contextual Teaching and Learning (CTL)">Contextual Teaching and Learning (CTL)</option>
                        <option value="Experiential Learning">Experiential Learning</option>
                        <option value="Cinema Education">Cinema Education</option>
                        <option value="Diskusi Kelompok & Penugasan">Diskusi Kelompok & Penugasan</option>
                        <option value="Ceramah Interaktif">Ceramah Interaktif</option>
                        <option value="Lainnya">Lainnya (Tulis Kustom)</option>
                    </select>
                    <!-- Custom input for model, hidden by default -->
                    <input type="text" name="custom_learning_model" id="custom_learning_model" placeholder="Tulis model pembelajaran kustom..."
                           class="hidden mt-2 w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                </div>

                <!-- CP & TP (Modul Ajar & Soal/BK) -->
                <div class="space-y-3" id="cptp-group">
                    <div class="flex justify-between items-center">
                        <label class="block text-sm font-bold text-charcoal select-none">Capaian & Tujuan Pembelajaran</label>
                        <button type="button" id="recommend-cptp-btn" class="text-xs font-bold text-[#8e52ff] hover:text-grape-soda flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            Dapatkan Ide CP/TP (AI)
                        </button>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-graphite select-none">Capaian Pembelajaran (CP) / Standar Kompetensi</label>
                        <textarea name="cp" id="cp_input" placeholder="Tulis CP di sini (atau klik Dapatkan Ide AI)..."
                                  class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-xs focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition h-20 resize-none"></textarea>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-graphite select-none" id="tp-label">Tujuan Pembelajaran (TP)</label>
                        <textarea name="tp" id="tp_input" placeholder="Tulis TP di sini (atau klik Dapatkan Ide AI)..."
                                  class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-xs focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition h-20 resize-none"></textarea>
                    </div>
                </div>

                <!-- Student Profile -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none" id="profile-label">Profil & Karakter Siswa</label>
                    <input type="text" name="student_profile" placeholder="Contoh: Senang praktek, aktif, butuh visual" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                </div>

                <!-- P5 Checkboxes (For Modul Ajar & P5) -->
                <div class="space-y-2 select-none" id="p5-group">
                    <label class="block text-sm font-bold text-charcoal">Profil Pelajar Pancasila (P5)</label>
                    <div class="grid grid-cols-2 gap-2 text-xs font-bold text-graphite bg-[#f9f9f9] border-2 border-cloud-gray p-3 rounded-xl">
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="p5[]" value="Beriman, bertakwa kepada Tuhan YME, dan berakhlak mulia" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5" checked>
                            <span>Berakhlak Mulia</span>
                        </label>
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="p5[]" value="Berkebinekaan global" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5" checked>
                            <span>Kebinekaan Global</span>
                        </label>
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="p5[]" value="Gotong royong" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5" checked>
                            <span>Gotong Royong</span>
                        </label>
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="p5[]" value="Mandiri" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5" checked>
                            <span>Mandiri</span>
                        </label>
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="p5[]" value="Bernalar kritis" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5" checked>
                            <span>Bernalar Kritis</span>
                        </label>
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="p5[]" value="Kreatif" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5" checked>
                            <span>Kreatif</span>
                        </label>
                    </div>
                </div>

                <!-- Cognitive Levels (Only for Soal) -->
                <div class="space-y-2 select-none hidden" id="cognitive-group">
                    <label class="block text-sm font-bold text-charcoal">Tingkat Kognitif Soal</label>
                    <div class="grid grid-cols-3 gap-2 text-xs font-bold text-graphite bg-[#f9f9f9] border-2 border-cloud-gray p-3 rounded-xl">
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="cognitive[]" value="LOTS (C1-C2)" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5" checked>
                            <span>LOTS</span>
                        </label>
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="cognitive[]" value="MOTS (C3-C4)" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5" checked>
                            <span>MOTS</span>
                        </label>
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="cognitive[]" value="HOTS (C5-C6)" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5" checked>
                            <span>HOTS</span>
                        </label>
                    </div>
                </div>

                <!-- Question Types (Only for Soal) -->
                <div class="space-y-2 select-none hidden" id="question-types-group">
                    <label class="block text-sm font-bold text-charcoal">Bentuk Soal / Ujian</label>
                    <div class="grid grid-cols-2 gap-2 text-xs font-bold text-graphite bg-[#f9f9f9] border-2 border-cloud-gray p-3 rounded-xl">
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="qtypes[]" value="Pilihan Ganda" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5" checked>
                            <span>Pilihan Ganda</span>
                        </label>
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="qtypes[]" value="Pilihan Ganda Kompleks" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5">
                            <span>PG Kompleks</span>
                        </label>
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="qtypes[]" value="Menjodohkan" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5">
                            <span>Menjodohkan</span>
                        </label>
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="qtypes[]" value="Uraian/Essay" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5" checked>
                            <span>Uraian / Essay</span>
                        </label>
                    </div>
                </div>

                <!-- Dynamic Support Components Checklist -->
                <div class="space-y-2 select-none">
                    <label class="block text-sm font-bold text-charcoal">Komponen Pendukung Tambahan</label>
                    <div id="components-checklist-container" class="space-y-1.5 text-xs font-bold text-graphite bg-[#f9f9f9] border-2 border-cloud-gray p-3 rounded-xl flex flex-col">
                        <!-- Dynamic checkboxes based on Prompt Type selection (via JavaScript) -->
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Instruksi Khusus (Opsional)</label>
                    <textarea name="additional_notes" placeholder="Contoh: Tambahkan fokus pada profil kemandirian, sertakan aktivitas ice breaking seru..."
                              class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition h-20 resize-none"></textarea>
                </div>

                <!-- Mode Demo (Hemat Kuota AI) -->
                <div class="hidden flex items-center space-x-2 pt-2 select-none">
                    <input type="checkbox" id="use-mock" name="use_mock" value="1"
                           class="w-4 h-4 text-grape-soda border-cloud-gray rounded focus:ring-grape-soda">
                    <label for="use-mock" class="text-xs font-bold text-graphite cursor-pointer">
                        Mode Demo (Cepat & Hemat Kuota AI)
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submit-btn" class="btn-3d-primary w-full text-sm py-3 tracking-wider bg-grape-soda hover:bg-[#8e52ff] shadow-[0_4px_0_#743ae8]">
                    Rakit Prompt Super
                </button>
            </form>
        </div>

        <!-- Right: Preview & Workspace Area -->
        <div class="lg:col-span-2 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm min-h-[500px] flex flex-col justify-between print:border-none print:shadow-none print:p-0 print:w-full print:absolute print:top-0 print:left-0 print:m-0">
            
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-4 mb-4 select-none print:hidden">
                <div class="flex space-x-4">
                    <h3 class="text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-grape-soda pb-1" id="tab-preview">Rancangan Prompt AI</h3>
                </div>
                <div class="flex items-center space-x-4">
                    <span id="save-status" class="text-xs font-bold text-silver bg-[#f9f9f9] px-2.5 py-1 rounded-full border border-cloud-gray">Belum Disimpan</span>
                </div>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="flex flex-col items-center justify-center flex-1 h-full opacity-50 select-none print:hidden py-12">
                <svg class="w-20 h-20 text-silver mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 113.536 0V21h2v-5.343z"></path></svg>
                <p class="text-graphite font-bold text-center">Belum ada prompt yang dirakit.<br><span class="text-sm font-normal">Tentukan parameter di panel kiri lalu klik "Rakit Prompt Super" untuk merancang prompt pedagogis Anda.</span></p>
            </div>

            <!-- Workspace / Content Area -->
            <div id="content-area" class="hidden flex-1 flex-col w-full bg-white print:block">
                
                <div class="mb-4 select-none print:hidden">
                    <label class="block text-sm font-bold text-charcoal mb-2">Editor Prompt (Anda dapat mengedit langsung sebelum menyalin):</label>
                    <textarea id="prompt-editor" class="w-full bg-slate-900 border-2 border-slate-800 rounded-xl p-5 font-mono text-sm text-slate-100 select-all min-h-[350px] focus:outline-none focus:border-grape-soda shadow-inner leading-relaxed"></textarea>
                </div>

                <!-- Copied Toast / Banner (Hidden by default) -->
                <div id="copied-toast" class="hidden select-none bg-duo-green-light border-2 border-duo-green text-almost-black rounded-xl p-3 text-xs font-bold mb-4 flex items-center justify-between animate-bounce">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-duo-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span id="copied-toast-msg">Prompt berhasil disalin ke clipboard! Siap ditempelkan ke LLM.</span>
                    </div>
                </div>

                <!-- Actions / Copy & Launch LLM -->
                <div class="space-y-4 print:hidden" id="actions-pane">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" id="copy-btn" class="btn-3d-primary flex-1 py-3 text-sm bg-grape-soda hover:bg-[#8e52ff] shadow-[0_4px_0_#743ae8] flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            Salin Prompt Utama
                        </button>
                        
                        <a id="download-btn" href="#" class="btn-outline flex-1 py-3 text-sm text-sky-blue hover:text-sky-blue/80 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Unduh Dokumen Word (.doc)
                        </a>
                    </div>

                    <!-- LLM Launch Center -->
                    <div class="bg-[#f9f9f9] border-2 border-cloud-gray rounded-xl p-4 space-y-3">
                        <h4 class="text-xs font-bold text-charcoal tracking-wide uppercase select-none">Kirim & Gunakan di AI Engine Eksternal:</h4>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" onclick="launchLLM('https://gemini.google.com')" class="flex items-center justify-center gap-2 bg-white hover:bg-[#f1edfb] hover:border-[#8e52ff] border-2 border-cloud-gray rounded-xl p-3 text-xs font-bold text-[#4B0082] transition shadow-sm cursor-pointer">
                                <span class="w-3.5 h-3.5 rounded-full bg-[#8e52ff] inline-block"></span>
                                Gemini
                            </button>
                            <button type="button" onclick="launchLLM('https://chatgpt.com')" class="flex items-center justify-center gap-2 bg-white hover:bg-[#ebf8f2] hover:border-[#10a37f] border-2 border-cloud-gray rounded-xl p-3 text-xs font-bold text-[#10a37f] transition shadow-sm cursor-pointer">
                                <span class="w-3.5 h-3.5 rounded-full bg-[#10a37f] inline-block"></span>
                                ChatGPT
                            </button>
                            <button type="button" onclick="launchLLM('https://chat.deepseek.com')" class="flex items-center justify-center gap-2 bg-white hover:bg-[#ebf4fa] hover:border-[#008cff] border-2 border-cloud-gray rounded-xl p-3 text-xs font-bold text-[#008cff] transition shadow-sm cursor-pointer">
                                <span class="w-3.5 h-3.5 rounded-full bg-[#008cff] inline-block"></span>
                                DeepSeek
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Printable view (hidden in screen, visible on print) -->
                <div class="hidden print:block font-serif text-almost-black text-sm p-4 leading-relaxed" id="printable-area">
                    <!-- Dynamic print content filled via JS -->
                </div>
            </div>

        </div>
    </div>

    <!-- spacer -->
    <div class="h-12"></div>
@endsection

@section('styles')
    <style>
        @media print {
            body { background-color: #ffffff !important; color: #000000 !important; }
            #left-sidebar, header, footer, .print\:hidden { display: none !important; }
            main { padding: 0 !important; margin: 0 !important; }
            .col-span-2 { width: 100% !important; border: none !important; box-shadow: none !important; padding: 0 !important; }
        }
    </style>
@endsection

@section('scripts')
    <script>
        // DOM Elements
        const form = document.getElementById('generate-form');
        const fillDemoBtn = document.getElementById('fill-demo-btn');
        const submitBtn = document.getElementById('submit-btn');
        const emptyState = document.getElementById('empty-state');
        const contentArea = document.getElementById('content-area');
        const actionsPane = document.getElementById('actions-pane');
        const saveStatus = document.getElementById('save-status');
        const downloadBtn = document.getElementById('download-btn');
        const promptEditor = document.getElementById('prompt-editor');
        const copiedToast = document.getElementById('copied-toast');
        const copiedToastMsg = document.getElementById('copied-toast-msg');
        const printableArea = document.getElementById('printable-area');
        
        const promptTypeSelect = document.getElementById('prompt_type');
        const themeGroup = document.getElementById('theme-group');
        const bkFieldGroup = document.getElementById('bk-field-group');
        const subjectGroup = document.getElementById('subject-group');
        
        const topicLabel = document.getElementById('topic-label');
        const gradeLabel = document.getElementById('grade-label');
        const modelLabel = document.getElementById('model-label');
        const profileLabel = document.getElementById('profile-label');
        const tpLabel = document.getElementById('tp-label');
        
        const cptpGroup = document.getElementById('cptp-group');
        const p5Group = document.getElementById('p5-group');
        const cognitiveGroup = document.getElementById('cognitive-group');
        const questionTypesGroup = document.getElementById('question-types-group');
        const componentsContainer = document.getElementById('components-checklist-container');
        
        const learningModelSelect = document.getElementById('learning_model');
        const customLearningModelInput = document.getElementById('custom_learning_model');
        const recommendCptpBtn = document.getElementById('recommend-cptp-btn');
        
        const cpInput = document.getElementById('cp_input');
        const tpInput = document.getElementById('tp_input');

        // Dynamic support components checklists
        const supportComponentOptions = {
            'modul_ajar': [
                { value: 'Rangkuman Materi', label: 'Rangkuman Materi Pembelajaran', checked: true },
                { value: 'LKPD', label: 'Lembar Kerja Siswa (LKPD)', checked: true },
                { value: 'Asesmen Formatif', label: 'Asesmen Formatif (Kuis/Soal)', checked: true },
                { value: 'Asesmen Sumatif', label: 'Asesmen Sumatif (Rubrik Nilai)', checked: true },
                { value: 'Peta Konsep', label: 'Peta Konsep (Mermaid.js)', checked: false }
            ],
            'rpl_bk': [
                { value: 'Lembar Kerja Siswa (LKS BK)', label: 'Lembar Kerja Siswa (LKS BK)', checked: true },
                { value: 'Lembar Evaluasi', label: 'Lembar Evaluasi (Proses & Hasil)', checked: true },
                { value: 'Rencana Tindak Lanjut (RTL)', label: 'Rencana Tindak Lanjut (RTL)', checked: true }
            ],
            'soal_ujian': [
                { value: 'Kisi-kisi Soal Lengkap', label: 'Kisi-kisi Kisi Soal Lengkap', checked: true },
                { value: 'Kunci Jawaban & Pembahasan Detail', label: 'Kunci Jawaban & Pembahasan Detail', checked: true },
                { value: 'Rubrik Penilaian Kinerja', label: 'Rubrik Penilaian Kinerja', checked: false }
            ],
            'proyek_p5': [
                { value: 'Alur Proyek Detail', label: 'Alur Proyek Detail (5 Tahap)', checked: true },
                { value: 'Aktivitas Praktek Siswa', label: 'Panduan Aktivitas Praktek', checked: true },
                { value: 'Lembar Refleksi Diri & Kelompok', label: 'Lembar Refleksi Diri/Kelompok', checked: true },
                { value: 'Rubrik Asesmen Proyek', label: 'Rubrik Asesmen (MB/SB/BSH/SAB)', checked: true }
            ]
        };

        // Render dynamic support checkboxes
        function updateSupportingChecklist(type) {
            componentsContainer.innerHTML = '';
            const options = supportComponentOptions[type] || [];
            options.forEach(opt => {
                const label = document.createElement('label');
                label.className = 'flex items-center space-x-1.5 cursor-pointer';
                label.innerHTML = `
                    <input type="checkbox" name="supporting[]" value="${opt.value}" class="text-grape-soda focus:ring-grape-soda rounded w-3.5 h-3.5" ${opt.checked ? 'checked' : ''}>
                    <span>${opt.label}</span>
                `;
                componentsContainer.appendChild(label);
            });
        }

        // Learning model dropdown change trigger kustom model input
        learningModelSelect.addEventListener('change', () => {
            if (learningModelSelect.value === 'Lainnya') {
                customLearningModelInput.classList.remove('hidden');
                customLearningModelInput.required = true;
            } else {
                customLearningModelInput.classList.add('hidden');
                customLearningModelInput.required = false;
            }
        });

        // Recommend CP/TP with AJAX Gemini API
        recommendCptpBtn.addEventListener('click', async () => {
            const subjectVal = form.subject.value || '-';
            const topicVal = form.topic.value;
            const gradeVal = form.grade.value;

            if (!topicVal || !gradeVal) {
                alert('Mohon isi kolom Topik/Materi dan Kelas terlebih dahulu.');
                return;
            }

            const isDemo = document.getElementById('use-mock') ? document.getElementById('use-mock').checked : false;

            recommendCptpBtn.disabled = true;
            recommendCptpBtn.innerHTML = `
                <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Menganalisis Kurikulum...
            `;
            
            cpInput.placeholder = 'Sedang merumuskan Capaian Pembelajaran secara pedagogis...';
            tpInput.placeholder = 'Sedang merumuskan Tujuan Pembelajaran yang terukur...';
            cpInput.value = '';
            tpInput.value = '';

            try {
                const response = await fetch("{{ route('tools.recommend-cptp') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        subject: subjectVal,
                        topic: topicVal,
                        grade: gradeVal,
                        use_mock: isDemo
                    })
                });

                if (!response.ok) {
                    throw new Error('Gagal memproses rekomendasi.');
                }

                const res = await response.json();
                if (res.status === 'success') {
                    cpInput.value = res.cp;
                    tpInput.value = res.tp;
                } else {
                    alert('Gagal mendapatkan usulan CP/TP: ' + (res.message || 'Error tidak diketahui'));
                    cpInput.placeholder = 'Gagal memuat. Silakan isi manual.';
                    tpInput.placeholder = 'Gagal memuat. Silakan isi manual.';
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan koneksi saat memuat rekomendasi CP/TP.');
                cpInput.placeholder = 'Koneksi gagal. Silakan isi manual.';
                tpInput.placeholder = 'Koneksi gagal. Silakan isi manual.';
            } finally {
                recommendCptpBtn.disabled = false;
                recommendCptpBtn.innerHTML = `
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    Dapatkan Ide CP/TP (AI)
                `;
            }
        });

        // Handle forms dynamic show/hide
        promptTypeSelect.addEventListener('change', () => {
            const val = promptTypeSelect.value;
            
            // Default setup
            themeGroup.classList.add('hidden');
            bkFieldGroup.classList.add('hidden');
            subjectGroup.classList.add('hidden');
            cptpGroup.classList.add('hidden');
            p5Group.classList.add('hidden');
            cognitiveGroup.classList.add('hidden');
            questionTypesGroup.classList.add('hidden');
            
            form.subject.required = false;

            // Update Dynamic Checkbox Options
            updateSupportingChecklist(val);

            if (val === 'modul_ajar') {
                subjectGroup.classList.remove('hidden');
                form.subject.required = true;
                cptpGroup.classList.remove('hidden');
                p5Group.classList.remove('hidden');
                
                topicLabel.textContent = 'Materi / Topik Pembelajaran';
                gradeLabel.textContent = 'Kelas / Jenjang Sasaran';
                modelLabel.textContent = 'Model / Metode Pembelajaran';
                profileLabel.textContent = 'Profil & Karakter Siswa';
                tpLabel.textContent = 'Tujuan Pembelajaran (TP)';
                
                form.topic.placeholder = 'Contoh: Fotosintesis pada Tumbuhan';
                form.grade.placeholder = 'Contoh: Kelas IV / Semester I';
                form.student_profile.placeholder = 'Contoh: Aktif bergerak, senang praktek lapangan';

            } else if (val === 'rpl_bk') {
                bkFieldGroup.classList.remove('hidden');
                cptpGroup.classList.remove('hidden'); // BK needs Tujuan Layanan input

                topicLabel.textContent = 'Topik / Tema Layanan';
                gradeLabel.textContent = 'Kelas / Sasaran BK';
                modelLabel.textContent = 'Metode / Teknik Layanan BK';
                profileLabel.textContent = 'Kebutuhan / Karakter Konseli';
                tpLabel.textContent = 'Tujuan Layanan BK (Umum & Khusus)';

                form.topic.placeholder = 'Contoh: Manajemen Waktu Belajar Mandiri';
                form.grade.placeholder = 'Contoh: Kelas VIII SMP';
                form.student_profile.placeholder = 'Contoh: Sering menunda pekerjaan rumah, cemas berlebih';

            } else if (val === 'soal_ujian') {
                subjectGroup.classList.remove('hidden');
                form.subject.required = true;
                cptpGroup.classList.remove('hidden');
                cognitiveGroup.classList.remove('hidden');
                questionTypesGroup.classList.remove('hidden');

                topicLabel.textContent = 'Topik / Materi Soal';
                gradeLabel.textContent = 'Kelas / Jenjang Ujian';
                modelLabel.textContent = 'Model Pembelajaran Terkait';
                profileLabel.textContent = 'Karakter & Kemampuan Siswa';
                tpLabel.textContent = 'Capaian / Tujuan Pembelajaran';

                form.topic.placeholder = 'Contoh: Sistem Persamaan Linear Dua Variabel (SPLDV)';
                form.grade.placeholder = 'Contoh: Kelas VIII SMP';
                form.student_profile.placeholder = 'Contoh: Kemampuan kognitif rata-rata, suka visual';

            } else if (val === 'proyek_p5') {
                themeGroup.classList.remove('hidden');
                p5Group.classList.remove('hidden');

                topicLabel.textContent = 'Fokus Proyek / Judul Rencana Proyek';
                gradeLabel.textContent = 'Kelas / Jenjang Sasaran P5';
                modelLabel.textContent = 'Model / Pendekatan Proyek';
                profileLabel.textContent = 'Karakter Siswa & Isu Sekitar';

                form.topic.placeholder = 'Contoh: Pembuatan Kebun Apotek Hidup di Sekolah';
                form.grade.placeholder = 'Contoh: Fase D / Kelas VII SMP';
                form.student_profile.placeholder = 'Contoh: Senang eksplorasi alam bebas, isu lahan kosong sekolah';
            }
        });

        // Initialize checkboxes
        updateSupportingChecklist('modul_ajar');

        // Browser Database / localStorage Persistence
        function saveProfileSettingsToLocalStorage() {
            localStorage.setItem('sa_teacher_name', form.elements['teacher_name'].value);
            localStorage.setItem('sa_teacher_nip', form.elements['teacher_nip'].value);
            localStorage.setItem('sa_school_name', form.elements['school_name'].value);
            localStorage.setItem('sa_principal_name', form.elements['principal_name'].value);
            localStorage.setItem('sa_principal_nip', form.elements['principal_nip'].value);
        }

        function loadProfileSettingsFromLocalStorage() {
            const teacher_name = localStorage.getItem('sa_teacher_name');
            const teacher_nip = localStorage.getItem('sa_teacher_nip');
            const school_name = localStorage.getItem('sa_school_name');
            const principal_name = localStorage.getItem('sa_principal_name');
            const principal_nip = localStorage.getItem('sa_principal_nip');

            if (teacher_name !== null) form.elements['teacher_name'].value = teacher_name;
            if (teacher_nip !== null) form.elements['teacher_nip'].value = teacher_nip;
            if (school_name !== null) form.elements['school_name'].value = school_name;
            if (principal_name !== null) form.elements['principal_name'].value = principal_name;
            if (principal_nip !== null) form.elements['principal_nip'].value = principal_nip;
        }

        // Load initial settings
        loadProfileSettingsFromLocalStorage();

        // Listen to changes to save automatically
        const profileFields = ['teacher_name', 'teacher_nip', 'school_name', 'principal_name', 'principal_nip'];
        profileFields.forEach(fieldName => {
            const input = form.elements[fieldName];
            if (input) {
                input.addEventListener('input', saveProfileSettingsToLocalStorage);
                input.addEventListener('change', saveProfileSettingsToLocalStorage);
            }
        });

        // Page Initial Load (Load Document from DB history if present)
        @if(isset($document))
            try {
                const docData = {!! $document->content !!};
                
                // Populate forms
                form.prompt_type.value = docData.prompt_type || 'modul_ajar';
                if (docData.prompt_type === 'Modul Ajar Kurikulum Merdeka') form.prompt_type.value = 'modul_ajar';
                if (docData.prompt_type === 'Rencana Layanan BK (RPL BK)') form.prompt_type.value = 'rpl_bk';
                if (docData.prompt_type === 'Bank Soal & Asesmen') form.prompt_type.value = 'soal_ujian';
                if (docData.prompt_type === 'Modul Proyek P5') form.prompt_type.value = 'proyek_p5';

                // Trigger change to update labels/visibility
                promptTypeSelect.dispatchEvent(new Event('change'));
                
                form.subject.value = docData.subject || '';
                form.topic.value = docData.topic || '';
                form.grade.value = docData.grade || '';
                form.time_allocation.value = docData.time_allocation || '2 x 45 Menit';
                
                // Populate Collapsible Identity fields
                form.teacher_name.value = docData.teacher_name || '';
                form.teacher_nip.value = docData.teacher_nip || '';
                form.school_name.value = docData.school_name || '';
                form.principal_name.value = docData.principal_name || '';
                form.principal_nip.value = docData.principal_nip || '';
                
                // Set learning model select or input
                if (docData.learning_model) {
                    const selectOptions = Array.from(learningModelSelect.options).map(opt => opt.value);
                    if (selectOptions.includes(docData.learning_model)) {
                        learningModelSelect.value = docData.learning_model;
                    } else {
                        learningModelSelect.value = 'Lainnya';
                        customLearningModelInput.classList.remove('hidden');
                        customLearningModelInput.value = docData.learning_model;
                    }
                }
                
                form.student_profile.value = docData.student_profile || '';
                form.additional_notes.value = docData.additional_notes || '';
                form.cp.value = docData.cp || '';
                form.tp.value = docData.tp || '';
                
                if (docData.theme) {
                    form.theme.value = docData.theme;
                }
                if (docData.bk_field) {
                    form.bk_field.value = docData.bk_field;
                }

                // Check profile checkboxes
                if (docData.p5 && Array.isArray(docData.p5)) {
                    document.querySelectorAll('input[name="p5[]"]').forEach(chk => {
                        chk.checked = docData.p5.includes(chk.value);
                    });
                }

                // Check supporting checkboxes
                if (docData.supporting_components && Array.isArray(docData.supporting_components)) {
                    document.querySelectorAll('input[name="supporting[]"]').forEach(chk => {
                        chk.checked = docData.supporting_components.includes(chk.value);
                    });
                }

                // Show workspace and fill prompt editor
                emptyState.style.display = 'none';
                contentArea.style.display = 'flex';
                promptEditor.value = docData.generated_prompt;
                
                // Set download link
                downloadBtn.href = "{{ route('documents.download', $document->id) }}";
                
                saveStatus.textContent = 'Dimuat dari Riwayat';
                saveStatus.className = 'text-xs font-bold text-sky-blue bg-sky-blue/10 px-2.5 py-1 rounded-full border border-sky-blue/20';

                // Populate print view
                renderPrintHtml(docData);
            } catch (e) {
                console.error("Failed to load document from history", e);
            }
        @endif

        // Fill Demo Data
        fillDemoBtn.addEventListener('click', () => {
            const val = promptTypeSelect.value;
            if (val === 'modul_ajar') {
                form.subject.value = 'Matematika';
                form.topic.value = 'Penjumlahan';
                form.grade.value = 'Kelas 12';
                form.time_allocation.value = '2 x 45 Menit';
                learningModelSelect.value = 'Project-Based Learning (PjBL)';
                customLearningModelInput.classList.add('hidden');
                form.student_profile.value = 'Siswa dianggap memiliki pemahaman dasar sesuai fase sebelumnya.';
                form.cp.value = 'Peserta didik dapat menerapkan konsep operasi penjumlahan dalam memecahkan masalah kehidupan sehari-hari.';
                form.tp.value = 'Siswa dapat menjelaskan konsep penjumlahan dan mendesain proyek perhitungan secara kolaboratif.';
                form.additional_notes.value = 'Fase E (Kelas 10) atau Kelas 12 SMA/MA.';
                
                // P5 checkboxes
                document.querySelectorAll('input[name="p5[]"]').forEach(chk => {
                    chk.checked = ['Beriman, bertakwa kepada Tuhan YME, dan berakhlak mulia', 'Berkebinekaan global', 'Gotong royong', 'Mandiri', 'Bernalar kritis', 'Kreatif'].includes(chk.value);
                });
            } else if (val === 'rpl_bk') {
                form.topic.value = 'Strategi Menghadapi Ujian Sekolah';
                form.grade.value = 'Kelas IX SMP';
                form.time_allocation.value = '1 JP (45 Menit)';
                learningModelSelect.value = 'Cinema Education';
                customLearningModelInput.classList.add('hidden');
                form.student_profile.value = 'Merasa cemas, butuh motivasi';
                form.tp.value = 'Membantu siswa meredakan kecemasan menghadapi ujian dan menyusun jadwal belajar mandiri secara efektif.';
                form.additional_notes.value = 'Masukkan teknik relaksasi pernapasan sederhana.';
            } else if (val === 'soal_ujian') {
                form.subject.value = 'Matematika';
                form.topic.value = 'Pecahan dan Operasi Hitungnya';
                form.grade.value = 'Kelas V SD';
                form.time_allocation.value = '60 Menit';
                learningModelSelect.value = 'Contextual Teaching and Learning (CTL)';
                customLearningModelInput.classList.add('hidden');
                form.student_profile.value = 'Butuh contoh kehidupan sehari-hari (jual beli/berbagi)';
                form.tp.value = 'Siswa dapat menghitung penjumlahan dan pengurangan pecahan campuran berpenyebut beda.';
                form.additional_notes.value = 'Buat soal cerita HOTS dengan ilustrasi cerita keluarga.';
            } else if (val === 'proyek_p5') {
                form.topic.value = 'Apotek Hidup di Halaman Sekolah';
                form.grade.value = 'Kelas VII SMP';
                form.time_allocation.value = '3-4 Minggu';
                learningModelSelect.value = 'Project-Based Learning (PjBL)';
                customLearningModelInput.classList.add('hidden');
                form.student_profile.value = 'Suka aktivitas outdoor, memiliki rasa ingin tahu tinggi';
                form.additional_notes.value = 'Hubungkan dengan dimensi Gotong Royong dan Kreatif.';
                
                document.querySelectorAll('input[name="p5[]"]').forEach(chk => {
                    chk.checked = ['Gotong royong', 'Kreatif', 'Mandiri'].includes(chk.value);
                });
            }
        });

        // Prompt templates builder in JS (Aligned with official Merdeka style)
        const promptTemplates = {
            'modul_ajar': (data) => `Peran: Anda adalah seorang ahli perancang kurikulum dan guru berpengalaman yang memahami Kurikulum Merdeka di Indonesia secara mendalam.

Tugas: Buatkan saya draf modul ajar yang Lengkap berdasarkan informasi detail di bawah ini. Pastikan semua komponen yang diminta saling terkait, logis, dan kontekstual.
---

**1. Informasi Umum Modul**
* **Nama Guru**: ${data.teacher_name || '-'}
* **NIP Guru**: ${data.teacher_nip || '-'}
* **Nama Sekolah**: ${data.school_name || '-'}
* **Nama Kepala Sekolah**: ${data.principal_name || '-'}
* **NIP Kepala Sekolah**: ${data.principal_nip || '-'}
* **Jenjang**: SMA/MA (atau sesuaikan)
* **Kelas**: ${data.grade}
* **Mata Pelajaran**: ${data.subject}
* **Topik Utama**: ${data.topic}
* **Alokasi Waktu**: ${data.time_allocation}
* **Kompetensi Awal Siswa**: ${data.student_profile}
* **Profil Pelajar Pancasila yang Dituju**: ${data.p5_list}
* **Model Pembelajaran**: Tatap Muka dengan pendekatan ${data.learning_model}

**2. Komponen Inti yang Diharapkan**
* **Capaian Pembelajaran (CP)**: ${data.cp || 'AI tentukan secara otomatis'}
* **Tujuan Pembelajaran Utama**: ${data.tp || 'AI tentukan secara otomatis'}
* **Kata Kunci Materi**: Belum ditentukan

**3. Rincian Komponen yang Harus Dibuat**
Tolong buatkan secara rinci komponen-komponen berikut:
* **Kegiatan Pembelajaran**: Buat langkah-langkah detail untuk Pendahuluan, Inti, dan Penutup. Pastikan kegiatan inti mengikuti sintaks ${data.learning_model} secara terperinci.
${data.supporting_list.map(c => {
    if (c === 'Rangkuman Materi') return '* **Pemahaman Bermakna**';
    if (c === 'LKPD') return '* **Lembar Kerja Peserta Didik (LKPD)**';
    if (c === 'Asesmen Formatif') return '* **Asesmen Formatif & Pertanyaan Pemantik**';
    if (c === 'Asesmen Sumatif') return '* **Asesmen Sumatif & Asesmen Diagnostik**';
    if (c === 'Peta Konsep') return '* **Bahan Bacaan Guru & Siswa serta Glosarium**';
    return `* **${c}**`;
}).join('\n')}

**Tabel Tanda Tangan Pembelajaran:**
Wajib buatkan tabel tanda tangan formal di bagian paling bawah modul antara Kepala Sekolah **${data.principal_name || 'Kepala Sekolah'}** (NIP: ${data.principal_nip || '-'}) dan Guru Mata Pelajaran **${data.teacher_name || 'Guru'}** (NIP: ${data.teacher_nip || '-'}).

**Instruksi Tambahan:**
${data.additional_notes ? `Catatan Tambahan untuk AI:\n- ${data.additional_notes}\n` : ''}Gunakan bahasa yang operasional, jelas, dan mudah diikuti oleh guru lain. Format jawaban dalam Markdown agar rapi dan terstruktur dengan baik (gunakan heading, bold, dan bullet points). JANGAN sertakan penjelasan pembuka atau penutup basa-basi.`,

            'rpl_bk': (data) => `Peran: Anda adalah seorang ahli Guru Bimbingan Konseling (BK) berpengalaman yang memahami struktur RPL BK Kurikulum Merdeka secara mendalam.

Tugas: Buatkan saya draf Rencana Pelaksanaan Layanan Bimbingan dan Konseling (RPL BK) yang lengkap berdasarkan informasi detail di bawah ini. Pastikan semua kegiatan saling terkait, logis, dan solutif bagi konseli.
---

**1. Informasi Umum RPL BK**
* **Nama Konselor/Guru BK**: ${data.teacher_name || '-'}
* **NIP Guru BK**: ${data.teacher_nip || '-'}
* **Nama Sekolah**: ${data.school_name || '-'}
* **Nama Kepala Sekolah**: ${data.principal_name || '-'}
* **NIP Kepala Sekolah**: ${data.principal_nip || '-'}
* **Sasaran Layanan**: ${data.grade}
* **Bidang Layanan**: ${data.bk_field}
* **Topik/Tema Layanan**: ${data.topic}
* **Alokasi Waktu**: ${data.time_allocation}
* **Model/Metode Layanan**: ${data.learning_model}
* **Karakteristik/Kebutuhan Siswa**: ${data.student_profile}

**2. Komponen Inti RPL BK**
* **Tujuan Layanan Utama**: ${data.tp || 'AI rumuskan secara otomatis (tujuan umum dan khusus).'}

**3. Rincian Komponen yang Harus Dibuat**
Tolong buatkan secara rinci komponen-komponen berikut:
* **Langkah-langkah Layanan**: Rincian kegiatan dari Tahap Awal/Pendahuluan, Tahap Inti (aktivitas model ${data.learning_model}), dan Tahap Penutup.
${data.supporting_list.map(c => `* **${c}**`).join('\n')}

**Tabel Tanda Tangan Layanan BK:**
Wajib buatkan tabel tanda tangan formal di bagian paling bawah RPL antara Kepala Sekolah **${data.principal_name || 'Kepala Sekolah'}** (NIP: ${data.principal_nip || '-'}) dan Guru Bimbingan Konseling **${data.teacher_name || 'Guru BK'}** (NIP: ${data.teacher_nip || '-'}).

**Instruksi Tambahan:**
${data.additional_notes ? `Catatan Tambahan untuk AI:\n- ${data.additional_notes}\n` : ''}Gunakan bahasa bimbingan konseling yang empatik, operasional, jelas, dan mudah diikuti. Format jawaban dalam Markdown agar rapi dan terstruktur. JANGAN sertakan penjelasan pembuka atau penutup basa-basi.`,

            'soal_ujian': (data) => `Peran: Anda adalah seorang ahli penilaian pendidikan (asesmen) dan pembuat instrumen evaluasi pembelajaran di Indonesia.

Tugas: Buatkan saya draf Bank Soal dan Kisi-kisi Asesmen berdasarkan informasi detail di bawah ini.
---

**1. Informasi Umum Asesmen**
* **Mata Pelajaran**: ${data.subject}
* **Materi/Topik Utama**: ${data.topic}
* **Tingkat/Kelas**: ${data.grade}
* **Alokasi Waktu**: ${data.time_allocation}
* **Model/Metode Pembelajaran Terkait**: ${data.learning_model}
* **Tingkat Kognitif Sasaran**: ${data.cognitive_levels}
* **Bentuk Soal yang Diinginkan**: ${data.question_types}
* **Karakter & Kebutuhan Siswa**: ${data.student_profile}

**2. Rincian Komponen yang Harus Dibuat**
Tolong buatkan secara rinci komponen-komponen berikut:
* **Kumpulan Soal**: Buat minimal 10 butir soal sesuai tipe dan tingkat kognitif yang diminta.
${data.supporting_list.map(c => `* **${c}**`).join('\n')}

**Instruksi Tambahan:**
${data.additional_notes ? `Catatan Tambahan untuk AI:\n- ${data.additional_notes}\n` : ''}Gunakan bahasa yang baku, operasional, serta berikan kisi-kisi soal dan kunci jawaban yang detail. Format jawaban dalam Markdown. JANGAN sertakan penjelasan pembuka atau penutup basa-basi.`,

            'proyek_p5': (data) => `Peran: Anda adalah seorang koordinator Proyek Penguatan Profil Pelajar Pancasila (P5) yang kreatif dan berpengalaman dalam Kurikulum Merdeka.

Tugas: Buatkan saya draf Modul Proyek P5 yang lengkap berdasarkan informasi detail di bawah ini.
---

**1. Informasi Proyek P5**
* **Tema Proyek P5**: ${data.theme}
* **Fokus / Rencana Proyek**: ${data.topic}
* **Sasaran / Kelas**: ${data.grade}
* **Alokasi Waktu**: ${data.time_allocation}
* **Model/Pendekatan Proyek**: ${data.learning_model}
* **Dimensi P5 yang Disasar**: ${data.p5_list}
* **Karakter & Kebutuhan Siswa**: ${data.student_profile}

**2. Rincian Komponen yang Harus Dibuat**
Tolong buatkan secara rinci komponen-komponen berikut:
* **Tahapan Alur Proyek**: Rincian langkah untuk 5 tahap alur proyek (Pengenalan, Kontekstualisasi, Aksi, Refleksi, Tindak Lanjut).
${data.supporting_list.map(c => `* **${c}**`).join('\n')}

**Instruksi Tambahan:**
${data.additional_notes ? `Catatan Tambahan untuk AI:\n- ${data.additional_notes}\n` : ''}Gunakan bahasa yang inspiratif, mudah dipahami, dan aplikatif bagi fasilitator sekolah. Format jawaban dalam Markdown. JANGAN sertakan penjelasan pembuka atau penutup basa-basi.`
        };

        // Form Submit to Rakit Prompt
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            submitBtn.disabled = true;
            submitBtn.innerText = 'Merakit Prompt...';

            const type = form.prompt_type.value;
            const subject = form.subject.value || '-';
            const topic = form.topic.value;
            const grade = form.grade.value;
            const time_allocation = form.time_allocation.value;
            
            const teacher_name = form.teacher_name.value;
            const teacher_nip = form.teacher_nip.value;
            const school_name = form.school_name.value;
            const principal_name = form.principal_name.value;
            const principal_nip = form.principal_nip.value;

            // Gather Learning model (dropdown + custom input fallback)
            let learning_model = learningModelSelect.value;
            if (learning_model === 'Lainnya') {
                learning_model = customLearningModelInput.value || 'Problem-Based Learning';
            }

            const student_profile = form.student_profile.value;
            const additional_notes = form.additional_notes.value;
            const cp = form.cp.value;
            const tp = form.tp.value;
            
            let theme = '';
            if (type === 'proyek_p5') theme = form.theme.value;
            
            let bk_field = '';
            if (type === 'rpl_bk') bk_field = form.bk_field.value;

            // Gather P5 list
            const p5Array = [];
            document.querySelectorAll('input[name="p5[]"]:checked').forEach(c => {
                p5Array.push(c.value);
            });
            const p5_list = p5Array.length > 0 ? p5Array.join(', ') : 'Mandiri, Bernalar Kritis, Gotong Royong';

            // Gather Cognitive levels
            const cogArray = [];
            document.querySelectorAll('input[name="cognitive[]"]:checked').forEach(c => {
                cogArray.push(c.value);
            });
            const cognitive_levels = cogArray.length > 0 ? cogArray.join(', ') : 'HOTS (High Order Thinking Skills)';

            // Gather Question Types
            const qtypesArray = [];
            document.querySelectorAll('input[name="qtypes[]"]:checked').forEach(c => {
                qtypesArray.push(c.value);
            });
            const question_types = qtypesArray.length > 0 ? qtypesArray.join(', ') : 'Pilihan Ganda dan Uraian';

            // Gather Supporting components
            const supportingArray = [];
            document.querySelectorAll('input[name="supporting[]"]:checked').forEach(c => {
                supportingArray.push(c.value);
            });

            // Instantly compile engineered prompt template on client side
            const dataObj = { 
                subject, topic, grade, time_allocation, learning_model, student_profile, additional_notes,
                cp, tp, theme, bk_field, p5_list, cognitive_levels, question_types, 
                supporting_list: supportingArray,
                teacher_name, teacher_nip, school_name, principal_name, principal_nip
            };
            const generated = promptTemplates[type] ? promptTemplates[type](dataObj) : '';

            // Show workspace area
            emptyState.style.display = 'none';
            contentArea.style.display = 'flex';
            promptEditor.value = generated;

            // Setup print view labels
            const mappedTypeLabel = {
                'modul_ajar': 'Modul Ajar Kurikulum Merdeka',
                'rpl_bk': 'Rencana Layanan BK (RPL BK)',
                'soal_ujian': 'Bank Soal & Asesmen',
                'proyek_p5': 'Modul Proyek P5'
            }[type] || 'Modul Pendidikan';

            renderPrintHtml({
                prompt_type: mappedTypeLabel,
                subject: type === 'rpl_bk' || type === 'proyek_p5' ? '-' : subject,
                topic: topic,
                grade: grade,
                time_allocation: time_allocation,
                learning_model: learning_model,
                student_profile: student_profile,
                additional_notes: additional_notes || '',
                cp: cp,
                tp: tp,
                p5: p5Array,
                theme: theme,
                bk_field: bk_field,
                supporting_components: supportingArray,
                generated_prompt: generated,
                teacher_name, teacher_nip, school_name, principal_name, principal_nip
            });

            // Save to database in the background
            try {
                const response = await fetch("{{ route('tools.save-prompt-history') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        prompt_type: mappedTypeLabel,
                        subject: type === 'rpl_bk' || type === 'proyek_p5' ? '-' : subject,
                        topic: topic,
                        grade: grade,
                        time_allocation: time_allocation,
                        learning_model: learning_model,
                        student_profile: student_profile,
                        additional_notes: additional_notes || '',
                        cp: cp,
                        tp: tp,
                        p5: p5Array,
                        theme: theme,
                        bk_field: bk_field,
                        supporting_components: supportingArray,
                        generated_prompt: generated,
                        teacher_name, teacher_nip, school_name, principal_name, principal_nip
                    })
                });

                if (response.ok) {
                    const res = await response.json();
                    if (res.status === 'success') {
                        downloadBtn.href = `/documents/${res.document_id}/download`;
                        saveStatus.textContent = 'Tersimpan di Riwayat';
                        saveStatus.className = 'text-xs font-bold text-duo-green bg-duo-green/10 px-2.5 py-1 rounded-full border border-duo-green/20';
                    }
                }
            } catch (err) {
                console.error("Database auto-save error: ", err);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Rakit Prompt Super';
            }
        });

        // Copy button trigger
        const copyBtn = document.getElementById('copy-btn');
        copyBtn.addEventListener('click', () => {
            copyToClipboard(promptEditor.value);
            showToast('Prompt berhasil disalin ke clipboard! Silakan paste di Gemini, ChatGPT, atau DeepSeek.');
        });

        // Helper to copy text to clipboard
        function copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text);
            } else {
                const textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                textArea.style.left = "-999999px";
                textArea.style.top = "-999999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                } catch (err) {
                    console.error('Fallback copy failed', err);
                }
                document.body.removeChild(textArea);
            }
        }

        // Show micro-toast notification
        function showToast(message) {
            copiedToastMsg.textContent = message;
            copiedToast.classList.remove('hidden');
            setTimeout(() => {
                copiedToast.classList.add('hidden');
            }, 5000);
        }

        // Copy & Launch LLM in new tab
        function launchLLM(url) {
            const textToCopy = promptEditor.value;
            copyToClipboard(textToCopy);
            
            const domainName = url.replace('https://', '').replace('.google.com', '').replace('.com', '').replace('chat.', '');
            const capitalizedName = domainName.charAt(0).toUpperCase() + domainName.slice(1);
            
            showToast(`Prompt disalin! Mengalihkan ke ${capitalizedName} di tab baru...`);
            
            setTimeout(() => {
                window.open(url, '_blank');
            }, 600);
        }

        // Render printable view helper
        function renderPrintHtml(data) {
            const themeRow = data.theme ? `<tr><td><strong>Tema Proyek P5</strong></td><td>${data.theme}</td></tr>` : '';
            const bkRow = data.bk_field ? `<tr><td><strong>Bidang Layanan BK</strong></td><td>${data.bk_field}</td></tr>` : '';
            const subjectRow = data.subject && data.subject !== '-' ? `<tr><td><strong>Mata Pelajaran</strong></td><td>${data.subject}</td></tr>` : '';
            const timeRow = data.time_allocation ? `<tr><td><strong>Alokasi Waktu</strong></td><td>${data.time_allocation}</td></tr>` : '';
            const cpRow = data.cp ? `<tr><td><strong>Capaian Pembelajaran (CP)</strong></td><td>${data.cp.replace(/\n/g, '<br>')}</td></tr>` : '';
            const tpRow = data.tp ? `<tr><td><strong>Tujuan Pembelajaran (TP)</strong></td><td>${data.tp.replace(/\n/g, '<br>')}</td></tr>` : '';
            const p5Row = data.p5 && data.p5.length > 0 ? `<tr><td><strong>Profil Pelajar Pancasila</strong></td><td>${data.p5.join(', ')}</td></tr>` : '';
            const supportingRow = data.supporting_components && data.supporting_components.length > 0 ? `<tr><td><strong>Komponen Pendukung</strong></td><td>${data.supporting_components.join(', ')}</td></tr>` : '';
            
            const schoolRow = data.school_name ? `<tr><td><strong>Nama Sekolah / Instansi</strong></td><td>${data.school_name}</td></tr>` : '';
            const teacherRow = data.teacher_name ? `<tr><td><strong>Nama Guru</strong></td><td>${data.teacher_name} (NIP: ${data.teacher_nip || '-'})</td></tr>` : '';
            const principalRow = data.principal_name ? `<tr><td><strong>Nama Kepala Sekolah</strong></td><td>${data.principal_name} (NIP: ${data.principal_nip || '-'})</td></tr>` : '';

            printableArea.innerHTML = `
                <div style="text-align:center; margin-bottom: 20px;">
                    <h1 style="font-size: 16pt; font-weight: bold; text-transform: uppercase; margin-bottom:5px;">PROMPT GURU AI (SUPER PROMPT BUILDER)</h1>
                    <p style="font-size: 10pt; font-style: italic; color: #555555; margin-bottom: 25px;">Hasil Rancangan Instan PandAI untuk LLM</p>
                </div>
                
                <table border="1" cellspacing="0" cellpadding="6" style="width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 10.5pt; border-color: #dddddd;">
                    <tr style="background-color: #f9f9f9;"><td width="30%"><strong>Jenis Produk / Output</strong></td><td>${data.prompt_type}</td></tr>
                    ${schoolRow}
                    ${teacherRow}
                    ${principalRow}
                    ${themeRow}
                    ${bkRow}
                    ${subjectRow}
                    <tr><td><strong>Materi / Topik</strong></td><td>${data.topic}</td></tr>
                    <tr><td><strong>Kelas / Jenjang</strong></td><td>${data.grade}</td></tr>
                    ${timeRow}
                    <tr><td><strong>Model Pembelajaran</strong></td><td>${data.learning_model}</td></tr>
                    <tr><td><strong>Karakteristik Siswa</strong></td><td>${data.student_profile}</td></tr>
                    ${cpRow}
                    ${tpRow}
                    ${p5Row}
                    ${supportingRow}
                    ${data.additional_notes ? `<tr><td><strong>Catatan Tambahan</strong></td><td>${data.additional_notes}</td></tr>` : ''}
                </table>

                <h3 style="font-size: 12pt; font-weight: bold; border-bottom: 1px solid #4B0082; padding-bottom: 5px; color: #4B0082;">Salinan Prompt Utama</h3>
                <div style="background-color: #f5f5f5; border: 1px solid #e0e0e0; padding: 15px; border-radius: 5px; margin-top: 10px; font-family: monospace; font-size: 10pt; white-space: pre-wrap; line-height: 1.4; color: #333333;">${data.generated_prompt}</div>
            `;
        }
    </script>
@endsection
