@extends('layouts.admin')

@section('title', 'Admin - Daftar Pembayaran')

@section('content')
    <div class="max-w-6xl mx-auto py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-heading font-feather text-almost-black mb-2">Manajemen Pembayaran</h1>
                <p class="text-body text-graphite">Setujui atau tolak bukti transfer pembayaran langganan pengguna.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-duo-green-light/50 border-l-4 border-duo-green text-charcoal p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-bubblegum-pink/10 border-l-4 border-bubblegum-pink text-charcoal p-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl overflow-hidden shadow-sm">
            <table class="min-w-full divide-y-2 divide-cloud-gray">
                <thead class="bg-[#f9f9f9]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Paket</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Nominal</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-silver uppercase tracking-wider">Bukti TF</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-silver uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-silver uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-snow-white divide-y-2 divide-cloud-gray">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-[#f9f9f9] transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-graphite">
                                {{ $payment->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-almost-black">{{ $payment->user->name }}</div>
                                <div class="text-xs text-graphite">{{ $payment->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="capitalize text-sm font-bold text-charcoal">{{ $payment->paket }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-sky-blue">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ asset('storage/' . $payment->bukti_transfer) }}" target="_blank" class="text-sky-blue hover:underline text-sm font-bold flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($payment->status === 'pending')
                                    <span class="px-3 py-1 bg-sunshine-yellow/20 text-sunshine-yellow text-xs font-bold rounded-full">Pending</span>
                                @elseif($payment->status === 'approved')
                                    <span class="px-3 py-1 bg-duo-green-light text-duo-green text-xs font-bold rounded-full">Disetujui</span>
                                @else
                                    <span class="px-3 py-1 bg-bubblegum-pink/10 text-bubblegum-pink text-xs font-bold rounded-full">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($payment->status === 'pending')
                                    <div class="flex justify-end space-x-2">
                                        <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-duo-green text-snow-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-[#3f8f01] transition">Setujui</button>
                                        </form>
                                        <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-bubblegum-pink text-snow-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-[#a62a73] transition">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-silver text-xs italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-graphite text-sm">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
