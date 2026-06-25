@extends('layouts.admin')

@section('title', 'Admin - Daftar Paket Harga')

@section('content')
    <div class="max-w-6xl mx-auto py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-heading font-feather text-almost-black mb-2">Paket Harga</h1>
                <p class="text-body text-graphite">Kelola paket langganan yang tersedia untuk pengguna.</p>
            </div>
            <a href="{{ route('admin.packages.create') }}" class="btn-3d-primary py-2 px-4 rounded-xl font-bold">
                + Tambah Paket
            </a>
        </div>

        @if(session('success'))
            <div class="bg-duo-green-light border-l-4 border-duo-green text-charcoal p-4 rounded-lg mb-8">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl overflow-hidden shadow-sm">
            <table class="min-w-full divide-y-2 divide-cloud-gray">
                <thead class="bg-[#f9f9f9]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Nama Paket</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Durasi (Hari)</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-silver uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-silver uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-snow-white divide-y-2 divide-cloud-gray">
                    @forelse($packages as $pkg)
                        <tr class="hover:bg-[#f9f9f9] transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-almost-black">{{ $pkg->name }}</div>
                                <div class="text-xs text-graphite">{{ $pkg->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal font-bold">
                                Rp {{ number_format($pkg->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-graphite">
                                {{ $pkg->duration_days }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($pkg->is_active)
                                    <span class="px-3 py-1 bg-duo-green-light text-duo-green text-xs font-bold rounded-full">Aktif</span>
                                @else
                                    <span class="px-3 py-1 bg-cloud-gray/50 text-graphite text-xs font-bold rounded-full">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold flex justify-center space-x-2">
                                <a href="{{ route('admin.packages.edit', $pkg->id) }}" class="text-sky-blue hover:underline">Edit</a>
                                <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus paket ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-bubblegum-pink hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-graphite text-sm">Belum ada paket terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
