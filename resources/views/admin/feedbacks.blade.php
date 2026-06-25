@extends('layouts.admin')

@section('title', 'Admin - Masukan Pengguna')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-heading font-feather text-almost-black mb-2">Masukan Pengguna</h1>
            <p class="text-body text-graphite">Kelola laporan bug, saran fitur, dan keluhan dari guru.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-duo-green-light border-2 border-duo-green text-duo-green-dark p-4 rounded-xl mb-8 font-bold text-sm flex items-center space-x-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-snow-white border-2 border-cloud-gray rounded-2xl overflow-hidden shadow-sm">
        <table class="min-w-full divide-y-2 divide-cloud-gray">
            <thead class="bg-[#f9f9f9]">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Tiket</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-silver uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-silver uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-silver uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-snow-white divide-y-2 divide-cloud-gray">
                @forelse($feedbacks as $fb)
                    <tr class="hover:bg-[#f9f9f9] transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-almost-black mb-1 line-clamp-1" title="{{ $fb->subject }}">{{ $fb->subject }}</div>
                            <div class="text-xs text-graphite">{{ $fb->created_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-bold px-2 py-1 bg-cloud-gray/50 text-graphite rounded-md">{{ $fb->type }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-charcoal">{{ $fb->user->name }}</div>
                            <div class="text-xs text-silver">{{ $fb->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($fb->status == 'Menunggu Tanggapan')
                                <span class="px-3 py-1 bg-sunshine-yellow/20 text-sunshine-yellow text-xs font-bold rounded-full uppercase">Menunggu</span>
                            @else
                                <span class="px-3 py-1 bg-duo-green-light text-duo-green text-xs font-bold rounded-full uppercase">Dijawab</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <button onclick="openReplyModal({{ $fb->id }}, '{{ addslashes($fb->subject) }}', '{{ addslashes($fb->message) }}', '{{ addslashes($fb->response ?? '') }}')" class="btn-3d-secondary !py-2 !px-4 !text-xs">
                                {{ $fb->status == 'Menunggu Tanggapan' ? 'Tanggapi' : 'Lihat' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-graphite text-sm">Belum ada masukan dari pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($feedbacks->hasPages())
            <div class="px-6 py-4 border-t-2 border-cloud-gray">
                {{ $feedbacks->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Balasan -->
<div id="replyModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-black/50" onclick="closeReplyModal()"></div>

        <div class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-snow-white shadow-xl rounded-3xl border-2 border-cloud-gray">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-black text-almost-black" id="modalTitle">Tanggapi Masukan</h3>
                <button onclick="closeReplyModal()" class="text-graphite hover:text-almost-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="bg-[#f9f9f9] border-2 border-cloud-gray p-4 rounded-xl mb-4">
                <p class="text-sm font-bold text-graphite mb-1" id="modalSubject">Subject</p>
                <p class="text-sm text-charcoal" id="modalMessage">Message content goes here.</p>
            </div>

            <form id="replyForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="response" class="block text-sm font-bold text-graphite mb-2 uppercase tracking-wide">Balasan Admin</label>
                    <textarea id="response" name="response" rows="4" required class="w-full bg-[#f9f9f9] border-2 border-cloud-gray rounded-xl px-4 py-3 font-bold text-almost-black outline-none focus:border-sky-blue focus:bg-snow-white resize-none" placeholder="Ketik balasan untuk pengguna di sini..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeReplyModal()" class="px-4 py-2 text-sm font-bold text-graphite hover:bg-cloud-gray rounded-xl transition">Batal</button>
                    <button type="submit" class="btn-3d-primary !py-2 !px-6 !text-sm">Kirim Balasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openReplyModal(id, subject, message, existingResponse) {
        document.getElementById('replyModal').classList.remove('hidden');
        document.getElementById('modalSubject').textContent = subject;
        document.getElementById('modalMessage').textContent = message;
        
        let responseField = document.getElementById('response');
        responseField.value = existingResponse;
        
        let form = document.getElementById('replyForm');
        form.action = `/admin/masukan/${id}/balas`;
    }

    function closeReplyModal() {
        document.getElementById('replyModal').classList.add('hidden');
    }
</script>
@endsection
