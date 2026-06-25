@extends('layouts.app')

@section('title', 'PPT Pembelajaran AI - PandAI')

@section('content')
    <!-- Header Page -->
    <section class="select-none">
        <h2 class="text-heading font-feather text-almost-black">Generator PPT Pembelajaran AI</h2>
        <p class="text-body text-graphite">Rancang struktur slide presentasi mengajar secara cepat dan interaktif langsung di browser.</p>
    </section>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Options Form -->
        <div class="col-span-1 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm space-y-5">
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-3 select-none">
                <h3 class="text-heading-sm font-feather text-almost-black">Pengaturan Slide</h3>
                <button type="button" id="fill-demo-btn" class="text-xs font-bold text-sky-blue hover:underline">Isi Contoh</button>
            </div>
            
            <form id="generate-form" class="space-y-4">
                @csrf

                <!-- Subject -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Mata Pelajaran</label>
                    <input type="text" name="subject" placeholder="Contoh: IPA, Sejarah" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-bubblegum-pink bg-[#f9f9f9] transition">
                </div>

                <!-- Topic -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Topik / Judul Utama</label>
                    <input type="text" name="topic" placeholder="Contoh: Energi Terbarukan, Proklamasi" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-bubblegum-pink bg-[#f9f9f9] transition">
                </div>

                <!-- Grade -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Jenjang / Kelas</label>
                    <select name="grade" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-bubblegum-pink bg-[#f9f9f9] transition">
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

                <!-- Difficulty -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Tingkat Pemahaman</label>
                    <select name="difficulty" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-bubblegum-pink bg-[#f9f9f9] transition">
                        <option>Dasar (Pengenalan)</option>
                        <option selected>Menengah (Analisis Ringkas)</option>
                        <option>Mendalam (Tingkat Tinggi/HOTS)</option>
                    </select>
                </div>

                <!-- Slides Count -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Jumlah Slide</label>
                    <select name="slides_count" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-bubblegum-pink bg-[#f9f9f9] transition">
                        <option value="5">5 Slide (Singkat)</option>
                        <option value="8" selected>8 Slide (Standar)</option>
                        <option value="12">12 Slide (Detil)</option>
                        <option value="15">15 Slide (Komprehensif)</option>
                    </select>
                </div>

                <!-- Theme / Template Colors -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Tema Warna Slide</label>
                    <select name="theme" id="theme-select" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-bubblegum-pink bg-[#f9f9f9] transition">
                        <option value="duo-green" selected>Natural Sage (Green)</option>
                        <option value="sky-blue">Classic Navy (Blue)</option>
                        <option value="sunshine-yellow">Terracotta Sand (Warm Orange)</option>
                        <option value="playful-pink">Modern Berry (Pink/Plum)</option>
                    </select>
                </div>

                <!-- Mode Demo (Hemat Kuota AI) -->
                <div class="hidden flex items-center space-x-2 pt-2 select-none">
                    <input type="checkbox" id="use-mock" name="use_mock" value="1"
                           class="w-4 h-4 text-bubblegum-pink border-cloud-gray rounded focus:ring-bubblegum-pink">
                    <label for="use-mock" class="text-xs font-bold text-graphite cursor-pointer">
                        Mode Demo (Hemat Kuota AI)
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submit-btn" class="btn-3d-primary w-full text-sm py-3 tracking-wider bg-bubblegum-pink hover:bg-bubblegum-pink shadow-[0_4px_0_#912464]">
                    Rancang Slide PPT
                </button>
            </form>
        </div>

        <!-- Right: Slide Editor & Preview Area -->
        <div class="lg:col-span-2 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm min-h-[500px] flex flex-col justify-between">
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-4 mb-4 select-none">
                <h3 class="text-heading-sm font-feather text-almost-black">Slide Editor (Draf Kerja)</h3>
                <span id="save-status" class="text-xs font-bold text-silver bg-[#f9f9f9] px-2.5 py-1 rounded-full border border-cloud-gray">Belum Disimpan</span>
            </div>

            <!-- Workspace Area -->
            <div id="editor-workspace" class="flex-1 flex flex-col space-y-6">
                
                <!-- Initial Empty State -->
                <div id="empty-state" class="flex-1 flex flex-col items-center justify-center text-center p-8 space-y-4">
                    <div class="w-20 h-20 rounded-2xl bg-bubblegum-pink/10 flex items-center justify-center text-bubblegum-pink">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    </div>
                    <div class="max-w-md space-y-2">
                        <h4 class="font-bold text-charcoal text-lg">Siap Merancang PPT Pembelajaran?</h4>
                        <p class="text-graphite text-sm">Masukkan topik mata pelajaran Anda di sisi kiri dan klik Rancang. Anda akan mendapatkan slide draf yang bisa disunting secara interaktif di layar ini.</p>
                    </div>
                </div>

                <!-- Editor Slide Canvas & Navigation (Hidden until generation) -->
                <div id="slide-editor-container" class="hidden flex flex-col space-y-4">
                    
                    <!-- Slide Canvas Area (Mimicking 16:9 Aspect Ratio) -->
                    <div id="slide-canvas" style="container-type: inline-size;" class="relative w-full aspect-video border-2 border-cloud-gray rounded-2xl overflow-hidden bg-white shadow-md select-none transition-colors duration-300">
                        
                        <!-- Top/Left Accent Background Shape -->
                        <div id="slide-theme-bg" class="absolute top-0 left-0"></div>
                        <div id="slide-theme-accent" class="absolute"></div>

                        <!-- Main Content Wrapper -->
                        <div id="slide-content-wrapper" class="absolute inset-0 px-6 py-4 md:px-8 md:py-6 flex flex-col">
                            
                            <!-- Header / Title -->
                            <div id="slide-header-box" class="w-full relative z-10 mb-4">
                                <h2 id="slide-title" contenteditable="true" class="font-bold font-feather text-charcoal outline-none border-b border-transparent hover:border-white/50 focus:border-white py-1"></h2>
                            </div>
                            
                            <!-- Body Area (Split Left/Right) -->
                            <div class="flex-1 flex w-full relative z-10">
                                
                                <!-- Left Text Area -->
                                <div id="slide-left-area" class="w-full md:w-[60%] flex flex-col justify-center h-full pr-4 md:pr-6">
                                    <ul id="slide-bullets" contenteditable="true" class="list-disc pl-6 text-graphite outline-none border border-transparent hover:border-cloud-gray focus:border-bubblegum-pink p-2 rounded-xl">
                                    </ul>
                                </div>
                                
                                <!-- Right Image Area -->
                                <div id="slide-right-area" class="w-full md:w-[40%] h-full flex justify-end items-center relative">
                                    <!-- Offset Shadow Base -->
                                    <div id="slide-image-shadow" class="absolute"></div>
                                    <!-- Image Container -->
                                    <div id="slide-image-container" class="relative">
                                        <img id="slide-image-preview" src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=300&auto=format&fit=crop&q=60" class="absolute inset-0 w-full h-full object-cover opacity-90 transition-opacity" alt="Slide Graphic">
                                        <div class="absolute inset-0 bg-black/20 flex items-center justify-center p-3 text-center">
                                            <span id="slide-image-keyword" class="text-[9px] font-bold text-white uppercase tracking-wider bg-black/60 px-2 py-1 rounded-md border border-white/20 select-none">keyword</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div id="slide-footer" class="absolute bottom-3 left-6 right-6 flex justify-between text-silver select-none z-10">
                                <span>PandAI Assistant</span>
                                <span id="slide-number-indicator">Slide 1 dari 5</span>
                            </div>
                        </div>
                    </div>

                    <!-- Slide Controller (Prev/Next, Fullscreen) -->
                    <div class="flex justify-between items-center select-none bg-[#fbfbfb] px-4 py-3 rounded-xl border border-cloud-gray">
                        <div class="flex space-x-2">
                            <button id="prev-slide-btn" class="btn-outline px-3 py-1.5 text-xs text-charcoal hover:border-charcoal">&larr; Slide Sebelumnya</button>
                            <button id="next-slide-btn" class="btn-outline px-3 py-1.5 text-xs text-charcoal hover:border-charcoal">Slide Selanjutnya &rarr;</button>
                        </div>
                        <button id="fullscreen-btn" class="btn-outline px-3 py-1.5 text-xs border-bubblegum-pink text-bubblegum-pink flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                            Tampilkan Layar Penuh
                        </button>
                    </div>

                    <!-- Presenter Notes Area (Editable) -->
                    <div class="space-y-1.5 border border-cloud-gray rounded-xl p-4 bg-[#f9f9f9]">
                        <label class="block text-xs font-bold text-graphite uppercase tracking-wider select-none">Catatan Guru (Presenter Notes)</label>
                        <textarea id="slide-notes" rows="2" class="w-full text-xs text-charcoal bg-transparent outline-none resize-none border border-transparent hover:border-cloud-gray focus:border-bubblegum-pink p-1.5 rounded-lg transition" placeholder="Masukkan catatan penjelasan presentasi Anda di sini..."></textarea>
                    </div>

                    <!-- Slide Thumbnails Grid -->
                    <div class="space-y-2 select-none">
                        <label class="block text-xs font-bold text-graphite uppercase tracking-wider">Daftar Urutan Slide</label>
                        <div id="slide-thumbnails" class="flex space-x-3 overflow-x-auto py-2 pr-1">
                            <!-- Populated dynamically -->
                        </div>
                    </div>

                </div>
            </div>

            <!-- Action Buttons (Disabled until generation) -->
            <div id="actions-pane" class="border-t-2 border-cloud-gray pt-4 flex justify-end space-x-3 select-none">
                <button id="download-pptx" class="btn-outline text-xs px-4 py-2 border-cloud-gray text-silver pointer-events-none">Unduh PPTX Premium</button>
            </div>
        </div>

    </div>

    <!-- Loading Modal Overlay -->
    <div id="loading-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-almost-black/40 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-8 max-w-sm w-full shadow-2xl flex flex-col items-center text-center space-y-4">
            <div class="w-16 h-16 border-4 border-bubblegum-pink border-t-transparent rounded-full animate-spin"></div>
            <div class="space-y-2">
                <h4 class="font-bold text-charcoal text-lg">Merancang Slide AI...</h4>
                <p class="text-graphite text-sm animate-pulse">Menghubungi Asisten AI Gemini untuk memformulasikan outline, struktur teks, dan kata kunci slide.</p>
            </div>
        </div>
    </div>

    <!-- spacer -->
    <div class="h-12"></div>
@endsection

@section('scripts')
<script>
    const fillDemoBtn = document.getElementById('fill-demo-btn');
    const generateForm = document.getElementById('generate-form');
    const submitBtn = document.getElementById('submit-btn');
    const emptyState = document.getElementById('empty-state');
    const loadingModal = document.getElementById('loading-modal');
    const saveStatus = document.getElementById('save-status');
    
    // Editor controls
    const workspaceContainer = document.getElementById('slide-editor-container');
    const slideCanvas = document.getElementById('slide-canvas');
    const slideThemeAccent = document.getElementById('slide-theme-accent');
    const slideTitle = document.getElementById('slide-title');
    const slideBullets = document.getElementById('slide-bullets');
    const slideImageKeyword = document.getElementById('slide-image-keyword');
    const slideImagePreview = document.getElementById('slide-image-preview');
    const slideNotes = document.getElementById('slide-notes');
    const slideNumberIndicator = document.getElementById('slide-number-indicator');
    
    const prevSlideBtn = document.getElementById('prev-slide-btn');
    const nextSlideBtn = document.getElementById('next-slide-btn');
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const thumbnailsContainer = document.getElementById('slide-thumbnails');
    const downloadPptxBtn = document.getElementById('download-pptx');

    // State Variables
    let slidesData = [];
    let activeSlideIndex = 0;
    let mainTopic = '';

    // Color Theme Definitions
    const themes = {
        'duo-green': { 
            name: 'Natural Sage', 
            primary: '#1b4332', 
            secondary: '#d8f3dc', 
            accent: '#40916c', 
            hexPrimary: '1B4332', 
            hexSecondary: 'D8F3DC', 
            hexAccent: '40916C' 
        },
        'sky-blue': { 
            name: 'Classic Navy', 
            primary: '#0f172a', 
            secondary: '#e2e8f0', 
            accent: '#38bdf8', 
            hexPrimary: '0F172A', 
            hexSecondary: 'E2E8F0', 
            hexAccent: '38BDF8' 
        },
        'sunshine-yellow': { 
            name: 'Terracotta Sand', 
            primary: '#3d348b', 
            secondary: '#f7ede2', 
            accent: '#f7b267', 
            hexPrimary: '3D348B', 
            hexSecondary: 'F7EDE2', 
            hexAccent: 'F7B267' 
        },
        'playful-pink': { 
            name: 'Modern Berry', 
            primary: '#2d0f2f', 
            secondary: '#fae8ff', 
            accent: '#db2777', 
            hexPrimary: '2D0F2F', 
            hexSecondary: 'FAE8FF', 
            hexAccent: 'DB2777' 
        }
    };

    // Unsplash High Quality fallbacks
    const themeFallbacks = {
        'duo-green': 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800&auto=format&fit=crop&q=80',
        'sky-blue': 'https://images.unsplash.com/photo-1518156677180-95a2893f3e9f?w=800&auto=format&fit=crop&q=80',
        'sunshine-yellow': 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&auto=format&fit=crop&q=80',
        'playful-pink': 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop&q=80'
    };

    // Curated Unsplash images that are guaranteed to look stunning, support CORS, and relate to school subjects
    const curatedImages = {
        'water': 'https://images.unsplash.com/photo-1438449805896-28a666819a20?w=800',
        'cycle': 'https://images.unsplash.com/photo-1438449805896-28a666819a20?w=800',
        'hidrologi': 'https://images.unsplash.com/photo-1438449805896-28a666819a20?w=800',
        'hujan': 'https://images.unsplash.com/photo-1534274988757-a28bf1a57c17?w=800',
        'science': 'https://images.unsplash.com/photo-1507668077129-56e32842fceb?w=800',
        'sains': 'https://images.unsplash.com/photo-1507668077129-56e32842fceb?w=800',
        'ipa': 'https://images.unsplash.com/photo-1507668077129-56e32842fceb?w=800',
        'history': 'https://images.unsplash.com/photo-1447069387593-a5de0862481e?w=800',
        'sejarah': 'https://images.unsplash.com/photo-1447069387593-a5de0862481e?w=800',
        'indonesia': 'https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?w=800',
        'merdeka': 'https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?w=800',
        'plant': 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=800',
        'tumbuhan': 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=800',
        'daun': 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=800',
        'fotosintesis': 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=800',
        'nature': 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800',
        'math': 'https://images.unsplash.com/photo-1509228468518-180dd4864904?w=800',
        'matematika': 'https://images.unsplash.com/photo-1509228468518-180dd4864904?w=800',
        'angka': 'https://images.unsplash.com/photo-1509228468518-180dd4864904?w=800',
        'physics': 'https://images.unsplash.com/photo-1507668077129-56e32842fceb?w=800',
        'fisika': 'https://images.unsplash.com/photo-1507668077129-56e32842fceb?w=800',
        'chemistry': 'https://images.unsplash.com/photo-1532187643603-ba119ca4109e?w=800',
        'kimia': 'https://images.unsplash.com/photo-1532187643603-ba119ca4109e?w=800',
        'computer': 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800',
        'komputer': 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800',
        'coding': 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800',
        'school': 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800',
        'sekolah': 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800',
        'belajar': 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800',
        'classroom': 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800',
        'books': 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800',
        'buku': 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800',
        'geografi': 'https://images.unsplash.com/photo-1524661135-423995f22d0b?w=800',
        'bumi': 'https://images.unsplash.com/photo-1524661135-423995f22d0b?w=800',
        'social': 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800',
        'ips': 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800'
    };

    // Asynchronously resolve image URLs for slides
    async function getSafeImageUrl(keyword, themeName, idx) {
        const cleanKey = keyword ? keyword.toLowerCase().trim() : '';
        
        if (cleanKey) {
            // Gunakan Picsum photos dengan seed berupa keyword + index agar selalu unik per slide
            const seed = encodeURIComponent(cleanKey + idx);
            return `https://picsum.photos/seed/${seed}/800/600`;
        }

        // Fallback jika tidak ada keyword
        return `https://picsum.photos/seed/slide${idx}/800/600`;
    }

    async function resolveSlideImages(slides, themeName) {
        const promises = slides.map(async (slide, idx) => {
            slide.imageUrl = await getSafeImageUrl(slide.image_keyword || mainTopic, themeName, idx);
        });
        await Promise.all(promises);
    }

    // Load school name from localStorage to sync and handle document loading from history
    document.addEventListener('DOMContentLoaded', async () => {
        const schoolName = localStorage.getItem('sa_school_name');
        
        @if(isset($document))
            // Pre-populate slide data from database history
            try {
                const parsed = {!! $document->content !!};
                slidesData = parsed.slides || [];
                activeSlideIndex = 0;
                mainTopic = "{{ $document->name }}".replace(/^PPT\s*/, '');
                
                // Display slide editor workspace
                emptyState.classList.add('hidden');
                workspaceContainer.classList.remove('hidden');
                
                // Resolve images and render
                saveStatus.innerText = 'Memuat Gambar...';
                saveStatus.className = "text-xs font-bold text-silver bg-[#f9f9f9] px-2.5 py-1 rounded-full border border-cloud-gray animate-pulse";
                
                await resolveSlideImages(slidesData, document.getElementById('theme-select').value);
                renderActiveSlide();
                
                saveStatus.innerText = 'Tersimpan (Dari Riwayat)';
                saveStatus.className = "text-xs font-bold text-duo-green bg-duo-green-light/25 px-2.5 py-1 rounded-full border border-duo-green";
                
                downloadPptxBtn.classList.remove('pointer-events-none', 'text-silver');
                downloadPptxBtn.classList.add('text-bubblegum-pink', 'border-bubblegum-pink', 'hover:bg-[#cc348d]/10');
            } catch (e) {
                console.error("Failed to load historical document: ", e);
            }
        @endif
    });

    // Fill Demo Data
    fillDemoBtn.addEventListener('click', () => {
        generateForm.elements['subject'].value = "IPAS (Sains)";
        generateForm.elements['topic'].value = "Siklus Air & Hidrologi Bumi";
        generateForm.elements['grade'].value = "Kelas 5 SD";
        generateForm.elements['difficulty'].value = "Menengah (Analisis Ringkas)";
        generateForm.elements['slides_count'].value = "8";
        document.getElementById('theme-select').value = "sky-blue";
    });

    // 1. Render Active Slide in Editor Canvas
    function renderActiveSlide() {
        if (!slidesData || slidesData.length === 0) return;
        const slide = slidesData[activeSlideIndex];
        const themeName = document.getElementById('theme-select').value;
        const theme = themes[themeName] || themes['duo-green'];

        // Synchronize school details on Title Slide (Slide 1)
        if (activeSlideIndex === 0) {
            const schoolName = localStorage.getItem('sa_school_name') || '';
            const teacherName = localStorage.getItem('sa_teacher_name') || '';
            if (teacherName || schoolName) {
                let infoText = 'Disusun oleh: ';
                if (teacherName) infoText += teacherName;
                if (schoolName) infoText += (teacherName ? ` di ${schoolName}` : schoolName);
                
                // Prefill or update slide cover author line if it matches the mock or default
                if (slide.bullets && slide.bullets.length > 2 && 
                    (slide.bullets[2].includes("Mari kita eksplorasi") || slide.bullets[2].includes("Disusun oleh:"))) {
                    slide.bullets[2] = infoText;
                }
            }
        }

        // Apply theme color to canvas accent
        const slideThemeBg = document.getElementById('slide-theme-bg');
        const slideThemeAccent = document.getElementById('slide-theme-accent');
        const slideLeftArea = document.getElementById('slide-left-area');
        const slideRightArea = document.getElementById('slide-right-area');
        const slideImageContainer = document.getElementById('slide-image-container');
        const slideImageShadow = document.getElementById('slide-image-shadow');
        const slideHeaderBox = document.getElementById('slide-header-box');
        const slideFooter = document.getElementById('slide-footer');

        if (activeSlideIndex === 0) {
            // COVER SLIDE PREVIEW (Mimicking Split Canvas)
            slideCanvas.style.backgroundColor = '#FAFAFB';
            
            // Left split block
            slideThemeBg.className = 'absolute top-0 left-0 h-full w-[50%] z-0';
            slideThemeBg.style.backgroundColor = theme.primary;
            
            // Accent line
            slideThemeAccent.className = 'absolute top-[18%] left-[6%] w-[10%] h-[1%] z-0';
            slideThemeAccent.style.backgroundColor = theme.accent;

            slideHeaderBox.className = 'w-[50%] relative z-10 mt-[15%] pl-[2cqw]';
            slideTitle.className = "font-bold font-feather text-white outline-none border-b border-transparent py-1 leading-tight";
            slideTitle.style.color = '#FFFFFF';
            slideTitle.style.fontSize = '3.5cqw';

            slideLeftArea.className = 'w-[50%] flex flex-col justify-end pb-[6cqw] pl-[2cqw] pr-[3cqw]';
            slideBullets.className = "list-none outline-none";
            slideBullets.style.color = theme.secondary;
            slideBullets.style.fontSize = '1.4cqw';
            slideBullets.style.lineHeight = '2cqw';
            slideBullets.style.gap = '1cqw';
            slideBullets.style.display = 'flex';
            slideBullets.style.flexDirection = 'column';

            // Full bleed right image
            slideRightArea.className = 'absolute top-0 right-0 w-[50%] h-full z-0 block';
            slideImageShadow.style.display = 'none';
            slideImageContainer.className = 'absolute inset-0 w-full h-full';
            slideImageContainer.style.borderRadius = '0';
            slideImageContainer.style.border = 'none';

            slideFooter.style.display = 'none';
        } else {
            // CONTENT SLIDE PREVIEW
            slideCanvas.style.backgroundColor = '#FAFAFB';
            
            // Top Header Bar
            slideThemeBg.className = 'absolute top-0 left-0 w-full h-[15%] z-0';
            slideThemeBg.style.backgroundColor = theme.primary;
            
            // Subtle Shadow Line
            slideThemeAccent.className = 'absolute top-[15%] left-0 w-full h-[1%] z-0';
            slideThemeAccent.style.backgroundColor = theme.accent;

            slideHeaderBox.className = 'w-full relative z-10 flex items-center h-[12%] -mt-1 pl-[2cqw]';
            slideTitle.className = "font-bold font-feather text-white outline-none border-b border-transparent py-1 truncate";
            slideTitle.style.color = '#FFFFFF';
            slideTitle.style.fontSize = '2.5cqw';

            slideLeftArea.className = 'w-[60%] flex flex-col justify-center h-full pr-[3cqw] pt-2 pl-[2cqw]';
            slideBullets.className = "list-disc outline-none font-medium leading-relaxed";
            slideBullets.style.color = '#333333';
            slideBullets.style.fontSize = '1.6cqw';
            slideBullets.style.paddingLeft = '3cqw';
            slideBullets.style.lineHeight = '2.5cqw';
            slideBullets.style.gap = '1.5cqw';
            slideBullets.style.display = 'flex';
            slideBullets.style.flexDirection = 'column';

            // Floating right image
            slideRightArea.className = 'w-[40%] h-full relative block';
            slideImageShadow.style.display = 'block';
            slideImageShadow.className = 'absolute w-[80%] h-[70%] rounded-xl left-[12%] top-[18%]';
            slideImageShadow.style.backgroundColor = theme.secondary;
            
            slideImageContainer.className = 'absolute w-[80%] h-[70%] rounded-xl overflow-hidden shadow-lg border-2 border-white bg-[#f9f9f9] left-[10%] top-[15%]';
            
            slideFooter.style.display = 'flex';
            slideFooter.style.fontSize = '1cqw';
        }

        // Render Text Content
        slideTitle.innerText = slide.title || 'Judul Slide';
        
        // Render Bullets List
        slideBullets.innerHTML = '';
        if (slide.bullets && slide.bullets.length > 0) {
            slide.bullets.forEach((bullet, idx) => {
                const li = document.createElement('li');
                let cleanBullet = bullet.replace(/^[📚🏫👤]\s*/u, '');
                
                if (activeSlideIndex === 0) {
                    if (idx === 0) {
                        li.innerHTML = `📚 <span class="font-bold text-charcoal">Mata Pelajaran:</span> ${cleanBullet.replace(/^Mata Pelajaran:\s*/i, '')}`;
                    } else if (idx === 1) {
                        li.innerHTML = `🏫 <span class="font-bold text-charcoal">Kelas:</span> ${cleanBullet.replace(/^Kelas:\s*/i, '')}`;
                    } else if (idx === 2) {
                        li.innerHTML = `👤 <span class="italic text-charcoal font-medium">${cleanBullet}</span>`;
                    } else {
                        li.innerText = cleanBullet;
                    }
                } else {
                    li.innerText = cleanBullet;
                }
                slideBullets.appendChild(li);
            });
        } else {
            const li = document.createElement('li');
            li.innerText = '[Klik untuk menambahkan poin penting...]';
            slideBullets.appendChild(li);
        }

        // Render Notes
        slideNotes.value = slide.notes || '';

        // Render Image Keywords & Preview
        slideImageKeyword.innerText = slide.image_keyword || 'education';
        
        // Use pre-resolved or fallback image
        slideImagePreview.src = slide.imageUrl || themeFallbacks[themeName] || themeFallbacks['duo-green'];

        // Update indicators
        slideNumberIndicator.innerText = `Slide ${activeSlideIndex + 1} dari ${slidesData.length}`;

        // Disable/enable switcher buttons
        prevSlideBtn.disabled = activeSlideIndex === 0;
        nextSlideBtn.disabled = activeSlideIndex === slidesData.length - 1;

        // Refresh Thumbnail grid highlight
        renderThumbnails();
    }

    // 2. Render Slide Thumbnail Strip
    function renderThumbnails() {
        thumbnailsContainer.innerHTML = '';
        const themeName = document.getElementById('theme-select').value;
        const theme = themes[themeName] || themes['duo-green'];

        slidesData.forEach((slide, idx) => {
            const thumb = document.createElement('div');
            const isActive = idx === activeSlideIndex;
            
            thumb.className = `w-28 flex-shrink-0 aspect-video rounded-lg border-2 p-2 bg-white flex flex-col justify-between cursor-pointer transition-all ${
                isActive ? 'border-bubblegum-pink shadow-md ring-2 ring-bubblegum-pink/20' : 'border-cloud-gray hover:border-silver'
            }`;
            
            thumb.innerHTML = `
                <div class="text-[9px] font-bold text-charcoal truncate">${slide.title || 'Slide ' + (idx + 1)}</div>
                <div class="text-[8px] text-silver text-right">#${idx + 1}</div>
            `;

            thumb.addEventListener('click', () => {
                saveCurrentSlideEdits();
                activeSlideIndex = idx;
                renderActiveSlide();
            });

            thumbnailsContainer.appendChild(thumb);
        });
    }

    // 3. Save Active Slide edits back to memory data array
    function saveCurrentSlideEdits() {
        if (!slidesData || slidesData.length === 0) return;
        
        const titleVal = slideTitle.innerText.trim();
        const bulletItems = Array.from(slideBullets.getElementsByTagName('li')).map(li => {
            let text = li.innerText.trim();
            // Strip dynamically-added cover emojis so they don't compound
            if (activeSlideIndex === 0) {
                text = text.replace(/^[📚🏫👤]\s*/u, '');
            }
            return text;
        });
        const notesVal = slideNotes.value;

        slidesData[activeSlideIndex].title = titleVal;
        slidesData[activeSlideIndex].bullets = bulletItems;
        slidesData[activeSlideIndex].notes = notesVal;
    }

    // Attach local input save listeners
    slideTitle.addEventListener('blur', saveCurrentSlideEdits);
    slideBullets.addEventListener('blur', saveCurrentSlideEdits);
    slideNotes.addEventListener('input', saveCurrentSlideEdits);

    // Slide Navigation
    prevSlideBtn.addEventListener('click', () => {
        saveCurrentSlideEdits();
        if (activeSlideIndex > 0) {
            activeSlideIndex--;
            renderActiveSlide();
        }
    });

    nextSlideBtn.addEventListener('click', () => {
        saveCurrentSlideEdits();
        if (activeSlideIndex < slidesData.length - 1) {
            activeSlideIndex++;
            renderActiveSlide();
        }
    });

    // Fullscreen Logic
    fullscreenBtn.addEventListener('click', () => {
        const previewContainer = document.getElementById('slide-preview-container');
        if (!document.fullscreenElement) {
            if (previewContainer.requestFullscreen) {
                previewContainer.requestFullscreen();
            } else if (previewContainer.webkitRequestFullscreen) { /* Safari */
                previewContainer.webkitRequestFullscreen();
            } else if (previewContainer.msRequestFullscreen) { /* IE11 */
                previewContainer.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) { /* Safari */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) { /* IE11 */
                document.msExitFullscreen();
            }
        }
    });

    // 4. Form Submission & Generation
    generateForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // UI States
        emptyState.classList.add('hidden');
        workspaceContainer.classList.add('hidden');
        
        // Show Loading Modal
        loadingModal.classList.remove('hidden');
        loadingModal.classList.add('flex');
        
        submitBtn.disabled = true;
        submitBtn.innerText = 'Rancangan Slide Sedang Berjalan...';
        downloadPptxBtn.classList.add('pointer-events-none', 'text-silver');

        try {
            const formData = new FormData(generateForm);
            const dataObj = {};
            formData.forEach((value, key) => dataObj[key] = value);

            dataObj['slides_count'] = parseInt(dataObj['slides_count']);

            const response = await fetch('/tools/generate-ppt', {
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
                // Parse returned JSON contents
                const parsedContent = JSON.parse(result.content);
                slidesData = parsedContent.slides || [];
                activeSlideIndex = 0;
                mainTopic = dataObj['topic'] || 'Pembelajaran';

                // Resolve image URLs asynchronously
                await resolveSlideImages(slidesData, dataObj['theme']);

                // Display slide editor workspace
                workspaceContainer.classList.remove('hidden');
                renderActiveSlide();

                // Update save status
                saveStatus.innerText = result.is_mock ? 'Draf Berhasil (Mode Demo)' : 'Tersimpan';
                saveStatus.className = "text-xs font-bold text-duo-green bg-duo-green-light/25 px-2.5 py-1 rounded-full border border-duo-green";

                // Enable PPTX download button
                downloadPptxBtn.classList.remove('pointer-events-none', 'text-silver');
                downloadPptxBtn.classList.add('text-bubblegum-pink', 'border-bubblegum-pink', 'hover:bg-[#cc348d]/10');
            } else {
                alert(`Gagal merancang slide: ${result.error || 'Terjadi kesalahan sistem.'}`);
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
            submitBtn.innerText = 'Rancang Slide PPT';
        }
    });

    // Theme selector change listener to re-resolve images and update layouts in real-time
    document.getElementById('theme-select').addEventListener('change', async () => {
        const themeName = document.getElementById('theme-select').value;
        if (slidesData && slidesData.length > 0) {
            saveStatus.innerText = 'Memperbarui Gambar Tema...';
            saveStatus.className = "text-xs font-bold text-silver bg-[#f9f9f9] px-2.5 py-1 rounded-full border border-cloud-gray animate-pulse";
            await resolveSlideImages(slidesData, themeName);
            saveStatus.innerText = 'Tema Diperbarui';
            saveStatus.className = "text-xs font-bold text-duo-green bg-duo-green-light/25 px-2.5 py-1 rounded-full border border-duo-green";
            renderActiveSlide();
        }
    });

    // Helper to convert proxied URL to Base64 securely
    async function urlToBase64(url) {
        try {
            const res = await fetch(url);
            if (!res.ok) return null;
            const blob = await res.blob();
            // Validate that the returned content is actually an image and not a text/html error page
            if (!blob.type.startsWith('image/')) {
                console.error("Fetched blob is not a valid image:", blob.type);
                return null;
            }
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.onerror = () => resolve(null);
                reader.readAsDataURL(blob);
            });
        } catch (e) {
            console.error("Base64 fetch failed:", e);
            return null;
        }
    }

    // 5. Exporter to PPTX using PptxGenJS (Premium Offline Design)
    downloadPptxBtn.addEventListener('click', async () => {
        saveCurrentSlideEdits();
        if (!slidesData || slidesData.length === 0) return;

        const originalText = downloadPptxBtn.innerText;
        downloadPptxBtn.disabled = true;
        downloadPptxBtn.classList.add('animate-pulse');

        try {
            const pptx = new PptxGenJS();
            pptx.layout = 'LAYOUT_16x9';

            const themeName = document.getElementById('theme-select').value;
            const theme = themes[themeName] || themes['duo-green'];

            // Download images to Base64
            const total = slidesData.length;
            for (let i = 0; i < total; i++) {
                downloadPptxBtn.innerText = `Menyiapkan Gambar (${i+1}/${total})...`;
                const slide = slidesData[i];
                if (slide.imageUrl && !slide.base64Image) {
                    const proxiedUrl = `/api/image-proxy?url=${encodeURIComponent(slide.imageUrl)}`;
                    slide.base64Image = await urlToBase64(proxiedUrl);
                }
            }

            downloadPptxBtn.innerText = "Merakit Presentasi...";

            slidesData.forEach((slide, idx) => {
                const pptSlide = pptx.addSlide();
                
                if (idx === 0) {
                    // SLIDE 1: PREMIUM COVER (Split Layout)
                    pptSlide.background = { color: 'FAFAFB' };

                    // Left solid block (50% width)
                    pptSlide.addShape(pptx.ShapeType.rect, {
                        x: '0%', y: '0%', w: '50%', h: '100%',
                        fill: { color: theme.hexPrimary }
                    });

                    // Accent line
                    pptSlide.addShape(pptx.ShapeType.rect, {
                        x: '5%', y: '15%', w: '10%', h: '1%',
                        fill: { color: theme.hexAccent }
                    });

                    // Main Title
                    pptSlide.addText(slide.title || 'Judul Slide', {
                        x: '5%', y: '20%', w: '40%', h: '30%',
                        fontFace: 'Trebuchet MS',
                        fontSize: 36,
                        bold: true,
                        color: 'FFFFFF',
                        valign: 'top',
                        lineSpacing: 42
                    });

                    // Bullets (Metadata)
                    const bullets = slide.bullets || [];
                    const subject = bullets[0] ? bullets[0].replace(/^[📚🏫👤]\s*/u, '') : '';
                    const grade = bullets[1] ? bullets[1].replace(/^[📚🏫👤]\s*/u, '') : '';
                    const author = bullets[2] ? bullets[2].replace(/^[📚🏫👤]\s*/u, '') : '';

                    pptSlide.addText(`${subject}\n${grade}`, {
                        x: '5%', y: '60%', w: '40%', h: '15%',
                        fontFace: 'Arial',
                        fontSize: 14,
                        bold: true,
                        color: theme.hexSecondary,
                        lineSpacing: 22
                    });

                    if (author) {
                        pptSlide.addText(author, {
                            x: '5%', y: '80%', w: '40%', h: '10%',
                            fontFace: 'Arial',
                            fontSize: 12,
                            italic: true,
                            color: 'E0E0E0'
                        });
                    }

                    // Right full-bleed image (50% width)
                    if (slide.base64Image) {
                        pptSlide.addImage({
                            data: slide.base64Image,
                            x: '50%', y: '0%', w: '50%', h: '100%',
                            sizing: { type: 'cover', w: '50%', h: '100%' }
                        });
                    }

                } else {
                    // SLIDE 2+: PREMIUM CONTENT
                    pptSlide.background = { color: 'FAFAFB' };

                    // Top Header Bar
                    pptSlide.addShape(pptx.ShapeType.rect, {
                        x: '0%', y: '0%', w: '100%', h: '15%',
                        fill: { color: theme.hexPrimary }
                    });
                    // Subtle shadow line
                    pptSlide.addShape(pptx.ShapeType.rect, {
                        x: '0%', y: '15%', w: '100%', h: '1%',
                        fill: { color: theme.hexAccent }
                    });

                    // Slide Title
                    pptSlide.addText(slide.title || 'Judul Konten', {
                        x: '4%', y: '2%', w: '92%', h: '10%',
                        fontFace: 'Trebuchet MS',
                        fontSize: 28,
                        bold: true,
                        color: 'FFFFFF',
                        valign: 'middle'
                    });

                    // Bullets Content
                    const bullets = slide.bullets || [];
                    const bulletObjs = bullets.map(b => {
                        return { 
                            text: b.trim(), 
                            options: { 
                                bullet: true,
                                color: '333333', 
                                fontFace: 'Arial', 
                                fontSize: 16 
                            } 
                        };
                    });

                    // Text Area (Left Side)
                    pptSlide.addText(bulletObjs, {
                        x: '4%', y: '25%', w: '55%', h: '60%',
                        lineSpacing: 28,
                        valign: 'top'
                    });

                    // Image Area (Offset Shadow Design - Right Side)
                    pptSlide.addShape(pptx.ShapeType.rect, {
                        x: '62%', y: '26%', w: '33%', h: '55%',
                        fill: { color: theme.hexSecondary }
                    });

                    if (slide.base64Image) {
                        pptSlide.addImage({
                            data: slide.base64Image,
                            x: '60%', y: '24%', w: '33%', h: '55%',
                            sizing: { type: 'cover', w: '33%', h: '55%' }
                        });
                    }

                    // Footer
                    pptSlide.addText(`PandAI  |  ${mainTopic}`, {
                        x: '4%', y: '92%', w: '70%', h: '5%',
                        fontFace: 'Arial',
                        fontSize: 10,
                        color: 'A0A0A0'
                    });
                    
                    pptSlide.addText(`Slide ${idx + 1}`, {
                        x: '86%', y: '92%', w: '10%', h: '5%',
                        fontFace: 'Arial',
                        fontSize: 10,
                        color: 'A0A0A0',
                        align: 'right'
                    });
                }

                if (slide.notes) {
                    pptSlide.notes = slide.notes;
                }
            });

            downloadPptxBtn.innerText = "Menyimpan File...";
            await pptx.writeFile({ fileName: `PandAI_${mainTopic.replace(/[^a-zA-Z0-9]/g, '_')}.pptx` });

        } catch (error) {
            console.error("PPTX Generation Error: ", error);
            alert("Gagal merakit presentasi. Silakan periksa koneksi internet Anda atau coba muat ulang halaman.");
        } finally {
            downloadPptxBtn.innerText = originalText;
            downloadPptxBtn.disabled = false;
            downloadPptxBtn.classList.remove('animate-pulse');
        }
    });

    // 6. Fullscreen Presentation Mode handler
    fullscreenBtn.addEventListener('click', () => {
        saveCurrentSlideEdits();
        if (slideCanvas.requestFullscreen) {
            slideCanvas.requestFullscreen();
        } else if (slideCanvas.webkitRequestFullscreen) { /* Safari */
            slideCanvas.webkitRequestFullscreen();
        } else if (slideCanvas.msRequestFullscreen) { /* IE11 */
            slideCanvas.msRequestFullscreen();
        }
    });

    // Keyboard controls for fullscreen presentation mode
    document.addEventListener('keydown', (e) => {
        // Only trigger if in fullscreen element
        if (document.fullscreenElement === slideCanvas || document.webkitFullscreenElement === slideCanvas) {
            if (e.key === 'ArrowRight' || e.key === 'Space') {
                e.preventDefault();
                if (activeSlideIndex < slidesData.length - 1) {
                    activeSlideIndex++;
                    renderActiveSlide();
                }
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                if (activeSlideIndex > 0) {
                    activeSlideIndex--;
                    renderActiveSlide();
                }
            }
        }
    });
</script>
@endsection
