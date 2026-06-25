<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - PandAI</title>
    
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
            --color-sunshine-yellow: #ffc700;
            --color-snow-white: #ffffff;
            --color-cloud-gray: #e5e5e5;
            --color-silver: #afafaf;
            --color-graphite: #777777;
            --color-almost-black: #4b4b4b;

            --font-feather: 'Baloo 2', ui-sans-serif, system-ui;
            --font-din-round: 'Nunito Sans', ui-sans-serif, system-ui;
        }

        body {
            font-family: var(--font-din-round);
            background-color: var(--color-snow-white);
            color: var(--color-almost-black);
            letter-spacing: 0.05em;
        }

        h1, h2, .font-feather {
            font-family: var(--font-feather);
        }

        /* 3D Button Styles */
        .btn-3d-primary {
            @apply inline-block bg-duo-green text-snow-white font-bold rounded-2xl px-6 py-3 transition-all duration-150 active:translate-y-1 active:shadow-none;
            box-shadow: 0 4px 0 #46a302;
        }
        
        .btn-3d-primary:hover {
            @apply bg-[#61df02];
        }

        .input-solid {
            @apply w-full bg-[#f7f7f7] border-2 border-cloud-gray rounded-xl px-4 py-3 font-bold text-almost-black outline-none transition-colors;
        }
        
        .input-solid:focus {
            @apply border-sky-blue bg-snow-white;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row">
    <!-- Left Side: Branding/Image -->
    <div class="hidden md:flex md:w-1/2 bg-duo-green items-center justify-center p-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
        <div class="relative z-10 text-center text-snow-white">
            <h1 class="text-6xl font-feather mb-6 drop-shadow-md">PandAI</h1>
            <p class="text-xl font-bold mb-8">Asisten AI Pintar untuk Guru Indonesia.</p>
            <div class="inline-block bg-snow-white/20 px-6 py-3 rounded-2xl border-2 border-snow-white/30 backdrop-blur-sm">
                <p class="text-sm font-bold">Mulai dengan Trial 7 Hari 🚀</p>
            </div>
        </div>
    </div>

    <!-- Right Side: Form -->
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <!-- Mobile Header -->
            <div class="md:hidden text-center mb-10">
                <h1 class="text-5xl font-feather text-duo-green">PandAI</h1>
            </div>

            <h2 class="text-3xl font-feather text-almost-black mb-2 text-center">
                @if(request('plan') == 'early')
                    Pendaftaran Early Access
                @else
                    Daftar Akun Baru
                @endif
            </h2>
            <p class="text-graphite text-center font-bold mb-8">
                @if(request('plan') == 'early')
                    Dapatkan akses penuh 1 bulan gratis sebagai pengguna perdana!
                @else
                    Dapatkan akses gratis 7 hari Modul Ajar!
                @endif
            </p>

            @if($errors->any())
                <div class="bg-[#ff4b4b]/10 border-l-4 border-[#ff4b4b] text-[#ff4b4b] p-4 rounded-xl mb-6 font-bold text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="plan" value="{{ request('plan') }}">
                
                <div>
                    <label for="name" class="block text-sm font-bold text-graphite mb-2 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="input-solid" placeholder="Mochammad Ali, S.Pd" value="{{ old('name') }}" required autofocus>
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-graphite mb-2 uppercase tracking-wide">Email</label>
                    <input type="email" id="email" name="email" class="input-solid" placeholder="guru@contoh.com" value="{{ old('email') }}" required>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-bold text-graphite mb-2 uppercase tracking-wide">Nomor Handphone (WhatsApp)</label>
                    <input type="text" id="phone" name="phone" class="input-solid" placeholder="08123456789" required>
                    <p class="text-xs text-graphite mt-2 font-bold">Nomor HP Anda akan digunakan sebagai <span class="text-duo-green">Password</span> untuk masuk.</p>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn-3d-primary w-full text-center text-lg uppercase tracking-wider py-4">DAFTAR SEKARANG</button>
                </div>
            </form>

            <div class="mt-8 text-center text-graphite font-bold text-sm">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-duo-green hover:text-[#46a302] uppercase tracking-wide ml-1 transition">Masuk di sini</a>
            </div>
            
            <div class="mt-8 text-center text-graphite font-bold text-sm border-t-2 border-cloud-gray pt-6">
                Ingin langsung akses Premium? 
                <a href="https://solusiedu.myr.id/pl/PandAI-by-e-Guru?email={{ auth()->check() ? urlencode(auth()->user()->email) : '' }}&name={{ auth()->check() ? urlencode(auth()->user()->name) : '' }}&mobile={{ auth()->check() && auth()->user()->phone ? urlencode(auth()->user()->phone) : '' }}" target="_blank" class="text-sunshine-yellow hover:text-[#e5b300] uppercase tracking-wide ml-1 transition">Beli Paket Premium</a>
            </div>
        </div>
    </div>
</body>
</html>
