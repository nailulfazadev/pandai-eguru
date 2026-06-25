@extends('layouts.app')

@section('title', 'Bantuan & Masukan - PandAI')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-8">
        <h1 class="text-heading font-feather text-almost-black mb-2">Bantuan & Masukan</h1>
        <p class="text-body text-graphite font-bold">Punya pertanyaan, ide fitur, atau menemukan masalah? Sampaikan langsung kepada tim kami.</p>
    </div>

    @if(session('success'))
        <div class="bg-duo-green-light border-2 border-duo-green text-duo-green-dark p-4 rounded-xl mb-8 font-bold text-sm flex items-center space-x-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form Section -->
    <div class="bg-snow-white border-2 border-cloud-gray rounded-3xl p-8 mb-12 shadow-[0_4px_0_#e5e5e5]">
        <h2 class="text-xl font-black text-almost-black mb-6">Kirim Tiket Baru</h2>
        
        <form action="{{ route('feedback.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipe Masukan -->
                <div class="space-y-2">
                    <label for="type" class="block text-sm font-bold text-graphite uppercase tracking-wide">Kategori</label>
                    <div class="relative">
                        <select id="type" name="type" required class="w-full appearance-none bg-[#f9f9f9] border-2 border-cloud-gray rounded-xl px-4 py-3 font-bold text-almost-black outline-none transition-colors focus:border-sky-blue focus:bg-snow-white">
                            <option value="">Pilih Kategori...</option>
                            <option value="Saran Fitur">Saran Fitur Baru</option>
                            <option value="Laporan Bug">Laporan Bug / Error</option>
                            <option value="Kritik & Saran">Kritik & Saran</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-graphite">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Subjek -->
                <div class="space-y-2">
                    <label for="subject" class="block text-sm font-bold text-graphite uppercase tracking-wide">Judul Singkat</label>
                    <input type="text" id="subject" name="subject" required placeholder="Contoh: Tambahkan fitur export ke Word" class="w-full bg-[#f9f9f9] border-2 border-cloud-gray rounded-xl px-4 py-3 font-bold text-almost-black outline-none transition-colors focus:border-sky-blue focus:bg-snow-white">
                </div>
            </div>

            <!-- Pesan -->
            <div class="space-y-2">
                <label for="message" class="block text-sm font-bold text-graphite uppercase tracking-wide">Detail Pesan</label>
                <textarea id="message" name="message" rows="5" required placeholder="Jelaskan secara detail apa yang Anda butuhkan atau kendala yang Anda alami..." class="w-full bg-[#f9f9f9] border-2 border-cloud-gray rounded-xl px-4 py-3 font-bold text-almost-black outline-none transition-colors focus:border-sky-blue focus:bg-snow-white resize-none"></textarea>
            </div>

            <button type="submit" class="btn-3d-primary w-full sm:w-auto min-w-[200px]">
                Kirim Laporan
            </button>
        </form>
    </div>

    <!-- History Section -->
    <div>
        <h2 class="text-xl font-black text-almost-black mb-6">Riwayat Tiket Anda</h2>
        
        @forelse($feedbacks as $feedback)
            <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 mb-4 relative overflow-hidden transition-all hover:border-silver">
                <!-- Status Badge -->
                <div class="absolute top-6 right-6">
                    @if($feedback->status == 'Menunggu Tanggapan')
                        <span class="px-3 py-1 bg-sunshine-yellow/20 text-sunshine-yellow text-xs font-bold rounded-full uppercase border border-sunshine-yellow/50">Menunggu</span>
                    @elseif($feedback->status == 'Dijawab')
                        <span class="px-3 py-1 bg-duo-green-light text-duo-green text-xs font-bold rounded-full uppercase border border-duo-green/30">Dijawab</span>
                    @else
                        <span class="px-3 py-1 bg-cloud-gray text-graphite text-xs font-bold rounded-full uppercase border border-silver">Selesai</span>
                    @endif
                </div>

                <div class="flex items-center space-x-2 mb-2">
                    <span class="text-xs font-bold px-2 py-1 bg-[#f1f1f1] text-graphite rounded-md">{{ $feedback->type }}</span>
                    <span class="text-xs font-bold text-silver">{{ $feedback->created_at->format('d M Y, H:i') }}</span>
                </div>
                
                <h3 class="text-lg font-black text-almost-black mb-2 pr-24">{{ $feedback->subject }}</h3>
                <p class="text-sm text-graphite mb-4">{{ $feedback->message }}</p>

                @if($feedback->response)
                    <div class="bg-[#f9f9f9] border-l-4 border-sky-blue p-4 rounded-r-xl">
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="w-6 h-6 rounded-full bg-sky-blue text-snow-white flex items-center justify-center font-bold text-xs">A</div>
                            <span class="text-xs font-black text-almost-black">Admin PandAI</span>
                            <span class="text-xs font-bold text-silver">{{ $feedback->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                        <p class="text-sm font-bold text-charcoal">{{ $feedback->response }}</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-12 bg-snow-white border-2 border-dashed border-cloud-gray rounded-3xl">
                <svg class="w-16 h-16 text-cloud-gray mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <h3 class="text-lg font-black text-graphite mb-2">Belum Ada Riwayat</h3>
                <p class="text-sm font-bold text-silver max-w-sm mx-auto">Anda belum pernah mengirimkan tiket. Jika butuh bantuan, jangan ragu untuk mengisi form di atas.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
