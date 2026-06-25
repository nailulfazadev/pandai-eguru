@extends('layouts.app')

@section('title', 'Modul Ajar AI - PandAI')

@section('content')
    <!-- Header Page -->
    <section class="select-none">
        <h2 class="text-heading font-feather text-almost-black">Modul Ajar / Rencana Pembelajaran AI (RPM)</h2>
        <p class="text-body text-graphite">Susun rencana pelaksanaan pembelajaran (RPP) / modul ajar kurikulum merdeka secara otomatis dan komprehensif.</p>
    </section>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Options Form -->
        <div class="col-span-1 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm space-y-6">
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-3 select-none">
                <h3 class="text-heading-sm font-feather text-almost-black">Detail Modul</h3>
                <button type="button" id="fill-demo-btn" class="text-xs font-bold text-sky-blue hover:underline">Isi Contoh</button>
            </div>
            
            <form id="generate-form" class="space-y-4">
                @csrf

                <!-- Identitas Guru (Pre-filled, editable) -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Nama Guru</label>
                    <input type="text" name="teacher_name" value="{{ Auth::user()->name }}" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">NIP Guru</label>
                    <input type="text" name="teacher_nip" value="{{ Auth::user()->nip }}" placeholder="Masukkan NIP (jika ada)"
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Nama Instansi / Sekolah</label>
                    <input type="text" name="school_name" value="{{ Auth::user()->school_name }}" placeholder="Contoh: SD Negeri 1 Merdeka" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- Tanda Tangan Kepala Sekolah -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Nama Kepala Sekolah</label>
                    <input type="text" name="principal_name" value="{{ Auth::user()->principal_name }}" placeholder="Nama untuk ttd kepala sekolah" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">NIP Kepala Sekolah</label>
                    <input type="text" name="principal_nip" value="{{ Auth::user()->principal_nip }}" placeholder="NIP Kepala Sekolah (jika ada)"
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <hr class="border-cloud-gray my-4">

                <!-- Fase / Kelas -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Fase / Kelas</label>
                    <select name="grade" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                        <option>Fase A (Kelas 1-2)</option>
                        <option>Fase B (Kelas 3-4)</option>
                        <option selected>Fase C (Kelas 5-6)</option>
                        <option>Fase D (Kelas 7-9)</option>
                        <option>Fase E (Kelas 10)</option>
                        <option>Fase F (Kelas 11-12)</option>
                    </select>
                </div>

                <!-- Mapel -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Mata Pelajaran</label>
                    <input type="text" name="subject" placeholder="Contoh: IPA, Matematika" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- Topik Utama -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Topik / Materi Pembelajaran</label>
                    <input type="text" name="topic" placeholder="Contoh: Fotosintesis, Siklus Air" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- Metode Pembelajaran -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Model Pembelajaran</label>
                    <select name="method" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                        <option selected>Project-Based Learning (PjBL)</option>
                        <option>Problem-Based Learning (PBL)</option>
                        <option>Discovery Learning</option>
                        <option>Inquiry Learning</option>
                        <option>Ceramah Interaktif & Diskusi</option>
                    </select>
                </div>

                <!-- Alokasi Waktu -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Alokasi Waktu</label>
                    <input type="text" name="duration" placeholder="Contoh: 2 x 35 Menit" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- Profil Pelajar Pancasila (P5) -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Profil Pelajar Pancasila (P5)</label>
                    <p class="text-xs text-graphite mb-1 select-none">Pilih dimensi yang paling relevan (disarankan 2-3):</p>
                    <div class="grid grid-cols-1 gap-2 border-2 border-cloud-gray rounded-xl p-3 bg-[#f9f9f9]">
                        <label class="flex items-center space-x-2.5 text-xs text-charcoal cursor-pointer select-none">
                            <input type="checkbox" name="p5[]" value="Beriman, Bertakwa kepada Tuhan YME, dan Berakhlak Mulia" class="w-4 h-4 text-duo-green border-cloud-gray rounded focus:ring-duo-green">
                            <span>Beriman, Bertakwa & Berakhlak Mulia</span>
                        </label>
                        <label class="flex items-center space-x-2.5 text-xs text-charcoal cursor-pointer select-none">
                            <input type="checkbox" name="p5[]" value="Berkebinekaan Global" class="w-4 h-4 text-duo-green border-cloud-gray rounded focus:ring-duo-green">
                            <span>Berkebinekaan Global</span>
                        </label>
                        <label class="flex items-center space-x-2.5 text-xs text-charcoal cursor-pointer select-none">
                            <input type="checkbox" name="p5[]" value="Gotong Royong" checked class="w-4 h-4 text-duo-green border-cloud-gray rounded focus:ring-duo-green">
                            <span>Gotong Royong</span>
                        </label>
                        <label class="flex items-center space-x-2.5 text-xs text-charcoal cursor-pointer select-none">
                            <input type="checkbox" name="p5[]" value="Mandiri" checked class="w-4 h-4 text-duo-green border-cloud-gray rounded focus:ring-duo-green">
                            <span>Mandiri</span>
                        </label>
                        <label class="flex items-center space-x-2.5 text-xs text-charcoal cursor-pointer select-none">
                            <input type="checkbox" name="p5[]" value="Bernalar Kritis" checked class="w-4 h-4 text-duo-green border-cloud-gray rounded focus:ring-duo-green">
                            <span>Bernalar Kritis</span>
                        </label>
                        <label class="flex items-center space-x-2.5 text-xs text-charcoal cursor-pointer select-none">
                            <input type="checkbox" name="p5[]" value="Kreatif" class="w-4 h-4 text-duo-green border-cloud-gray rounded focus:ring-duo-green">
                            <span>Kreatif</span>
                        </label>
                    </div>
                </div>

                <!-- CP & TP -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center select-none">
                        <label class="block text-sm font-bold text-charcoal">Capaian & Tujuan Pembelajaran</label>
                        <button type="button" id="recommend-cptp-btn" class="text-xs font-bold text-sky-blue hover:underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            Dapatkan Ide CP/TP (AI)
                        </button>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-graphite select-none">Capaian Pembelajaran (CP)</label>
                        <textarea name="cp" id="cp_input" rows="3" placeholder="Opsional: masukkan CP spesifik atau klik Dapatkan Ide AI" 
                                  class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] resize-none transition"></textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-graphite select-none">Tujuan Pembelajaran (TP)</label>
                        <textarea name="tp" id="tp_input" rows="3" placeholder="Opsional: masukkan TP spesifik atau klik Dapatkan Ide AI" 
                                  class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] resize-none transition"></textarea>
                    </div>
                </div>

                <!-- Mode Demo Toggle -->
                <div class="hidden flex items-center space-x-2 py-1 select-none">
                    <input type="checkbox" name="use_mock" id="use_mock" value="1"
                           class="w-4 h-4 text-duo-green border-cloud-gray rounded focus:ring-duo-green cursor-pointer">
                    <label for="use_mock" class="text-xs font-bold text-charcoal cursor-pointer">Mode Demo (Hemat Kuota AI)</label>
                </div>

                <!-- Submit -->
                <button type="submit" id="submit-btn" class="btn-3d-primary w-full text-sm py-3 tracking-wider">
                    Buat Modul Ajar
                </button>
            </form>
        </div>

        <!-- Right: Preview Area -->
        <div class="lg:col-span-2 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm min-h-[600px] flex flex-col justify-between">
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-4 mb-4 select-none">
                <h3 class="text-heading-sm font-feather text-almost-black">Hasil Pratinjau</h3>
                <span id="save-status" class="text-xs font-bold text-silver bg-[#f9f9f9] px-2.5 py-1 rounded-full border border-cloud-gray">Belum Disimpan</span>
            </div>

            <!-- Preview Content Pane -->
            <div id="preview-box" class="flex-1 overflow-y-auto max-h-[700px] p-4 text-sm text-graphite leading-relaxed">
                <!-- Initial Empty State -->
                <div id="empty-state" class="h-full flex flex-col items-center justify-center text-center p-8 space-y-4">
                    <div class="w-20 h-20 rounded-2xl bg-duo-green/10 flex items-center justify-center text-duo-green">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div class="max-w-md space-y-2">
                        <h4 class="font-bold text-charcoal text-lg">Siap Menyusun RPP / Modul Ajar?</h4>
                        <p class="text-graphite text-sm">Lengkapi rincian kurikulum di panel kiri, kemudian klik tombol generate. Hasil modul ajar yang terstruktur dan lengkap akan ditampilkan di panel ini.</p>
                    </div>
                </div>


                <!-- Real Output Pane -->
                <div id="output-pane" class="hidden space-y-4 markdown-body"></div>
            </div>

            <!-- Action Buttons (Disabled until generation) -->
            <div id="actions-pane" class="border-t-2 border-cloud-gray pt-4 flex justify-end space-x-3 select-none">
                <a id="download-word" href="#" download class="btn-outline text-xs px-4 py-2 border-cloud-gray text-silver pointer-events-none">Unduh Word (.doc)</a>
                <a id="print-pdf" href="#" target="_blank" class="btn-outline text-xs px-4 py-2 border-cloud-gray text-silver pointer-events-none">Cetak PDF</a>
            </div>
        </div>

    </div>

    <!-- spacer -->
    <div class="h-12"></div>

    <!-- Loading Modal Overlay -->
    <div id="loading-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-almost-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-8 max-w-sm w-full shadow-2xl flex flex-col items-center text-center space-y-4">
            <div class="w-16 h-16 border-4 border-duo-green border-t-transparent rounded-full animate-spin"></div>
            <div class="space-y-2">
                <h4 class="font-bold text-charcoal text-lg">Merancang Modul Ajar...</h4>
                <p class="text-graphite text-sm animate-pulse">Menghubungi Asisten AI Gemini untuk memformulasikan materi terstruktur.</p>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Styling for generated content inside Preview Box mimicking standard A4 Document paper */
    .markdown-body {
        font-family: 'Times New Roman', Times, serif;
        color: #000000;
        line-height: 1.5;
        background-color: #ffffff;
        padding: 2.5rem;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        max-width: 100%;
        margin: 0.5rem auto;
    }
    .markdown-body h1 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 16pt;
        font-weight: bold;
        margin-top: 18pt;
        margin-bottom: 18pt;
        color: #000000;
        text-align: center;
        text-transform: uppercase;
    }
    .markdown-body h2 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 13pt;
        font-weight: bold;
        margin-top: 20pt;
        margin-bottom: 10pt;
        color: #000000;
        border-bottom: 1px solid #000000;
        padding-bottom: 3px;
    }
    .markdown-body h3 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt;
        font-weight: bold;
        margin-top: 14pt;
        margin-bottom: 8pt;
        color: #000000;
    }
    .markdown-body h4 {
        font-family: 'Times New Roman', Times, serif;
        font-weight: bold;
        font-size: 11pt;
        margin-top: 12pt;
        margin-bottom: 6pt;
        color: #000000;
    }
    .markdown-body p {
        margin-top: 0in;
        margin-bottom: 8pt;
        text-align: justify;
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt;
        line-height: 1.5;
        color: #000000;
    }
    .markdown-body ul, .markdown-body ol {
        margin-top: 0in;
        margin-bottom: 8pt;
        padding-left: 20pt;
    }
    .markdown-body li {
        margin-bottom: 4pt;
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt;
        line-height: 1.5;
        color: #000000;
        text-align: justify;
    }
    .markdown-body hr {
        border: 0;
        border-top: 1px solid #000000;
        margin: 2rem 0;
    }
    .markdown-body table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 12pt;
        margin-bottom: 12pt;
        background-color: #ffffff;
        border: 1px solid #000000;
        font-family: 'Times New Roman', Times, serif;
        font-size: 11pt;
    }
    .markdown-body th {
        background-color: #f2f2f2;
        font-weight: bold;
        color: #000000;
        border: 1px solid #000000;
        padding: 6pt 8pt;
        text-align: left;
        font-size: 11pt;
    }
    .markdown-body td {
        border: 1px solid #000000;
        padding: 6pt 8pt;
        color: #000000;
        vertical-align: top;
        font-size: 11pt;
    }
    .markdown-body blockquote {
        border-left: 3pt solid #333333;
        padding-left: 10pt;
        margin: 12pt 0 12pt 20pt;
        color: #333333;
        font-style: italic;
        font-size: 11pt;
    }
    .markdown-body table[border='0'],
    .markdown-body table[border='0'] td,
    .markdown-body table[border='0'] th,
    .markdown-body table.borderless-table,
    .markdown-body table.borderless-table td,
    .markdown-body table.borderless-table th {
        border: none !important;
    }

    /* Print styles specifically to isolate the preview pane and layout nicely */
    @media print {
        body * {
            visibility: hidden;
        }
        #preview-box, #preview-box * {
            visibility: visible;
        }
        #preview-box {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: auto;
            max-height: none;
            overflow: visible;
        }
        .markdown-body {
            box-shadow: none;
            border: none;
            padding: 0;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    const fillDemoBtn = document.getElementById('fill-demo-btn');
    const generateForm = document.getElementById('generate-form');
    const submitBtn = document.getElementById('submit-btn');
    const emptyState = document.getElementById('empty-state');
    const loadingModal = document.getElementById('loading-modal');
    const outputPane = document.getElementById('output-pane');
    const saveStatus = document.getElementById('save-status');
    const downloadWord = document.getElementById('download-word');
    const printPdf = document.getElementById('print-pdf');
    const recommendCptpBtn = document.getElementById('recommend-cptp-btn');
    const cpInput = document.getElementById('cp_input');
    const tpInput = document.getElementById('tp_input');

    recommendCptpBtn.addEventListener('click', async function() {
        const subjectVal = generateForm.elements['subject'].value;
        const topicVal = generateForm.elements['topic'].value;
        const gradeVal = generateForm.elements['grade'].value;
        const isDemo = document.getElementById('use_mock').checked ? 1 : 0;

        if (!topicVal || !gradeVal) {
            alert('Mohon isi kolom Topik/Materi dan Kelas terlebih dahulu.');
            return;
        }

        recommendCptpBtn.disabled = true;
        recommendCptpBtn.innerHTML = `
            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            Merumuskan...
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

    // Browser Database / localStorage Persistence
    function saveProfileSettingsToLocalStorage() {
        localStorage.setItem('sa_teacher_name', generateForm.elements['teacher_name'].value);
        localStorage.setItem('sa_teacher_nip', generateForm.elements['teacher_nip'].value);
        localStorage.setItem('sa_school_name', generateForm.elements['school_name'].value);
        localStorage.setItem('sa_principal_name', generateForm.elements['principal_name'].value);
        localStorage.setItem('sa_principal_nip', generateForm.elements['principal_nip'].value);
    }

    function loadProfileSettingsFromLocalStorage() {
        const teacher_name = localStorage.getItem('sa_teacher_name');
        const teacher_nip = localStorage.getItem('sa_teacher_nip');
        const school_name = localStorage.getItem('sa_school_name');
        const principal_name = localStorage.getItem('sa_principal_name');
        const principal_nip = localStorage.getItem('sa_principal_nip');

        if (teacher_name !== null) generateForm.elements['teacher_name'].value = teacher_name;
        if (teacher_nip !== null) generateForm.elements['teacher_nip'].value = teacher_nip;
        if (school_name !== null) generateForm.elements['school_name'].value = school_name;
        if (principal_name !== null) generateForm.elements['principal_name'].value = principal_name;
        if (principal_nip !== null) generateForm.elements['principal_nip'].value = principal_nip;
    }

    // Load initial settings
    loadProfileSettingsFromLocalStorage();

    // Listen to changes to save automatically
    const profileFields = ['teacher_name', 'teacher_nip', 'school_name', 'principal_name', 'principal_nip'];
    profileFields.forEach(fieldName => {
        const input = generateForm.elements[fieldName];
        if (input) {
            input.addEventListener('input', saveProfileSettingsToLocalStorage);
            input.addEventListener('change', saveProfileSettingsToLocalStorage);
        }
    });

    fillDemoBtn.addEventListener('click', function() {
        generateForm.elements['teacher_name'].value = "Budi Santoso, S.Pd.";
        generateForm.elements['teacher_nip'].value = "198503152011011002";
        generateForm.elements['school_name'].value = "SD Negeri 1 Merdeka";
        generateForm.elements['principal_name'].value = "Dr. H. Ahmad Yani, M.Pd.";
        generateForm.elements['principal_nip'].value = "197208241998031001";
        generateForm.elements['grade'].value = "Fase C (Kelas 5-6)";
        generateForm.elements['subject'].value = "IPAS (Sains)";
        generateForm.elements['topic'].value = "Siklus Air & Kelestarian Lingkungan";
        generateForm.elements['method'].value = "Project-Based Learning (PjBL)";
        generateForm.elements['duration'].value = "2 x 35 Menit";
        
        // Select P5 checkboxes for IPAS demo
        const p5Checkboxes = generateForm.querySelectorAll('input[name="p5[]"]');
        p5Checkboxes.forEach(cb => {
            if (cb.value === "Mandiri" || cb.value === "Bernalar Kritis" || cb.value === "Gotong Royong") {
                cb.checked = true;
            } else {
                cb.checked = false;
            }
        });

        generateForm.elements['cp'].value = "Peserta didik menganalisis hubungan antara siklus air dengan kondisi lingkungan bumi serta aktivitas manusia.";
        generateForm.elements['tp'].value = "1. Mengidentifikasi tahapan siklus air melalui pembuatan diorama.\n2. Menganalisis dampak penebangan hutan terhadap infiltrasi air.";
    });

    generateForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // UI States
        emptyState.classList.add('hidden');
        outputPane.classList.add('hidden');
        
        // Show Loading Modal
        loadingModal.classList.remove('hidden');
        loadingModal.classList.add('flex');
        
        submitBtn.disabled = true;
        submitBtn.innerText = 'Memproses...';
        
        // Disable buttons
        downloadWord.classList.add('pointer-events-none', 'text-silver');
        printPdf.classList.add('pointer-events-none', 'text-silver');

        try {
            const formData = new FormData(generateForm);
            const dataObj = {};
            formData.forEach((value, key) => dataObj[key] = value);

            const response = await fetch('/tools/generate-modul', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': dataObj['_token'],
                    'Accept': 'application/json'
                },
                body: JSON.stringify(dataObj)
            });

            const result = await response.json();

            if (response.ok) {
                // Render content using generated HTML from server
                outputPane.innerHTML = result.html_content;
                outputPane.classList.remove('hidden');
                
                // Update save status
                saveStatus.innerText = result.is_mock ? 'Selesai (Mode Demo)' : 'Tersimpan';
                saveStatus.classList.remove('text-silver');
                saveStatus.classList.add('text-duo-green', 'bg-duo-green-light/25', 'border-duo-green');

                // Enable Action Buttons
                downloadWord.href = `/documents/${result.id}/download`;
                downloadWord.classList.remove('pointer-events-none', 'text-silver');
                downloadWord.classList.add('text-sky-blue');
                
                printPdf.href = `/documents/${result.id}/print`;
                printPdf.classList.remove('pointer-events-none', 'text-silver');
                printPdf.classList.add('text-sky-blue');
            } else {
                alert(`Gagal membuat modul: ${result.error || 'Terjadi kesalahan sistem.'}`);
                emptyState.classList.remove('hidden');
            }
        } catch (error) {
            console.error(error);
            alert('Gagal menghubungi server. Periksa koneksi internet Anda.');
            emptyState.classList.remove('hidden');
        } finally {
            // Hide Loading Modal
            loadingModal.classList.add('hidden');
            loadingModal.classList.remove('flex');
            
            submitBtn.disabled = false;
            submitBtn.innerText = 'Buat Modul Ajar';
        }
    });
</script>
@endsection
