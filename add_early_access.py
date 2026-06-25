import re

# 1. Update welcome.blade.php
with open('resources/views/welcome.blade.php', 'r') as f:
    content = f.read()

# Modify the pricing grid to accommodate 4 columns
content = content.replace('class="grid md:grid-cols-2 lg:grid-cols-3 gap-8"', 'class="grid md:grid-cols-2 lg:grid-cols-4 gap-6"')

early_access_html = """
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
"""

# Insert right after the Free Plan block
content = content.replace('<!-- Semester Plan -->', early_access_html + '\n                <!-- Semester Plan -->')

with open('resources/views/welcome.blade.php', 'w') as f:
    f.write(content)

print("Updated welcome.blade.php")

# 2. Update AuthController.php
with open('app/Http/Controllers/AuthController.php', 'r') as f:
    content = f.read()

# Update the register method
old_register_logic = """        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Illuminate\Support\Facades\Hash::make($request->phone),
            'subscription_tier' => 'trial',
            'subscription_ends_at' => now()->addDays(7),
            'is_admin' => false,
        ]);"""

new_register_logic = """        $plan = $request->input('plan', 'trial');
        $tier = 'trial';
        $endsAt = now()->addDays(7);

        if ($plan === 'early') {
            $tier = 'early';
            $endsAt = now()->addDays(30);
        }

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Illuminate\Support\Facades\Hash::make($request->phone),
            'subscription_tier' => $tier,
            'subscription_ends_at' => $endsAt,
            'is_admin' => false,
        ]);"""
content = content.replace(old_register_logic, new_register_logic)
with open('app/Http/Controllers/AuthController.php', 'w') as f:
    f.write(content)
print("Updated AuthController.php")

# 3. Update register.blade.php
with open('resources/views/auth/register.blade.php', 'r') as f:
    content = f.read()

if 'name="plan"' not in content:
    form_tag = '<form method="POST" action="{{ route(\'register\') }}" class="space-y-4">'
    form_with_plan = form_tag + '\n                <input type="hidden" name="plan" value="{{ request(\'plan\') }}">'
    content = content.replace(form_tag, form_with_plan)
    
    # Change heading based on plan
    old_heading = '<h2 class="text-3xl font-feather text-almost-black mb-2 text-center">Buat Akun Baru</h2>'
    new_heading = """<h2 class="text-3xl font-feather text-almost-black mb-2 text-center">
                    @if(request('plan') == 'early')
                        Pendaftaran Early Access
                    @else
                        Buat Akun Baru
                    @endif
                </h2>"""
    content = content.replace(old_heading, new_heading)
    
    old_subheading = '<p class="text-graphite font-medium text-center mb-6">Mulai pengalaman mengajar yang revolusioner!</p>'
    new_subheading = """<p class="text-graphite font-medium text-center mb-6">
                    @if(request('plan') == 'early')
                        Dapatkan akses penuh 1 bulan secara gratis sebagai pengguna awal!
                    @else
                        Mulai pengalaman mengajar yang revolusioner!
                    @endif
                </p>"""
    content = content.replace(old_subheading, new_subheading)

    with open('resources/views/auth/register.blade.php', 'w') as f:
        f.write(content)
    print("Updated register.blade.php")
else:
    print("register.blade.php already has plan input")

# 4. Update app.blade.php (add banner)
with open('resources/views/layouts/app.blade.php', 'r') as f:
    content = f.read()

if 'subscription_tier == \'early\'' not in content:
    header_start = '        <!-- Top navigation -->'
    banner_html = """
        @if(Auth::check() && Auth::user()->subscription_tier == 'early')
        <!-- Early Access Banner -->
        <div class="bg-[#ef4444] text-snow-white px-4 py-3 shadow-md border-b-4 border-[#b91c1c] text-center z-50 relative flex flex-col md:flex-row items-center justify-center gap-3">
            <span class="font-medium text-sm md:text-base">🎉 Selamat datang di program Early Access! Bantu kami memberikan pengalaman terbaik dengan mengisi form evaluasi singkat.</span>
            <a href="https://forms.gle/your-form-link-here" target="_blank" class="bg-snow-white text-[#ef4444] px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide hover:bg-cloud-gray transition whitespace-nowrap">
                Isi Feedback Sekarang
            </a>
        </div>
        @endif
        
        <!-- Top navigation -->"""
    content = content.replace(header_start, banner_html)
    with open('resources/views/layouts/app.blade.php', 'w') as f:
        f.write(content)
    print("Updated app.blade.php")
else:
    print("app.blade.php already has banner")

