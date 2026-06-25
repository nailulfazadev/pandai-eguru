@extends('layouts.app')

@section('title', 'Semua Tools - PandAI')

@section('content')
    @php
        $isTrial = auth()->user()->isTrial();
    @endphp

    <!-- Header Page -->
    <section class="select-none mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-heading font-feather text-almost-black">Peralatan Mengajar AI</h2>
            <p class="text-body text-graphite">Pilih salah satu alat di bawah ini untuk membantu merancang dokumen dan materi pembelajaran secara instan.</p>
        </div>
        @if($isTrial)
            <a href="{{ route('langganan') }}" class="bg-sunshine-yellow text-almost-black px-4 py-2 rounded-xl font-bold border-2 border-almost-black hover:-translate-y-1 hover:shadow-lg transition">
                🚀 Upgrade Premium
            </a>
        @endif
    </section>

    <!-- Tools Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Tool 1: Modul Ajar AI (Active for all) -->
        <a href="{{ route('tools.modul-ajar') }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray hover:border-duo-green hover:shadow-md transition group flex flex-col justify-between h-full cursor-pointer">
            <div>
                <div class="w-12 h-12 rounded-xl bg-duo-green/10 flex items-center justify-center text-duo-green mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="font-bold text-charcoal text-lg mb-2">Modul Ajar AI</h3>
                <p class="text-graphite text-sm leading-relaxed mb-6">Buat modul ajar lengkap dan terstruktur berbasis Rencana Pembelajaran Mendalam (RPM) sesuai Kurikulum Merdeka secara otomatis.</p>
            </div>
            <span class="text-duo-green font-bold text-xs uppercase tracking-wider flex items-center gap-1">
                Mulai Menggunakan
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </span>
        </a>

        <!-- Tool 2: Generator Soal AI -->
        <a href="{{ $isTrial ? route('langganan') : route('tools.generator-soal') }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray {{ $isTrial ? 'opacity-70 hover:border-cloud-gray' : 'hover:border-sky-blue hover:shadow-md' }} transition group flex flex-col justify-between h-full cursor-pointer relative">
            @if($isTrial)
                <div class="absolute top-4 right-4 text-graphite bg-cloud-gray/30 p-2 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
            @endif
            <div>
                <div class="w-12 h-12 rounded-xl {{ $isTrial ? 'bg-cloud-gray/20 text-graphite' : 'bg-sky-blue/10 text-sky-blue' }} flex items-center justify-center mb-4 {{ !$isTrial ? 'group-hover:scale-110' : '' }} transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="font-bold text-charcoal text-lg mb-2">Generator Soal</h3>
                <p class="text-graphite text-sm leading-relaxed mb-6">Buat soal ulangan pilihan ganda atau essay secara otomatis dari materi pelajaran lengkap dengan kunci jawaban.</p>
            </div>
            <span class="{{ $isTrial ? 'text-graphite' : 'text-sky-blue' }} font-bold text-xs uppercase tracking-wider flex items-center gap-1">
                {{ $isTrial ? 'Terkunci (Upgrade)' : 'Mulai Menggunakan' }}
                @if(!$isTrial)<svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>@endif
            </span>
        </a>

        <!-- Tool 3: PPT Pembelajaran -->
        <a href="{{ $isTrial ? route('langganan') : route('tools.ppt-pembelajaran') }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray {{ $isTrial ? 'opacity-70 hover:border-cloud-gray' : 'hover:border-bubblegum-pink hover:shadow-md' }} transition group flex flex-col justify-between h-full cursor-pointer relative">
            @if($isTrial)
                <div class="absolute top-4 right-4 text-graphite bg-cloud-gray/30 p-2 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
            @endif
            <div>
                <div class="w-12 h-12 rounded-xl {{ $isTrial ? 'bg-cloud-gray/20 text-graphite' : 'bg-bubblegum-pink/10 text-bubblegum-pink' }} flex items-center justify-center mb-4 {{ !$isTrial ? 'group-hover:scale-110' : '' }} transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                </div>
                <h3 class="font-bold text-charcoal text-lg mb-2">PPT Pembelajaran</h3>
                <p class="text-graphite text-sm leading-relaxed mb-6">Rancang struktur slide presentasi mengajar yang dinamis dan terstruktur untuk mempermudah pemahaman siswa.</p>
            </div>
            <span class="{{ $isTrial ? 'text-graphite' : 'text-bubblegum-pink' }} font-bold text-xs uppercase tracking-wider flex items-center gap-1">
                {{ $isTrial ? 'Terkunci (Upgrade)' : 'Mulai Menggunakan' }}
                @if(!$isTrial)<svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>@endif
            </span>
        </a>

        <!-- Tool 4: LKPD Generator -->
        <a href="{{ $isTrial ? route('langganan') : route('tools.lkpd-generator') }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray {{ $isTrial ? 'opacity-70 hover:border-cloud-gray' : 'hover:border-grape-soda hover:shadow-md' }} transition group flex flex-col justify-between h-full cursor-pointer relative">
            @if($isTrial)
                <div class="absolute top-4 right-4 text-graphite bg-cloud-gray/30 p-2 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
            @endif
            <div>
                <div class="w-12 h-12 rounded-xl {{ $isTrial ? 'bg-cloud-gray/20 text-graphite' : 'bg-grape-soda/10 text-grape-soda' }} flex items-center justify-center mb-4 {{ !$isTrial ? 'group-hover:scale-110' : '' }} transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h3 class="font-bold text-charcoal text-lg mb-2">LKPD Generator</h3>
                <p class="text-graphite text-sm leading-relaxed mb-6">Buat Lembar Kerja Peserta Didik (LKPD) yang menarik dan interaktif untuk tugas individu maupun kelompok.</p>
            </div>
            <span class="{{ $isTrial ? 'text-graphite' : 'text-grape-soda' }} font-bold text-xs uppercase tracking-wider flex items-center gap-1">
                {{ $isTrial ? 'Terkunci (Upgrade)' : 'Mulai Menggunakan' }}
                @if(!$isTrial)<svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>@endif
            </span>
        </a>

        <!-- Tool 5: Rubrik Penilaian -->
        <a href="{{ $isTrial ? route('langganan') : route('tools.rubrik-penilaian') }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray {{ $isTrial ? 'opacity-70 hover:border-cloud-gray' : 'hover:border-sunshine-yellow hover:shadow-md' }} transition group flex flex-col justify-between h-full cursor-pointer relative">
            @if($isTrial)
                <div class="absolute top-4 right-4 text-graphite bg-cloud-gray/30 p-2 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
            @endif
            <div>
                <div class="w-12 h-12 rounded-xl {{ $isTrial ? 'bg-cloud-gray/20 text-graphite' : 'bg-sunshine-yellow/20 text-sunshine-yellow' }} flex items-center justify-center mb-4 {{ !$isTrial ? 'group-hover:scale-110' : '' }} transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <h3 class="font-bold text-charcoal text-lg mb-2">Rubrik Penilaian</h3>
                <p class="text-graphite text-sm leading-relaxed mb-6">Susun matriks kriteria rubrik penilaian secara instan dan buat lembar nilai lengkap dengan daftar siswa.</p>
            </div>
            <span class="{{ $isTrial ? 'text-graphite' : 'text-sunshine-yellow' }} font-bold text-xs uppercase tracking-wider flex items-center gap-1">
                {{ $isTrial ? 'Terkunci (Upgrade)' : 'Mulai Menggunakan' }}
                @if(!$isTrial)<svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>@endif
            </span>
        </a>

        <!-- Tool 6: Bahan Ajar AI -->
        <a href="{{ $isTrial ? route('langganan') : route('tools.bahan-ajar-utama') }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray {{ $isTrial ? 'opacity-70 hover:border-cloud-gray' : 'hover:border-grape-soda hover:shadow-md' }} transition group flex flex-col justify-between h-full cursor-pointer relative">
            @if($isTrial)
                <div class="absolute top-4 right-4 text-graphite bg-cloud-gray/30 p-2 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
            @endif
            <div>
                <div class="w-12 h-12 rounded-xl {{ $isTrial ? 'bg-cloud-gray/20 text-graphite' : 'bg-grape-soda/10 text-grape-soda' }} flex items-center justify-center mb-4 {{ !$isTrial ? 'group-hover:scale-110' : '' }} transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                </div>
                <h3 class="font-bold text-charcoal text-lg mb-2">Bahan Ajar AI</h3>
                <p class="text-graphite text-sm leading-relaxed mb-6">Buat paket bahan ajar komprehensif berisi rangkuman materi mendalam, peta konsep visual, LKPD aktivitas siswa, dan kuis pemahaman.</p>
            </div>
            <span class="{{ $isTrial ? 'text-graphite' : 'text-grape-soda' }} font-bold text-xs uppercase tracking-wider flex items-center gap-1">
                {{ $isTrial ? 'Terkunci (Upgrade)' : 'Mulai Menggunakan' }}
                @if(!$isTrial)<svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>@endif
            </span>
        </a>

        <!-- Tool 7: Prompt Guru AI -->
        <a href="{{ $isTrial ? route('langganan') : route('tools.prompt-guru') }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray {{ $isTrial ? 'opacity-70 hover:border-cloud-gray' : 'hover:border-grape-soda hover:shadow-md' }} transition group flex flex-col justify-between h-full cursor-pointer relative">
            @if($isTrial)
                <div class="absolute top-4 right-4 text-graphite bg-cloud-gray/30 p-2 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
            @endif
            <div>
                <div class="w-12 h-12 rounded-xl {{ $isTrial ? 'bg-cloud-gray/20 text-graphite' : 'bg-grape-soda/10 text-grape-soda' }} flex items-center justify-center mb-4 {{ !$isTrial ? 'group-hover:scale-110' : '' }} transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 113.536 0V21h2v-5.343z"></path></svg>
                </div>
                <h3 class="font-bold text-charcoal text-lg mb-2">Prompt Guru AI</h3>
                <p class="text-graphite text-sm leading-relaxed mb-6">Rakit prompt instruksi super detail dan teroptimasi untuk disalin ke Google Gemini, ChatGPT, atau DeepSeek secara instan.</p>
            </div>
            <span class="{{ $isTrial ? 'text-graphite' : 'text-grape-soda' }} font-bold text-xs uppercase tracking-wider flex items-center gap-1">
                {{ $isTrial ? 'Terkunci (Upgrade)' : 'Mulai Menggunakan' }}
                @if(!$isTrial)<svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>@endif
            </span>
        </a>

    </div>

    <!-- Spacer -->
    <div class="h-12"></div>
@endsection
