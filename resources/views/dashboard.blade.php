@extends('layouts.app')

@section('title', 'Dashboard - PandAI')

@section('content')
    @if(session('success'))
        <div class="bg-duo-green-light/50 border-l-4 border-duo-green text-charcoal p-4 rounded-lg mb-6 flex items-center shadow-sm">
            <svg class="w-6 h-6 text-duo-green mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @php
        $isTrial = auth()->user() ? auth()->user()->isTrial() : false;
    @endphp

    <!-- Welcome Section -->
    <section class="bg-duo-green-light/20 rounded-2xl p-8 border-2 border-cloud-gray flex items-center justify-between relative overflow-hidden select-none">
        <div class="relative z-10">
            <h2 class="text-heading font-feather text-almost-black mb-2">Selamat Datang di PandAI!</h2>
            <p class="text-body text-graphite mb-6 max-w-lg">Gunakan AI untuk membantu pekerjaan mengajar Anda lebih cepat, menyenangkan, dan efektif.</p>
            <a href="{{ route('tools.modul-ajar') }}" class="btn-3d-primary inline-block">Mulai Buat Modul</a>
        </div>
        <!-- Decorative blobs (simulated) -->
        <div class="absolute right-0 top-0 h-full w-1/3 flex justify-end">
            <div class="w-48 h-48 bg-duo-green-light rounded-full -mr-10 -mt-10 opacity-50 blur-2xl"></div>
            <div class="w-32 h-32 bg-sunshine-yellow rounded-full absolute bottom-0 right-10 opacity-30 blur-xl"></div>
        </div>
    </section>

    <!-- Quick Stats Cards -->
    <section class="grid grid-cols-4 gap-6 select-none mt-6">
        <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray shadow-sm">
            <div class="flex items-center space-x-3 mb-2 text-duo-green">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-bold text-silver uppercase text-xs tracking-wider">Total Dokumen</span>
            </div>
            <div class="text-heading font-feather text-almost-black">{{ number_format($totalDocuments) }}</div>
        </div>
        <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray shadow-sm">
            <div class="flex items-center space-x-3 mb-2 text-sky-blue">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002-2v-2"></path></svg>
                <span class="font-bold text-silver uppercase text-xs tracking-wider">Total Soal</span>
            </div>
            <div class="text-heading font-feather text-almost-black">{{ number_format($totalQuestions) }}</div>
        </div>
        <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray shadow-sm">
            <div class="flex items-center space-x-3 mb-2 text-grape-soda">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="font-bold text-silver uppercase text-xs tracking-wider">Modul Ajar</span>
            </div>
            <div class="text-heading font-feather text-almost-black">{{ number_format($totalModulAjar) }}</div>
        </div>
        <div class="bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray shadow-sm relative overflow-hidden">
            <div class="flex items-center space-x-3 mb-2 text-sunshine-yellow">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span class="font-bold text-silver uppercase text-xs tracking-wider">Sisa Kredit AI</span>
            </div>
            <div class="text-heading font-feather text-almost-black">{{ number_format($sisaKredit) }}</div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-cloud-gray">
                <div class="h-full bg-sunshine-yellow" style="width: {{ ($sisaKredit / 5000) * 100 }}%"></div>
            </div>
        </div>
    </section>

    <!-- Featured Tools Section -->
    <section class="mt-8">
        <div class="flex justify-between items-end mb-6 select-none">
            <h3 class="text-heading-sm font-feather text-almost-black">Peralatan Super Guru</h3>
            <a href="{{ route('tools.index') }}" class="text-sky-blue font-bold text-sm hover:underline">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-3 gap-6">
            <!-- Tool 1 -->
            <a href="{{ $isTrial ? route('langganan') : route('tools.generator-soal') }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray {{ $isTrial ? 'opacity-70 hover:border-cloud-gray' : 'hover:border-sky-blue hover:shadow-md' }} transition group flex flex-col items-start h-full cursor-pointer relative">
                @if($isTrial)
                <div class="absolute top-4 right-4 text-silver bg-cloud-gray/50 p-2 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                @endif
                <div class="w-12 h-12 rounded-xl bg-sky-blue/10 flex items-center justify-center text-sky-blue mb-4 {{ $isTrial ? '' : 'group-hover:scale-110' }} transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h4 class="font-bold text-charcoal text-lg mb-2">Generator Soal</h4>
                <p class="text-graphite text-sm leading-relaxed mb-4 flex-1">Buat soal pilihan ganda dan essay secara otomatis dari materi pelajaran.</p>
            </a>
            
            <!-- Tool 2 -->
            <a href="{{ route('tools.modul-ajar') }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray hover:border-duo-green hover:shadow-md transition group flex flex-col items-start h-full cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-duo-green/10 flex items-center justify-center text-duo-green mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h4 class="font-bold text-charcoal text-lg mb-2">Modul Ajar AI</h4>
                <p class="text-graphite text-sm leading-relaxed mb-4 flex-1">Buat modul ajar lengkap dan terstruktur sesuai kurikulum merdeka.</p>
            </a>

            <!-- Tool 3 -->
            <a href="{{ $isTrial ? route('langganan') : route('tools.ppt-pembelajaran') }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray {{ $isTrial ? 'opacity-70 hover:border-cloud-gray' : 'hover:border-bubblegum-pink hover:shadow-md' }} transition cursor-pointer group flex flex-col items-start h-full relative">
                @if($isTrial)
                <div class="absolute top-4 right-4 text-silver bg-cloud-gray/50 p-2 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                @endif
                <div class="w-12 h-12 rounded-xl bg-bubblegum-pink/10 flex items-center justify-center text-bubblegum-pink mb-4 {{ $isTrial ? '' : 'group-hover:scale-110' }} transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                </div>
                <h4 class="font-bold text-charcoal text-lg mb-2">PPT Pembelajaran</h4>
                <p class="text-graphite text-sm leading-relaxed mb-4 flex-1">Generate presentasi siap mengajar dengan struktur yang menarik.</p>
            </a>

            <!-- Tool 4 -->
            <a href="{{ $isTrial ? route('langganan') : route('tools.lkpd-generator') }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray {{ $isTrial ? 'opacity-70 hover:border-cloud-gray' : 'hover:border-grape-soda hover:shadow-md' }} transition cursor-pointer group flex flex-col items-start h-full relative">
                @if($isTrial)
                <div class="absolute top-4 right-4 text-silver bg-cloud-gray/50 p-2 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                @endif
                <div class="w-12 h-12 rounded-xl bg-grape-soda/10 flex items-center justify-center text-grape-soda mb-4 {{ $isTrial ? '' : 'group-hover:scale-110' }} transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h4 class="font-bold text-charcoal text-lg mb-2">LKPD Generator</h4>
                <p class="text-graphite text-sm leading-relaxed mb-4 flex-1">Buat lembar kerja peserta didik interaktif untuk kelas.</p>
            </a>

            <!-- Tool 5 -->
            <a href="{{ $isTrial ? route('langganan') : route('tools.rubrik-penilaian') }}" class="block bg-snow-white p-6 rounded-2xl border-2 border-cloud-gray {{ $isTrial ? 'opacity-70 hover:border-cloud-gray' : 'hover:border-sunshine-yellow hover:shadow-md' }} transition cursor-pointer group flex flex-col items-start h-full relative">
                @if($isTrial)
                <div class="absolute top-4 right-4 text-silver bg-cloud-gray/50 p-2 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                @endif
                <div class="w-12 h-12 rounded-xl bg-sunshine-yellow/20 flex items-center justify-center text-sunshine-yellow mb-4 {{ $isTrial ? '' : 'group-hover:scale-110' }} transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <h4 class="font-bold text-charcoal text-lg mb-2">Rubrik Penilaian</h4>
                <p class="text-graphite text-sm leading-relaxed mb-4 flex-1">Buat rubrik penilaian otomatis untuk berbagai macam tugas.</p>
            </a>
        </div>
    </section>

    <!-- Recent Activity Section -->
    <section>
        <h3 class="text-heading-sm font-feather text-almost-black mb-6 select-none">Aktivitas Terakhir</h3>
        <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl overflow-hidden shadow-sm">
            <table class="min-w-full divide-y-2 divide-cloud-gray">
                <thead class="bg-[#f9f9f9] select-none">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Nama Dokumen</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Jenis</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-silver uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-snow-white divide-y-2 divide-cloud-gray">
                    @if($recentActivity->isEmpty())
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center select-none">
                                <div class="w-12 h-12 rounded-xl bg-cloud-gray/40 flex items-center justify-center text-graphite mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h4 class="font-bold text-charcoal text-sm">Belum Ada Aktivitas</h4>
                                <p class="text-graphite text-xs">Dokumen yang Anda buat menggunakan AI akan terdaftar di sini.</p>
                            </td>
                        </tr>
                    @else
                        @foreach($recentActivity as $act)
                            <tr class="hover:bg-[#f9f9f9] transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-almost-black">{{ $act->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $badgeClass = 'bg-cloud-gray/40 text-graphite';
                                        $typeLower = strtolower($act->type);
                                        if ($typeLower === 'modul ajar') {
                                            $badgeClass = 'bg-duo-green-light text-duo-green';
                                        } elseif ($typeLower === 'soal') {
                                            $badgeClass = 'bg-sky-blue/20 text-sky-blue';
                                        } elseif ($typeLower === 'ppt') {
                                            $badgeClass = 'bg-bubblegum-pink/10 text-bubblegum-pink';
                                        } elseif ($typeLower === 'rubrik') {
                                            $badgeClass = 'bg-sunshine-yellow/20 text-sunshine-yellow';
                                        } elseif ($typeLower === 'lkpd') {
                                            $badgeClass = 'bg-grape-soda/10 text-grape-soda';
                                        } elseif ($typeLower === 'prompt') {
                                            $badgeClass = 'bg-grape-soda/20 text-grape-soda';
                                        } elseif ($typeLower === 'bahan ajar utama' || $typeLower === 'bahan ajar') {
                                            $badgeClass = 'bg-grape-soda/10 text-grape-soda';
                                        }
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $badgeClass }}">
                                        {{ $act->type === 'prompt' ? 'Prompt Guru' : $act->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-graphite">
                                    {{ $act->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2 text-sm text-graphite">
                                        <div class="w-2 h-2 rounded-full bg-duo-green"></div>
                                        <span>{{ $act->status }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold">
                                    @php
                                        $openUrl = '#';
                                        $isTargetBlank = false;
                                        if ($typeLower === 'ppt') {
                                            $openUrl = route('tools.ppt-pembelajaran', ['document_id' => $act->id]);
                                        } elseif ($typeLower === 'rubrik') {
                                            $openUrl = route('tools.rubrik-penilaian', ['document_id' => $act->id]);
                                        } elseif ($typeLower === 'lkpd') {
                                            $openUrl = route('tools.lkpd-generator', ['document_id' => $act->id]);
                                        } elseif ($typeLower === 'prompt') {
                                            $openUrl = route('tools.prompt-guru', ['document_id' => $act->id]);
                                        } elseif ($typeLower === 'bahan ajar utama') {
                                            $openUrl = route('tools.bahan-ajar-utama', ['document_id' => $act->id]);
                                        } elseif ($typeLower === 'bahan ajar') {
                                            $openUrl = route('documents.download', $act->id);
                                        } else {
                                            $openUrl = route('documents.print', $act->id);
                                            $isTargetBlank = true;
                                        }
                                    @endphp
                                    <a href="{{ $openUrl }}" {!! $isTargetBlank ? 'target="_blank"' : '' !!} class="text-sky-blue hover:text-sky-blue/80 font-bold">Buka</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </section>

    <!-- spacer to ensure bottom is reached -->
    <div class="h-12"></div>
@endsection
