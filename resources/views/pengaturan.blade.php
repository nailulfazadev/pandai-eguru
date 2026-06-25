@extends('layouts.app')

@section('title', 'Pengaturan - PandAI')

@section('content')
    <!-- Header -->
    <section class="select-none">
        <h2 class="text-heading font-feather text-almost-black">Pengaturan Profil</h2>
        <p class="text-body text-graphite">Sesuaikan data identitas guru dan sekolah Anda untuk digunakan pada kop surat dan tanda tangan Modul Ajar.</p>
    </section>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-duo-green-light/20 border-2 border-duo-green text-duo-green p-4 rounded-xl text-sm font-bold flex items-center space-x-2 select-none shadow-sm mb-4">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-bubblegum-pink/15 border-2 border-bubblegum-pink text-bubblegum-pink p-4 rounded-xl text-sm font-bold flex items-start space-x-2 select-none shadow-sm mb-4">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl bg-snow-white border-2 border-cloud-gray rounded-2xl p-8 shadow-sm">
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf

            <h3 class="text-heading-sm font-feather text-almost-black border-b-2 border-cloud-gray pb-3 select-none">Data Identitas Guru</h3>
            
            <div class="grid grid-cols-2 gap-6">
                <!-- Nama Guru -->
                <div class="space-y-1 col-span-2 sm:col-span-1">
                    <label for="name" class="block text-sm font-bold text-charcoal">Nama Lengkap Guru</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- NIP Guru -->
                <div class="space-y-1 col-span-2 sm:col-span-1">
                    <label for="nip" class="block text-sm font-bold text-charcoal">NIP Guru</label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip', $user->nip) }}" placeholder="Contoh: 198503112009121002"
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- Email -->
                <div class="space-y-1 col-span-2 sm:col-span-1">
                    <label for="email" class="block text-sm font-bold text-charcoal">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- Nomor HP -->
                <div class="space-y-1 col-span-2 sm:col-span-1">
                    <label for="phone" class="block text-sm font-bold text-charcoal">Nomor Handphone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789"
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>
            </div>

            <h3 class="text-heading-sm font-feather text-almost-black border-b-2 border-cloud-gray pb-3 pt-4 select-none">Data Instansi & Kepala Sekolah</h3>

            <div class="grid grid-cols-2 gap-6">
                <!-- Nama Instansi / Sekolah -->
                <div class="space-y-1 col-span-2">
                    <label for="school_name" class="block text-sm font-bold text-charcoal">Nama Instansi / Sekolah</label>
                    <input type="text" id="school_name" name="school_name" value="{{ old('school_name', $user->school_name) }}" placeholder="Contoh: SD Negeri 1 Merdeka"
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- Nama Kepala Sekolah -->
                <div class="space-y-1 col-span-2 sm:col-span-1">
                    <label for="principal_name" class="block text-sm font-bold text-charcoal">Nama Kepala Sekolah</label>
                    <input type="text" id="principal_name" name="principal_name" value="{{ old('principal_name', $user->principal_name) }}" placeholder="Untuk tanda tangan Modul Ajar"
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- NIP Kepala Sekolah -->
                <div class="space-y-1 col-span-2 sm:col-span-1">
                    <label for="principal_nip" class="block text-sm font-bold text-charcoal">NIP Kepala Sekolah</label>
                    <input type="text" id="principal_nip" name="principal_nip" value="{{ old('principal_nip', $user->principal_nip) }}" placeholder="Contoh: 197204151998032001"
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>
            </div>

            <h3 class="text-heading-sm font-feather text-almost-black border-b-2 border-cloud-gray pb-3 pt-4 select-none">Ubah Password (Opsional)</h3>
            <p class="text-xs font-bold text-graphite mb-2">Kosongkan bagian ini jika Anda tidak ingin mengubah password Anda.</p>

            <div class="grid grid-cols-2 gap-6">
                <!-- Current Password -->
                <div class="space-y-1 col-span-2">
                    <label for="current_password" class="block text-sm font-bold text-charcoal">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" placeholder="Masukkan password saat ini"
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- New Password -->
                <div class="space-y-1 col-span-2 sm:col-span-1">
                    <label for="new_password" class="block text-sm font-bold text-charcoal">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Minimal 6 karakter"
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>

                <!-- Confirm New Password -->
                <div class="space-y-1 col-span-2 sm:col-span-1">
                    <label for="new_password_confirmation" class="block text-sm font-bold text-charcoal">Konfirmasi Password Baru</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Ketik ulang password baru"
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition">
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t-2 border-cloud-gray flex justify-end">
                <button type="submit" class="btn-3d-primary text-sm px-6">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- spacer -->
    <div class="h-12"></div>
@endsection

@section('scripts')
    <script>
        // Sync database user profile details to localStorage on page load/redirect
        localStorage.setItem('sa_teacher_name', {!! json_encode($user->name ?: '') !!});
        localStorage.setItem('sa_teacher_nip', {!! json_encode($user->nip ?: '') !!});
        localStorage.setItem('sa_school_name', {!! json_encode($user->school_name ?: '') !!});
        localStorage.setItem('sa_principal_name', {!! json_encode($user->principal_name ?: '') !!});
        localStorage.setItem('sa_principal_nip', {!! json_encode($user->principal_nip ?: '') !!});
    </script>
@endsection
