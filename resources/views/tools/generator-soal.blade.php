@extends('layouts.app')

@section('title', 'Generator Soal - PandAI')

@section('content')
    <!-- Header Page -->
    <section class="select-none">
        <h2 class="text-heading font-feather text-almost-black">Generator Soal AI</h2>
        <p class="text-body text-graphite">Buat kumpulan soal latihan atau evaluasi secara otomatis lengkap dengan kunci jawaban.</p>
    </section>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Options Form -->
        <div class="col-span-1 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm space-y-5">
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-3 select-none">
                <h3 class="text-heading-sm font-feather text-almost-black">Pengaturan Soal</h3>
                <button type="button" id="fill-demo-btn" class="text-xs font-bold text-sky-blue hover:underline">Isi Contoh</button>
            </div>
            
            <form id="generate-form" class="space-y-4">
                @csrf

                <!-- Kustomisasi Kop / Header Soal (Collapsible) -->
                <details open class="group border-2 border-cloud-gray rounded-xl p-4 bg-[#f9f9f9] transition">
                    <summary class="font-bold text-xs text-charcoal cursor-pointer flex justify-between items-center select-none">
                        Kustomisasi Kop & Header Soal
                        <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="space-y-3 pt-3">
                        <!-- Desain Kop Surat Dropdown -->
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-graphite">Desain Kop Surat</label>
                            <select name="kop_template" id="kop_template" class="w-full border border-cloud-gray rounded-lg py-1.5 px-3 text-xs focus:outline-none focus:border-sky-blue bg-snow-white">
                                <option value="kemendikbud" selected>Kemendikbud (1 Logo Kiri)</option>
                                <option value="kemenag">Kementerian Agama (1 Logo Kiri)</option>
                                <option value="yayasan">Yayasan / Swasta (2 Logo: Kiri & Kanan)</option>
                                <option value="sederhana">Sederhana (Tanpa Logo)</option>
                            </select>
                        </div>

                        <!-- Logo Kiri Section -->
                        <div class="space-y-1" id="logo-kiri-wrapper">
                            <label class="block text-[10px] font-bold text-graphite">Logo Kiri (Utama)</label>
                            <div class="flex items-center space-x-3">
                                <!-- Thumbnail preview -->
                                <div class="w-12 h-12 border border-cloud-gray rounded bg-white flex items-center justify-center p-1 overflow-hidden select-none">
                                    <img id="logo-kiri-preview" src="https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Kementerian_Pendidikan_dan_Kebudayaan.png" class="max-w-full max-h-full object-contain" />
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex space-x-1.5">
                                        <button type="button" id="upload-logo-kiri-btn" class="px-2 py-0.5 text-[9px] font-bold bg-cloud-gray hover:bg-silver/40 text-charcoal rounded transition select-none">Unggah Logo</button>
                                        <button type="button" id="preset-logo-kiri-btn" class="px-2 py-0.5 text-[9px] font-bold bg-cloud-gray hover:bg-silver/40 text-charcoal rounded transition select-none">Reset Default</button>
                                    </div>
                                    <input type="hidden" name="kop_logo" id="kop_logo" value="https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Kementerian_Pendidikan_dan_Kebudayaan.png">
                                    <input type="file" id="logo-kiri-file" accept="image/*" class="hidden">
                                </div>
                            </div>
                        </div>

                        <!-- Logo Kanan Section (Yayasan only) -->
                        <div class="space-y-1 hidden" id="logo-kanan-wrapper">
                            <label class="block text-[10px] font-bold text-graphite">Logo Kanan (Sekolah)</label>
                            <div class="flex items-center space-x-3">
                                <!-- Thumbnail preview -->
                                <div class="w-12 h-12 border border-cloud-gray rounded bg-white flex items-center justify-center p-1 overflow-hidden select-none">
                                    <img id="logo-kanan-preview" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Has_No_Logo.svg/512px-Has_No_Logo.svg.png" class="max-w-full max-h-full object-contain" />
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex space-x-1.5">
                                        <button type="button" id="upload-logo-kanan-btn" class="px-2 py-0.5 text-[9px] font-bold bg-cloud-gray hover:bg-silver/40 text-charcoal rounded transition select-none">Unggah Logo</button>
                                        <button type="button" id="clear-logo-kanan-btn" class="px-2 py-0.5 text-[9px] font-bold bg-rose-50 border border-rose-200 text-rose-600 rounded hover:bg-rose-100 transition select-none">Hapus</button>
                                    </div>
                                    <input type="hidden" name="kop_logo_kanan" id="kop_logo_kanan" value="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Has_No_Logo.svg/512px-Has_No_Logo.svg.png">
                                    <input type="file" id="logo-kanan-file" accept="image/*" class="hidden">
                                </div>
                            </div>
                        </div>

                        <!-- Dinas -->
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-graphite">Nama Dinas / Yayasan</label>
                            <input type="text" name="kop_dinas" id="kop_dinas" value="PEMERINTAH KABUPATEN INDRAMAYU<br>DINAS PENDIDIKAN" required
                                   class="w-full border border-cloud-gray rounded-lg py-1.5 px-3 text-xs focus:outline-none focus:border-sky-blue bg-snow-white">
                        </div>
                        <!-- Sekolah -->
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-graphite">Nama Sekolah</label>
                            <input type="text" name="kop_sekolah" id="kop_sekolah" value="SD NEGERI 1 MERDEKA" required
                                   class="w-full border border-cloud-gray rounded-lg py-1.5 px-3 text-xs focus:outline-none focus:border-sky-blue bg-snow-white">
                        </div>
                        <!-- Alamat -->
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-graphite">Alamat Sekolah</label>
                            <input type="text" name="kop_alamat" id="kop_alamat" value="Jl. Ki Hajar Dewantara No. 17, Indramayu, Telp. (0234) 123456" required
                                   class="w-full border border-cloud-gray rounded-lg py-1.5 px-3 text-xs focus:outline-none focus:border-sky-blue bg-snow-white">
                        </div>
                        <!-- Nama Ujian -->
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-graphite">Nama Ujian</label>
                            <input type="text" name="kop_ujian" id="kop_ujian" value="PENILAIAN HARIAN (PH) BERSAMA" required
                                   class="w-full border border-cloud-gray rounded-lg py-1.5 px-3 text-xs focus:outline-none focus:border-sky-blue bg-snow-white">
                        </div>
                        <!-- TA -->
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-graphite">Tahun Ajaran</label>
                            <input type="text" name="kop_ta" id="kop_ta" value="2026/2027" required
                                   class="w-full border border-cloud-gray rounded-lg py-1.5 px-3 text-xs focus:outline-none focus:border-sky-blue bg-snow-white">
                        </div>
                        <!-- Waktu -->
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-graphite">Waktu Pengerjaan</label>
                            <input type="text" name="kop_waktu" id="kop_waktu" value="90 Menit" required
                                   class="w-full border border-cloud-gray rounded-lg py-1.5 px-3 text-xs focus:outline-none focus:border-sky-blue bg-snow-white">
                        </div>
                    </div>
                </details>

                <!-- Mapel -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Mata Pelajaran</label>
                    <input type="text" name="subject" placeholder="Contoh: IPA, Matematika" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- Topik -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Topik / Materi Utama</label>
                    <input type="text" name="topic" placeholder="Contoh: Fotosintesis, Persamaan Linear" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- Kelas -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Jenjang / Kelas</label>
                    <select name="grade" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                        <option>Kelas 1 SD</option>
                        <option>Kelas 2 SD</option>
                        <option>Kelas 3 SD</option>
                        <option>Kelas 4 SD</option>
                        <option selected>Kelas 5 SD</option>
                        <option>Kelas 6 SD</option>
                        <option>Kelas 7 SMP</option>
                        <option>Kelas 8 SMP</option>
                        <option>Kelas 9 SMP</option>
                        <option>Kelas 10 SMA</option>
                        <option>Kelas 11 SMA</option>
                        <option>Kelas 12 SMA</option>
                    </select>
                </div>

                <!-- Level Kesulitan -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Tingkat Kesulitan</label>
                    <select name="difficulty" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                        <option>LOTS (Analisis Rendah)</option>
                        <option>MOTS (Analisis Sedang)</option>
                        <option selected>HOTS (Berpikir Tingkat Tinggi)</option>
                        <option>Campuran (LOTS, MOTS, HOTS)</option>
                    </select>
                </div>

                <!-- Jenis / Tipe Soal -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Tipe Soal</label>
                    <select name="question_type" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                        <option selected>Pilihan Ganda Biasa</option>
                        <option>Pilihan Ganda Kompleks (Banyak Jawaban Benar)</option>
                        <option>Pilihan Ganda Dua Tingkat (Two-Tier MCQ)</option>
                        <option>Soal Berantai (Chain Problem MCQ)</option>
                        <option>Pilihan Ganda Matriks (Matrix MCQ)</option>
                        <option>Mencocokkan / Jodohkan</option>
                        <option>Essay / Uraian</option>
                        <option>Campuran (Pilihan Ganda & Essay)</option>
                    </select>
                </div>

                <!-- Jumlah Soal -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Jumlah Soal</label>
                    <select name="quantity" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                        <option>5 Soal</option>
                        <option selected>10 Soal</option>
                        <option>15 Soal</option>
                        <option>20 Soal</option>
                    </select>
                </div>

                <!-- Indikator / Kisi-kisi -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Indikator Pembelajaran / Kisi-Kisi</label>
                    <textarea name="indicator" rows="3" placeholder="Masukkan indikator atau batasan soal di sini (opsional)..." 
                              class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] resize-none transition"></textarea>
                </div>

                <!-- Mode Demo (Hemat Kuota AI) -->
                <div class="hidden flex items-center space-x-2 pt-2 select-none">
                    <input type="checkbox" id="use-mock" name="use_mock" value="1"
                           class="w-4 h-4 text-duo-green border-cloud-gray rounded focus:ring-duo-green">
                    <label for="use-mock" class="text-xs font-bold text-graphite cursor-pointer">
                        Mode Demo (Hemat Kuota AI)
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submit-btn" class="btn-3d-primary w-full text-sm py-3 tracking-wider">
                    Buat Soal Evaluasi
                </button>
            </form>
        </div>

        <!-- Right: Preview Area -->
        <div class="lg:col-span-2 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm min-h-[500px] flex flex-col justify-between">
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-4 mb-4 select-none">
                <h3 class="text-heading-sm font-feather text-almost-black">Hasil Pratinjau</h3>
                <span id="save-status" class="text-xs font-bold text-silver bg-[#f9f9f9] px-2.5 py-1 rounded-full border border-cloud-gray">Belum Disimpan</span>
            </div>

            <!-- Scrollable Output Container -->
            <div class="flex-1 overflow-y-auto max-h-[600px] pr-2">
                <!-- Empty State / Live Draft Preview Container -->
                <div id="empty-state" class="space-y-4 text-left">
                    <!-- Banner Info -->
                    <div class="bg-sky-blue/5 border border-sky-blue/20 rounded-xl p-4 flex items-start space-x-3 select-none">
                        <div class="text-sky-blue mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="space-y-0.5">
                            <h5 class="font-bold text-charcoal text-sm">Pratinjau Lembar Soal (Draft)</h5>
                            <p class="text-graphite text-xs">Di bawah ini adalah draf tampilan lembar soal Anda. Isi form di panel kiri untuk mengubah kop surat, logo, dan identitas secara real-time.</p>
                        </div>
                    </div>
                    
                    <!-- Real-time Live Kop & Student Table rendering -->
                    <div id="live-kop-container" class="markdown-body">
                        <!-- Filled by JS -->
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

    <!-- Loading Modal Overlay -->
    <div id="loading-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-almost-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-8 max-w-sm w-full shadow-2xl flex flex-col items-center text-center space-y-4">
            <div class="w-16 h-16 border-4 border-sky-blue border-t-transparent rounded-full animate-spin"></div>
            <div class="space-y-2">
                <h4 class="font-bold text-charcoal text-lg">Merumuskan Soal AI...</h4>
                <p class="text-graphite text-sm animate-pulse">Menghubungi Asisten AI Gemini untuk memformulasikan pertanyaan & kunci jawaban.</p>
            </div>
        </div>
    </div>

    <!-- Image Generation Modal -->
    <div id="image-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-almost-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 max-w-md w-full shadow-2xl relative">
            <button id="close-image-modal" class="absolute top-4 right-4 text-graphite hover:text-charcoal"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            <h3 class="text-lg font-bold text-charcoal mb-2">Tambahkan Ilustrasi Soal</h3>
            <p class="text-sm text-graphite mb-4">Masukkan kata kunci atau deskripsi gambar yang ingin disisipkan pada soal ini.</p>
            <form id="image-form" class="space-y-4">
                <input type="text" id="image-prompt" required placeholder="Contoh: Tata surya dengan planet-planet" class="w-full border-2 border-cloud-gray rounded-xl py-2 px-3 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                <button type="submit" id="generate-image-btn" class="btn-3d-primary w-full text-sm py-2">Generate Gambar</button>
            </form>
            <!-- Loader within modal -->
            <div id="image-loader" class="hidden mt-4 flex-col items-center justify-center space-y-2">
                <div class="w-8 h-8 border-4 border-sky-blue border-t-transparent rounded-full animate-spin"></div>
                <p class="text-xs text-graphite">Membuat ilustrasi (AI)...</p>
            </div>
        </div>
    </div>

    <!-- spacer -->
    <div class="h-12"></div>
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

    // Kop elements
    const kopTemplate = document.getElementById('kop_template');
    const kopLogoInput = document.getElementById('kop_logo');
    const logoKiriFile = document.getElementById('logo-kiri-file');
    const logoKiriPreview = document.getElementById('logo-kiri-preview');
    const uploadLogoKiriBtn = document.getElementById('upload-logo-kiri-btn');
    const presetLogoKiriBtn = document.getElementById('preset-logo-kiri-btn');
    const logoKiriWrapper = document.getElementById('logo-kiri-wrapper');

    const kopLogoKananInput = document.getElementById('kop_logo_kanan');
    const logoKananFile = document.getElementById('logo-kanan-file');
    const logoKananPreview = document.getElementById('logo-kanan-preview');
    const uploadLogoKananBtn = document.getElementById('upload-logo-kanan-btn');
    const clearLogoKananBtn = document.getElementById('clear-logo-kanan-btn');
    const logoKananWrapper = document.getElementById('logo-kanan-wrapper');

    // Preset Constants
    const DEFAULT_KEMENDIKBUD_LOGO = "https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Kementerian_Pendidikan_dan_Kebudayaan.png";
    const DEFAULT_KEMENAG_LOGO = "https://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Lencana_Kementerian_Agama.svg/512px-Lencana_Kementerian_Agama.svg.png";
    const DEFAULT_YAYASAN_LOGO = "https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Kementerian_Pendidikan_dan_Kebudayaan.png";
    const PLACEHOLDER_LOGO = "https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Has_No_Logo.svg/512px-Has_No_Logo.svg.png";

    // Browser Database / localStorage Persistence for Kop & Header
    function saveKopSettingsToLocalStorage() {
        localStorage.setItem('sa_kop_template', kopTemplate.value);
        localStorage.setItem('sa_kop_dinas', generateForm.elements['kop_dinas'].value);
        localStorage.setItem('sa_school_name', generateForm.elements['kop_sekolah'].value);
        localStorage.setItem('sa_kop_alamat', generateForm.elements['kop_alamat'].value);
        localStorage.setItem('sa_kop_ujian', generateForm.elements['kop_ujian'].value);
        localStorage.setItem('sa_kop_ta', generateForm.elements['kop_ta'].value);
        localStorage.setItem('sa_kop_waktu', generateForm.elements['kop_waktu'].value);
        localStorage.setItem('sa_kop_logo', kopLogoInput.value || '');
        localStorage.setItem('sa_kop_logo_kanan', kopLogoKananInput.value || '');
    }

    function loadKopSettingsFromLocalStorage() {
        const template = localStorage.getItem('sa_kop_template');
        const dinas = localStorage.getItem('sa_kop_dinas');
        const sekolah = localStorage.getItem('sa_school_name');
        const alamat = localStorage.getItem('sa_kop_alamat');
        const ujian = localStorage.getItem('sa_kop_ujian');
        const ta = localStorage.getItem('sa_kop_ta');
        const waktu = localStorage.getItem('sa_kop_waktu');
        const logoL = localStorage.getItem('sa_kop_logo');
        const logoR = localStorage.getItem('sa_kop_logo_kanan');

        if (template !== null) kopTemplate.value = template;
        
        // Show/hide wrappers based on template
        const val = kopTemplate.value;
        if (val === 'sederhana') {
            logoKiriWrapper.classList.add('hidden');
            logoKananWrapper.classList.add('hidden');
        } else if (val === 'yayasan') {
            logoKiriWrapper.classList.remove('hidden');
            logoKananWrapper.classList.remove('hidden');
        } else {
            logoKiriWrapper.classList.remove('hidden');
            logoKananWrapper.classList.add('hidden');
        }

        if (logoL !== null) {
            kopLogoInput.value = logoL;
            logoKiriPreview.src = logoL || PLACEHOLDER_LOGO;
        }
        if (logoR !== null) {
            kopLogoKananInput.value = logoR;
            logoKananPreview.src = logoR || PLACEHOLDER_LOGO;
        }
        if (dinas !== null) generateForm.elements['kop_dinas'].value = dinas;
        if (sekolah !== null) generateForm.elements['kop_sekolah'].value = sekolah;
        if (alamat !== null) generateForm.elements['kop_alamat'].value = alamat;
        if (ujian !== null) generateForm.elements['kop_ujian'].value = ujian;
        if (ta !== null) generateForm.elements['kop_ta'].value = ta;
        if (waktu !== null) generateForm.elements['kop_waktu'].value = waktu;
    }

    // 1. Live Preview Renderer
    function updateLiveKopPreview() {
        const template = kopTemplate.value;
        const dinas = generateForm.elements['kop_dinas'].value;
        const sekolah = generateForm.elements['kop_sekolah'].value;
        const alamat = generateForm.elements['kop_alamat'].value;
        const ujian = generateForm.elements['kop_ujian'].value;
        const ta = generateForm.elements['kop_ta'].value;
        const waktu = generateForm.elements['kop_waktu'].value;
        const logoL = kopLogoInput.value;
        const logoR = kopLogoKananInput.value;
        
        const subject = generateForm.elements['subject'].value || '[Mata Pelajaran]';
        const grade = generateForm.elements['grade'].value || '[Kelas / Semester]';

        let logoHtmlL = logoL ? `<img src="${logoL}" width="60" style="width: 50pt; height: auto;" />` : '';
        let logoHtmlR = logoR ? `<img src="${logoR}" width="60" style="width: 50pt; height: auto;" />` : '';

        let kopTable = '';
        if (template === 'sederhana') {
            kopTable = `
<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 15pt; font-family: 'Times New Roman', Times, serif;">
  <tr>
    <td style="width: 100%; border: none; text-align: center; vertical-align: middle; line-height: 1.2; padding-bottom: 5pt;">
      <span style="font-size: 11pt; font-weight: bold; text-transform: uppercase;">${dinas}</span><br>
      <span style="font-size: 14pt; font-weight: bold; text-transform: uppercase;">${sekolah}</span><br>
      <span style="font-size: 9pt; font-style: italic;">${alamat}</span>
    </td>
  </tr>
  <tr>
    <td style="border: none; border-top: 2px solid #000000; padding: 0; height: 1px; line-height: 1px;">&nbsp;</td>
  </tr>
</table>
            `;
        } else if (template === 'yayasan') {
            kopTable = `
<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 15pt; font-family: 'Times New Roman', Times, serif;">
  <tr>
    <td style="width: 15%; border: none; text-align: center; vertical-align: middle; padding-bottom: 5pt;">
      ${logoHtmlL}
    </td>
    <td style="width: 70%; border: none; text-align: center; vertical-align: middle; line-height: 1.2; padding-bottom: 5pt;">
      <span style="font-size: 11pt; font-weight: bold; text-transform: uppercase;">${dinas}</span><br>
      <span style="font-size: 14pt; font-weight: bold; text-transform: uppercase;">${sekolah}</span><br>
      <span style="font-size: 9pt; font-style: italic;">${alamat}</span>
    </td>
    <td style="width: 15%; border: none; text-align: center; vertical-align: middle; padding-bottom: 5pt;">
      ${logoHtmlR}
    </td>
  </tr>
  <tr>
    <td colspan="3" style="border: none; border-top: 3px double #000000; padding: 0; height: 1px; line-height: 1px;">&nbsp;</td>
  </tr>
</table>
            `;
        } else { // kemendikbud or kemenag
            kopTable = `
<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 15pt; font-family: 'Times New Roman', Times, serif;">
  <tr>
    <td style="width: 15%; border: none; text-align: center; vertical-align: middle; padding-bottom: 5pt;">
      ${logoHtmlL}
    </td>
    <td style="width: 85%; border: none; text-align: center; vertical-align: middle; line-height: 1.2; padding-right: 15pt; padding-bottom: 5pt;">
      <span style="font-size: 11pt; font-weight: bold; text-transform: uppercase;">${dinas}</span><br>
      <span style="font-size: 14pt; font-weight: bold; text-transform: uppercase;">${sekolah}</span><br>
      <span style="font-size: 9pt; font-style: italic;">${alamat}</span>
    </td>
  </tr>
  <tr>
    <td colspan="2" style="border: none; border-top: 3px double #000000; padding: 0; height: 1px; line-height: 1px;">&nbsp;</td>
  </tr>
</table>
            `;
        }

        const metadataTable = `
<h3 style="text-align: center; font-size: 12pt; font-weight: bold; margin-bottom: 10pt; text-transform: uppercase; font-family: 'Times New Roman', Times, serif;">${ujian}<br>TAHUN AJARAN ${ta}</h3>

<table border="1" cellspacing="0" cellpadding="4" style="width: 100%; border-collapse: collapse; border: 1px solid #000000; margin-bottom: 20pt; font-family: 'Times New Roman', Times, serif; font-size: 10pt;">
  <tr>
    <td style="width: 15%; font-weight: bold; border: 1px solid #000000;">Mata Pelajaran</td>
    <td style="width: 35%; border: 1px solid #000000;">${subject}</td>
    <td style="width: 15%; font-weight: bold; border: 1px solid #000000;">Nama Siswa</td>
    <td style="width: 35%; border: 1px solid #000000;">......................................</td>
  </tr>
  <tr>
    <td style="width: 15%; font-weight: bold; border: 1px solid #000000;">Kelas / Semester</td>
    <td style="width: 35%; border: 1px solid #000000;">${grade}</td>
    <td style="width: 15%; font-weight: bold; border: 1px solid #000000;">Nomor Absen</td>
    <td style="width: 35%; border: 1px solid #000000;">......................................</td>
  </tr>
  <tr>
    <td style="width: 15%; font-weight: bold; border: 1px solid #000000;">Hari / Tanggal</td>
    <td style="width: 35%; border: 1px solid #000000;">............................</td>
    <td style="width: 15%; font-weight: bold; border: 1px solid #000000;">Waktu</td>
    <td style="width: 35%; border: 1px solid #000000;">${waktu}</td>
  </tr>
</table>

<div class="mt-8 pt-8 border-t border-dashed border-cloud-gray text-center text-silver italic text-xs select-none">
  [Butir-butir Soal Evaluasi akan digenerate oleh AI di sini...]
</div>
        `;

        const liveKopContainer = document.getElementById('live-kop-container');
        if (liveKopContainer) {
            liveKopContainer.innerHTML = kopTable + metadataTable;
        }
        saveKopSettingsToLocalStorage();
    }

    // 2. File Uploading & Base64 Converting
    uploadLogoKiriBtn.addEventListener('click', () => logoKiriFile.click());
    logoKiriFile.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                logoKiriPreview.src = e.target.result;
                kopLogoInput.value = e.target.result;
                updateLiveKopPreview();
            }
            reader.readAsDataURL(file);
        }
    });

    uploadLogoKananBtn.addEventListener('click', () => logoKananFile.click());
    logoKananFile.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                logoKananPreview.src = e.target.result;
                kopLogoKananInput.value = e.target.result;
                updateLiveKopPreview();
            }
            reader.readAsDataURL(file);
        }
    });

    // 3. Preset and Clear Logo actions
    presetLogoKiriBtn.addEventListener('click', function() {
        const val = kopTemplate.value;
        if (val === 'kemenag') {
            kopLogoInput.value = DEFAULT_KEMENAG_LOGO;
            logoKiriPreview.src = DEFAULT_KEMENAG_LOGO;
        } else {
            kopLogoInput.value = DEFAULT_KEMENDIKBUD_LOGO;
            logoKiriPreview.src = DEFAULT_KEMENDIKBUD_LOGO;
        }
        logoKiriFile.value = '';
        updateLiveKopPreview();
    });

    clearLogoKananBtn.addEventListener('click', function() {
        kopLogoKananInput.value = PLACEHOLDER_LOGO;
        logoKananPreview.src = PLACEHOLDER_LOGO;
        logoKananFile.value = '';
        updateLiveKopPreview();
    });

    // 4. Template Swapper logic
    kopTemplate.addEventListener('change', function() {
        const val = this.value;
        
        if (val === 'sederhana') {
            logoKiriWrapper.classList.add('hidden');
            logoKananWrapper.classList.add('hidden');
        } else if (val === 'yayasan') {
            logoKiriWrapper.classList.remove('hidden');
            logoKananWrapper.classList.remove('hidden');
            
            // Re-apply defaults if currently using Kemendikbud or Kemenag defaults
            if (kopLogoInput.value === DEFAULT_KEMENAG_LOGO || !kopLogoInput.value) {
                kopLogoInput.value = DEFAULT_YAYASAN_LOGO;
                logoKiriPreview.src = DEFAULT_YAYASAN_LOGO;
            }
            if (kopLogoKananInput.value === PLACEHOLDER_LOGO || !kopLogoKananInput.value) {
                kopLogoKananInput.value = PLACEHOLDER_LOGO;
                logoKananPreview.src = PLACEHOLDER_LOGO;
            }
            
            generateForm.elements['kop_dinas'].value = "YAYASAN PENDIDIKAN DHARMA BAKTI";
            generateForm.elements['kop_sekolah'].value = "SMA DHARMA BAKTI INDRAMAYU";
            generateForm.elements['kop_alamat'].value = "Jl. Jenderal Sudirman No. 99, Indramayu, Telp. (0234) 987654";
        } else if (val === 'kemenag') {
            logoKiriWrapper.classList.remove('hidden');
            logoKananWrapper.classList.add('hidden');
            
            kopLogoInput.value = DEFAULT_KEMENAG_LOGO;
            logoKiriPreview.src = DEFAULT_KEMENAG_LOGO;
            
            generateForm.elements['kop_dinas'].value = "KEMENTERIAN AGAMA REPUBLIK INDONESIA<br>KANTOR KEMENTERIAN AGAMA KABUPATEN INDRAMAYU";
            generateForm.elements['kop_sekolah'].value = "MADRASAH IBTIDAIYAH NEGERI 1 INDRAMAYU";
            generateForm.elements['kop_alamat'].value = "Jl. Raya Jati Barang No. 45, Indramayu, Telp. (0234) 654321";
        } else { // kemendikbud
            logoKiriWrapper.classList.remove('hidden');
            logoKananWrapper.classList.add('hidden');
            
            kopLogoInput.value = DEFAULT_KEMENDIKBUD_LOGO;
            logoKiriPreview.src = DEFAULT_KEMENDIKBUD_LOGO;
            
            generateForm.elements['kop_dinas'].value = "PEMERINTAH KABUPATEN INDRAMAYU<br>DINAS PENDIDIKAN";
            generateForm.elements['kop_sekolah'].value = "SD NEGERI 1 MERDEKA";
            generateForm.elements['kop_alamat'].value = "Jl. Ki Hajar Dewantara No. 17, Indramayu, Telp. (0234) 123456";
        }
        
        updateLiveKopPreview();
    });

    // 5. Watch for input changes to automatically refresh the live preview
    const fieldsToWatch = [
        'kop_dinas', 'kop_sekolah', 'kop_alamat', 'kop_ujian', 'kop_ta', 'kop_waktu',
        'subject', 'grade'
    ];
    fieldsToWatch.forEach(fieldName => {
        const input = generateForm.elements[fieldName];
        if (input) {
            input.addEventListener('input', updateLiveKopPreview);
            input.addEventListener('change', updateLiveKopPreview);
        }
    });

    // Initialize the live preview on load
    document.addEventListener('DOMContentLoaded', () => {
        loadKopSettingsFromLocalStorage();
        updateLiveKopPreview();
    });

    // 6. Fill Demo / Contoh Action
    fillDemoBtn.addEventListener('click', function() {
        // Form parameters
        generateForm.elements['subject'].value = "IPAS (Sains)";
        generateForm.elements['topic'].value = "Fotosintesis & Rantai Makanan";
        generateForm.elements['grade'].value = "Kelas 5 SD";
        generateForm.elements['difficulty'].value = "HOTS (Berpikir Tingkat Tinggi)";
        generateForm.elements['question_type'].value = "Campuran (Pilihan Ganda & Essay)";
        generateForm.elements['quantity'].value = "10 Soal";
        generateForm.elements['indicator'].value = "1. Menganalisis peran cahaya matahari dalam proses fotosintesis.\n2. Mengevaluasi dampak kepunahan produsen terhadap kestabilan ekosistem.";
        
        // Select Kemendikbud template by default
        kopTemplate.value = "kemendikbud";
        logoKiriWrapper.classList.remove('hidden');
        logoKananWrapper.classList.add('hidden');
        
        kopLogoInput.value = DEFAULT_KEMENDIKBUD_LOGO;
        logoKiriPreview.src = DEFAULT_KEMENDIKBUD_LOGO;
        logoKiriFile.value = '';

        kopLogoKananInput.value = PLACEHOLDER_LOGO;
        logoKananPreview.src = PLACEHOLDER_LOGO;
        logoKananFile.value = '';

        // Text Kop parameters
        generateForm.elements['kop_dinas'].value = "PEMERINTAH KABUPATEN INDRAMAYU<br>DINAS PENDIDIKAN";
        generateForm.elements['kop_sekolah'].value = "SD NEGERI 1 MERDEKA";
        generateForm.elements['kop_alamat'].value = "Jl. Ki Hajar Dewantara No. 17, Indramayu, Telp. (0234) 123456";
        generateForm.elements['kop_ujian'].value = "PENILAIAN HARIAN (PH) BERSAMA";
        generateForm.elements['kop_ta'].value = "2026/2027";
        generateForm.elements['kop_waktu'].value = "90 Menit";

        // Refresh Preview
        updateLiveKopPreview();
    });

    // 7. Form Submission Handler
    generateForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // UI States
        emptyState.classList.add('hidden');
        outputPane.classList.add('hidden');
        
        // Show Loading Modal
        loadingModal.classList.remove('hidden');
        loadingModal.classList.add('flex');
        
        submitBtn.disabled = true;
        submitBtn.innerText = 'Memproses Soal...';
        
        // Disable buttons
        downloadWord.classList.add('pointer-events-none', 'text-silver');
        printPdf.classList.add('pointer-events-none', 'text-silver');

        try {
            const formData = new FormData(generateForm);
            const dataObj = {};
            formData.forEach((value, key) => dataObj[key] = value);

            // Parse select box string numbers like "10 Soal" into integers
            dataObj['quantity'] = parseInt(dataObj['quantity']);

            const response = await fetch('/tools/generate-soal', {
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
                let htmlContent = result.html_content;
                htmlContent = htmlContent.replace(/<br style=['"]page-break-before:\s*always;\s*clear:\s*both;\s*mso-special-character:\s*page-break;['"]\s*\/?>/gi, '\n\n<div class="relative my-10 border-t-2 border-dashed border-cloud-gray select-none"><span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-snow-white px-4 text-xs font-bold text-silver uppercase tracking-wider">Halaman Baru (Pemisah Halaman)</span></div>\n\n');

                // Render content using generated HTML from server
                outputPane.innerHTML = htmlContent;
                outputPane.classList.remove('hidden');
                
                // Update save status
                saveStatus.innerText = result.is_mock ? 'Selesai (Mode Demo)' : 'Tersimpan';
                saveStatus.classList.remove('text-silver');
                saveStatus.classList.add('text-duo-green', 'bg-duo-green-light/25', 'border-duo-green');

                // Inject Illustration Buttons (HIDDEN PER USER REQUEST)
                // injectIllustrationButtons();

                // Enable Action Buttons
                downloadWord.href = `/documents/${result.id}/download`;
                downloadWord.classList.remove('pointer-events-none', 'text-silver');
                downloadWord.classList.add('text-sky-blue');
                
                printPdf.href = `/documents/${result.id}/print`;
                printPdf.classList.remove('pointer-events-none', 'text-silver');
                printPdf.classList.add('text-sky-blue');
            } else {
                alert(`Gagal membuat soal: ${result.error || 'Terjadi kesalahan sistem.'}`);
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
            submitBtn.innerText = 'Buat Soal Evaluasi';
        }
    });

    // --- On-Demand Image Generation Logic ---
    let currentTargetNode = null;
    const imageModal = document.getElementById('image-modal');
    const closeImageModal = document.getElementById('close-image-modal');
    const imageForm = document.getElementById('image-form');
    const imagePromptInput = document.getElementById('image-prompt');
    const imageLoader = document.getElementById('image-loader');
    const generateImageBtn = document.getElementById('generate-image-btn');

    function injectIllustrationButtons() {
        // Find elements that look like question numbers (p or li)
        const candidates = outputPane.querySelectorAll('p, li');
        let questionCounter = 0;
        
        candidates.forEach(el => {
            const text = el.innerText.trim();
            // Check if text starts with "1. ", "2. ", etc.
            if (/^\d+\.\s/.test(text)) {
                questionCounter++;
                
                // Create button container
                const btnContainer = document.createElement('div');
                btnContainer.className = 'mt-2 mb-4';
                
                const addImgBtn = document.createElement('button');
                addImgBtn.className = 'text-[11px] font-bold bg-sky-blue/10 hover:bg-sky-blue/20 text-sky-blue px-3 py-1.5 rounded-lg border border-sky-blue/30 flex items-center space-x-1.5 transition select-none';
                addImgBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg><span>Tambahkan Ilustrasi</span>';
                
                addImgBtn.onclick = function(e) {
                    e.preventDefault();
                    currentTargetNode = btnContainer;
                    
                    imagePromptInput.value = '';
                    imageModal.classList.remove('hidden');
                    imagePromptInput.focus();
                };
                
                btnContainer.appendChild(addImgBtn);
                
                // Insert after the element. If it's LI, insert inside it at the end or after it if it's block.
                if (el.tagName.toLowerCase() === 'li') {
                    el.appendChild(btnContainer);
                } else {
                    el.insertAdjacentElement('afterend', btnContainer);
                }
            }
        });
    }

    closeImageModal.onclick = function() {
        imageModal.classList.add('hidden');
        imageLoader.classList.add('hidden');
    };

    imageForm.onsubmit = async function(e) {
        e.preventDefault();
        
        const promptText = imagePromptInput.value.trim();
        if (!promptText || !currentTargetNode) return;
        
        // Show loader, disable inputs
        generateImageBtn.disabled = true;
        imagePromptInput.disabled = true;
        imageLoader.classList.remove('hidden');
        imageLoader.classList.add('flex');
        
        try {
            const token = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');
            const response = await fetch('/tools/generate-image', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ prompt: promptText })
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                // Insert image into target node
                const imgWrap = document.createElement('div');
                imgWrap.className = 'my-4 text-center';
                
                const img = document.createElement('img');
                img.src = result.image_url;
                img.className = 'max-w-full h-auto max-h-64 object-contain rounded-lg border-2 border-cloud-gray shadow-sm inline-block';
                img.alt = promptText;
                
                imgWrap.appendChild(img);
                
                // Insert before the button container
                currentTargetNode.parentNode.insertBefore(imgWrap, currentTargetNode);
                
                // Hide button container after inserting image
                // Optional: we can remove the button or leave it if they want multiple images
                // currentTargetNode.remove();
                
                // Close modal
                imageModal.classList.add('hidden');
            } else {
                alert('Gagal menghasilkan gambar. Silakan coba lagi.');
            }
        } catch (err) {
            console.error(err);
            alert('Gagal menghubungi server.');
        } finally {
            generateImageBtn.disabled = false;
            imagePromptInput.disabled = false;
            imageLoader.classList.add('hidden');
            imageLoader.classList.remove('flex');
        }
    };
</script>
@endsection
