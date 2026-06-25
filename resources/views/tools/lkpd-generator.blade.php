@extends('layouts.app')

@section('title', 'LKPD Generator AI - PandAI')

@section('content')
    <!-- Header Page -->
    <section class="select-none mb-6 print:hidden">
        <h2 class="text-heading font-feather text-almost-black">Generator LKPD AI</h2>
        <p class="text-body text-graphite">Buat Lembar Kerja Peserta Didik (LKPD) terstruktur lengkap dengan instruksi eksperimen, materi pendukung, dan soal evaluasi secara instan.</p>
    </section>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Options Form -->
        <div class="col-span-1 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm space-y-5 print:hidden">
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-3 select-none">
                <h3 class="text-heading-sm font-feather text-almost-black">Pengaturan LKPD</h3>
                <button type="button" id="fill-demo-btn" class="text-xs font-bold text-grape-soda hover:underline">Isi Contoh</button>
            </div>
            
            <form id="generate-form" class="space-y-4">
                @csrf

                <!-- Subject -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Mata Pelajaran</label>
                    <input type="text" name="subject" placeholder="Contoh: IPAS, Matematika, IPS" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                </div>

                <!-- Topic -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Topik / Materi Pembelajaran</label>
                    <input type="text" name="topic" placeholder="Contoh: Fotosintesis pada Tumbuhan" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                </div>

                <!-- School / Satuan Pendidikan -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Satuan Pendidikan</label>
                    <input type="text" name="school" placeholder="Contoh: SD Negeri Merdeka" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                </div>

                <!-- Grade / Kelas & Semester -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Kelas / Semester</label>
                    <input type="text" name="grade" placeholder="Contoh: Kelas IV / Semester I" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                </div>

                <!-- Time Allocation & Num Questions -->
                <div class="space-y-1 flex space-x-2">
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-charcoal select-none">Alokasi Waktu</label>
                        <input type="text" name="time_allocation" placeholder="Contoh: 2 x 35 Menit" required
                               class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-charcoal select-none">Jumlah Soal</label>
                        <select name="num_questions" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                            <option value="2">2 Soal</option>
                            <option value="3" selected>3 Soal</option>
                            <option value="4">4 Soal</option>
                            <option value="5">5 Soal</option>
                        </select>
                    </div>
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
                    Buat LKPD Sekarang
                </button>
            </form>
        </div>

        <!-- Right: Preview Area -->
        <div class="lg:col-span-2 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm min-h-[500px] flex flex-col justify-between print:border-none print:shadow-none print:p-0 print:w-full print:absolute print:top-0 print:left-0 print:m-0">
            
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-4 mb-4 select-none print:hidden">
                <div class="flex space-x-4">
                    <h3 class="text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-grape-soda pb-1" id="tab-preview">Preview Lembar Kerja</h3>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Toggle Kunci Jawaban -->
                    <div id="toggle-key-container" class="hidden flex items-center space-x-2 border border-cloud-gray px-3 py-1.5 rounded-xl bg-[#f9f9f9]">
                        <input type="checkbox" id="toggle-key-checkbox" checked class="w-4 h-4 text-grape-soda focus:ring-grape-soda cursor-pointer">
                        <label for="toggle-key-checkbox" class="text-xs font-bold text-charcoal cursor-pointer select-none">Tampilkan Kunci (Guru)</label>
                    </div>
                    <span id="save-status" class="text-xs font-bold text-silver bg-[#f9f9f9] px-2.5 py-1 rounded-full border border-cloud-gray">Belum Disimpan</span>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loading-state" class="hidden flex-col items-center justify-center flex-1 h-full opacity-50 select-none print:hidden py-12">
                <div class="w-12 h-12 border-4 border-cloud-gray border-t-grape-soda rounded-full animate-spin mb-4"></div>
                <p class="text-graphite font-bold animate-pulse">Menghasilkan Dokumen LKPD...</p>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="flex flex-col items-center justify-center flex-1 h-full opacity-50 select-none print:hidden py-12">
                <svg class="w-20 h-20 text-silver mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <p class="text-graphite font-bold text-center">Belum ada LKPD.<br><span class="text-sm font-normal">Isi pengaturan di sebelah kiri untuk mulai merancang.</span></p>
            </div>

            <!-- Content Area (A4 Paper Spec Look) -->
            <div id="content-area" class="hidden flex-1 flex-col w-full bg-white print:block border-2 border-cloud-gray p-8 rounded-xl shadow-inner max-w-4xl mx-auto print:border-none print:shadow-none print:p-0">
                
                <!-- Kop Surat / Identity Header -->
                <div class="border-4 border-double border-almost-black p-4 mb-6 rounded-xl select-none">
                    <h2 id="lkpd-header-title" class="text-xl font-bold font-feather text-center text-almost-black tracking-wide uppercase mb-3">LEMBAR KERJA PESERTA DIDIK (LKPD)</h2>
                    <div class="grid grid-cols-2 gap-4 text-xs font-bold text-charcoal border-t-2 border-almost-black pt-3">
                        <div class="space-y-1">
                            <div>Satuan Pendidikan: <span id="hdr-school" class="font-normal"></span></div>
                            <div>Kelas / Semester: <span id="hdr-grade" class="font-normal"></span></div>
                            <div>Mata Pelajaran: <span id="hdr-subject" class="font-normal"></span></div>
                        </div>
                        <div class="space-y-1">
                            <div>Materi Utama: <span id="hdr-topic" class="font-normal"></span></div>
                            <div>Alokasi Waktu: <span id="hdr-time" class="font-normal"></span></div>
                            <div class="border border-cloud-gray p-1 rounded bg-cloud-gray/10 text-[10px] text-graphite flex justify-between">
                                <span>Kelompok: _______________</span>
                                <span>Nilai: _________</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Body -->
                <div id="lkpd-body" class="space-y-6 text-sm text-almost-black leading-relaxed">
                    <!-- A. Indikator -->
                    <div id="sec-indicators">
                        <h4 class="font-bold border-b border-cloud-gray pb-1 mb-2 text-charcoal flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-grape-soda/10 text-grape-soda flex items-center justify-center font-bold text-xs select-none">A</span>
                            Indikator Pencapaian Kompetensi
                        </h4>
                        <ul id="list-indicators" class="list-disc pl-6 space-y-1"></ul>
                    </div>

                    <!-- B. Tujuan Pembelajaran -->
                    <div id="sec-objectives">
                        <h4 class="font-bold border-b border-cloud-gray pb-1 mb-2 text-charcoal flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-grape-soda/10 text-grape-soda flex items-center justify-center font-bold text-xs select-none">B</span>
                            Tujuan Pembelajaran
                        </h4>
                        <ul id="list-objectives" class="list-disc pl-6 space-y-1"></ul>
                    </div>

                    <!-- C. Petunjuk Belajar -->
                    <div id="sec-instructions">
                        <h4 class="font-bold border-b border-cloud-gray pb-1 mb-2 text-charcoal flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-grape-soda/10 text-grape-soda flex items-center justify-center font-bold text-xs select-none">C</span>
                            Petunjuk Belajar
                        </h4>
                        <div id="text-instructions" class="pl-2 prose max-w-none text-sm text-almost-black"></div>
                    </div>

                    <!-- D. Informasi Pendukung -->
                    <div id="sec-supporting-info">
                        <h4 class="font-bold border-b border-cloud-gray pb-1 mb-2 text-charcoal flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-grape-soda/10 text-grape-soda flex items-center justify-center font-bold text-xs select-none">D</span>
                            Informasi Pendukung
                        </h4>
                        <div id="text-supporting-info" class="p-4 bg-grape-soda/5 border-l-4 border-grape-soda rounded-xl prose max-w-none text-sm text-almost-black"></div>
                    </div>

                    <!-- E. Langkah-langkah Kerja -->
                    <div id="sec-steps">
                        <h4 class="font-bold border-b border-cloud-gray pb-1 mb-2 text-charcoal flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-grape-soda/10 text-grape-soda flex items-center justify-center font-bold text-xs select-none">E</span>
                            Langkah-langkah Kerja
                        </h4>
                        <ol id="list-steps" class="list-decimal pl-6 space-y-2"></ol>
                    </div>

                    <!-- F. Soal-soal Latihan / Evaluasi -->
                    <div id="sec-questions">
                        <h4 class="font-bold border-b border-cloud-gray pb-1 mb-2 text-charcoal flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-grape-soda/10 text-grape-soda flex items-center justify-center font-bold text-xs select-none">F</span>
                            Soal Latihan & Evaluasi
                        </h4>
                        <div id="list-questions" class="space-y-6"></div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Pane -->
            <div id="actions-pane" class="hidden border-t-2 border-cloud-gray pt-4 justify-end space-x-3 select-none print:hidden mt-6">
                <!-- Cetak PDF -->
                <button id="print-btn" class="btn-outline text-charcoal border-cloud-gray hover:border-grape-soda hover:text-grape-soda text-sm py-2 px-4 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Cetak PDF</span>
                </button>
                <!-- Unduh Word -->
                <a id="download-btn" href="#" class="btn-3d-primary text-sm py-2 px-4 bg-sky-blue hover:bg-[#189cdb] shadow-[0_4px_0_#127cb0] flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Unduh Word (.doc)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Hidden CSS to force print styles -->
    <style>
        @media print {
            body { background: white; margin: 0; padding: 0; }
            .print\:hidden { display: none !important; }
            .print\:block { display: block !important; }
            .print\:absolute { position: absolute !important; }
            .print\:top-0 { top: 0 !important; }
            .print\:left-0 { left: 0 !important; }
            .print\:w-full { width: 100% !important; }
            .print\:m-0 { margin: 0 !important; }
            .print\:p-0 { padding: 0 !important; }
            .print\:border-none { border: none !important; }
            .print\:shadow-none { box-shadow: none !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            th, td { border: 1px solid #ccc !important; padding: 8px !important; }
        }
        /* Prose customization to align styling */
        .prose ul { list-style-type: disc !important; padding-left: 20px !important; margin-bottom: 8px !important; }
        .prose ol { list-style-type: decimal !important; padding-left: 20px !important; margin-bottom: 8px !important; }
        .prose strong { font-weight: bold !important; }
    </style>

    <!-- Page Logic -->
    <script>
        // DOM Elements
        const form = document.getElementById('generate-form');
        const fillDemoBtn = document.getElementById('fill-demo-btn');
        const submitBtn = document.getElementById('submit-btn');
        const loadingState = document.getElementById('loading-state');
        const emptyState = document.getElementById('empty-state');
        const contentArea = document.getElementById('content-area');
        const actionsPane = document.getElementById('actions-pane');
        const saveStatus = document.getElementById('save-status');
        const printBtn = document.getElementById('print-btn');
        const downloadBtn = document.getElementById('download-btn');
        
        const toggleKeyContainer = document.getElementById('toggle-key-container');
        const toggleKeyCheckbox = document.getElementById('toggle-key-checkbox');

        let currentData = null;

        function stripPrefix(text, type = 'step') {
            if (!text) return '';
            text = text.trim();
            if (type === 'step') {
                return text.replace(/^(langkah\s*\d+[:\.]?\s*|\d+[\.\s]+)/i, '').trim();
            } else {
                return text.replace(/^((pertanyaan|soal)\s*\d+[:\.]?\s*|\d+[\.\s]+)/i, '').trim();
            }
        }

        // Page Initial Load (Load Document from DB history if present)
        @if(isset($document))
            try {
                const docData = {!! $document->content !!};
                
                const mockFormData = new FormData();
                mockFormData.append('subject', "{{ $document->name }}");
                mockFormData.append('school', docData.satuan_pendidikan || "Sekolah");

                renderLKPD(docData);
                
                // Set download link
                downloadBtn.href = "{{ route('documents.download', $document->id) }}";
                
                saveStatus.textContent = 'Dimuat dari Riwayat';
                saveStatus.className = 'text-xs font-bold text-sky-blue bg-sky-blue/10 px-2.5 py-1 rounded-full border border-sky-blue/20';
            } catch (e) {
                console.error("Failed to load document from history", e);
            }
        @endif

        // Fill Demo Data
        fillDemoBtn.addEventListener('click', () => {
            form.subject.value = 'Ilmu Pengetahuan Alam dan Sosial (IPAS)';
            form.topic.value = 'Proses Fotosintesis pada Tumbuhan Hijau';
            form.school.value = 'SD Negeri Merdeka Belajar';
            form.grade.value = 'Kelas IV / Semester I';
            form.time_allocation.value = '2 x 35 Menit';
            form.num_questions.value = '3';
            form.use_mock.checked = false;
        });

        // Print Trigger
        printBtn.addEventListener('click', () => {
            window.print();
        });

        // Toggle Kunci Jawaban Visibility
        toggleKeyCheckbox.addEventListener('change', () => {
            const keys = document.querySelectorAll('.answer-key-div');
            keys.forEach(k => {
                if (toggleKeyCheckbox.checked) {
                    k.style.display = 'block';
                } else {
                    k.style.display = 'none';
                }
            });
        });

        // Submit Form
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // UI State transitions
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-pulse">Menghasilkan...</span>';
            emptyState.style.display = 'none';
            contentArea.style.display = 'none';
            actionsPane.style.display = 'none';
            toggleKeyContainer.style.display = 'none';
            loadingState.style.display = 'flex';

            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route("tools.generate-lkpd-submit") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Server error: ${response.status}`);
                }

                const res = await response.json();
                
                if (res.status === 'success') {
                    renderLKPD(res.data);
                    
                    // Set download link to route
                    downloadBtn.href = `/documents/${res.document_id}/download`;
                    
                    saveStatus.textContent = 'Tersimpan (Sesi Ini)';
                    saveStatus.className = 'text-xs font-bold text-duo-green bg-duo-green/10 px-2.5 py-1 rounded-full border border-duo-green/20';
                } else {
                    alert('Gagal menghasilkan LKPD: ' + (res.message || 'Unknown error'));
                    emptyState.style.display = 'flex';
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan koneksi atau sistem saat menghubungi server.');
                emptyState.style.display = 'flex';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Buat LKPD Sekarang';
                loadingState.style.display = 'none';
            }
        });

        // Renderer function
        function renderLKPD(data) {
            currentData = data;

            // Header Kop
            document.getElementById('hdr-school').textContent = data.satuan_pendidikan || '-';
            document.getElementById('hdr-grade').textContent = data.kelas_semester || '-';
            document.getElementById('hdr-subject').textContent = data.materi_ajar ? (data.judul ? data.judul.split(':')[0] : 'IPAS') : '-'; // Fallback
            document.getElementById('hdr-topic').textContent = data.materi_ajar || '-';
            document.getElementById('hdr-time').textContent = data.alokasi_waktu || '-';
            document.getElementById('lkpd-header-title').textContent = data.judul || 'LEMBAR KERJA PESERTA DIDIK (LKPD)';

            // A. Indikator
            const indicatorsList = document.getElementById('list-indicators');
            indicatorsList.innerHTML = '';
            if (data.indikator && data.indikator.length > 0) {
                data.indikator.forEach(ind => {
                    const li = document.createElement('li');
                    li.textContent = ind;
                    indicatorsList.appendChild(li);
                });
            } else {
                indicatorsList.innerHTML = '<li class="text-graphite italic text-xs">Tidak ada indikator.</li>';
            }

            // B. Tujuan
            const objectivesList = document.getElementById('list-objectives');
            objectivesList.innerHTML = '';
            if (data.tujuan && data.tujuan.length > 0) {
                data.tujuan.forEach(tuj => {
                    const li = document.createElement('li');
                    li.textContent = tuj;
                    objectivesList.appendChild(li);
                });
            } else {
                objectivesList.innerHTML = '<li class="text-graphite italic text-xs">Tidak ada tujuan pembelajaran.</li>';
            }

            // C. Petunjuk Belajar (Markdown)
            const instructionsDiv = document.getElementById('text-instructions');
            if (data.petunjuk_belajar) {
                instructionsDiv.innerHTML = marked.parse(data.petunjuk_belajar);
            } else {
                instructionsDiv.innerHTML = '<p class="text-graphite italic text-xs">Tidak ada petunjuk belajar.</p>';
            }

            // D. Informasi Pendukung (Markdown)
            const supportingDiv = document.getElementById('text-supporting-info');
            if (data.informasi_pendukung) {
                supportingDiv.style.display = 'block';
                supportingDiv.innerHTML = marked.parse(data.informasi_pendukung);
            } else {
                supportingDiv.style.display = 'none';
            }

            // E. Langkah Kerja
            const stepsList = document.getElementById('list-steps');
            stepsList.innerHTML = '';
            if (data.langkah_kerja && data.langkah_kerja.length > 0) {
                data.langkah_kerja.forEach(lk => {
                    const li = document.createElement('li');
                    li.textContent = stripPrefix(lk, 'step');
                    stepsList.appendChild(li);
                });
            } else {
                stepsList.innerHTML = '<li class="text-graphite italic text-xs">Tidak ada langkah-langkah kerja.</li>';
            }

            // F. Soal-soal Latihan / Evaluasi
            const questionsDiv = document.getElementById('list-questions');
            questionsDiv.innerHTML = '';
            if (data.soal_soal && data.soal_soal.length > 0) {
                data.soal_soal.forEach((s, idx) => {
                    const qContainer = document.createElement('div');
                    qContainer.className = 'space-y-2';
                    
                    const qText = document.createElement('div');
                    qText.className = 'font-bold text-charcoal';
                    qText.innerHTML = `${idx + 1}. ${stripPrefix(s.pertanyaan, 'question')}`;
                    qContainer.appendChild(qText);

                    // Student writing area (dotted lines)
                    const dottedArea = document.createElement('div');
                    dottedArea.className = 'space-y-2 select-none print:block';
                    dottedArea.innerHTML = `
                        <div class="border-b border-dashed border-cloud-gray h-6 w-full"></div>
                        <div class="border-b border-dashed border-cloud-gray h-6 w-full"></div>
                        <div class="border-b border-dashed border-cloud-gray h-6 w-full"></div>
                    `;
                    qContainer.appendChild(dottedArea);

                    // Answer key (Teacher reference)
                    const keyDiv = document.createElement('div');
                    keyDiv.className = 'answer-key-div p-3 bg-sunshine-yellow/10 border-l-4 border-sunshine-yellow text-xs text-charcoal rounded-r-lg mt-2';
                    keyDiv.innerHTML = `<strong>Kunci Jawaban/Panduan Guru:</strong><br>${s.kunci_jawaban || 'Tidak ada kunci jawaban.'}`;
                    // Respect current state of checkbox
                    keyDiv.style.display = toggleKeyCheckbox.checked ? 'block' : 'none';
                    qContainer.appendChild(keyDiv);

                    questionsDiv.appendChild(qContainer);
                });
            } else {
                questionsDiv.innerHTML = '<div class="text-graphite italic text-xs">Tidak ada soal latihan.</div>';
            }

            // Show UI Elements
            contentArea.style.display = 'block';
            actionsPane.style.display = 'flex';
            toggleKeyContainer.style.display = 'flex';
        }
    </script>
@endsection
