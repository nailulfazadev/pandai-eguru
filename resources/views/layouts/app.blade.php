<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PandAI - Asisten Guru AI')</title>
    
    <!-- Fonts Fallback (Baloo 2 for headlines, Nunito Sans for body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@700&family=Nunito+Sans:opsz,wght@6..12,500;6..12,700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v4 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <!-- Marked.js CDN for Markdown Rendering -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    
    <!-- PptxGenJS CDN for PowerPoint Presentation Export -->
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/PptxGenJS@3.12.0/dist/pptxgen.bundle.js"></script>
    
    <!-- Custom Theme config based on design.md -->
    <style type="text/tailwindcss">
        @theme {
            /* Colors */
            --color-duo-green: #58cc02;
            --color-sky-blue: #1cb0f6;
            --color-duo-green-light: #d7ffb8;
            --color-sunshine-yellow: #ffc700;
            --color-grape-soda: #a570ff;
            --color-bubblegum-pink: #cc348d;
            --color-snow-white: #ffffff;
            --color-cloud-gray: #e5e5e5;
            --color-silver: #afafaf;
            --color-graphite: #777777;
            --color-charcoal: #4b4b4b;
            --color-almost-black: #3c3c3c;

            /* Typography */
            --font-feather: 'Baloo 2', 'feather', ui-sans-serif, system-ui, sans-serif;
            --font-din-round: 'Nunito Sans', 'din-round', ui-sans-serif, system-ui, sans-serif;

            /* Typography — Scale */
            --text-caption: 13px;
            --text-body: 15px;
            --text-heading-sm: 19px;
            --text-heading: 32px;
            --text-heading-lg: 48px;
            --text-display: 64px;

            /* Border Radius */
            --radius-xl: 12px;
        }

        body {
            font-family: var(--font-din-round);
            background-color: var(--color-snow-white);
            color: var(--color-almost-black);
            letter-spacing: 0.053em; /* From din-round spec */
        }

        h1, h2, .font-feather {
            font-family: var(--font-feather);
            letter-spacing: -0.02em;
        }

        /* 3D Button Style */
        .btn-3d-primary {
            background-color: var(--color-duo-green);
            color: var(--color-snow-white);
            border-radius: var(--radius-xl);
            padding: 12px 24px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 4px 0 #3f8f01;
            transition: transform 0.1s, box-shadow 0.1s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
        }
        .btn-3d-primary:active {
            transform: translateY(4px);
            box-shadow: 0 0 0 #3f8f01;
        }

        .btn-outline {
            background-color: transparent;
            color: var(--color-sky-blue);
            border: 2px solid var(--color-cloud-gray);
            border-radius: var(--radius-xl);
            padding: 10px 24px;
            font-weight: 700;
            text-transform: uppercase;
            transition: background-color 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .btn-outline:hover {
            background-color: #f7f7f7;
        }

        /* Left Sidebar Responsive Fix */
        #left-sidebar {
            transform: translateX(-100%) !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }
        #left-sidebar.translate-x-0 {
            transform: translateX(0) !important;
        }
        @media (min-width: 768px) {
            #left-sidebar {
                transform: translateX(0) !important;
                position: relative !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body class="flex h-screen overflow-hidden antialiased">

    <!-- Left Sidebar -->
    <aside id="left-sidebar" class="w-64 max-w-[85vw] fixed md:relative md:max-w-none inset-y-0 left-0 z-40 border-r-2 border-cloud-gray flex flex-col justify-between h-full bg-snow-white select-none transform -translate-x-full md:translate-x-0 transition-transform duration-300">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="block">
                    <h1 class="text-duo-green text-3xl font-feather mb-8">PandAI</h1>
                </a>
                <button onclick="toggleSidebar(false)" class="md:hidden text-graphite hover:text-almost-black transition mb-8 cursor-pointer" title="Tutup Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <nav class="space-y-2">
                <!-- Dashboard Link -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('dashboard') ? 'text-almost-black bg-duo-green-light/30' : 'text-graphite hover:bg-cloud-gray/30 transition' }}">
                    <svg class="w-6 h-6 {{ Request::is('dashboard') ? 'text-duo-green' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Dashboard</span>
                </a>
                
                <!-- Semua Tools -->
                <a href="{{ route('tools.index') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('tools') ? 'text-almost-black bg-duo-green-light/30' : 'text-graphite hover:bg-cloud-gray/30 transition' }}">
                    <svg class="w-6 h-6 {{ Request::is('tools') ? 'text-duo-green' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span>Semua Tools</span>
                </a>

                @php
                    $isTrial = auth()->user() ? auth()->user()->isTrial() : false;
                @endphp

                <!-- Kelas & Jurnal (Premium) -->
                <a href="{{ $isTrial ? route('langganan') : route('classrooms.index') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('kelas*') || Request::is('jurnal*') ? 'text-almost-black bg-duo-green-light/30' : 'text-graphite hover:bg-cloud-gray/30 transition' }} relative group">
                    <svg class="w-6 h-6 {{ Request::is('kelas*') || Request::is('jurnal*') ? 'text-duo-green' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Kelas & Jurnal</span>
                    @if($isTrial)
                    <div class="absolute right-3 text-silver">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    @endif
                </a>

                
                @php
                    $isTrial = auth()->check() ? auth()->user()->isTrial() : false;
                @endphp
                
                <!-- Generator Soal Link -->
                <a href="{{ $isTrial ? route('langganan') : route('tools.generator-soal') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('generator-soal') ? 'text-almost-black bg-duo-green-light/30' : 'text-graphite hover:bg-cloud-gray/30 transition' }}">
                    <svg class="w-6 h-6 {{ Request::is('generator-soal') ? 'text-sky-blue' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Generator Soal {!! $isTrial ? '<svg class="w-4 h-4 inline text-graphite ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>' : '' !!}</span>
                </a>
                
                <!-- Modul Ajar Link -->
                <a href="{{ route('tools.modul-ajar') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('modul-ajar') ? 'text-almost-black bg-duo-green-light/30' : 'text-graphite hover:bg-cloud-gray/30 transition' }}">
                    <svg class="w-6 h-6 {{ Request::is('modul-ajar') ? 'text-duo-green' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span>Modul Ajar</span>
                </a>
                
                <!-- LKPD Generator Link -->
                <a href="{{ $isTrial ? route('langganan') : route('tools.lkpd-generator') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('lkpd-generator') ? 'bg-snow-white text-almost-black shadow-[2px_2px_0_#ccc]' : 'text-graphite hover:bg-cloud-gray/30 transition' }}">
                    <svg class="w-6 h-6 {{ Request::is('lkpd-generator') ? 'text-grape-soda' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>LKPD Generator {!! $isTrial ? '<svg class="w-4 h-4 inline text-graphite ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>' : '' !!}</span>
                </a>
                
                <!-- Bahan Ajar -->
                <a href="{{ $isTrial ? route('langganan') : route('tools.bahan-ajar-utama') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('bahan-ajar-utama') ? 'bg-snow-white text-almost-black shadow-[2px_2px_0_#ccc]' : 'text-graphite hover:bg-cloud-gray/30 transition' }}">
                    <svg class="w-6 h-6 {{ Request::is('bahan-ajar-utama') ? 'text-grape-soda' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                    <span>Bahan Ajar {!! $isTrial ? '<svg class="w-4 h-4 inline text-graphite ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>' : '' !!}</span>
                </a>
                
                <!-- PPT Pembelajaran Link -->
                <a href="{{ $isTrial ? route('langganan') : route('tools.ppt-pembelajaran') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('ppt-pembelajaran') ? 'text-almost-black bg-duo-green-light/30' : 'text-graphite hover:bg-cloud-gray/30 transition' }}">
                    <svg class="w-6 h-6 {{ Request::is('ppt-pembelajaran') ? 'text-bubblegum-pink' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    <span>PPT Pembelajaran {!! $isTrial ? '<svg class="w-4 h-4 inline text-graphite ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>' : '' !!}</span>
                </a>
                
                <!-- Rubrik Penilaian -->
                <a href="{{ $isTrial ? route('langganan') : route('tools.rubrik-penilaian') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('rubrik-penilaian') ? 'bg-snow-white text-almost-black shadow-[2px_2px_0_#ccc]' : 'text-graphite hover:bg-cloud-gray/30 transition' }}">
                    <svg class="w-6 h-6 {{ Request::is('rubrik-penilaian') ? 'text-sunshine-yellow' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span>Rubrik Penilaian {!! $isTrial ? '<svg class="w-4 h-4 inline text-graphite ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>' : '' !!}</span>
                </a>
                
                <!-- Prompt Guru AI -->
                <a href="{{ $isTrial ? route('langganan') : route('tools.prompt-guru') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('prompt-guru') ? 'bg-snow-white text-almost-black shadow-[2px_2px_0_#ccc]' : 'text-graphite hover:bg-cloud-gray/30 transition' }}">
                    <svg class="w-6 h-6 {{ Request::is('prompt-guru') ? 'text-grape-soda' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 113.536 0V21h2v-5.343z"></path></svg>
                    <span>Prompt Guru AI {!! $isTrial ? '<svg class="w-4 h-4 inline text-graphite ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>' : '' !!}</span>
                </a>
            </nav>
        </div>
        <div class="p-6 border-t-2 border-cloud-gray space-y-2">
            <!-- Riwayat -->
            <a href="{{ route('documents.index') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('riwayat') ? 'text-almost-black bg-duo-green-light/30' : 'text-graphite hover:bg-cloud-gray/30 transition' }}">
                <svg class="w-6 h-6 {{ Request::is('riwayat') ? 'text-duo-green' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Riwayat</span>
            </a>
            <!-- Langganan -->
            <a href="{{ route('langganan') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('langganan') || Request::is('checkout/*') ? 'text-almost-black bg-sunshine-yellow/30' : 'text-graphite hover:bg-cloud-gray/30 transition' }}">
                <svg class="w-6 h-6 {{ Request::is('langganan') || Request::is('checkout/*') ? 'text-sunshine-yellow' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <span>Langganan</span>
            </a>
            <!-- Bantuan -->
            <a href="{{ route('feedback.index') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('bantuan') ? 'bg-snow-white text-duo-green border-cloud-gray shadow-sm' : 'text-graphite hover:bg-cloud-gray transition' }}">
                <svg class="w-6 h-6 {{ Request::is('bantuan') ? 'text-duo-green' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <span>Bantuan & Masukan</span>
            </a>
            <!-- Pengaturan -->
            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('pengaturan') ? 'text-almost-black bg-duo-green-light/30' : 'text-graphite hover:bg-cloud-gray/30 transition' }}">
                <svg class="w-6 h-6 {{ Request::is('pengaturan') ? 'text-duo-green' : 'text-graphite' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>Pengaturan</span>
            </a>
            
            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center space-x-3 text-bubblegum-pink font-bold p-3 rounded-xl hover:bg-bubblegum-pink/10 transition border-2 border-transparent cursor-pointer">
                <svg class="w-6 h-6 text-bubblegum-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <!-- Backdrop for Mobile Sidebar -->
    <div id="sidebar-backdrop" onclick="closeAllSidebars()" class="fixed inset-0 bg-black/40 z-30 hidden"></div>

    <!-- Main Dashboard Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-[#f9f9f9]">
        @if(Auth::check() && Auth::user()->subscription_tier == 'early')
        <!-- Early Access Banner -->
        <div class="bg-[#ef4444] text-snow-white px-4 py-3 shadow-md border-b-2 border-[#b91c1c] text-center z-50 relative flex flex-col md:flex-row items-center justify-center gap-3 w-full shrink-0">
            <span class="font-medium text-sm md:text-base">🎉 Selamat datang di program Early Access! Bantu kami memberikan pengalaman terbaik dengan mengisi form evaluasi singkat.</span>
            <a href="https://e-guru.link/Angket-PandAI" target="_blank" class="bg-snow-white text-[#ef4444] px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide hover:bg-cloud-gray transition whitespace-nowrap">
                Isi Feedback Sekarang
            </a>
        </div>
        @endif

        <!-- Top Header -->
        <header class="h-20 bg-snow-white border-b-2 border-cloud-gray flex items-center justify-between px-4 md:px-8 select-none">
            <!-- Hamburger Menu Button (Mobile Only) -->
            <button onclick="toggleSidebar(true)" class="md:hidden mr-2 md:mr-4 text-graphite hover:text-almost-black transition cursor-pointer" title="Buka Menu">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            
            <div class="flex-1 max-w-[150px] sm:max-w-xl">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 md:pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 md:h-5 md:w-5 text-silver" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" class="block w-full pl-8 md:pl-11 pr-3 py-2 md:py-3 border-2 border-cloud-gray rounded-xl leading-5 bg-snow-white placeholder-silver focus:outline-none focus:border-sky-blue focus:ring-0 text-xs md:text-sm transition" placeholder="Cari...">
                </div>
            </div>
            <div class="flex items-center space-x-3 md:space-x-6 ml-2 md:ml-4">
                @php
                    $generationsToday = auth()->user()->generationsToday();
                    $limitText = min($generationsToday, 10) . '/10';
                    $limitColor = $generationsToday >= 10 ? 'text-bubblegum-pink' : 'text-graphite';
                @endphp
                <div class="hidden md:flex items-center space-x-2 px-3 py-1.5 bg-cloud-gray/30 rounded-xl border-2 border-transparent" title="Limit Generate Harian">
                    <svg class="w-5 h-5 text-sunshine-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span class="text-sm font-bold {{ $limitColor }}">{{ $limitText }}</span>
                </div>

                <!-- Toggle Assistant Button (Hidden for now as requested) -->
                <button id="toggle-assistant-btn" onclick="toggleAssistant()" class="hidden text-silver hover:text-duo-green transition relative cursor-pointer" title="Tanya Asisten AI">
                    <svg class="h-6 w-6 md:h-7 md:w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </button>
 
                <!-- Notification Dropdown Container -->
                <div class="relative">
                    <button id="notification-btn" class="text-silver hover:text-almost-black transition relative cursor-pointer" title="Notifikasi">
                        <span class="absolute top-0 right-0 w-2 h-2 md:w-2.5 md:h-2.5 bg-bubblegum-pink rounded-full border-2 border-snow-white"></span>
                        <svg class="h-6 w-6 md:h-7 md:w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </button>
                    <!-- Notification Dropdown -->
                    <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-64 bg-snow-white rounded-xl shadow-lg border-2 border-cloud-gray z-50 overflow-hidden">
                        <div class="p-3 border-b-2 border-cloud-gray font-bold text-sm text-charcoal">
                            Notifikasi Baru
                        </div>
                        <div class="p-4 text-center text-graphite text-sm">
                            Belum ada notifikasi
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown Container -->
                <div class="relative">
                    <button id="profile-btn" class="flex items-center space-x-2 md:space-x-3 cursor-pointer focus:outline-none bg-transparent border-none">
                        <img class="h-8 w-8 md:h-10 md:w-10 rounded-full border-2 border-cloud-gray" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=a570ff&color=fff" alt="User Profile">
                        <span class="font-bold text-charcoal hidden sm:inline text-sm md:text-base">{{ Auth::user()->name }}</span>
                        <svg class="h-4 w-4 text-silver hidden sm:block transition-transform duration-200" id="profile-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <!-- Profile Dropdown -->
                    <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-snow-white rounded-xl shadow-lg border-2 border-cloud-gray z-50 overflow-hidden">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-charcoal hover:bg-cloud-gray/30 transition border-b border-cloud-gray flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Edit Profil
                        </a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-3 text-sm text-bubblegum-pink hover:bg-bubblegum-pink/10 transition font-bold flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Scrollable -->
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-5xl mx-auto space-y-10">
                @yield('content')
            </div>
        </div>
    </main>

    <!-- Right Sidebar AI Assistant -->
    <aside id="assistant-sidebar" class="w-0 border-l-0 overflow-hidden flex flex-col h-full bg-snow-white shadow-[-4px_0_15px_rgba(0,0,0,0.02)] z-40 fixed right-0 inset-y-0 md:relative md:z-10 transition-all duration-300 max-w-[85vw] md:max-w-none">
        <div class="w-80 max-w-full flex flex-col h-full flex-shrink-0">
            <!-- Header -->
            <div class="p-6 border-b-2 border-cloud-gray flex items-center justify-between bg-duo-green text-snow-white select-none">
                <div class="flex items-center space-x-3">
              <a href="{{ route('langganan') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('langganan') ? 'bg-snow-white text-duo-green border-cloud-gray shadow-sm' : 'text-graphite hover:bg-cloud-gray transition' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                <span>Langganan</span>
            </a>

            <a href="{{ route('feedback.index') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('bantuan') ? 'bg-snow-white text-duo-green border-cloud-gray shadow-sm' : 'text-graphite hover:bg-cloud-gray transition' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <span>Bantuan & Masukan</span>
            </a>
        </nav>      <h2 class="text-heading-sm font-feather m-0 leading-none mt-1">Asisten Guru AI</h2>
                </div>
                <!-- Close Button -->
                <button onclick="toggleAssistant(false)" class="text-snow-white hover:text-cloud-gray transition cursor-pointer" title="Tutup Asisten">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Chat Content Area -->
            <div id="chat-messages" class="flex-1 p-6 overflow-y-auto bg-[#fbfbfb] space-y-4">
                <!-- Chat Message Assistant -->
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-full bg-duo-green flex items-center justify-center flex-shrink-0 text-white select-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <div class="bg-snow-white border-2 border-cloud-gray p-3 rounded-2xl rounded-tl-none shadow-sm text-sm text-graphite max-w-[85%]">
                        Halo! Saya Asisten AI PandAI. Apa yang ingin Anda buat hari ini?
                    </div>
                </div>

                <!-- Suggestions -->
                <div id="suggestions-container" class="space-y-2 mt-4 w-full">
                    <button onclick="sendSuggestion('Buat soal matematika kelas 5')" class="w-full text-left bg-snow-white border-2 border-cloud-gray p-3 rounded-xl hover:border-sky-blue hover:text-sky-blue transition text-sm text-charcoal font-bold flex items-center justify-between group cursor-pointer">
                        <span>Buat soal matematika kelas 5</span>
                        <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    <button onclick="sendSuggestion('Buat modul ajar IPA')" class="w-full text-left bg-snow-white border-2 border-cloud-gray p-3 rounded-xl hover:border-duo-green hover:text-duo-green transition text-sm text-charcoal font-bold flex items-center justify-between group cursor-pointer">
                        <span>Buat modul ajar IPA</span>
                        <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    <button onclick="sendSuggestion('Buat PPT tentang fotosintesis')" class="w-full text-left bg-snow-white border-2 border-cloud-gray p-3 rounded-xl hover:border-bubblegum-pink hover:text-bubblegum-pink transition text-sm text-charcoal font-bold flex items-center justify-between group cursor-pointer">
                        <span>Buat PPT tentang fotosintesis</span>
                        <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>

                <!-- Typing Indicator -->
                <div id="typing-indicator" class="flex items-start space-x-3 hidden select-none">
                    <div class="w-8 h-8 rounded-full bg-duo-green flex items-center justify-center flex-shrink-0 text-white animate-pulse">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <div class="bg-snow-white border-2 border-cloud-gray p-3 rounded-2xl rounded-tl-none shadow-sm text-sm text-graphite flex items-center space-x-1">
                        <span class="w-1.5 h-1.5 bg-silver rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-silver rounded-full animate-bounce [animation-delay:0.2s]"></span>
                        <span class="w-1.5 h-1.5 bg-silver rounded-full animate-bounce [animation-delay:0.4s]"></span>
                    </div>
                </div>
            </div>

            <!-- Input Box Area -->
            <div class="p-4 border-t-2 border-cloud-gray bg-snow-white">
                <div class="relative">
                    <textarea id="chat-input" class="w-full border-2 border-cloud-gray rounded-xl py-3 pl-4 pr-12 text-sm bg-[#f9f9f9] focus:outline-none focus:border-duo-green focus:bg-snow-white resize-none transition" rows="2" placeholder="Ketik permintaan Anda di sini..."></textarea>
                    <button id="chat-submit" class="absolute bottom-3 right-3 w-8 h-8 bg-duo-green text-snow-white rounded-lg flex items-center justify-center hover:bg-[#4ea802] transition shadow-[0_2px_0_#3f8f01] active:translate-y-0.5 active:shadow-none cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <!-- Interactive script for Assistant -->
    <script>
        function closeAllSidebars() {
            toggleSidebar(false);
            toggleAssistant(false);
        }

        function toggleSidebar(show) {
            const sidebar = document.getElementById('left-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (show === undefined) {
                show = sidebar.classList.contains('-translate-x-full');
            }
            
            if (show) {
                if (window.innerWidth < 768) {
                    toggleAssistant(false);
                }
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                const assistant = document.getElementById('assistant-sidebar');
                if (assistant.classList.contains('w-0') || window.innerWidth >= 768) {
                    backdrop.classList.add('hidden');
                }
            }
        }

        // Close sidebar on resize if screen becomes md or larger
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                const sidebar = document.getElementById('left-sidebar');
                const backdrop = document.getElementById('sidebar-backdrop');
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                
                const assistant = document.getElementById('assistant-sidebar');
                const isOpen = localStorage.getItem('assistant-open') === 'true';
                if (!isOpen) {
                    assistant.classList.remove('w-80', 'border-l-2', 'border-cloud-gray');
                    assistant.classList.add('w-0', 'border-l-0', 'overflow-hidden');
                }
                backdrop.classList.add('hidden');
            }
        });

        function toggleAssistant(show) {
            const sidebar = document.getElementById('assistant-sidebar');
            const toggleBtn = document.getElementById('toggle-assistant-btn');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (show === undefined) {
                show = sidebar.classList.contains('w-0');
            }
            
            if (show) {
                if (window.innerWidth < 768) {
                    toggleSidebar(false);
                    backdrop.classList.remove('hidden');
                }
                sidebar.classList.remove('w-0', 'border-l-0', 'overflow-hidden');
                sidebar.classList.add('w-80', 'border-l-2', 'border-cloud-gray');
                toggleBtn.classList.remove('text-silver');
                toggleBtn.classList.add('text-duo-green');
                localStorage.setItem('assistant-open', 'true');
            } else {
                sidebar.classList.remove('w-80', 'border-l-2', 'border-cloud-gray');
                sidebar.classList.add('w-0', 'border-l-0', 'overflow-hidden');
                toggleBtn.classList.remove('text-duo-green');
                toggleBtn.classList.add('text-silver');
                localStorage.setItem('assistant-open', 'false');
                if (window.innerWidth < 768) {
                    backdrop.classList.add('hidden');
                }
            }
        }

        // Initialize state on load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('assistant-sidebar');
            const toggleBtn = document.getElementById('toggle-assistant-btn');
            const isOpen = localStorage.getItem('assistant-open') === 'true';
            
            if (isOpen && window.innerWidth >= 768) {
                sidebar.classList.remove('w-0', 'border-l-0', 'overflow-hidden');
                sidebar.classList.add('w-80', 'border-l-2', 'border-cloud-gray');
                toggleBtn.classList.remove('text-silver');
                toggleBtn.classList.add('text-duo-green');
            }
        });

        const chatMessages = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');
        const chatSubmit = document.getElementById('chat-submit');
        const typingIndicator = document.getElementById('typing-indicator');
        const suggestionsContainer = document.getElementById('suggestions-container');

        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function formatMarkdown(text) {
            return marked.parse(text);
        }

        function appendMessage(sender, text, isMock = false) {
            const msgDiv = document.createElement('div');
            
            if (sender === 'user') {
                msgDiv.className = 'flex items-start justify-end space-x-3';
                msgDiv.innerHTML = `
                    <div class="bg-duo-green-light/35 border-2 border-duo-green-light p-3 rounded-2xl rounded-tr-none shadow-sm text-sm text-almost-black max-w-[85%] break-words">
                        ${formatMarkdown(text)}
                    </div>
                `;
            } else {
                msgDiv.className = 'flex items-start space-x-3';
                
                let mockBadge = '';
                if (isMock) {
                    mockBadge = `
                        <div class="hidden text-[10px] text-amber-600 font-bold mt-1.5 flex items-center gap-1 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 w-fit select-none">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Mode Demo
                        </div>
                    `;
                }

                msgDiv.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-duo-green flex items-center justify-center flex-shrink-0 text-white select-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <div class="flex flex-col max-w-[85%]">
                        <div class="bg-snow-white border-2 border-cloud-gray p-3 rounded-2xl rounded-tl-none shadow-sm text-sm text-graphite break-words">
                            ${formatMarkdown(text)}
                        </div>
                        ${mockBadge}
                    </div>
                `;
            }
            
            // Insert before typing indicator to maintain it at the end
            chatMessages.insertBefore(msgDiv, typingIndicator);
            scrollToBottom();
        }

        async function handleSend() {
            const text = chatInput.value.trim();
            if (!text) return;
            
            // Hide initial suggestions once conversation starts
            if (suggestionsContainer) {
                suggestionsContainer.classList.add('hidden');
            }

            chatInput.value = '';
            appendMessage('user', text);
            
            // Show typing indicator
            typingIndicator.classList.remove('hidden');
            scrollToBottom();
            
            try {
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
                
                const response = await fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ prompt: text })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    appendMessage('assistant', data.response, data.is_mock);
                } else {
                    appendMessage('assistant', `Maaf, terjadi kesalahan: ${data.error || 'Terjadi kesalahan sistem.'}`);
                }
            } catch (error) {
                console.error(error);
                appendMessage('assistant', 'Maaf, gagal menghubungi server. Silakan periksa koneksi Anda.');
            } finally {
                // Hide typing indicator
                typingIndicator.classList.add('hidden');
                scrollToBottom();
            }
        }

        function sendSuggestion(promptText) {
            chatInput.value = promptText;
            handleSend();
        }

        chatSubmit.addEventListener('click', handleSend);
        
        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                handleSend();
            }
        });

        // Dropdown Logic for Header
        const profileBtn = document.getElementById('profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');
        const profileChevron = document.getElementById('profile-chevron');
        const notifBtn = document.getElementById('notification-btn');
        const notifDropdown = document.getElementById('notification-dropdown');

        if (profileBtn) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
                if(profileChevron) profileChevron.classList.toggle('rotate-180');
                if(notifDropdown) notifDropdown.classList.add('hidden');
            });
        }

        if (notifBtn) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.classList.toggle('hidden');
                if(profileDropdown) profileDropdown.classList.add('hidden');
                if(profileChevron) profileChevron.classList.remove('rotate-180');
            });
        }

        window.addEventListener('click', () => {
            if(profileDropdown) profileDropdown.classList.add('hidden');
            if(profileChevron) profileChevron.classList.remove('rotate-180');
            if(notifDropdown) notifDropdown.classList.add('hidden');
        });

        if(profileDropdown) profileDropdown.addEventListener('click', (e) => e.stopPropagation());
        if(notifDropdown) notifDropdown.addEventListener('click', (e) => e.stopPropagation());
    </script>
    @yield('scripts')
</body>
</html>
