@extends('layouts.admin')

@section('title', 'Admin - Tambah Paket Harga')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-heading font-feather text-almost-black mb-2">Tambah Paket Harga</h1>
                <p class="text-body text-graphite">Tambahkan paket berlangganan baru untuk pengguna.</p>
            </div>
            <a href="{{ route('admin.packages.index') }}" class="text-sky-blue font-bold hover:underline">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-8 shadow-sm">
            <form action="{{ route('admin.packages.store') }}" method="POST">
                @csrf
                
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-charcoal mb-2">Nama Paket</label>
                        <input type="text" name="name" class="w-full px-4 py-3 rounded-xl border-2 border-cloud-gray focus:border-sky-blue focus:outline-none" placeholder="Contoh: Paket Bulanan" required value="{{ old('name') }}">
                        @error('name') <span class="text-bubblegum-pink text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-charcoal mb-2">Slug (ID Unik)</label>
                        <input type="text" name="slug" class="w-full px-4 py-3 rounded-xl border-2 border-cloud-gray focus:border-sky-blue focus:outline-none" placeholder="Contoh: bulanan" required value="{{ old('slug') }}">
                        @error('slug') <span class="text-bubblegum-pink text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-charcoal mb-2">Harga (Rp)</label>
                        <input type="number" name="price" class="w-full px-4 py-3 rounded-xl border-2 border-cloud-gray focus:border-sky-blue focus:outline-none" placeholder="Contoh: 50000" required value="{{ old('price') }}">
                        @error('price') <span class="text-bubblegum-pink text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-charcoal mb-2">Durasi (Hari)</label>
                        <input type="number" name="duration_days" class="w-full px-4 py-3 rounded-xl border-2 border-cloud-gray focus:border-sky-blue focus:outline-none" placeholder="Contoh: 30" required value="{{ old('duration_days') }}">
                        @error('duration_days') <span class="text-bubblegum-pink text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-charcoal mb-2">Fitur (Gunakan baris baru untuk setiap fitur)</label>
                    <textarea name="features" rows="4" class="w-full px-4 py-3 rounded-xl border-2 border-cloud-gray focus:border-sky-blue focus:outline-none" placeholder="Akses semua tools&#10;Limit 10x per hari&#10;Dukungan prioritas">{{ old('features') }}</textarea>
                    <p class="text-xs text-graphite mt-1">Bisa menggunakan Markdown seperti **teks tebal**.</p>
                    @error('features') <span class="text-bubblegum-pink text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-8 flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="w-5 h-5 rounded border-cloud-gray text-sky-blue focus:ring-sky-blue" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-3 text-sm font-bold text-charcoal">Paket Aktif (Ditampilkan di halaman langganan)</label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-3d-primary py-3 px-8 text-lg w-full md:w-auto">
                        Simpan Paket
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
