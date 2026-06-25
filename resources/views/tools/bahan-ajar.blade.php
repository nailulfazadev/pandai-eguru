@extends('layouts.app')

@section('title', 'Bahan Ajar Web Story - PandAI')

@section('content')
    <!-- Header Page -->
    <section class="select-none mb-6">
        <h2 class="text-heading font-feather text-almost-black">Generator Bahan Ajar "Web Story"</h2>
        <p class="text-body text-graphite">Kembangkan materi ajar menjadi pengalaman visual interaktif bergaya TikTok, lengkap dengan narasi suara otomatis.</p>
    </section>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Options Form -->
        <div class="col-span-1 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm space-y-5">
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-3 select-none">
                <h3 class="text-heading-sm font-feather text-almost-black">Pengaturan Cerita</h3>
                <button type="button" id="fill-demo-btn" class="text-xs font-bold text-bubblegum-pink hover:underline">Isi Contoh</button>
            </div>
            
            <form id="generate-form" class="space-y-4">
                @csrf

                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Mata Pelajaran</label>
                    <input type="text" name="subject" placeholder="Contoh: Sejarah Indonesia" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-bubblegum-pink bg-[#f9f9f9] transition">
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Topik / Materi Spesifik</label>
                    <input type="text" name="topic" placeholder="Contoh: Perjuangan Kemerdekaan 1945" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-bubblegum-pink bg-[#f9f9f9] transition">
                </div>

                <div class="space-y-1 flex space-x-2">
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-charcoal select-none">Jenjang/Kelas</label>
                        <select name="grade" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-bubblegum-pink bg-[#f9f9f9] transition">
                            <option>SD</option>
                            <option>SMP</option>
                            <option selected>SMA / SMK</option>
                        </select>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-charcoal select-none">Gaya Visual</label>
                        <select name="visual_style" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-bubblegum-pink bg-[#f9f9f9] transition">
                            <option value="minimalis" selected>Minimalis Edukasi</option>
                            <option value="neon">Neon Terang</option>
                            <option value="elegan">Elegan Gelap</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Suara Narator (Bawaan Browser)</label>
                    <select id="voice-select" name="voice" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-bubblegum-pink bg-[#f9f9f9] transition">
                        <option value="">Memuat suara...</option>
                    </select>
                </div>

                <div class="flex items-center space-x-2 pt-2 select-none">
                    <input type="checkbox" id="use-mock" name="use_mock" value="1"
                           class="w-4 h-4 text-bubblegum-pink border-cloud-gray rounded focus:ring-bubblegum-pink">
                    <label for="use-mock" class="text-xs font-bold text-graphite cursor-pointer">
                        Mode Demo (Cepat & Hemat Kuota AI)
                    </label>
                </div>

                <button type="submit" id="submit-btn" class="btn-3d-primary w-full text-sm py-3 tracking-wider bg-bubblegum-pink hover:bg-[#FF4D8D] shadow-[0_4px_0_#CC2966]">
                    Buat Web Story
                </button>
            </form>
        </div>

        <!-- Right: Preview Area -->
        <div class="lg:col-span-2 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm min-h-[600px] flex flex-col items-center">
            
            <div class="w-full flex justify-between items-center border-b-2 border-cloud-gray pb-4 mb-4 select-none">
                <div class="flex space-x-4">
                    <h3 class="text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-bubblegum-pink pb-1" id="tab-story">Web Story</h3>
                    <h3 class="text-heading-sm font-feather text-silver cursor-pointer hover:text-charcoal pb-1" id="tab-handout">Teks Handout</h3>
                </div>
                <div class="flex items-center space-x-3">
                    <span id="save-status" class="text-xs font-bold text-silver bg-[#f9f9f9] px-2.5 py-1 rounded-full border border-cloud-gray">Belum Disimpan</span>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loading-state" class="hidden flex-col items-center justify-center flex-1 w-full opacity-50 select-none">
                <div class="w-12 h-12 border-4 border-cloud-gray border-t-bubblegum-pink rounded-full animate-spin mb-4"></div>
                <p class="text-graphite font-bold animate-pulse">Menyusun Adegan & Skenario...</p>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="flex flex-col items-center justify-center flex-1 w-full opacity-50 select-none">
                <svg class="w-20 h-20 text-silver mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                <p class="text-graphite font-bold text-center">Belum ada cerita.<br><span class="text-sm font-normal">Isi form untuk membuat bahan ajar interaktif.</span></p>
            </div>

            <!-- Tab 1: Web Story Player -->
            <div id="view-story" class="hidden flex-1 w-full flex-col items-center justify-center">
                
                <!-- Phone Container -->
                <div class="relative w-[320px] h-[568px] bg-black rounded-[2rem] border-[8px] border-gray-800 shadow-2xl overflow-hidden group">
                    
                    <!-- Dynamic Background -->
                    <div id="story-bg" class="absolute inset-0 bg-cover bg-center transition-all duration-1000 scale-105 opacity-50"></div>
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>

                    <!-- Progress Bar -->
                    <div class="absolute top-4 left-4 right-4 flex space-x-1 z-20" id="progress-container">
                        <!-- Bars injected via JS -->
                    </div>

                    <!-- Story Content -->
                    <div class="absolute inset-0 flex flex-col justify-end p-6 z-10 pb-20">
                        <h2 id="story-text" class="text-white text-2xl font-bold font-feather leading-tight drop-shadow-lg mb-4 animate-[slideUp_0.5s_ease-out]">
                            <!-- Text injected here -->
                        </h2>
                        <p id="story-narration" class="text-gray-200 text-sm font-medium drop-shadow-md border-l-4 border-bubblegum-pink pl-3 opacity-90 animate-[fadeIn_1s_ease-in]">
                            <!-- Narration transcript -->
                        </p>
                    </div>

                    <!-- Play/Pause Overlay -->
                    <button id="play-pause-btn" class="absolute inset-0 w-full h-full z-30 cursor-pointer focus:outline-none flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div id="play-icon" class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center hidden">
                            <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                        </div>
                    </button>

                    <!-- Navigation Zones -->
                    <div class="absolute inset-y-0 left-0 w-1/3 z-40 cursor-pointer" id="nav-prev" title="Sebelumnya"></div>
                    <div class="absolute inset-y-0 right-0 w-1/3 z-40 cursor-pointer" id="nav-next" title="Selanjutnya"></div>
                </div>

                <div class="mt-4 flex space-x-4">
                    <button id="toggle-audio-btn" class="flex items-center space-x-2 text-sm font-bold text-graphite hover:text-bubblegum-pink transition">
                        <svg id="audio-icon" class="w-5 h-5 text-bubblegum-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5 10v4a2 2 0 002 2h2l5 5V3l-5 5H7a2 2 0 00-2 2z"></path></svg>
                        <span id="audio-label">Suara Nyala</span>
                    </button>
                </div>
            </div>

            <!-- Tab 2: Teks Handout -->
            <div id="view-handout" class="hidden flex-1 w-full bg-white border border-cloud-gray rounded-xl p-8 overflow-y-auto max-h-[600px] prose prose-sm max-w-none">
                <!-- Markdown injected here -->
            </div>
            
            <!-- Actions Pane -->
            <div id="actions-pane" class="hidden w-full border-t-2 border-cloud-gray pt-4 flex justify-end space-x-3 select-none mt-6">
                <a id="download-word-btn" href="#" class="btn-outline text-charcoal border-cloud-gray hover:border-sky-blue hover:text-sky-blue text-sm py-2 px-4 flex items-center space-x-2 hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Unduh Handout Word</span>
                </a>
            </div>

        </div>
    </div>

    <!-- Custom Animations for Web Story -->
    <style>
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        /* Progress Bar Animation Classes */
        .progress-bar-fill { height: 100%; background-color: white; width: 0%; border-radius: 2px; }
        .progress-bar-active { animation: progress linear forwards; }
        .progress-bar-done { width: 100%; }
        @keyframes progress { from { width: 0%; } to { width: 100%; } }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        // DOM Elements
        const form = document.getElementById('generate-form');
        const fillDemoBtn = document.getElementById('fill-demo-btn');
        const submitBtn = document.getElementById('submit-btn');
        const loadingState = document.getElementById('loading-state');
        const emptyState = document.getElementById('empty-state');
        const actionsPane = document.getElementById('actions-pane');
        const saveStatus = document.getElementById('save-status');
        
        // Tabs
        const tabStory = document.getElementById('tab-story');
        const tabHandout = document.getElementById('tab-handout');
        const viewStory = document.getElementById('view-story');
        const viewHandout = document.getElementById('view-handout');
        const downloadWordBtn = document.getElementById('download-word-btn');

        // Voice
        const voiceSelect = document.getElementById('voice-select');
        let voices = [];

        // Story Player Elements
        const storyBg = document.getElementById('story-bg');
        const storyText = document.getElementById('story-text');
        const storyNarration = document.getElementById('story-narration');
        const progressContainer = document.getElementById('progress-container');
        const playPauseBtn = document.getElementById('play-pause-btn');
        const playIcon = document.getElementById('play-icon');
        const navPrev = document.getElementById('nav-prev');
        const navNext = document.getElementById('nav-next');
        const toggleAudioBtn = document.getElementById('toggle-audio-btn');
        const audioIcon = document.getElementById('audio-icon');
        const audioLabel = document.getElementById('audio-label');

        // Player State
        let currentData = null;
        let currentSceneIndex = 0;
        let isPlaying = false;
        let audioEnabled = true;
        let storyTimeout = null;
        const SCENE_DURATION_MS = 6000; // Fallback if audio disabled

        // --- Voice Initialization ---
        function populateVoices() {
            voices = speechSynthesis.getVoices();
            voiceSelect.innerHTML = '';
            // Try to find Indonesian voices
            const idVoices = voices.filter(v => v.lang.includes('id') || v.lang.includes('ID'));
            const options = idVoices.length > 0 ? idVoices : voices;
            
            options.forEach((voice, i) => {
                const option = document.createElement('option');
                option.textContent = `${voice.name} (${voice.lang})`;
                option.value = i;
                voiceSelect.appendChild(option);
            });
            if(options.length === 0) {
                voiceSelect.innerHTML = '<option value="">Suara tidak tersedia di browser ini</option>';
            }
        }

        populateVoices();
        if (speechSynthesis.onvoiceschanged !== undefined) {
            speechSynthesis.onvoiceschanged = populateVoices;
        }

        // --- Tab Logic ---
        tabStory.addEventListener('click', () => {
            tabStory.className = "text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-bubblegum-pink pb-1";
            tabHandout.className = "text-heading-sm font-feather text-silver cursor-pointer hover:text-charcoal pb-1";
            viewStory.style.display = 'flex';
            viewHandout.style.display = 'none';
        });

        tabHandout.addEventListener('click', () => {
            tabHandout.className = "text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-bubblegum-pink pb-1";
            tabStory.className = "text-heading-sm font-feather text-silver cursor-pointer hover:text-charcoal pb-1";
            viewHandout.style.display = 'block';
            viewStory.style.display = 'none';
        });

        // --- Demo Data ---
        fillDemoBtn.addEventListener('click', () => {
            form.subject.value = 'Biologi';
            form.topic.value = 'Metamorfosis Kupu-Kupu';
            form.grade.value = 'SMP';
            form.visual_style.value = 'minimalis';
            form.use_mock.checked = false;
        });

        // --- Audio Toggle ---
        toggleAudioBtn.addEventListener('click', () => {
            audioEnabled = !audioEnabled;
            if(audioEnabled) {
                audioIcon.classList.remove('text-silver');
                audioIcon.classList.add('text-bubblegum-pink');
                audioLabel.textContent = 'Suara Nyala';
                audioIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5 10v4a2 2 0 002 2h2l5 5V3l-5 5H7a2 2 0 00-2 2z"></path>';
                if(isPlaying) playCurrentScene(); // Replay with voice
            } else {
                audioIcon.classList.add('text-silver');
                audioIcon.classList.remove('text-bubblegum-pink');
                audioLabel.textContent = 'Suara Mati';
                audioIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path>';
                speechSynthesis.cancel();
            }
        });

        // --- Player Logic ---
        function buildProgressBars() {
            progressContainer.innerHTML = '';
            if(!currentData || !currentData.scenes) return;
            currentData.scenes.forEach((_, i) => {
                const bg = document.createElement('div');
                bg.className = 'h-1 bg-white/30 rounded-full flex-1 overflow-hidden';
                const fill = document.createElement('div');
                fill.className = 'progress-bar-fill';
                fill.id = `pb-${i}`;
                bg.appendChild(fill);
                progressContainer.appendChild(bg);
            });
        }

        function updateProgressBars() {
            currentData.scenes.forEach((_, i) => {
                const pb = document.getElementById(`pb-${i}`);
                if (!pb) return;
                pb.style.animationDuration = ''; // reset
                pb.classList.remove('progress-bar-active', 'progress-bar-done');
                
                if (i < currentSceneIndex) {
                    pb.classList.add('progress-bar-done');
                } else if (i === currentSceneIndex && isPlaying) {
                    // Animation handled dynamically below based on speech duration
                }
            });
        }

        function playCurrentScene() {
            if (!currentData || !currentData.scenes || currentData.scenes.length === 0) return;
            
            speechSynthesis.cancel();
            clearTimeout(storyTimeout);

            const scene = currentData.scenes[currentSceneIndex];
            
            // Re-trigger CSS animations by cloning elements
            storyText.innerHTML = scene.text || '';
            storyNarration.innerHTML = scene.narration || '';
            const newText = storyText.cloneNode(true);
            storyText.parentNode.replaceChild(newText, storyText);
            const newNarr = storyNarration.cloneNode(true);
            storyNarration.parentNode.replaceChild(newNarr, storyNarration);

            // Fetch image from loremflickr (using topic and keyword)
            const keyword = scene.image_keyword ? scene.image_keyword.replace(/\s+/g, ',') : 'abstract';
            storyBg.style.backgroundImage = `url('https://loremflickr.com/1080/1920/${keyword}?random=${currentSceneIndex}')`;
            
            updateProgressBars();

            if (audioEnabled && scene.narration) {
                const utterance = new SpeechSynthesisUtterance(scene.narration);
                
                // Set selected voice
                const selectedVoiceIndex = voiceSelect.value;
                const idVoices = voices.filter(v => v.lang.includes('id') || v.lang.includes('ID'));
                const options = idVoices.length > 0 ? idVoices : voices;
                if(selectedVoiceIndex !== "" && options[selectedVoiceIndex]) {
                    utterance.voice = options[selectedVoiceIndex];
                }

                utterance.rate = 1.0;
                utterance.pitch = 1.1; // Make it sound slightly more energetic

                utterance.onstart = () => {
                    // We don't know exact duration, so estimate: ~130 words per minute
                    const words = scene.narration.split(' ').length;
                    const estimatedMs = Math.max(3000, (words / 130) * 60000);
                    
                    const pb = document.getElementById(`pb-${currentSceneIndex}`);
                    if (pb) {
                        pb.style.animationDuration = `${estimatedMs}ms`;
                        pb.classList.add('progress-bar-active');
                    }
                };

                utterance.onend = () => {
                    if (isPlaying) goToNextScene();
                };

                utterance.onerror = (e) => {
                    console.error("Speech error", e);
                    // Fallback to timeout
                    startFallbackTimer();
                };

                speechSynthesis.speak(utterance);
            } else {
                startFallbackTimer();
            }
        }

        function startFallbackTimer() {
            const pb = document.getElementById(`pb-${currentSceneIndex}`);
            if (pb) {
                pb.style.animationDuration = `${SCENE_DURATION_MS}ms`;
                pb.classList.add('progress-bar-active');
            }
            storyTimeout = setTimeout(() => {
                if(isPlaying) goToNextScene();
            }, SCENE_DURATION_MS);
        }

        function goToNextScene() {
            if(currentSceneIndex < currentData.scenes.length - 1) {
                currentSceneIndex++;
                playCurrentScene();
            } else {
                // End of story
                pauseStory();
                currentSceneIndex = 0; // Reset
                updateProgressBars();
            }
        }

        function goToPrevScene() {
            if(currentSceneIndex > 0) {
                currentSceneIndex--;
                playCurrentScene();
            } else {
                playCurrentScene(); // Replay first
            }
        }

        function pauseStory() {
            isPlaying = false;
            speechSynthesis.cancel();
            clearTimeout(storyTimeout);
            playIcon.classList.remove('hidden');
            const pb = document.getElementById(`pb-${currentSceneIndex}`);
            if (pb) pb.style.animationPlayState = 'paused';
        }

        function resumeStory() {
            isPlaying = true;
            playIcon.classList.add('hidden');
            playCurrentScene(); // Restart current scene
        }

        // Navigation Interactions
        playPauseBtn.addEventListener('click', (e) => {
            if(e.target.id === 'nav-prev' || e.target.id === 'nav-next') return; // let nav handlers work
            if (isPlaying) pauseStory(); else resumeStory();
        });

        navNext.addEventListener('click', () => {
            goToNextScene();
            if(!isPlaying) resumeStory();
        });

        navPrev.addEventListener('click', () => {
            goToPrevScene();
            if(!isPlaying) resumeStory();
        });

        // --- Render Logic ---
        function renderDocument(data, isFromHistory = false) {
            currentData = data;
            
            // 1. Build Tab 1 (Story)
            buildProgressBars();
            currentSceneIndex = 0;
            isPlaying = true;
            playIcon.classList.add('hidden');
            
            // Apply visual style if selected
            let themeClass = 'from-black via-black/40';
            const style = form.visual_style.value;
            if(style === 'neon') themeClass = 'from-purple-900 via-pink-900/40';
            else if(style === 'elegan') themeClass = 'from-slate-900 via-slate-800/40';
            document.querySelector('.bg-gradient-to-t').className = `absolute inset-0 bg-gradient-to-t ${themeClass} to-transparent`;

            playCurrentScene();

            // 2. Build Tab 2 (Handout)
            if(data.handout) {
                viewHandout.innerHTML = marked.parse(data.handout);
            } else {
                viewHandout.innerHTML = "<p>Tidak ada teks handout.</p>";
            }

            // UI Adjustments
            emptyState.style.display = 'none';
            loadingState.style.display = 'none';
            tabStory.click();
            actionsPane.style.display = 'flex';
            
            if(!isFromHistory) {
                saveStatus.textContent = 'Tersimpan (Dari Sesi Ini)';
                saveStatus.className = 'text-xs font-bold text-duo-green bg-duo-green/10 px-2.5 py-1 rounded-full border border-duo-green/20';
            }
        }

        // Page Initial Load (History Document)
        @if(isset($document))
            try {
                const docData = {!! $document->content !!};
                
                // Update download link
                downloadWordBtn.href = "{{ route('documents.download', $document->id) }}";
                downloadWordBtn.classList.remove('hidden');

                renderDocument(docData, true);
                
                saveStatus.textContent = 'Dimuat dari Riwayat';
                saveStatus.className = 'text-xs font-bold text-sky-blue bg-sky-blue/10 px-2.5 py-1 rounded-full border border-sky-blue/20';
            } catch (e) {
                console.error("Failed to load document from history", e);
            }
        @endif

        // Form Submit
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // UI State to Loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-pulse">Menyusun Adegan...</span>';
            emptyState.style.display = 'none';
            viewStory.style.display = 'none';
            viewHandout.style.display = 'none';
            actionsPane.style.display = 'none';
            loadingState.style.display = 'flex';
            pauseStory(); // stop existing

            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route("tools.generate-bahan-ajar-submit") }}', {
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

                const data = await response.json();
                
                if (data.status === 'success') {
                    // Update download link for newly created document
                    if(data.document_id) {
                        downloadWordBtn.href = `/documents/${data.document_id}/download`;
                        downloadWordBtn.classList.remove('hidden');
                    }
                    renderDocument(data.data, false);
                } else {
                    alert('Gagal membuat cerita: ' + (data.message || 'Unknown error'));
                    emptyState.style.display = 'flex';
                }
            } catch (error) {
                console.error("AJAX Error:", error);
                alert('Terjadi kesalahan sistem saat menghubungi server.\nDetail: ' + error.message);
                emptyState.style.display = 'flex';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Buat Web Story';
                loadingState.style.display = 'none';
            }
        });

        // Cleanup audio on leave
        window.addEventListener('beforeunload', () => {
            speechSynthesis.cancel();
        });
    </script>
@endsection
