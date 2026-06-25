<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PandAI - Asisten AI Pintar untuk Guru Indonesia</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@700;800&family=Nunito+Sans:opsz,wght@6..12,500;6..12,700;6..12,900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v4 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <!-- Custom Theme -->
    <style type="text/tailwindcss">
        @theme {
            --color-primary: #58cc02; /* Green as primary */
            --color-primary-dark: #46a302;
            --color-primary-light: #d7ffb8;
            --color-secondary: #ffc700; /* Yellow */
            --color-secondary-dark: #e5b300;
            --color-accent: #1cb0f6; /* Blue for accents */
            --color-snow-white: #ffffff;
            --color-cloud-gray: #e5e5e5;
            --color-silver: #afafaf;
            --color-graphite: #777777;
            --color-almost-black: #2a2a2a;

            --font-feather: 'Baloo 2', ui-sans-serif, system-ui;
            --font-din-round: 'Nunito Sans', ui-sans-serif, system-ui;
        }

        body {
            font-family: var(--font-din-round);
            background-color: var(--color-snow-white);
            color: var(--color-almost-black);
            letter-spacing: 0.03em;
        }

        h1, h2, h3, h4, .font-feather {
            font-family: var(--font-feather);
        }

        /* 3D Button Styles */
        .btn-3d-primary {
            @apply inline-block bg-primary text-snow-white font-medium rounded-2xl px-8 py-4 text-center transition-all duration-150 active:translate-y-1 active:shadow-none uppercase tracking-wider text-lg;
            box-shadow: 0 4px 0 var(--color-primary-dark);
        }
        
        .btn-3d-primary:hover {
            @apply bg-[#61df02];
        }

        .btn-3d-secondary {
            @apply inline-block bg-secondary text-almost-black font-medium rounded-2xl px-8 py-4 text-center transition-all duration-150 active:translate-y-1 active:shadow-none uppercase tracking-wider text-lg border-2 border-cloud-gray;
            box-shadow: 0 4px 0 var(--color-secondary-dark);
        }
        
        .btn-3d-secondary:hover {
            @apply bg-[#ffd433];
        }
        
        .glass-card {
            @apply bg-snow-white/80 backdrop-blur-md border-2 border-cloud-gray shadow-lg rounded-3xl p-8;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    
    <!-- Navigation -->
    <nav class="bg-snow-white/90 backdrop-blur-md border-b-2 border-cloud-gray py-4 px-6 fixed w-full top-0 z-50">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="text-3xl font-feather text-primary flex items-center space-x-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span>PandAI</span>
            </div>
            <div class="space-x-2 md:space-x-4">
                <a href="{{ route('login') }}" class="font-medium text-graphite hover:text-primary uppercase tracking-wide px-2 md:px-4 py-2 text-sm md:text-base transition">Masuk</a>
                <a href="{{ route('register') }}" class="btn-3d-primary !py-2 !px-4 md:!px-6 !text-sm border-2 border-transparent">Coba Gratis</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-grow pt-32 pb-20 px-6 relative overflow-hidden bg-[#f9f9f9]">
        <!-- Decorative Elements -->
        <div class="absolute top-20 left-10 w-64 h-64 bg-primary-light rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
        <div class="absolute top-40 right-10 w-64 h-64 bg-secondary/30 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
        
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center relative z-10 gap-12">
            <div class="flex-1 text-center md:text-left">
                <div class="inline-flex items-center space-x-2 bg-primary-light text-primary-dark px-4 py-2 rounded-full font-extrabold text-xs md:text-sm mb-6 border-2 border-primary/30 uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <span>Revolusi Mengajar Era Digital</span>
                </div>
                
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-feather text-almost-black leading-[1.1] mb-6">
                    Bikin Modul Ajar <br> 
                    <span class="text-primary relative inline-block">
                        Sekejap Mata.
                        <svg class="absolute w-full h-3 -bottom-1 left-0 text-secondary" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="4" fill="transparent"/></svg>
                    </span>
                </h1>
                
                <p class="text-lg md:text-xl font-medium text-graphite mb-10 max-w-xl mx-auto md:mx-0 leading-relaxed">
                    Tinggalkan administrasi berjam-jam. PandAI membantu Anda menyusun Modul Ajar, Soal Ujian, dan Materi secara otomatis sesuai Kurikulum Merdeka.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center md:justify-start">
                    <a href="{{ route('register') }}" class="btn-3d-primary w-full sm:w-auto flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Mulai Trial 7 Hari
                    </a>
                    <a href="#pricing" class="btn-3d-secondary w-full sm:w-auto">
                        Beli Akses Premium
                    </a>
                </div>
                
                <div class="mt-8 flex items-center justify-center md:justify-start space-x-4 text-sm font-medium text-silver">
                    <div class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-full bg-cloud-gray border-2 border-snow-white"></div>
                        <div class="w-8 h-8 rounded-full bg-silver border-2 border-snow-white"></div>
                        <div class="w-8 h-8 rounded-full bg-graphite border-2 border-snow-white"></div>
                    </div>
                    <span>Dipercaya oleh 1.000+ Guru Indonesia</span>
                </div>
            </div>
            
            <!-- Hero Mockup/Illustration -->
            <div class="flex-1 w-full max-w-lg relative">
                <div class="bg-snow-white border-4 border-cloud-gray rounded-3xl shadow-2xl overflow-hidden transform md:rotate-2 hover:rotate-0 transition duration-500">
                    <div class="bg-cloud-gray h-8 px-4 flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
                    </div>
                    <div class="p-6 bg-[#f9f9f9]">
                        <div class="h-4 bg-cloud-gray rounded w-1/3 mb-6"></div>
                        <div class="space-y-4 mb-8">
                            <div class="h-8 bg-primary-light rounded-xl w-3/4 flex items-center px-4"><span class="w-2/3 h-2 bg-primary rounded"></span></div>
                            <div class="h-8 bg-cloud-gray rounded-xl w-full flex items-center px-4"><span class="w-1/2 h-2 bg-silver rounded"></span></div>
                            <div class="h-8 bg-cloud-gray rounded-xl w-5/6 flex items-center px-4"><span class="w-1/3 h-2 bg-silver rounded"></span></div>
                        </div>
                        <div class="p-4 bg-primary text-snow-white rounded-xl shadow-inner font-medium text-sm flex items-center">
                            <svg class="w-5 h-5 mr-2 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Modul Ajar Berhasil Dibuat!
                        </div>
                    </div>
                </div>
                
                <!-- Floating Badges -->
                <div class="absolute -bottom-6 -left-6 bg-snow-white border-2 border-cloud-gray p-4 rounded-2xl shadow-xl flex items-center space-x-3 transform -rotate-3 animate-pulse">
                    <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-almost-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-graphite uppercase">Hemat Waktu</p>
                        <p class="text-lg font-extrabold text-almost-black">90% Lebih Cepat</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Features Detail Section -->
    <section class="py-20 px-6 bg-snow-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-feather text-almost-black mb-4">Senjata Utama Sang Pendidik</h2>
                <p class="text-lg font-medium text-graphite max-w-2xl mx-auto">Satu aplikasi, ragam solusi. PandAI didesain khusus menyesuaikan kebutuhan kelas Anda hari ini.</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-card hover:-translate-y-2 transition duration-300">
                    <div class="w-14 h-14 bg-primary text-snow-white rounded-2xl flex items-center justify-center mb-6 shadow-md shadow-primary/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-3">Modul Ajar Spesifik</h3>
                    <p class="font-medium text-graphite text-sm leading-relaxed mb-4">Hanya dengan mengetikkan topik dan kelas, AI kami menyusun Tujuan Pembelajaran, Langkah-Langkah Kegiatan, Profil Pelajar Pancasila, hingga Asesmen secara komprehensif dalam satu klik. Hemat waktu berjam-jam untuk administrasi!</p>
                    <ul class="space-y-2">
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Kurikulum Merdeka</li>
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Lengkap dengan Alokasi Waktu</li>
                    </ul>
                </div>
                
                <!-- Feature 2 -->
                <div class="glass-card hover:-translate-y-2 transition duration-300">
                    <div class="w-14 h-14 bg-secondary text-almost-black rounded-2xl flex items-center justify-center mb-6 shadow-md shadow-secondary/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-3">Generator Soal Cerdas</h3>
                    <p class="font-medium text-graphite text-sm leading-relaxed mb-4">Perlu evaluasi harian atau ujian dadakan? PandAI secara cerdas membuat paket soal Pilihan Ganda dan Esai yang disesuaikan dengan tingkat kesulitan siswa, lengkap dengan kunci jawaban dan rubrik penilaian mendetail.</p>
                    <ul class="space-y-2">
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Level Kognitif Beragam (HOTS)</li>
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Pilihan Ganda & Uraian</li>
                    </ul>
                </div>
                
                <!-- Feature 3 -->
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
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 px-6 bg-cloud-gray/30">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-feather text-almost-black mb-4">Investasi Mengajar Anda</h2>
                <p class="text-lg font-medium text-graphite">Pilih paket yang sesuai untuk membuka batas kreativitas mengajar Anda setiap hari.</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Free Plan -->
                <div class="bg-snow-white p-8 rounded-3xl border-2 border-cloud-gray shadow-sm relative overflow-hidden">
                    <h3 class="text-2xl font-extrabold text-almost-black mb-2">Paket Trial</h3>
                    <p class="text-sm font-medium text-graphite mb-6">Masa percobaan untuk guru cerdas.</p>
                    <div class="mb-8">
                        <span class="text-4xl font-extrabold text-almost-black">Gratis</span>
                        <span class="text-graphite font-medium">/ 7 Hari</span>
                    </div>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center text-sm font-medium"><svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Akses Modul Ajar</li>
                        <li class="flex items-center text-sm font-medium text-silver"><svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Generator Soal</li>
                        <li class="flex items-center text-sm font-medium text-silver"><svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> LKPD & Bahan Ajar</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full py-4 px-6 bg-cloud-gray hover:bg-silver text-almost-black font-medium rounded-2xl text-center uppercase tracking-wider transition">Daftar Sekarang</a>
                </div>

                <!-- Early Access Plan -->
                <div class="bg-[#fef2f2] p-8 rounded-3xl border-2 border-[#ef4444] shadow-xl relative overflow-hidden transform md:-translate-y-2">
                    <div class="absolute top-6 right-6 bg-[#ef4444] text-snow-white text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                        Eksklusif
                    </div>
                    <h3 class="text-2xl font-extrabold text-[#ef4444] mb-2">Early Access</h3>
                    <p class="text-sm font-medium text-graphite mb-6">Paket khusus undangan untuk guru penguji.</p>
                    <div class="mb-8">
                        <span class="text-4xl font-extrabold text-almost-black">Gratis</span>
                        <span class="text-graphite font-medium">/ 1 Bulan</span>
                    </div>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-5 h-5 text-[#ef4444] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Semua Fitur Premium</li>
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-5 h-5 text-[#ef4444] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Kuota Generator Penuh</li>
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-5 h-5 text-[#ef4444] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Akses Langsung</li>
                    </ul>
                    <a href="{{ url('/register?plan=early') }}" class="block w-full py-4 px-6 bg-[#ef4444] hover:bg-[#dc2626] text-snow-white font-medium rounded-2xl text-center uppercase tracking-wider transition" style="box-shadow: 0 4px 0 #b91c1c;">Daftar Sekarang</a>
                </div>
                
                <!-- Semester Plan -->
                <div class="bg-[#f0f9ff] p-8 rounded-3xl border-2 border-accent shadow-xl shadow-accent/20 relative overflow-hidden transform md:-translate-y-2">
                    <h3 class="text-2xl font-extrabold text-accent mb-2">Paket Semester</h3>
                    <p class="text-sm font-medium text-graphite mb-6">Akses penuh selama 6 bulan.</p>
                    <div class="mb-8">
                        <span class="text-4xl font-extrabold text-almost-black">Rp 89k</span>
                        <span class="text-graphite font-medium">/ 6 bulan</span>
                    </div>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Buka Semua Tools AI</li>
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Limit 10x per hari</li>
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Prioritas Server Cepat</li>
                    </ul>
                    <a href="https://solusiedu.myr.id/pl/PandAI-by-e-Guru?email={{ auth()->check() ? urlencode(auth()->user()->email) : '' }}&name={{ auth()->check() ? urlencode(auth()->user()->name) : '' }}&mobile={{ auth()->check() && auth()->user()->phone ? urlencode(auth()->user()->phone) : '' }}" target="_blank" class="block w-full py-4 px-6 bg-accent hover:bg-[#189ce0] text-snow-white font-medium rounded-2xl text-center uppercase tracking-wider transition" style="box-shadow: 0 4px 0 #1583bc;">Beli Paket Semester</a>
                </div>

                <!-- Tahunan Plan -->
                <div class="bg-primary-light p-8 rounded-3xl border-2 border-primary shadow-xl shadow-primary/20 relative overflow-hidden transform md:-translate-y-6">
                    <div class="absolute top-6 right-6 bg-secondary text-almost-black text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider border-2 border-almost-black transform rotate-6">
                        Paling Hemat
                    </div>
                    <h3 class="text-2xl font-extrabold text-primary-dark mb-2">Paket Tahunan</h3>
                    <p class="text-sm font-medium text-primary-dark/70 mb-6">Akses tanpa batas, lebih hemat Rp 31k.</p>
                    <div class="mb-8">
                        <span class="text-4xl font-extrabold text-almost-black">Rp 147k</span>
                        <span class="text-graphite font-medium">/ tahun</span>
                    </div>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Semua fitur Paket Semester</li>
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Harga Lebih Murah</li>
                        <li class="flex items-center text-sm font-medium text-almost-black"><svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Akses Fitur Beta Duluan</li>
                    </ul>
                    <a href="https://solusiedu.myr.id/pl/PandAI-by-e-Guru?email={{ auth()->check() ? urlencode(auth()->user()->email) : '' }}&name={{ auth()->check() ? urlencode(auth()->user()->name) : '' }}&mobile={{ auth()->check() && auth()->user()->phone ? urlencode(auth()->user()->phone) : '' }}" target="_blank" class="block w-full py-4 px-6 bg-primary hover:bg-[#61df02] text-snow-white font-medium rounded-2xl text-center uppercase tracking-wider transition" style="box-shadow: 0 4px 0 #46a302;">Beli Paket Tahunan</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer CTA -->
    <footer class="bg-almost-black text-snow-white pt-20 pb-10 px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-feather mb-6">Siap Mengajar Lebih Cerdas?</h2>
        <p class="text-silver font-medium mb-10 max-w-lg mx-auto">Bergabunglah dengan ribuan guru yang telah mengubah cara mereka bekerja dengan PandAI.</p>
        <a href="{{ route('register') }}" class="btn-3d-primary shadow-none border-2 border-primary-dark hover:border-snow-white bg-transparent text-primary hover:bg-primary hover:text-snow-white">Mulai Secara Gratis</a>
        
        <div class="mt-20 pt-8 border-t border-[#3c3c3c] text-sm font-medium text-graphite flex flex-col md:flex-row justify-between items-center max-w-6xl mx-auto">
            <p>&copy; 2026 PandAI by e-Guru. Hak Cipta Dilindungi.</p>
            <div class="mt-4 md:mt-0 space-x-6">
                <a href="#" class="hover:text-snow-white transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-snow-white transition">Syarat & Ketentuan</a>
            </div>
        </div>
    </footer>

</body>
</html>
