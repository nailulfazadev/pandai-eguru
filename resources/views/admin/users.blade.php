@extends('layouts.admin')

@section('title', 'Admin - Daftar Pengguna')

@section('content')
    <div class="max-w-6xl mx-auto py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-heading font-feather text-almost-black mb-2">Manajemen Pengguna</h1>
                <p class="text-body text-graphite">Lihat semua akun guru yang terdaftar di sistem PandAI.</p>
            </div>
        </div>

        <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl overflow-hidden shadow-sm">
            <table class="min-w-full divide-y-2 divide-cloud-gray">
                <thead class="bg-[#f9f9f9]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Tanggal Daftar</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Sekolah</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-silver uppercase tracking-wider">Status Langganan</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-silver uppercase tracking-wider">Sisa Waktu</th>
                    </tr>
                </thead>
                <tbody class="bg-snow-white divide-y-2 divide-cloud-gray">
                    @forelse($users as $u)
                        <tr class="hover:bg-[#f9f9f9] transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-graphite">
                                {{ $u->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-almost-black">{{ $u->name }}</div>
                                <div class="text-xs text-graphite">{{ $u->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal">
                                {{ $u->school_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($u->subscription_tier)
                                    <span class="px-3 py-1 bg-duo-green-light text-duo-green text-xs font-bold rounded-full uppercase">{{ $u->subscription_tier }}</span>
                                @elseif($u->isTrial())
                                    <span class="px-3 py-1 bg-cloud-gray/50 text-charcoal text-xs font-bold rounded-full">Trial</span>
                                @else
                                    <span class="px-3 py-1 bg-bubblegum-pink/10 text-bubblegum-pink text-xs font-bold rounded-full">Expired</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-charcoal">
                                @if($u->subscription_ends_at && $u->subscription_ends_at->isFuture())
                                    {{ now()->diffInDays($u->subscription_ends_at) }} Hari
                                @else
                                    <span class="text-silver">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-graphite text-sm">Belum ada pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($users->hasPages())
                <div class="px-6 py-4 border-t-2 border-cloud-gray">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
