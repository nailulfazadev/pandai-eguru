<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - PandAI')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@700&family=Nunito+Sans:opsz,wght@6..12,500;6..12,700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v4 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <!-- Custom Theme -->
    <style type="text/tailwindcss">
        @theme {
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
            --color-almost-black: #2a2a2a; /* Slightly darker for admin */

            --font-feather: 'Baloo 2', ui-sans-serif, system-ui;
            --font-din-round: 'Nunito Sans', ui-sans-serif, system-ui;
        }

        body {
            font-family: var(--font-din-round);
            background-color: #f1f1f1;
            color: var(--color-almost-black);
            letter-spacing: 0.05em;
        }

        h1, h2, .font-feather {
            font-family: var(--font-feather);
        }
    </style>
</head>
<body class="h-screen flex overflow-hidden">
    <!-- Admin Sidebar -->
    <aside id="sidebar" class="w-64 bg-[#2a2a2a] text-snow-white border-r-2 border-[#1a1a1a] flex flex-col h-full transform -translate-x-full md:translate-x-0 transition-transform duration-300 fixed md:relative z-40">
        <div class="h-20 flex items-center justify-between px-6 border-b-2 border-[#1a1a1a]">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <span class="text-2xl font-feather text-sky-blue">PandAI</span>
                <span class="bg-bubblegum-pink text-xs font-bold px-2 py-0.5 rounded-full">Admin</span>
            </a>
            <button onclick="toggleSidebar(false)" class="md:hidden text-silver hover:text-snow-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('admin') ? 'bg-[#3c3c3c] text-snow-white' : 'text-silver hover:bg-[#3c3c3c] transition' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Beranda</span>
            </a>

            <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('admin/pengguna') ? 'bg-[#3c3c3c] text-snow-white' : 'text-silver hover:bg-[#3c3c3c] transition' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span>Daftar Pengguna</span>
            </a>

            <a href="{{ route('admin.feedbacks') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('admin/masukan') ? 'bg-[#3c3c3c] text-snow-white' : 'text-silver hover:bg-[#3c3c3c] transition' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <span>Masukan Pengguna</span>
            </a>

            <a href="{{ route('admin.payments') }}" class="flex items-center space-x-3 font-bold p-3 rounded-xl border-2 border-transparent {{ Request::is('admin/pembayaran') ? 'bg-[#3c3c3c] text-snow-white' : 'text-silver hover:bg-[#3c3c3c] transition' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span>Daftar Pembayaran</span>
            </a>
        </div>

        <div class="p-6 border-t-2 border-[#1a1a1a]">
            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">@csrf</form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center space-x-3 text-bubblegum-pink font-bold p-3 rounded-xl hover:bg-bubblegum-pink/10 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span>Logout Admin</span>
            </a>
        </div>
    </aside>

    <div id="sidebar-backdrop" onclick="toggleSidebar(false)" class="fixed inset-0 bg-black/40 z-30 hidden"></div>

    <!-- Main Admin Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-[#f1f1f1]">
        <header class="h-20 bg-snow-white border-b-2 border-cloud-gray flex items-center justify-between px-4 md:px-8">
            <button onclick="toggleSidebar(true)" class="md:hidden text-graphite">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div class="flex-1"></div>
            <div class="flex items-center space-x-4">
                <span class="font-bold text-sm text-charcoal">{{ auth()->user()->name ?? 'Admin' }}</span>
                <div class="w-10 h-10 rounded-xl bg-bubblegum-pink text-snow-white flex items-center justify-center font-bold text-lg">
                    A
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleSidebar(show) {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (show) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
