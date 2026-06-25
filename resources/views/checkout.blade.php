@extends('layouts.app')

@section('title', 'Pembayaran - PandAI')

@section('content')
    <div class="max-w-2xl mx-auto py-8">
        <div class="text-center mb-8">
            <h1 class="text-heading font-feather text-almost-black mb-2">Instruksi Pembayaran</h1>
            <p class="text-body text-graphite">Silakan selesaikan pembayaran untuk mengaktifkan paket Anda.</p>
        </div>

        @if($hasPending)
            <div class="bg-sunshine-yellow/20 border-l-4 border-sunshine-yellow text-charcoal p-6 rounded-2xl mb-8 flex items-start">
                <svg class="w-8 h-8 text-sunshine-yellow mr-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="font-bold text-lg mb-1">Pembayaran Sedang Diverifikasi</h3>
                    <p class="text-sm">Anda sudah mengunggah bukti transfer sebelumnya. Admin kami sedang mengecek mutasi rekening. Mohon tunggu maksimal 1x24 jam.</p>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('dashboard') }}" class="btn-outline">Kembali ke Dashboard</a>
            </div>
        @else
            <div class="bg-snow-white p-8 rounded-2xl border-2 border-cloud-gray shadow-sm mb-8">
                <div class="flex justify-between items-center mb-6 pb-6 border-b-2 border-cloud-gray">
                    <div>
                        <p class="text-sm text-graphite font-bold uppercase tracking-wider mb-1">Paket Pilihan</p>
                        <h3 class="font-feather text-xl font-bold text-almost-black capitalize">Paket {{ $paket }}</h3>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-graphite font-bold uppercase tracking-wider mb-1">Total Tagihan</p>
                        <h3 class="text-2xl font-black text-sky-blue">Rp {{ number_format($nominal, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="font-bold text-charcoal mb-4">Transfer Ke Rekening Berikut:</h4>
                    <div class="bg-[#f9f9f9] p-4 rounded-xl border border-cloud-gray flex items-center justify-between">
                        <div>
                            <p class="font-bold text-lg text-almost-black">BCA - 1234567890</p>
                            <p class="text-sm text-graphite">a.n. PT Pandai Edukasi Indonesia</p>
                        </div>
                        <div class="w-12 h-12 bg-cloud-gray/30 rounded-lg flex items-center justify-center text-graphite font-bold">
                            BCA
                        </div>
                    </div>
                </div>

                <form action="{{ route('checkout.process', ['paket' => $paket]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label class="block font-bold text-charcoal mb-2">Upload Bukti Transfer</label>
                        <div class="border-2 border-dashed border-cloud-gray hover:border-sky-blue transition rounded-xl p-6 text-center cursor-pointer bg-[#f9f9f9]" onclick="document.getElementById('file-upload').click()">
                            <svg class="w-10 h-10 text-silver mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <p class="text-sm font-bold text-charcoal" id="file-name">Pilih File Gambar (JPG/PNG)</p>
                            <p class="text-xs text-graphite mt-1">Maksimal ukuran file: 2MB</p>
                        </div>
                        <input type="file" name="bukti_transfer" id="file-upload" class="hidden" accept="image/png, image/jpeg, image/jpg" required onchange="document.getElementById('file-name').innerText = this.files[0].name">
                        
                        @error('bukti_transfer')
                            <p class="text-bubblegum-pink text-sm mt-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-3d-primary w-full py-4 text-lg">Konfirmasi Pembayaran</button>
                </form>
            </div>
        @endif
    </div>
@endsection
