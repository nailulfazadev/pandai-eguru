<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - PandAI</title>
    
    <!-- Fonts Fallback (Baloo 2 for headlines, Nunito Sans for body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@700&family=Nunito+Sans:opsz,wght@6..12,500;6..12,700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v4 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
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
            letter-spacing: 0.053em;
        }

        h1, h2, h3, .font-feather {
            font-family: var(--font-feather);
            letter-spacing: -0.02em;
        }

        /* 3D Button Style */
        .btn-3d-primary {
            background-color: var(--color-duo-green);
            color: var(--color-snow-white);
            border-radius: var(--radius-xl);
            padding: 14px 24px;
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
    </style>
</head>
<body class="bg-[#f9f9f9] min-h-screen flex items-center justify-center p-6 antialiased">

    <div class="w-full max-w-md">
        <!-- Brand Logo Header -->
        <div class="text-center mb-8">
            <h1 class="text-duo-green text-5xl font-feather mb-2">PandAI</h1>
            <p class="text-graphite font-bold">Masuk ke Ruang Kerja Asisten Guru AI Anda</p>
        </div>

        <!-- Form Box -->
        <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-8 shadow-[0_4px_0_#e5e5e5] relative">
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-bubblegum-pink/15 border-2 border-bubblegum-pink text-bubblegum-pink p-4 rounded-xl text-sm font-bold flex items-start space-x-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Email Input -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-bold text-charcoal">Alamat Email</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full border-2 border-cloud-gray rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-sky-blue focus:bg-snow-white transition bg-[#f9f9f9]"
                               placeholder="guru@pandai.com">
                    </div>
                </div>

                <!-- Password (No HP) Input -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label for="password" class="block text-sm font-bold text-charcoal">Nomor Handphone (Password)</label>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               class="w-full border-2 border-cloud-gray rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-sky-blue focus:bg-snow-white transition bg-[#f9f9f9]"
                               placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="h-4 w-4 border-2 border-cloud-gray text-duo-green focus:ring-0 rounded cursor-pointer">
                    <label for="remember" class="ml-2 block text-sm font-bold text-graphite cursor-pointer select-none">Ingat saya di perangkat ini</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-3d-primary w-full text-center tracking-wider text-sm select-none">
                    Masuk Sekarang
                </button>
            </form>
        </div>

        <!-- Seeder Info Note -->
        <div class="mt-6 bg-sunshine-yellow/15 border-2 border-sunshine-yellow rounded-xl p-4 text-xs text-charcoal font-bold space-y-1 shadow-[0_2px_0_#ffc700/20] select-none">
            <div class="flex items-center space-x-1.5 text-amber-600">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span>Akun Demo Uji Coba:</span>
            </div>
            <p class="font-normal text-graphite">Email: <code class="bg-snow-white px-1.5 py-0.5 rounded border border-cloud-gray font-bold text-almost-black">guru@pandai.com</code></p>
            <p class="font-normal text-graphite">Nomor HP / Password: <code class="bg-snow-white px-1.5 py-0.5 rounded border border-cloud-gray font-bold text-almost-black">08123456789</code></p>
        </div>
    </div>

</body>
</html>
