@extends('layouts.admin')

@section('title', 'Admin Dashboard - PandAI')

@section('content')
    <div class="max-w-6xl mx-auto py-8">
        <div class="mb-8">
            <h1 class="text-heading font-feather text-almost-black mb-2">Dashboard Utama</h1>
            <p class="text-body text-graphite">Ringkasan performa dan data pengguna PandAI.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            
            <!-- Card 1 -->
            <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-sky-blue/10 flex items-center justify-center text-sky-blue">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-almost-black">{{ number_format($totalUsers) }}</h3>
                    <p class="text-sm font-bold text-graphite mt-1">Total Pengguna (Guru)</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-duo-green-light flex items-center justify-center text-duo-green">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-almost-black">{{ number_format($totalPremium) }}</h3>
                    <p class="text-sm font-bold text-graphite mt-1">Akun Premium Aktif</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-sunshine-yellow/20 flex items-center justify-center text-sunshine-yellow">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-almost-black">{{ number_format($pendingPayments) }}</h3>
                    <p class="text-sm font-bold text-graphite mt-1">Menunggu Verifikasi (Pending)</p>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-bubblegum-pink/10 flex items-center justify-center text-bubblegum-pink">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-almost-black">Rp {{ number_format($revenue, 0, ',', '.') }}</h3>
                    <p class="text-sm font-bold text-graphite mt-1">Pemasukan Disetujui</p>
                </div>
            </div>

        </div>

    </div>
@endsection
