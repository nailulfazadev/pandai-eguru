@extends('layouts.app')

@section('title', 'Rubrik Penilaian AI - PandAI')

@section('content')
    <!-- Header Page -->
    <section class="select-none mb-6 print:hidden">
        <h2 class="text-heading font-feather text-almost-black">Generator Rubrik Penilaian AI</h2>
        <p class="text-body text-graphite">Susun matriks kriteria rubrik penilaian secara instan dan buat lembar nilai lengkap dengan daftar siswa.</p>
    </section>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Options Form -->
        <div class="col-span-1 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm space-y-5 print:hidden">
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-3 select-none">
                <h3 class="text-heading-sm font-feather text-almost-black">Pengaturan Rubrik</h3>
                <button type="button" id="fill-demo-btn" class="text-xs font-bold text-sunshine-yellow hover:underline">Isi Contoh</button>
            </div>
            
            <form id="generate-form" class="space-y-4">
                @csrf

                <!-- Subject -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Mata Pelajaran</label>
                    <input type="text" name="subject" placeholder="Contoh: IPA, Bahasa Indonesia" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sunshine-yellow bg-[#f9f9f9] transition">
                </div>

                <!-- Topic -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Topik / Judul Tugas</label>
                    <input type="text" name="topic" placeholder="Contoh: Dampak Pemanasan Global" required
                           class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sunshine-yellow bg-[#f9f9f9] transition">
                </div>

                <!-- Task Type -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Jenis Tugas</label>
                    <select name="task_type" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-sunshine-yellow bg-[#f9f9f9] transition">
                        <option>Presentasi Kelas</option>
                        <option selected>Esai / Makalah</option>
                        <option>Proyek Kelompok / Karya</option>
                        <option>Praktikum Laboratorium</option>
                        <option>Observasi Sikap</option>
                    </select>
                </div>

                <!-- Grade -->
                <div class="space-y-1 flex space-x-2">
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-charcoal select-none">Jenjang/Kelas</label>
                        <select name="grade" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-sunshine-yellow bg-[#f9f9f9] transition">
                            <option>SD (Kelas 1-3)</option>
                            <option selected>SD (Kelas 4-6)</option>
                            <option>SMP</option>
                            <option>SMA / SMK</option>
                        </select>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-charcoal select-none">Skala</label>
                        <select name="scale" class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:border-sunshine-yellow bg-[#f9f9f9] transition">
                            <option value="4" selected>1 - 4 (Sangat Baik - Kurang)</option>
                            <option value="5">1 - 5 (Lengkap)</option>
                        </select>
                    </div>
                </div>

                <!-- Student List (Advanced) -->
                <div class="space-y-1">
                    <label class="block text-sm font-bold text-charcoal select-none">Daftar Nama Siswa <span class="text-xs font-normal text-silver">(Opsional)</span></label>
                    <textarea name="students" rows="4" placeholder="Paste daftar nama siswa di sini (satu nama per baris) untuk membuat Lembar Penilaian siap cetak."
                              class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sunshine-yellow bg-[#f9f9f9] transition"></textarea>
                </div>

                <!-- Mode Demo (Hemat Kuota AI) -->
                <div class="hidden flex items-center space-x-2 pt-2 select-none">
                    <input type="checkbox" id="use-mock" name="use_mock" value="1"
                           class="w-4 h-4 text-sunshine-yellow border-cloud-gray rounded focus:ring-sunshine-yellow">
                    <label for="use-mock" class="text-xs font-bold text-graphite cursor-pointer">
                        Mode Demo (Cepat & Hemat Kuota AI)
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submit-btn" class="btn-3d-primary w-full text-sm py-3 tracking-wider bg-sunshine-yellow hover:bg-[#E5A500] shadow-[0_4px_0_#CC9300]">
                    Buat Rubrik Penilaian
                </button>
            </form>
        </div>

        <!-- Right: Preview Area -->
        <div class="lg:col-span-2 bg-snow-white border-2 border-cloud-gray rounded-2xl p-6 shadow-sm min-h-[500px] flex flex-col justify-between print:border-none print:shadow-none print:p-0 print:w-full print:absolute print:top-0 print:left-0 print:m-0">
            
            <div class="flex justify-between items-center border-b-2 border-cloud-gray pb-4 mb-4 select-none print:hidden">
                <div class="flex space-x-4">
                    <h3 class="text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-sunshine-yellow pb-1" id="tab-rubrik">Matriks Rubrik</h3>
                    <h3 class="text-heading-sm font-feather text-silver cursor-pointer hover:text-charcoal pb-1 hidden" id="tab-lembar">Lembar Nilai</h3>
                </div>
                <span id="save-status" class="text-xs font-bold text-silver bg-[#f9f9f9] px-2.5 py-1 rounded-full border border-cloud-gray">Belum Disimpan</span>
            </div>

            <!-- Loading State -->
            <div id="loading-state" class="hidden flex-col items-center justify-center flex-1 h-full opacity-50 select-none print:hidden">
                <div class="w-12 h-12 border-4 border-cloud-gray border-t-sunshine-yellow rounded-full animate-spin mb-4"></div>
                <p class="text-graphite font-bold animate-pulse">Menyusun Kriteria Penilaian...</p>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="flex flex-col items-center justify-center flex-1 h-full opacity-50 select-none print:hidden">
                <svg class="w-20 h-20 text-silver mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <p class="text-graphite font-bold text-center">Belum ada rubrik.<br><span class="text-sm font-normal">Isi pengaturan di sebelah kiri untuk mulai membuat.</span></p>
            </div>

            <!-- Content Area (Printable) -->
            <div id="content-area" class="hidden flex-1 flex flex-col w-full bg-white print:block">
                
                <!-- View 1: Matriks Rubrik -->
                <div id="view-rubrik" class="w-full">
                    <div class="mb-6 text-center print:text-left print:mb-4">
                        <h2 id="rubrik-title" class="text-2xl font-bold font-feather text-charcoal">Judul Rubrik</h2>
                        <p id="rubrik-subtitle" class="text-sm text-graphite mt-1">Mata Pelajaran • Kelas</p>
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="w-full border-collapse border border-cloud-gray text-sm print:text-xs">
                            <thead class="bg-[#f9f9f9]" id="rubrik-thead">
                                <!-- Headers will be injected here -->
                            </thead>
                            <tbody id="rubrik-tbody">
                                <!-- Rows will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- View 2: Lembar Penilaian Siswa -->
                <div id="view-lembar" class="w-full hidden print:mt-12">
                    <div class="mb-6 text-center print:text-left print:mb-4">
                        <h2 id="lembar-title" class="text-2xl font-bold font-feather text-charcoal">Lembar Penilaian Siswa</h2>
                        <p id="lembar-subtitle" class="text-sm text-graphite mt-1">Mata Pelajaran • Kelas</p>
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="w-full border-collapse border border-cloud-gray text-sm print:text-xs">
                            <thead class="bg-[#f9f9f9]" id="lembar-thead">
                                <!-- Headers will be injected here -->
                            </thead>
                            <tbody id="lembar-tbody">
                                <!-- Student rows will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div id="actions-pane" class="hidden border-t-2 border-cloud-gray pt-4 flex justify-end space-x-3 select-none print:hidden mt-6">
                <button id="print-btn" class="btn-outline text-charcoal border-cloud-gray hover:border-sunshine-yellow hover:text-sunshine-yellow text-sm py-2 px-4 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Cetak PDF</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden CSS to force print styles -->
    <style>
        @media print {
            body { background: white; margin: 0; padding: 0; }
            .print\:hidden { display: none !important; }
            .print\:block { display: block !important; }
            .print\:absolute { position: absolute !important; }
            .print\:top-0 { top: 0 !important; }
            .print\:left-0 { left: 0 !important; }
            .print\:w-full { width: 100% !important; }
            .print\:m-0 { margin: 0 !important; }
            .print\:p-0 { padding: 0 !important; }
            .print\:border-none { border: none !important; }
            .print\:shadow-none { box-shadow: none !important; }
            #view-lembar { page-break-before: always; display: block !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            th, td { border: 1px solid #ccc !important; padding: 8px !important; }
            th { background-color: #f9f9f9 !important; -webkit-print-color-adjust: exact; }
        }
    </style>

    <!-- Page Logic -->
    <script>
        // DOM Elements
        const form = document.getElementById('generate-form');
        const fillDemoBtn = document.getElementById('fill-demo-btn');
        const submitBtn = document.getElementById('submit-btn');
        const loadingState = document.getElementById('loading-state');
        const emptyState = document.getElementById('empty-state');
        const contentArea = document.getElementById('content-area');
        const actionsPane = document.getElementById('actions-pane');
        const saveStatus = document.getElementById('save-status');
        const printBtn = document.getElementById('print-btn');
        
        const tabRubrik = document.getElementById('tab-rubrik');
        const tabLembar = document.getElementById('tab-lembar');
        const viewRubrik = document.getElementById('view-rubrik');
        const viewLembar = document.getElementById('view-lembar');

        let currentData = null;

        // Page Initial Load (History Document)
        @if(isset($document))
            try {
                const docData = {!! $document->content !!};
                
                const mockFormData = new FormData();
                mockFormData.append('subject', "Arsip");
                mockFormData.append('grade', "{{ $document->name }}");

                renderRubrik(docData, mockFormData);
                saveStatus.textContent = 'Dimuat dari Riwayat';
                saveStatus.className = 'text-xs font-bold text-sky-blue bg-sky-blue/10 px-2.5 py-1 rounded-full border border-sky-blue/20';
            } catch (e) {
                console.error("Failed to load document from history", e);
            }
        @endif

        // Tabs Logic
        tabRubrik.addEventListener('click', () => {
            tabRubrik.className = "text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-sunshine-yellow pb-1";
            tabLembar.className = "text-heading-sm font-feather text-silver cursor-pointer hover:text-charcoal pb-1";
            viewRubrik.style.display = 'block';
            viewLembar.style.display = 'none';
            // In print mode, we want both visible if students exist, so we handle print visibility via CSS
        });

        tabLembar.addEventListener('click', () => {
            tabLembar.className = "text-heading-sm font-feather text-almost-black cursor-pointer border-b-2 border-sunshine-yellow pb-1";
            tabRubrik.className = "text-heading-sm font-feather text-silver cursor-pointer hover:text-charcoal pb-1";
            viewLembar.style.display = 'block';
            viewRubrik.style.display = 'none';
        });

        // Fill Demo Data
        fillDemoBtn.addEventListener('click', () => {
            form.subject.value = 'Ilmu Pengetahuan Alam';
            form.topic.value = 'Daur Hidup Kupu-kupu (Metamorfosis)';
            form.task_type.value = 'Presentasi Kelas';
            form.grade.value = 'SD (Kelas 4-6)';
            form.scale.value = '4';
            form.students.value = "Budi Santoso\nSiti Aminah\nJoko Anwar\nRina Gunawan\nAndi Saputra";
            form.use_mock.checked = false;
        });

        // Print
        printBtn.addEventListener('click', () => {
            window.print();
        });

        // Generate Submission
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // UI State to Loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-pulse">Memproses...</span>';
            emptyState.style.display = 'none';
            contentArea.style.display = 'none';
            actionsPane.style.display = 'none';
            loadingState.style.display = 'flex';
            tabLembar.style.display = 'none';

            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route("tools.generate-rubrik-submit") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Server error: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.status === 'success') {
                    renderRubrik(data.data, formData);
                    saveStatus.textContent = 'Tersimpan (Dari Sesi Ini)';
                    saveStatus.className = 'text-xs font-bold text-duo-green bg-duo-green/10 px-2.5 py-1 rounded-full border border-duo-green/20';
                } else {
                    alert('Gagal merancang rubrik: ' + (data.message || 'Unknown error'));
                    emptyState.style.display = 'flex';
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan sistem saat menghubungi server.');
                emptyState.style.display = 'flex';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Buat Rubrik Penilaian';
                loadingState.style.display = 'none';
            }
        });

        function renderRubrik(data, formData) {
            currentData = data;
            const rubrikJson = data.rubrik;
            const students = data.students || [];

            // 1. Render Matriks Rubrik
            document.getElementById('rubrik-title').innerText = rubrikJson.title || 'Rubrik Penilaian';
            document.getElementById('rubrik-subtitle').innerText = `${formData.get('subject')} • ${formData.get('grade')}`;

            const thead = document.getElementById('rubrik-thead');
            const tbody = document.getElementById('rubrik-tbody');
            thead.innerHTML = '';
            tbody.innerHTML = '';

            // Render Headers (Aspek + Levels)
            if (rubrikJson.criteria && rubrikJson.criteria.length > 0) {
                const firstCriteria = rubrikJson.criteria[0];
                let headerHTML = '<tr><th class="p-3 text-left border border-cloud-gray w-1/4">Aspek Penilaian</th>';
                firstCriteria.levels.forEach(level => {
                    headerHTML += `<th class="p-3 text-left border border-cloud-gray">${level.score}</th>`;
                });
                headerHTML += '</tr>';
                thead.innerHTML = headerHTML;

                // Render Rows
                rubrikJson.criteria.forEach(crit => {
                    let rowHTML = `<tr><td class="p-3 border border-cloud-gray font-bold text-charcoal bg-[#f9f9f9]">${crit.aspect}</td>`;
                    crit.levels.forEach(level => {
                        rowHTML += `<td class="p-3 border border-cloud-gray align-top">${level.description}</td>`;
                    });
                    rowHTML += '</tr>';
                    tbody.innerHTML += rowHTML;
                });
            }

            // 2. Render Lembar Siswa (if students exist)
            if (students.length > 0) {
                tabLembar.style.display = 'block'; // Show tab
                
                document.getElementById('lembar-title').innerText = 'Lembar Nilai: ' + (rubrikJson.title || 'Rubrik Penilaian');
                document.getElementById('lembar-subtitle').innerText = `${formData.get('subject')} • ${formData.get('grade')}`;

                const lThead = document.getElementById('lembar-thead');
                const lTbody = document.getElementById('lembar-tbody');
                
                // Lembar Headers: No, Nama Siswa, [Aspek 1], [Aspek 2], ..., Nilai Akhir
                let lHeaderHTML = '<tr><th class="p-2 text-center border border-cloud-gray w-12">No</th><th class="p-2 text-left border border-cloud-gray">Nama Siswa</th>';
                rubrikJson.criteria.forEach(crit => {
                    lHeaderHTML += `<th class="p-2 text-center border border-cloud-gray">${crit.aspect}</th>`;
                });
                lHeaderHTML += '<th class="p-2 text-center border border-cloud-gray">Nilai Akhir</th></tr>';
                lThead.innerHTML = lHeaderHTML;

                // Get max score from first level for validation
                let maxScore = 0;
                if (rubrikJson.criteria.length > 0 && rubrikJson.criteria[0].levels.length > 0) {
                    const firstScoreStr = rubrikJson.criteria[0].levels[0].score;
                    const match = firstScoreStr.match(/\((\d+)\)/);
                    if (match) maxScore = parseInt(match[1]);
                }
                const maxAttr = maxScore > 0 ? `max="${maxScore}"` : '';

                // Lembar Rows: Students
                let lRowHTML = '';
                students.forEach((student, idx) => {
                    lRowHTML += `<tr><td class="p-2 text-center border border-cloud-gray">${idx + 1}</td><td class="p-2 font-medium border border-cloud-gray">${student}</td>`;
                    rubrikJson.criteria.forEach(() => {
                        lRowHTML += `<td class="p-0 border border-cloud-gray"><input type="number" min="0" ${maxAttr} class="score-input w-full p-2 text-center bg-transparent focus:outline-none focus:bg-sunshine-yellow/10" placeholder="-" /></td>`; // Interactive cell
                    });
                    lRowHTML += `<td class="p-2 border border-cloud-gray text-center font-bold final-score text-charcoal bg-[#f9f9f9]">0</td></tr>`; // Final score
                });
                lTbody.innerHTML = lRowHTML;
                
                // Add real-time calculation logic
                lTbody.addEventListener('input', function(e) {
                    if (e.target.classList.contains('score-input')) {
                        const row = e.target.closest('tr');
                        const inputs = row.querySelectorAll('.score-input');
                        let total = 0;
                        inputs.forEach(input => {
                            const val = parseFloat(input.value);
                            if (!isNaN(val)) total += val;
                        });
                        row.querySelector('.final-score').innerText = total;
                    }
                });
            } else {
                tabLembar.style.display = 'none';
            }

            // Show content
            contentArea.style.display = 'flex';
            actionsPane.style.display = 'flex';
            tabRubrik.click(); // Default to tab 1
        }
    </script>
@endsection
