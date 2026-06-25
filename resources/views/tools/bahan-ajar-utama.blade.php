@extends('layouts.app')

@section('title', 'Bahan Ajar Utama - PandAI')

@section('content')
    <!-- Header Page -->
    <section class="select-none mb-6">
        <h2 class="text-heading font-feather text-almost-black">Generator Bahan Ajar AI</h2>
        <p class="text-body text-graphite font-medium">Buat paket mengajar lengkap secara otomatis: rangkuman materi mendalam, peta konsep visual, LKPD aktivitas siswa, dan kuis formatif interaktif.</p>
    </section>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Options Form -->
        <div class="col-span-1 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm space-y-5">
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-3 select-none">
                <h3 class="text-heading-sm font-feather text-almost-black">Parameter Bahan Ajar</h3>
                <button type="button" id="fill-demo-btn" class="text-xs font-bold text-grape-soda hover:underline">Isi Contoh</button>
            </div>
            
            <form id="generate-form" class="space-y-4">
                @csrf

                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Mata Pelajaran</label>
                    <input type="text" name="subject" placeholder="Contoh: Biologi, Sejarah Indonesia" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Topik / Materi Spesifik</label>
                    <input type="text" name="topic" placeholder="Contoh: Metamorfosis Kupu-Kupu" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-charcoal select-none">Jenjang/Kelas</label>
                        <select name="grade" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                            <option>SD Kelas 5</option>
                            <option selected>SMP Kelas 7</option>
                            <option>SMA Kelas 11</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-charcoal select-none">Kedalaman Materi</label>
                        <select name="depth" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-grape-soda bg-[#f9f9f9] transition">
                            <option value="pengenalan">Pengenalan Dasar</option>
                            <option value="menengah" selected>Menengah / Diskusi</option>
                            <option value="mendalam">Analisis Mendalam</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2 select-none pt-2">
                    <label class="block text-xs font-bold text-silver uppercase tracking-wider">Pilih Bagian yang Dibuat</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center space-x-2 p-2 bg-[#f9f9f9] rounded-lg border border-cloud-gray cursor-pointer text-xs font-bold">
                            <input type="checkbox" name="features[]" value="materi" checked disabled class="text-grape-soda focus:ring-grape-soda">
                            <span>Materi Ajar</span>
                        </label>
                        <label class="flex items-center space-x-2 p-2 bg-[#f9f9f9] rounded-lg border border-cloud-gray cursor-pointer text-xs font-bold">
                            <input type="checkbox" name="features[]" value="concept_map" checked class="text-grape-soda focus:ring-grape-soda">
                            <span>Peta Konsep</span>
                        </label>
                        <label class="flex items-center space-x-2 p-2 bg-[#f9f9f9] rounded-lg border border-cloud-gray cursor-pointer text-xs font-bold">
                            <input type="checkbox" name="features[]" value="lkpd" checked class="text-grape-soda focus:ring-grape-soda">
                            <span>LKPD Siswa</span>
                        </label>
                        <label class="flex items-center space-x-2 p-2 bg-[#f9f9f9] rounded-lg border border-cloud-gray cursor-pointer text-xs font-bold">
                            <input type="checkbox" name="features[]" value="quiz" checked class="text-grape-soda focus:ring-grape-soda">
                            <span>Kuis Formatif</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center space-x-2 pt-2 select-none">
                    <input type="checkbox" id="use-mock" name="use_mock" value="1"
                           class="w-4 h-4 text-grape-soda border-cloud-gray rounded focus:ring-grape-soda">
                    <label for="use-mock" class="text-xs font-bold text-graphite cursor-pointer">
                        Mode Demo (Cepat & Tanpa Kuota AI)
                    </label>
                </div>

                <button type="submit" id="submit-btn" class="btn-3d-primary w-full text-sm py-3 tracking-wider bg-grape-soda hover:bg-[#965eff] shadow-[0_4px_0_#804ce6] text-white">
                    Buat Bahan Ajar Lengkap
                </button>
            </form>
        </div>

        <!-- Right: Interactive Preview & Tabs -->
        <div class="lg:col-span-2 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm min-h-[600px] flex flex-col">
            
            <!-- Tab Navigation Headers -->
            <div class="w-full flex flex-wrap justify-between items-center border-b-2 border-cloud-gray pb-4 mb-4 select-none gap-4">
                <div class="flex flex-wrap gap-4">
                    <h3 class="text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-grape-soda pb-1 flex items-center gap-1.5" id="tab-materi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>Materi Ajar</span>
                    </h3>
                    <h3 class="text-heading-sm font-feather text-silver cursor-pointer hover:text-charcoal pb-1 flex items-center gap-1.5" id="tab-concept">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        <span>Peta Konsep</span>
                    </h3>
                    <h3 class="text-heading-sm font-feather text-silver cursor-pointer hover:text-charcoal pb-1 flex items-center gap-1.5" id="tab-lkpd">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Lembar Kerja (LKPD)</span>
                    </h3>
                    <h3 class="text-heading-sm font-feather text-silver cursor-pointer hover:text-charcoal pb-1 flex items-center gap-1.5" id="tab-quiz">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Kuis Formatif</span>
                    </h3>
                </div>
                <div>
                    <span id="save-status" class="text-xs font-bold text-silver bg-[#f9f9f9] px-2.5 py-1 rounded-full border border-cloud-gray">Belum Dibuat</span>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loading-state" class="hidden flex-col items-center justify-center flex-1 w-full py-20 select-none">
                <div class="w-12 h-12 border-4 border-cloud-gray border-t-grape-soda rounded-full animate-spin mb-4"></div>
                <p class="text-graphite font-bold animate-pulse">Menghubungi AI untuk Menyusun Paket Pembelajaran...</p>
                <p class="text-xs text-silver mt-2">Materi, Peta Konsep, LKPD, dan Kuis sedang dirumuskan...</p>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="flex flex-col items-center justify-center flex-1 w-full py-20 opacity-50 select-none">
                <svg class="w-20 h-20 text-silver mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <p class="text-graphite font-bold text-center">Belum ada Bahan Ajar.<br><span class="text-sm font-normal">Isi parameter di kiri untuk memulai proses pembuatan otomatis.</span></p>
            </div>

            <!-- Tab Content Container -->
            <div class="flex-1 flex flex-col justify-between">
                
                <!-- Tab 1: Materi Ajar -->
                <div id="view-materi" class="hidden flex-1 w-full bg-white border border-cloud-gray rounded-xl p-6 overflow-y-auto max-h-[600px] prose prose-sm max-w-none">
                    <!-- Materi HTML injected here -->
                </div>

                <!-- Tab 2: Peta Konsep (Mermaid.js) -->
                <div id="view-concept" class="hidden flex-1 w-full flex-col">
                    <p class="text-sm text-graphite mb-3 select-none">Peta konsep divisualisasikan dari diagram alir hubungan topik utama:</p>
                    <div id="mermaid-diagram" class="mermaid flex justify-center p-6 bg-white rounded-xl border-2 border-cloud-gray overflow-auto min-h-[300px]">
                        <!-- Rendered Mermaid diagram here -->
                    </div>
                </div>

                <!-- Tab 3: LKPD Siswa -->
                <div id="view-lkpd" class="hidden flex-1 w-full bg-white border border-cloud-gray rounded-xl p-6 overflow-y-auto max-h-[600px] prose prose-sm max-w-none">
                    <!-- LKPD HTML injected here -->
                </div>

                <!-- Tab 4: Kuis Formatif (Gamified Play) -->
                <div id="view-quiz" class="hidden flex-1 w-full flex-col">
                    <!-- Progress Bar -->
                    <div id="quiz-progress-wrapper" class="w-full bg-cloud-gray h-3 rounded-full overflow-hidden mb-6 hidden select-none">
                        <div id="quiz-progress-bar" class="bg-duo-green h-full w-0 transition-all duration-300"></div>
                    </div>

                    <!-- Quiz Content Frame -->
                    <div id="quiz-content-area" class="flex-1 flex flex-col items-center justify-center">
                        <!-- Quiz screens dynamically injected via JS -->
                    </div>
                </div>

                <!-- Actions Pane -->
                <div id="actions-pane" class="hidden w-full border-t-2 border-cloud-gray pt-4 flex flex-wrap justify-between items-center select-none mt-6 gap-2">
                    <button id="copy-markdown-btn" class="btn-outline text-charcoal border-cloud-gray hover:border-grape-soda hover:text-grape-soda text-xs py-2 px-3 flex items-center space-x-1.5 font-bold rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 14h1.5M9 17h1.5"></path></svg>
                        <span>Salin Salinan Markdown</span>
                    </button>
                    
                    <div class="flex space-x-2">
                        <a id="print-pdf-btn" href="#" target="_blank" class="btn-outline text-charcoal border-cloud-gray hover:border-sky-blue hover:text-sky-blue text-xs py-2 px-3 flex items-center space-x-1.5 font-bold rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>Cetak PDF</span>
                        </a>
                        <a id="download-word-btn" href="#" class="btn-outline text-white bg-grape-soda border-transparent hover:bg-[#965eff] text-xs py-2 px-3 flex items-center space-x-1.5 font-bold rounded-xl transition shadow-[0_3px_0_#804ce6]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Unduh Handout Word</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Gamified sound & effects scripts -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
    <script>
        // Init Mermaid
        mermaid.initialize({ 
            startOnLoad: false, 
            theme: 'forest',
            securityLevel: 'loose',
            flowchart: {
                useMaxWidth: true,
                htmlLabels: true
            }
        });

        // DOM elements
        const form = document.getElementById('generate-form');
        const fillDemoBtn = document.getElementById('fill-demo-btn');
        const submitBtn = document.getElementById('submit-btn');
        const loadingState = document.getElementById('loading-state');
        const emptyState = document.getElementById('empty-state');
        const actionsPane = document.getElementById('actions-pane');
        const saveStatus = document.getElementById('save-status');

        // Tabs
        const tabMateri = document.getElementById('tab-materi');
        const tabConcept = document.getElementById('tab-concept');
        const tabLkpd = document.getElementById('tab-lkpd');
        const tabQuiz = document.getElementById('tab-quiz');

        const viewMateri = document.getElementById('view-materi');
        const viewConcept = document.getElementById('view-concept');
        const viewLkpd = document.getElementById('view-lkpd');
        const viewQuiz = document.getElementById('view-quiz');

        // Actions
        const copyMarkdownBtn = document.getElementById('copy-markdown-btn');
        const printPdfBtn = document.getElementById('print-pdf-btn');
        const downloadWordBtn = document.getElementById('download-word-btn');

        // Quiz State
        const progressWrapper = document.getElementById('quiz-progress-wrapper');
        const progressBar = document.getElementById('quiz-progress-bar');
        const quizArea = document.getElementById('quiz-content-area');

        let currentData = null;
        let quizIndex = 0;
        let correctAnswersCount = 0;

        // --- Demo Data Trigger ---
        fillDemoBtn.addEventListener('click', () => {
            form.subject.value = 'Biologi';
            form.topic.value = 'Metamorfosis Kupu-Kupu';
            form.grade.value = 'SMP Kelas 7';
            form.depth.value = 'menengah';
            form.use_mock.checked = false;
        });

        // --- Tab switching logic ---
        function resetTabs() {
            const tabs = [tabMateri, tabConcept, tabLkpd, tabQuiz];
            tabs.forEach(t => {
                t.className = "text-heading-sm font-feather text-silver cursor-pointer hover:text-charcoal pb-1 flex items-center gap-1.5";
            });
            const views = [viewMateri, viewConcept, viewLkpd, viewQuiz];
            views.forEach(v => v.style.display = 'none');
        }

        tabMateri.addEventListener('click', () => {
            resetTabs();
            tabMateri.className = "text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-grape-soda pb-1 flex items-center gap-1.5";
            viewMateri.style.display = 'block';
        });

        tabConcept.addEventListener('click', () => {
            resetTabs();
            tabConcept.className = "text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-grape-soda pb-1 flex items-center gap-1.5";
            viewConcept.style.display = 'flex';
            // Render concept map diagram
            if (currentData && currentData.concept_map) {
                renderMermaidDiagram(currentData.concept_map);
            }
        });

        tabLkpd.addEventListener('click', () => {
            resetTabs();
            tabLkpd.className = "text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-grape-soda pb-1 flex items-center gap-1.5";
            viewLkpd.style.display = 'block';
        });

        tabQuiz.addEventListener('click', () => {
            resetTabs();
            tabQuiz.className = "text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-grape-soda pb-1 flex items-center gap-1.5";
            viewQuiz.style.display = 'flex';
            // Initialize Quiz
            if (currentData && currentData.quiz) {
                initQuiz();
            }
        });

        // --- Mermaid Renderer ---
        async function renderMermaidDiagram(code) {
            const wrapper = document.getElementById('mermaid-diagram');
            wrapper.removeAttribute('data-processed');
            
            // Clean and sanitize code
            let cleanCode = code.replace(/```mermaid\s*/gi, '').replace(/```/g, '').trim();
            
            // Auto-wrap unquoted node texts in double quotes to prevent Mermaid syntax errors
            // 1. Match node[text] -> node["text"]
            cleanCode = cleanCode.replace(/([a-zA-Z0-9_-]+)\[([^"\n\]][^\n\]]*|[^"\n\]])\]/g, '$1["$2"]');
            
            // 2. Match node{text} -> node{"text"}
            cleanCode = cleanCode.replace(/([a-zA-Z0-9_-]+)\{([^"\n\}][^\n\}]*|[^"\n\}])\}/g, '$1{"$2"}');
            
            // 3. Match node(text) -> node("text")
            cleanCode = cleanCode.replace(/([a-zA-Z0-9_-]+)\(([^"\n\)]+)\)/g, (match, p1, p2) => {
                const lowerId = p1.toLowerCase();
                if (lowerId === 'graph' || lowerId === 'flowchart' || lowerId === 'subgraph') {
                    return match;
                }
                return `${p1}("${p2}")`;
            });

            // 4. Strip semicolons at the end of lines to prevent Mermaid v10+ syntax errors
            cleanCode = cleanCode.replace(/;+\s*$/gm, '');

            wrapper.innerHTML = cleanCode;
            try {
                await mermaid.run({
                    nodes: [wrapper]
                });
            } catch (err) {
                console.error("Mermaid parsing error:", err);
                wrapper.innerHTML = `<div class="text-rose-500 font-bold p-4">Gagal merender peta konsep. Format diagram:</div><pre class="bg-gray-50 border border-cloud-gray p-4 rounded-xl text-left text-xs">${cleanCode}</pre>`;
            }
        }

        // --- Quiz Gamified Logic ---
        function initQuiz() {
            quizIndex = 0;
            correctAnswersCount = 0;
            progressWrapper.classList.remove('hidden');
            renderQuizQuestion();
        }

        function renderQuizQuestion() {
            const totalQuestions = currentData.quiz.length;
            const percentage = (quizIndex / totalQuestions) * 100;
            progressBar.style.width = `${percentage}%`;

            if (quizIndex >= totalQuestions) {
                renderQuizSummary();
                return;
            }

            const q = currentData.quiz[quizIndex];
            
            let optionsHtml = '';
            q.options.forEach((opt, idx) => {
                optionsHtml += `
                    <button onclick="handleOptionSelect(${idx})" class="w-full text-left bg-white border-2 border-cloud-gray rounded-xl p-4 font-bold text-charcoal hover:bg-cloud-gray/10 hover:border-grape-soda transition-all duration-200 shadow-[0_3px_0_#e5e5e5] active:translate-y-[2px] active:shadow-[0_1px_0_#e5e5e5]" id="opt-${idx}">
                        <span class="inline-block w-6 h-6 bg-cloud-gray rounded-full text-center leading-6 text-xs text-charcoal font-black mr-3 uppercase">${String.fromCharCode(65 + idx)}</span>
                        ${opt}
                    </button>
                `;
            });

            quizArea.innerHTML = `
                <div class="w-full max-w-lg space-y-6 animate-[fadeIn_0.5s_ease-out]">
                    <div class="text-center">
                        <span class="text-xs uppercase font-extrabold tracking-wider text-grape-soda bg-grape-soda/10 px-3 py-1 rounded-full border border-grape-soda/20">Soal Ke-${quizIndex + 1} Dari ${totalQuestions}</span>
                        <h4 class="text-lg font-bold text-almost-black mt-3 leading-snug">${q.question}</h4>
                    </div>
                    <div class="space-y-3">
                        ${optionsHtml}
                    </div>
                    <div id="explanation-box" class="hidden bg-snow-white border-2 border-duo-green-light rounded-xl p-4 animate-[slideUp_0.4s_ease-out]">
                        <div class="flex items-center space-x-2 mb-2 text-duo-green font-bold" id="result-title">
                            <!-- Injected icon + status -->
                        </div>
                        <p class="text-sm text-graphite" id="explanation-text">${q.explanation || ''}</p>
                        <button onclick="nextQuizQuestion()" class="mt-4 w-full bg-grape-soda hover:bg-[#965eff] text-white font-bold py-2.5 px-4 rounded-xl shadow-[0_3px_0_#804ce6] transition active:translate-y-[2px] active:shadow-[0_1px_0_#804ce6] text-sm">
                            Lanjutkan
                        </button>
                    </div>
                </div>
            `;
        }

        window.handleOptionSelect = function(selectedIndex) {
            const q = currentData.quiz[quizIndex];
            const correctIndex = q.answer;
            
            // Disable all option buttons
            for (let i = 0; i < q.options.length; i++) {
                const btn = document.getElementById(`opt-${i}`);
                btn.disabled = true;
                if (i === correctIndex) {
                    btn.className = "w-full text-left bg-duo-green-light/20 border-2 border-duo-green rounded-xl p-4 font-bold text-duo-green shadow-[0_3px_0_#58cc02]";
                } else if (i === selectedIndex) {
                    btn.className = "w-full text-left bg-bubblegum-pink/10 border-2 border-bubblegum-pink rounded-xl p-4 font-bold text-bubblegum-pink shadow-[0_3px_0_#cc348d]";
                } else {
                    btn.className = "w-full text-left bg-white border border-cloud-gray rounded-xl p-4 font-bold text-silver opacity-60";
                }
            }

            const expBox = document.getElementById('explanation-box');
            const resTitle = document.getElementById('result-title');
            
            expBox.classList.remove('hidden');

            if (selectedIndex === correctIndex) {
                correctAnswersCount++;
                resTitle.innerHTML = `
                    <svg class="w-6 h-6 text-duo-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Luar Biasa! Jawabanmu Benar ✨</span>
                `;
                expBox.className = "bg-duo-green-light/10 border-2 border-duo-green rounded-xl p-4 animate-[slideUp_0.4s_ease-out] mt-6";
                playCorrectBeep();
            } else {
                resTitle.innerHTML = `
                    <svg class="w-6 h-6 text-bubblegum-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Oops, Kurang Tepat!</span>
                `;
                expBox.className = "bg-bubblegum-pink/5 border-2 border-bubblegum-pink rounded-xl p-4 animate-[slideUp_0.4s_ease-out] mt-6";
                playIncorrectBeep();
            }
        };

        window.nextQuizQuestion = function() {
            quizIndex++;
            renderQuizQuestion();
        };

        function renderQuizSummary() {
            progressWrapper.classList.add('hidden');
            const total = currentData.quiz.length;
            const score = Math.round((correctAnswersCount / total) * 100);
            
            let congratsText = 'Terus belajar dan pertajam pemahamanmu!';
            let trophy = '🥉';
            let color = 'text-silver';

            if (score === 100) {
                congratsText = 'Sempurna! Kamu menguasai materi ini dengan luar biasa! ✨';
                trophy = '🏆';
                color = 'text-sunshine-yellow';
            } else if (score >= 70) {
                congratsText = 'Hebat sekali! Pemahamanmu terhadap materi sangat baik! 👏';
                trophy = '🥈';
                color = 'text-sky-blue';
            }

            quizArea.innerHTML = `
                <div class="w-full max-w-md text-center p-8 bg-[#f9f9f9] border-2 border-cloud-gray rounded-2xl animate-[fadeIn_0.5s_ease-out] space-y-6">
                    <div class="text-6xl">${trophy}</div>
                    <div class="space-y-2">
                        <h4 class="text-2xl font-feather text-almost-black">Kuis Selesai!</h4>
                        <p class="text-graphite font-bold text-sm px-4">${congratsText}</p>
                    </div>
                    
                    <div class="inline-block bg-white border-2 border-cloud-gray rounded-2xl px-8 py-4 shadow-sm select-none">
                        <div class="text-xs uppercase font-extrabold tracking-wider text-silver">Skor Anda</div>
                        <div class="text-5xl font-black ${color} mt-1">${score}</div>
                        <div class="text-xs text-graphite font-semibold mt-1">${correctAnswersCount} dari ${total} jawaban benar</div>
                    </div>

                    <button onclick="initQuiz()" class="w-full bg-grape-soda hover:bg-[#965eff] text-white font-bold py-3 px-6 rounded-xl shadow-[0_4px_0_#804ce6] transition active:translate-y-[2px] active:shadow-[0_1px_0_#804ce6] text-sm">
                        Ulangi Kuis
                    </button>
                </div>
            `;
        }

        // --- Dynamic Audio Beeps ---
        function playCorrectBeep() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(523.25, ctx.currentTime); // C5
                osc.frequency.setValueAtTime(659.25, ctx.currentTime + 0.1); // E5
                osc.frequency.setValueAtTime(783.99, ctx.currentTime + 0.2); // G5
                
                gain.gain.setValueAtTime(0.1, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.45);
                
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.5);
            } catch(e) {}
        }

        function playIncorrectBeep() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(220, ctx.currentTime); // A3
                osc.frequency.setValueAtTime(147, ctx.currentTime + 0.15); // D3
                
                gain.gain.setValueAtTime(0.1, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
                
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.45);
            } catch(e) {}
        }

        // --- Render Document Flow ---
        function renderDocument(data, isFromHistory = false) {
            currentData = data;

            // Render Tab 1 (Materi)
            if (data.materi) {
                viewMateri.innerHTML = marked.parse(data.materi);
            } else {
                viewMateri.innerHTML = '<p class="text-silver">Tidak ada materi ajar.</p>';
            }

            // Render Tab 3 (LKPD)
            if (data.lkpd) {
                viewLkpd.innerHTML = marked.parse(data.lkpd);
            } else {
                viewLkpd.innerHTML = '<p class="text-silver">Tidak ada Lembar Kerja Peserta Didik.</p>';
            }

            // UI adjustments
            emptyState.style.display = 'none';
            loadingState.style.display = 'none';
            actionsPane.classList.remove('hidden');
            tabMateri.click();

            if (!isFromHistory) {
                saveStatus.textContent = 'Tersimpan';
                saveStatus.className = 'text-xs font-bold text-duo-green bg-duo-green/10 px-2.5 py-1 rounded-full border border-duo-green/20';
            }
        }

        // Page Initial Load (Load Document from DB history)
        @if(isset($document))
            try {
                const docData = {!! $document->content !!};
                
                // Bind print / download actions
                printPdfBtn.href = "{{ route('documents.print', $document->id) }}";
                downloadWordBtn.href = "{{ route('documents.download', $document->id) }}";

                renderDocument(docData, true);
                
                saveStatus.textContent = 'Riwayat';
                saveStatus.className = 'text-xs font-bold text-sky-blue bg-sky-blue/10 px-2.5 py-1 rounded-full border border-sky-blue/20';
            } catch (e) {
                console.error("Failed to load document from database", e);
            }
        @endif

        // --- Form Submit Ajax ---
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Set UI to loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-pulse">Menghubungi AI...</span>';
            emptyState.style.display = 'none';
            viewMateri.style.display = 'none';
            viewConcept.style.display = 'none';
            viewLkpd.style.display = 'none';
            viewQuiz.style.display = 'none';
            actionsPane.classList.add('hidden');
            loadingState.style.display = 'flex';

            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route("tools.generate-bahan-ajar-utama-submit") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errText = await response.text();
                    console.error("Server error response:", errText);
                    throw new Error(`Server error ${response.status}: ${errText.substring(0, 100)}`);
                }

                const res = await response.json();
                
                if (res.status === 'success') {
                    // Update actions link URLs
                    if (res.document_id) {
                        printPdfBtn.href = `/documents/${res.document_id}/print`;
                        downloadWordBtn.href = `/documents/${res.document_id}/download`;
                    }
                    renderDocument(res.data, false);
                } else {
                    alert('Gagal membuat bahan ajar: ' + (res.message || 'Error tidak diketahui'));
                    emptyState.style.display = 'flex';
                }
            } catch (error) {
                console.error("AJAX Error:", error);
                alert('Terjadi kesalahan jaringan atau server saat memproses data.\nDetail: ' + error.message);
                emptyState.style.display = 'flex';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Buat Bahan Ajar Lengkap';
                loadingState.style.display = 'none';
            }
        });

        // --- Copy Markdown Feature ---
        copyMarkdownBtn.addEventListener('click', () => {
            if (!currentData) return;
            
            let markdownText = `# ${currentData.title}\n\n`;
            
            if (currentData.materi) {
                markdownText += `## I. Rangkuman Materi\n\n${currentData.materi}\n\n`;
            }
            if (currentData.concept_map) {
                markdownText += `## II. Peta Konsep (Mermaid.js Flowchart)\n\n\`\`\`mermaid\n${currentData.concept_map}\n\`\`\`\n\n`;
            }
            if (currentData.lkpd) {
                markdownText += `## III. Lembar Kerja Peserta Didik (LKPD)\n\n${currentData.lkpd}\n\n`;
            }
            if (currentData.quiz) {
                markdownText += `## IV. Kuis Formatif & Kunci Jawaban\n\n`;
                currentData.quiz.forEach((q, idx) => {
                    markdownText += `### Pertanyaan ${idx+1}\n${q.question}\n`;
                    q.options.forEach((o, oIdx) => {
                        const isCorrect = oIdx === q.answer ? ' (Kunci Jawaban)' : '';
                        markdownText += `- ${o}${isCorrect}\n`;
                    });
                    markdownText += `*Penjelasan: ${q.explanation}*\n\n`;
                });
            }

            navigator.clipboard.writeText(markdownText).then(() => {
                alert('Seluruh paket bahan ajar berhasil disalin ke clipboard!');
            }).catch(err => {
                console.error('Failed to copy text', err);
            });
        });
    </script>

    <!-- Slide & Fade In Keyframes CSS -->
    <style>
        @keyframes slideUp { from { transform: translateY(16px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        .prose strong { color: #3c3c3c; font-weight: 800; }
        .prose h1, .prose h2, .prose h3 { font-family: 'feather', sans-serif; color: #3c3c3c; }
        .prose blockquote { border-left-color: #a570ff; background-color: #f9f9f9; padding: 12px 18px; border-radius: 12px; font-style: italic; }
        
        /* Mermaid preview responsive scaling */
        .mermaid {
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            overflow: hidden !important;
        }
        .mermaid svg {
            max-width: 100% !important;
            height: auto !important;
        }
    </style>
@endsection
