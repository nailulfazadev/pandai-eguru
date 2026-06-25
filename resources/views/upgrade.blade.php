@extends('layouts.app')

@section('title', 'Upgrade Paket - PandAI')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <div class="text-center mb-12">
            <h1 class="text-heading font-feather text-almost-black mb-4">Pilih Paket Belajar Anda</h1>
            <p class="text-body text-graphite max-w-xl mx-auto">Tingkatkan efektivitas mengajar Anda dengan akses tak terbatas ke semua fitur AI canggih di PandAI.</p>
        </div>

        @if(session('error'))
            <div class="bg-bubblegum-pink/10 border-l-4 border-bubblegum-pink text-charcoal p-4 rounded-lg mb-8">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-8">
            @foreach($packages as $index => $package)
            <div class="{{ $index % 2 == 1 ? 'bg-sky-blue/5 border-sky-blue shadow-xl' : 'bg-snow-white border-cloud-gray hover:border-sky-blue hover:shadow-xl' }} p-8 rounded-2xl border-2 transition relative flex flex-col">
                @if($index % 2 == 1)
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-sunshine-yellow text-almost-black text-xs font-black px-4 py-1 rounded-full uppercase tracking-widest border-2 border-almost-black">
                    Paling Hemat
                </div>
                @endif

                <h3 class="font-feather text-xl font-bold text-almost-black mb-2">{{ $package->name }}</h3>
                <p class="text-sm text-graphite mb-6">Akses penuh selama {{ $package->duration_days }} hari.</p>
                
                <div class="mb-6">
                    <span class="text-3xl font-black text-almost-black">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                    <span class="text-graphite">/{{ $package->duration_days }} hari</span>
                </div>

                <ul class="space-y-3 mb-8 flex-1">
                    @if(is_array($package->features))
                        @foreach($package->features as $feature)
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-duo-green mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-sm text-charcoal">{!! \Illuminate\Support\Str::inlineMarkdown($feature) !!}</span>
                        </li>
                        @endforeach
                    @endif
                </ul>

                <a href="{{ route('checkout', $package->slug) }}" class="{{ $index % 2 == 1 ? 'btn-3d-primary' : 'btn-3d-secondary' }} w-full text-center py-3">Beli Paket Premium</a>
            </div>
            @endforeach
        </div>

        <div class="mt-12 bg-snow-white p-6 rounded-2xl border border-cloud-gray flex items-center justify-between">
            <div>
                <h4 class="font-bold text-charcoal">Coba Gratis (Trial)</h4>
                <p class="text-sm text-graphite">Anda memiliki askes trial 7 hari untuk fitur Modul Ajar saat pertama mendaftar.</p>
            </div>
            @if($user->isTrial())
                <div class="text-right">
                    <span class="block text-sm font-bold text-sky-blue">Aktif</span>
                    <span class="block text-xs text-graphite">Sisa {{ max(0, now()->diffInDays($user->subscription_ends_at)) }} hari</span>
                </div>
            @else
                <span class="px-3 py-1 bg-cloud-gray/40 text-graphite text-xs font-bold rounded-full">Selesai</span>
            @endif
        </div>
    </div>
@endsection
