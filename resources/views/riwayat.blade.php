@extends('layouts.app')

@section('title', 'Riwayat Dokumen - PandAI')

@section('content')
    <!-- Header Page -->
    <section class="select-none">
        <h2 class="text-heading font-feather text-almost-black">Riwayat Dokumen</h2>
        <p class="text-body text-graphite">Lihat dan unduh kembali semua modul ajar atau soal yang telah Anda buat sebelumnya.</p>
    </section>

    <!-- Documents Grouped by Category (Tabbed UI) -->
    <div class="space-y-6">
        @if($documents->isEmpty())
            <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl overflow-hidden shadow-sm p-12 text-center flex flex-col items-center justify-center space-y-4 select-none">
                <div class="w-16 h-16 rounded-xl bg-cloud-gray/40 flex items-center justify-center text-graphite">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div class="space-y-1">
                    <h4 class="font-bold text-charcoal text-lg">Belum Ada Dokumen</h4>
                    <p class="text-graphite text-sm">Dokumen yang Anda buat menggunakan AI akan diarsipkan di sini.</p>
                </div>
            </div>
        @else
            <!-- Tabs Header -->
            <div class="flex overflow-x-auto space-x-2 pb-2 hide-scrollbar">
                @foreach($documents as $category => $docs)
                    @php
                        $tabId = Str::slug($category);
                        $isActive = $loop->first;
                    @endphp
                    <button onclick="switchTab('{{ $tabId }}')" id="btn-{{ $tabId }}" class="tab-btn whitespace-nowrap px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200 border-2 {{ $isActive ? 'bg-duo-green text-snow-white border-duo-green shadow-[0_4px_0_#46a302]' : 'bg-snow-white text-graphite border-cloud-gray hover:bg-cloud-gray hover:text-almost-black' }}">
                        {{ $category === 'prompt' ? 'Prompt Guru' : ($category === 'lkpd' ? 'LKPD' : ucwords($category)) }} 
                        <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ $isActive ? 'bg-snow-white/20' : 'bg-cloud-gray text-graphite' }}">{{ $docs->count() }}</span>
                    </button>
                @endforeach
            </div>

            <!-- Tabs Content -->
            <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl overflow-hidden shadow-sm">
                @foreach($documents as $category => $docs)
                    @php
                        $tabId = Str::slug($category);
                        $isActive = $loop->first;
                    @endphp
                    <div id="tab-{{ $tabId }}" class="tab-content {{ $isActive ? '' : 'hidden' }}">
                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y-2 divide-cloud-gray">
                                <thead class="bg-[#f9f9f9] select-none">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Nama Dokumen</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Tanggal Dibuat</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-silver uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-snow-white divide-y-2 divide-cloud-gray">
                                    @foreach($docs as $doc)
                                        <tr class="hover:bg-[#f9f9f9] transition">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-almost-black">{{ $doc->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-graphite">
                                                {{ $doc->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2 text-sm text-graphite select-none">
                                                    <div class="w-2 h-2 rounded-full bg-duo-green"></div>
                                                    <span>{{ $doc->status }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold space-x-3">
                                                @if(strtolower($doc->type) === 'ppt')
                                                    <a href="{{ route('tools.ppt-pembelajaran', ['document_id' => $doc->id]) }}" class="text-bubblegum-pink hover:text-bubblegum-pink/80">Buka & Unduh PPTX</a>
                                                @elseif(strtolower($doc->type) === 'bahan ajar')
                                                    <a href="{{ route('documents.download', $doc->id) }}" download class="text-sky-blue hover:text-sky-blue/80">Unduh Word</a>
                                                @elseif(strtolower($doc->type) === 'rubrik')
                                                    <a href="{{ route('documents.download', $doc->id) }}" download class="text-sky-blue hover:text-sky-blue/80">Unduh Word</a>
                                                    <span class="text-cloud-gray">|</span>
                                                    <a href="{{ route('tools.rubrik-penilaian', ['document_id' => $doc->id]) }}" class="text-sunshine-yellow hover:text-[#E5A500]">Buka & Cetak PDF</a>
                                                @elseif(strtolower($doc->type) === 'lkpd')
                                                    <a href="{{ route('documents.download', $doc->id) }}" download class="text-sky-blue hover:text-sky-blue/80">Unduh Word</a>
                                                    <span class="text-cloud-gray">|</span>
                                                    <a href="{{ route('tools.lkpd-generator', ['document_id' => $doc->id]) }}" class="text-grape-soda hover:text-[#8e52ff]">Buka & Cetak LKPD</a>
                                                @elseif(strtolower($doc->type) === 'prompt')
                                                    <a href="{{ route('documents.download', $doc->id) }}" download class="text-sky-blue hover:text-sky-blue/80">Unduh Word</a>
                                                    <span class="text-cloud-gray">|</span>
                                                    <a href="{{ route('tools.prompt-guru', ['document_id' => $doc->id]) }}" class="text-grape-soda hover:text-[#8e52ff]">Buka Prompt</a>
                                                @else
                                                    <a href="{{ route('documents.download', $doc->id) }}" download class="text-sky-blue hover:text-sky-blue/80">Unduh Word</a>
                                                    <span class="text-cloud-gray">|</span>
                                                    <a href="{{ route('documents.print', $doc->id) }}" target="_blank" class="text-duo-green hover:text-duo-green/80">Cetak PDF</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- spacer -->
    <div class="h-12"></div>
@endsection

@section('scripts')
<script>
    function switchTab(tabId) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Remove active state from all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-duo-green', 'text-snow-white', 'border-duo-green', 'shadow-[0_4px_0_#46a302]');
            btn.classList.add('bg-snow-white', 'text-graphite', 'border-cloud-gray', 'hover:bg-cloud-gray', 'hover:text-almost-black');
            
            // Update badge colors
            const badge = btn.querySelector('span');
            if (badge) {
                badge.classList.remove('bg-snow-white/20');
                badge.classList.add('bg-cloud-gray', 'text-graphite');
            }
        });

        // Show selected content
        const targetContent = document.getElementById('tab-' + tabId);
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }

        // Add active state to selected button
        const targetBtn = document.getElementById('btn-' + tabId);
        if (targetBtn) {
            targetBtn.classList.remove('bg-snow-white', 'text-graphite', 'border-cloud-gray', 'hover:bg-cloud-gray', 'hover:text-almost-black');
            targetBtn.classList.add('bg-duo-green', 'text-snow-white', 'border-duo-green', 'shadow-[0_4px_0_#46a302]');
            
            // Update badge colors
            const badge = targetBtn.querySelector('span');
            if (badge) {
                badge.classList.remove('bg-cloud-gray', 'text-graphite');
                badge.classList.add('bg-snow-white/20');
            }
        }
    }
</script>
<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
