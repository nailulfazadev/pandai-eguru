<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;

class GeminiController extends Controller
{
    public function chat(Request $request)
    {
        set_time_limit(120);

        $request->validate([
            'prompt' => 'required|string',
        ]);

        $apiKey = config('services.gemini.api_key');
        
        if (!$apiKey) {
            // Fallback to mock responses for demo purposes
            $prompt = $request->input('prompt');
            $responseText = $this->getMockResponse($prompt);
            
            return response()->json([
                'response' => $responseText,
                'is_mock' => true
            ]);
        }

        $prompt = $request->input('prompt');

        try {
            $response = Http::timeout(60)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "Kamu adalah asisten AI untuk guru bernama PandAI. Jawab dalam bahasa Indonesia. Permintaan: {$prompt}"]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Tidak ada jawaban dari AI.';
                
                return response()->json([
                    'response' => $text,
                    'is_mock' => false
                ]);
            }
            
            Log::error('Gemini API Error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Gemini connection failed: ' . $e->getMessage());
        }

        return response()->json([
            'error' => 'Gagal terhubung ke Gemini API.'
        ], 500);
    }

    private function getMockResponse($prompt)
    {
        $promptLower = strtolower($prompt);

        if (str_contains($promptLower, 'matematika') && (str_contains($promptLower, 'soal') || str_contains($promptLower, 'tugas'))) {
            return "### 📝 Kumpulan Soal Matematika Kelas 5 (Materi: Pecahan)\n\nBerikut adalah contoh soal latihan untuk kelas Anda:\n\n1. **Hasil penyederhanaan dari pecahan 12/20 adalah...**\n   - A. 3/5\n   - B. 4/5\n   - C. 3/4\n   - D. 2/5\n   *Kunci Jawaban: A (pembilang dan penyebut dibagi 4)*\n\n2. **Pak Budi membeli 2,5 kg beras dan 1 1/4 kg gula. Berat total belanjaan Pak Budi adalah...**\n   - A. 3,5 kg\n   - B. 3,75 kg\n   - C. 4,25 kg\n   - D. 4,5 kg\n   *Kunci Jawaban: B (2,5 + 1,25 = 3,75 kg)*\n\n3. **Hasil dari 3/4 x 2/3 adalah...**\n   - A. 5/7\n   - B. 6/7\n   - C. 1/2\n   - D. 6/12\n   *Kunci Jawaban: C (6/12 disederhanakan menjadi 1/2)*\n\nApakah Anda ingin saya membuat kunci jawaban lengkap dengan pembahasannya?";
        }

        if (str_contains($promptLower, 'modul ajar') || str_contains($promptLower, 'ipa')) {
            return "### 📘 Rencana Pelaksanaan Pembelajaran / Modul Ajar AI\n\n**Mata Pelajaran:** Ilmu Pengetahuan Alam (IPA)\n**Kelas/Semester:** V / I\n**Materi Pokok:** Siklus Air (Hidrologi)\n**Alokasi Waktu:** 2 x 35 Menit (1 Pertemuan)\n\n#### 🎯 Tujuan Pembelajaran\n1. Siswa mampu menjelaskan tahapan siklus air melalui bagan sederhana dengan benar.\n2. Siswa mampu mengidentifikasi aktivitas manusia yang memengaruhi kualitas air dengan tepat.\n\n#### 🚶‍♂️ Langkah-Langkah Pembelajaran\n*   **Kegiatan Pendahuluan (10 Menit):**\n    - Orientasi kelas (Salam dan doa).\n    - Apersepsi: Guru menyemprotkan air ke udara, menanyakan: \"Mengapa air di bumi tidak pernah habis?\"\n*   **Kegiatan Inti (50 Menit):**\n    - *Eksplorasi:* Siswa mengamati video/bagan tentang evaporasi, kondensasi, presipitasi, dan infiltrasi.\n    - *Elaborasi:* Kelompok siswa membuat diorama siklus air menggunakan botol plastik bekas.\n*   **Kegiatan Penutup (10 Menit):**\n    - Refleksi bersama siswa mengenai pentingnya menghemat air.\n    - Evaluasi formatif singkat.\n\n#### 📊 Penilaian (Asesmen)\n*   **Sikap:** Lembar observasi keaktifan kelompok.\n*   **Pengetahuan:** Tes tertulis (pilihan ganda 5 butir).\n*   **Keterampilan:** Penilaian produk diorama siklus air.";
        }

        if (str_contains($promptLower, 'ppt') || str_contains($promptLower, 'fotosintesis')) {
            return "### 📊 Rancangan Slide PPT Pembelajaran: Fotosintesis\n\nBerikut adalah draf outline slide presentasi pembelajaran yang siap Anda gunakan:\n\n*   **Slide 1: Judul Presentasi**\n    - *Judul Utama:* Dapur Hijau: Bagaimana Tumbuhan Memasak?\n    - *Sub-judul:* Mengenal Proses Fotosintesis Secara Seru!\n*   **Slide 2: Apa itu Fotosintesis?**\n    - *Poin:* Proses tumbuhan membuat makanannya sendiri dengan bantuan sinar matahari.\n    - *Visual:* Ilustrasi daun yang tersenyum memegang panci masak.\n*   **Slide 3: Bahan-Bahan Masakan Daun**\n    - *Daftar:* Air (dari tanah), Karbondioksida (dari udara), Klorofil (zat hijau daun), Cahaya Matahari.\n*   **Slide 4: Proses Memasak (Reaksi Kimia)**\n    - *Bagan:* Air + Karbondioksida + Matahari ➔ Glukosa (Energi) + Oksigen (Udara bersih).\n*   **Slide 5: Kuis Cepat!**\n    - *Pertanyaan:* Apa gas yang dilepaskan tumbuhan hasil dari fotosintesis yang kita hirup sehari-hari?\n    - *Jawaban:* Oksigen!\n\nIngin saya membantu merancang narasi lengkap untuk dibacakan saat presentasi?";
        }

        return "Halo! Terima kasih atas pertanyaan Anda: **" . e($prompt) . "**.\n\nSaya bisa membantu Anda merancang soal ulangan, membuat modul ajar lengkap, menyusun kerangka PPT presentasi, membuat Lembar Kerja Peserta Didik (LKPD), hingga membuat kriteria rubrik penilaian secara otomatis.\n\nSilakan pilih salah satu menu rekomendasi di atas atau ketik materi pelajaran yang ingin Anda kembangkan!";
    }

    private function checkSubscriptionLimits($toolType)
    {
        $user = Auth::user();
        
        if (!$user->hasActiveSubscription()) {
            return response()->json([
                'error' => 'Masa aktif paket Anda telah habis. Silakan perpanjang untuk melanjutkan.'
            ], 403);
        }

        if ($user->isTrial() && $toolType !== 'Modul Ajar') {
            return response()->json([
                'error' => 'Pada paket Trial, Anda hanya dapat mengakses fitur Modul Ajar. Silakan upgrade ke paket berbayar.'
            ], 403);
        }

        if ($user->generationsToday() >= 10) {
            return response()->json([
                'error' => 'Batas maksimal generate harian (10x) telah tercapai. Silakan coba lagi besok.'
            ], 403);
        }

        return null;
    }

    public function generateModulAjar(Request $request)
    {
        $limitCheck = $this->checkSubscriptionLimits('Modul Ajar');
        if ($limitCheck) return $limitCheck;

        set_time_limit(120);

        $request->validate([
            'teacher_name' => 'required|string|max:255',
            'teacher_nip' => 'nullable|string|max:50',
            'school_name' => 'required|string|max:255',
            'principal_name' => 'required|string|max:255',
            'principal_nip' => 'nullable|string|max:50',
            'grade' => 'required|string|max:100',
            'subject' => 'required|string|max:255',
            'topic' => 'required|string|max:255',
            'method' => 'required|string|max:255',
            'duration' => 'required|string|max:100',
            'cp' => 'nullable|string',
            'tp' => 'nullable|string',
        ]);

        $user = Auth::user();
        if ($user) {
            $user->update([
                'name' => $request->input('teacher_name'),
                'nip' => $request->input('teacher_nip'),
                'school_name' => $request->input('school_name'),
                'principal_name' => $request->input('principal_name'),
                'principal_nip' => $request->input('principal_nip'),
            ]);
        }

        $apiKey = config('services.gemini.api_key');
        $useMock = $request->input('use_mock') === '1' || !$apiKey;

        // Limit to 10 generations per user per day (only enforced when calling Gemini API)
        if (!$useMock) {
            $todayCount = Auth::user()->documents()
                ->where('is_mock', false)
                ->whereDate('created_at', now()->toDateString())
                ->count();

            if ($todayCount >= 10) {
                return response()->json([
                    'error' => 'Anda telah mencapai batas maksimum pembuatan dokumen (10 kali per hari).'
                ], 429);
            }
        }

        $p5List = $request->input('p5') ? implode(', ', $request->input('p5')) : 'Mandiri, Bernalar Kritis, Gotong Royong';

        $prompt = "Buatlah Rencana Pelaksanaan Pembelajaran (RPP) / Modul Ajar Kurikulum Merdeka berbasis RPM (Rencana Pembelajaran Mendalam) yang lengkap dan profesional dengan detail identitas berikut:
Nama Guru: {$request->input('teacher_name')}
NIP Guru: " . ($request->input('teacher_nip') ?: '-') . "
Nama Sekolah/Instansi: {$request->input('school_name')}
Nama Kepala Sekolah: {$request->input('principal_name')}
NIP Kepala Sekolah: " . ($request->input('principal_nip') ?: '-') . "
Fase/Kelas: {$request->input('grade')}
Mata Pelajaran: {$request->input('subject')}
Topik/Materi Pembelajaran: {$request->input('topic')}
Model Pembelajaran: {$request->input('method')}
Alokasi Waktu: {$request->input('duration')}
Capaian Pembelajaran (CP): " . ($request->input('cp') ?: 'AI lengkapi secara otomatis berdasarkan materi.') . "
Tujuan Pembelajaran (TP): " . ($request->input('tp') ?: 'AI rumuskan secara otomatis berdasarkan CP dan topik.') . "

Karakteristik Utama Dokumen:
Dokumen ini disusun sebagai Rencana Pembelajaran Mendalam (RPM) yang berfokus pada pendekatan Pembelajaran Mendalam (Deep Learning). Langkah pembelajaran harus menekankan aktivitas yang bermakna (meaningful) dan menyenangkan (joyful) bagi murid.

ATURAN OUTPUT SANGAT KETAT:
1. HANYA keluarkan konten dokumen Modul Ajar dalam format Markdown. JANGAN ada teks pengantar atau penutup.
2. JANGAN mengulangi pembuatan dokumen atau bagian mana pun. Tulis tepat SATU kali dari awal sampai akhir. JANGAN menduplikasi section 'INFORMASI UMUM' atau section lainnya.
3. Langsung mulai dokumen dengan judul utama: `# MODUL AJAR: " . strtoupper($request->input('topic')) . " - " . strtoupper($request->input('subject')) . " (" . strtoupper($request->input('grade')) . ")`.
4. JANGAN menulis ulang informasi yang sudah terwakili di dalam tabel identitas. Target Peserta Didik dan Model Pembelajaran harus digabungkan langsung ke dalam tabel 'Identitas Modul' di bagian Informasi Umum agar ringkas, jangan dibuatkan tabel/bagian terpisah.
5. Gunakan tabel Markdown yang rapi untuk bagian informasi terstruktur. Untuk pemisah header tabel, WAJIB hanya menggunakan minimal 3 tanda hubung (contoh: `| --- | --- |`), JANGAN menulis tanda hubung yang panjang atau berulang secara berlebihan karena dapat merusak format parsing.
6. ATURAN LKPD: Untuk bagian isian/soal di Lembar Kerja Peserta Didik, CUKUP BERIKAN PERTANYAANNYA SAJA. **DILARANG KERAS** membuat area kosong untuk jawaban siswa (TIDAK BOLEH ada titik-titik `.....`, TIDAK BOLEH ada garis bawah `____`, TIDAK BOLEH ada tabel kosong bertumpuk, dan TIDAK BOLEH ada enter/spasi kosong yang panjang). Biarkan teks padat dan rapat, guru yang akan mengatur spasi jaraknya di Microsoft Word nanti.

Struktur Modul Ajar yang harus disusun (Tulis secara berurutan dan jangan diulang):
### 1. INFORMASI UMUM
* **Identitas Modul**: Buat tabel Markdown 2 kolom berisi data Guru, NIP, Instansi, Kelas/Fase, Mapel, Topik, Alokasi Waktu, Target Peserta Didik, dan Model Pembelajaran.
* **Kompetensi Awal**: Buat tabel atau penjelasan ringkas.
* **Profil Pelajar Pancasila**: HANYA cantumkan dan jelaskan dimensi berikut: **{$p5List}**. DILARANG KERAS menambahkan/menyebutkan dimensi Profil Pelajar Pancasila selain yang diminta tersebut! Jelaskan secara ringkas bagaimana dimensi tersebut diterapkan dalam aktivitas pembelajaran.
* **Sarana dan Prasarana (Sarpas)**: Sajikan daftar alat/media secara rapi.

---

### 2. KOMPETENSI INTI
* **Tujuan Pembelajaran (TP) & Kriteria Ketercapaian (KKTP)**
* **Pemahaman Bermakna & Pertanyaan Pemantik**
* **Langkah-Langkah Kegiatan Pembelajaran**:
  * Gunakan heading H3: `### Langkah-Langkah Kegiatan Pembelajaran`
  * Di bawah heading H3, cantumkan model pembelajaran dan total alokasi waktu dalam satu baris miring: `*Model Pembelajaran: {$request->input('method')} | Alokasi Waktu: {$request->input('duration')}*`
  * WAJIB sajikan seluruh rincian langkah kegiatan pembelajaran dalam bentuk tabel Markdown 3 kolom dengan susunan berikut:
    * Header tabel: `| Tahap | Alokasi Waktu | Deskripsi Kegiatan |`
    * Baris 1 (Pendahuluan): Tahap berisi `Kegiatan Pendahuluan`, Alokasi Waktu berisi durasi pendahuluan (misal `10 Menit`), Deskripsi Kegiatan berisi rincian pembuka (seperti salam, doa, apersepsi, motivasi) dipisahkan dengan tag `<br>` agar berbaris rapi.
    * Baris 2 (Inti): Tahap berisi `Kegiatan Inti (Sintaks Model [Nama Model])`, Alokasi Waktu berisi durasi kegiatan inti (misal `50 Menit`), Deskripsi Kegiatan berisi rincian pelaksanaan terperinci dari Fase 1 sampai dengan Fase terakhir sesuai sintaks model pembelajaran yang dipilih (seperti PBL/PjBL), dipisahkan dengan tag `<br>`.
    * Baris 3 (Penutup): Tahap berisi `Kegiatan Penutup`, Alokasi Waktu berisi durasi penutup (misal `10 Menit`), Deskripsi Kegiatan berisi rincian penutup (seperti refleksi, kesimpulan, tindak lanjut, doa/salam) dipisahkan dengan tag `<br>`.
* **Asesmen**: Buat tabel berisi Jenis Asesmen, Bentuk Asesmen, dan Alat/Instrumen Asesmen.
* **Pengayaan dan Remedial**
* **Refleksi Peserta Didik dan Guru**

---

### 3. LAMPIRAN
* **Lembar Kerja Peserta Didik (LKPD)** sederhana. (Ingat Aturan 6: DILARANG membuat area kosong untuk jawaban siswa).
* **Bahan Bacaan Guru & Peserta Didik**
* **Glosarium & Daftar Pustaka**

---

Di bagian paling bawah modul ajar, wajib buat kolom Tanda Tangan formal menggunakan tabel Markdown 2 kolom (samping-menyamping) seperti berikut:
| Mengetahui, | Guru Mata Pelajaran |
| :--- | :--- |
| Kepala {$request->input('school_name')}<br><br><br><br>**{$request->input('principal_name')}**<br>NIP: " . ($request->input('principal_nip') ?: '-') . " | <br><br><br><br>**{$request->input('teacher_name')}**<br>NIP: " . ($request->input('teacher_nip') ?: '-') . " |

Jawab dalam Bahasa Indonesia dengan format Markdown yang sangat rapi dan lengkap tanpa terpotong.";

        $text = '';
        $isMock = false;

        if ($useMock) {
            $isMock = true;
            $text = $this->getMockModulAjar($request);
        } else {
            try {
                $response = Http::timeout(90)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 8192,
                        'temperature' => 0.3
                    ],
                    'safetySettings' => [
                        ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE']
                    ]
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Gagal memproses pembuatan modul.';
                } else {
                    Log::error('Gemini API Error (Modul Ajar): ' . $response->body());
                    return response()->json(['error' => 'Gagal menghubungi Gemini API.'], 500);
                }
            } catch (\Exception $e) {
                Log::error('Gemini Connection Error (Modul Ajar): ' . $e->getMessage());
                return response()->json(['error' => 'Koneksi ke Gemini API gagal.'], 500);
            }
        }

        // Save to Database
        $document = Auth::user()->documents()->create([
            'name' => 'Modul Ajar ' . $request->input('subject') . ' - ' . $request->input('topic'),
            'type' => 'Modul Ajar',
            'status' => 'Selesai',
            'content' => $text,
            'is_mock' => $isMock,
        ]);

        $htmlContent = (new DocumentController())->markdownToHtml($text, false);

        return response()->json([
            'id' => $document->id,
            'content' => $text,
            'html_content' => $htmlContent,
            'is_mock' => $isMock,
        ]);
    }

    private function getMockModulAjar($request)
    {
        $teacherName = $request->input('teacher_name');
        $teacherNip = $request->input('teacher_nip') ?: '-';
        $schoolName = $request->input('school_name');
        $principalName = $request->input('principal_name');
        $principalNip = $request->input('principal_nip') ?: '-';
        $grade = $request->input('grade');
        $subject = $request->input('subject');
        $topic = $request->input('topic');
        $method = $request->input('method');
        $duration = $request->input('duration');
        
        $cp = $request->input('cp') ?: "Peserta didik memahami proses siklus air and kaitannya dengan upaya menjaga kelestarian lingkungan.";
        $tp = $request->input('tp') ?: "1. Peserta didik dapat mengidentifikasi urutan proses siklus air dengan benar.\n2. Peserta didik dapat menyebutkan faktor yang memengaruhi kelestarian air.";

        $p5Selected = $request->input('p5') ?: ['Mandiri', 'Bernalar Kritis', 'Gotong Royong'];
        $p5Markdown = '';
        foreach ($p5Selected as $dimensi) {
            if ($dimensi === 'Beriman, Bertakwa kepada Tuhan YME, dan Berakhlak Mulia') {
                $p5Markdown .= "*   **Beriman, Bertakwa kepada Tuhan YME, dan Berakhlak Mulia**: Murid membiasakan berdoa sebelum dan sesudah belajar.\n";
            } elseif ($dimensi === 'Berkebinekaan Global') {
                $p5Markdown .= "*   **Berkebinekaan Global**: Murid saling menghormati pendapat teman dari latar belakang berbeda.\n";
            } elseif ($dimensi === 'Gotong Royong') {
                $p5Markdown .= "*   **Gotong Royong**: Bekerja sama dalam menyelesaikan eksperimen/simulasi kelompok.\n";
            } elseif ($dimensi === 'Mandiri') {
                $p5Markdown .= "*   **Mandiri**: Bertanggung jawab atas tugas individual dan hasil belajarnya sendiri.\n";
            } elseif ($dimensi === 'Bernalar Kritis') {
                $p5Markdown .= "*   **Bernalar Kritis**: Menganalisis data pengamatan dan menarik kesimpulan rasional.\n";
            } elseif ($dimensi === 'Kreatif') {
                $p5Markdown .= "*   **Kreatif**: Merancang hasil karya / produk baru yang orisinal dari ide kelompok.\n";
            } else {
                $p5Markdown .= "*   **" . $dimensi . "**: Diterapkan dalam aktivitas pembelajaran.\n";
            }
        }

        $topicUpper = strtoupper($topic);
        $subjectUpper = strtoupper($subject);
        $gradeUpper = strtoupper($grade);

        return "# MODUL AJAR: {$topicUpper} - {$subjectUpper} ({$gradeUpper})

## 1. INFORMASI UMUM

### Identitas Modul
| Parameter | Keterangan |
| --- | --- |
| **Nama Penulis** | {$teacherName} |
| **NIP Penulis** | {$teacherNip} |
| **Sekolah/Instansi** | {$schoolName} |
| **Fase / Kelas** | {$grade} |
| **Mata Pelajaran** | {$subject} |
| **Topik/Materi** | {$topic} |
| **Alokasi Waktu** | {$duration} |
| **Target Murid** | Reguler (30 Peserta didik) |
| **Model Belajar** | {$method} |

### Kompetensi Awal
| Aspek | Deskripsi |
| --- | --- |
| **Kompetensi Awal** | Peserta didik telah mengenal bentuk-bentuk air di sekitar lingkungan rumah. |

### Profil Pelajar Pancasila
{$p5Markdown}

### Sarana dan Prasarana (Sarpas)
| Jenis Sarpras | Rincian Media |
| --- | --- |
| **Fisik & Alat** | Papan tulis, Proyektor, Bagan Siklus Air, Air, Gelas bening, Kantong plastik transparan |

---

## 2. KOMPETENSI INTI

### Tujuan Pembelajaran
{$tp}

### Pemahaman Bermakna
*   Air di bumi memiliki jumlah yang tetap namun terus bergerak berputar melalui tahapan evaporasi, kondensasi, presipitasi, dan infiltrasi.
*   Kelestarian air bergantung pada perilaku menjaga lingkungan dari polusi.

### Pertanyaan Pemantik
*   Mengapa jemuran basah di bawah terik matahari bisa kering?
*   Dari mana asal air hujan yang jatuh dari langit?

### Langkah-Langkah Kegiatan Pembelajaran
*Model Pembelajaran: {$method} | Alokasi Waktu: {$duration}*

| Tahap | Alokasi Waktu | Deskripsi Kegiatan |
| --- | --- | --- |
| **Kegiatan Pendahuluan** | 10 Menit | 1. **Orientasi:** Guru mengucapkan salam, berdoa, dan memeriksa kehadiran siswa.<br>2. **Apersepsi:** Guru menunjukkan segelas air dan bertanya: \"Apakah air yang kita minum hari ini sama dengan air yang diminum dinosaurus jutaan tahun lalu?\"<br>3. **Motivasi:** Guru menyampaikan manfaat memahami siklus air demi menjaga persediaan air bersih. |
| **Kegiatan Inti** | 50 Menit | **Sintaks Model Pembelajaran:**<br>1. **Orientasi Masalah:** Guru menayangkan bagan proses siklus air dan menjelaskan proses evaporasi, transpirasi, kondensasi, presipitasi, dan infiltrasi.<br>2. **Penyelidikan Mandiri:** Siswa berkelompok (4-5 orang) untuk membuat replika siklus air mini menggunakan mangkuk, air hangat, cangkir kecil di tengahnya, dan ditutup plastik bening yang diikat karet di bawah sinar matahari/lampu hangat.<br>3. **Diskusi & Analisis:** Kelompok mencatat hasil pengamatan (adanya uap air yang menempel di plastik lalu menetes ke dalam cangkir).<br>4. **Presentasi:** Perwakilan kelompok mempresentasikan proses pembentukan hujan berdasarkan eksperimen mini. |
| **Kegiatan Penutup** | 10 Menit | 1. **Evaluasi:** Siswa mengerjakan kuis formatif 3 soal pilihan ganda secara mandiri.<br>2. **Kesimpulan & Refleksi:** Guru membimbing siswa merangkum materi. Siswa mengungkapkan perasaannya mengenai eksperimen hari ini.<br>3. **Tindak Lanjut:** Berdoa dan salam penutup. |

### Asesmen
| Jenis Asesmen | Bentuk Penilaian | Instrumen/Alat |
| --- | --- | --- |
| **Asesmen Diagnostik** | Tanya jawab lisan di awal kelas | Pertanyaan pemantik |
| **Asesmen Formatif** | Penilaian kinerja praktikum | Lembar observasi diskusi & praktikum |
| **Asesmen Sumatif** | Tes tertulis mandiri | Soal pilihan ganda |

---

## 3. LAMPIRAN

### Lembar Kerja Peserta Didik (LKPD)
*Tugas:* Lengkapilah bagan berikut dengan menuliskan nama proses siklus air (Evaporasi, Kondensasi, Presipitasi, Infiltrasi) berdasarkan nomor urutan kejadian!

### Daftar Pustaka
*   Kementerian Pendidikan dan Kebudayaan. (2021). *Buku Panduan Guru & Buku Siswa Ilmu Pengetahuan Alam dan Sosial SD Kelas V*. Jakarta.

---

| | |
| :--- | :--- |
| **Mengetahui,**<br>Kepala {$schoolName}<br><br><br><br>**{$principalName}**<br>NIP: {$principalNip} | **Guru Kelas / Mata Pelajaran**<br><br><br><br><br>**{$teacherName}**<br>NIP: {$teacherNip} |";
    }

    public function generateSoal(Request $request)
    {
        $limitCheck = $this->checkSubscriptionLimits('Generator Soal');
        if ($limitCheck) return $limitCheck;

        set_time_limit(120);

        $request->validate([
            'subject' => 'required|string|max:255',
            'topic' => 'required|string|max:255',
            'grade' => 'required|string|max:100',
            'difficulty' => 'required|string|max:100',
            'question_type' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1|max:50',
            'indicator' => 'nullable|string',
            'kop_dinas' => 'required|string|max:255',
            'kop_sekolah' => 'required|string|max:255',
            'kop_alamat' => 'required|string|max:255',
            'kop_ujian' => 'required|string|max:255',
            'kop_ta' => 'required|string|max:100',
            'kop_waktu' => 'required|string|max:100',
            'kop_logo' => 'nullable|string',
            'kop_template' => 'nullable|string|in:kemendikbud,kemenag,yayasan,sederhana',
            'kop_logo_kanan' => 'nullable|string',
        ]);

        $apiKey = config('services.gemini.api_key');
        $useMock = $request->input('use_mock') === '1' || !$apiKey;

        // Limit to 10 generations per user per day (only enforced when calling Gemini API)
        if (!$useMock) {
            $todayCount = Auth::user()->documents()
                ->where('is_mock', false)
                ->whereDate('created_at', now()->toDateString())
                ->count();

            if ($todayCount >= 10) {
                return response()->json([
                    'error' => 'Anda telah mencapai batas maksimum pembuatan dokumen (10 kali per hari).'
                ], 429);
            }
        }

        $difficultyText = $request->input('difficulty');
        $typeText = $request->input('question_type');
        $indicatorText = $request->input('indicator') ?: 'Lengkapi secara otomatis berdasarkan topik.';

        $template = $request->input('kop_template', 'kemendikbud');
        $logoL = $request->input('kop_logo');
        $logoR = $request->input('kop_logo_kanan');
        $dinas = $request->input('kop_dinas');
        $sekolah = $request->input('kop_sekolah');
        $alamat = $request->input('kop_alamat');
        $ujian = $request->input('kop_ujian');
        $ta = $request->input('kop_ta');
        $waktu = $request->input('kop_waktu');

        $kopHtml = "";
        if ($template === 'sederhana') {
            $kopHtml = "
<table border='0' cellspacing='0' cellpadding='0' style='width: 100%; border: none; border-collapse: collapse; margin-bottom: 15pt; font-family: \"Times New Roman\", Times, serif;'>
  <tr>
    <td style='width: 100%; border: none; text-align: center; vertical-align: middle; line-height: 1.2; padding-bottom: 5pt;'>
      <span style='font-size: 11pt; font-weight: bold; text-transform: uppercase;'>{$dinas}</span><br>
      <span style='font-size: 14pt; font-weight: bold; text-transform: uppercase;'>{$sekolah}</span><br>
      <span style='font-size: 9pt; font-style: italic;'>{$alamat}</span>
    </td>
  </tr>
  <tr>
    <td style='border: none; border-top: 2px solid #000000; padding: 0; height: 1px; line-height: 1px;'>&nbsp;</td>
  </tr>
</table>
";
        } elseif ($template === 'yayasan') {
            $kopHtml = "
<table border='0' cellspacing='0' cellpadding='0' style='width: 100%; border: none; border-collapse: collapse; margin-bottom: 15pt; font-family: \"Times New Roman\", Times, serif;'>
  <tr>
    <td style='width: 15%; border: none; text-align: center; vertical-align: middle; padding-bottom: 5pt;'>
      " . ($logoL ? "<img src=\"{$logoL}\" width=\"60\" style=\"width: 50pt; height: auto;\" />" : "") . "
    </td>
    <td style='width: 70%; border: none; text-align: center; vertical-align: middle; line-height: 1.2; padding-bottom: 5pt;'>
      <span style='font-size: 11pt; font-weight: bold; text-transform: uppercase;'>{$dinas}</span><br>
      <span style='font-size: 14pt; font-weight: bold; text-transform: uppercase;'>{$sekolah}</span><br>
      <span style='font-size: 9pt; font-style: italic;'>{$alamat}</span>
    </td>
    <td style='width: 15%; border: none; text-align: center; vertical-align: middle; padding-bottom: 5pt;'>
      " . ($logoR ? "<img src=\"{$logoR}\" width=\"60\" style=\"width: 50pt; height: auto;\" />" : "") . "
    </td>
  </tr>
  <tr>
    <td colspan='3' style='border: none; border-top: 3px double #000000; padding: 0; height: 1px; line-height: 1px;'>&nbsp;</td>
  </tr>
</table>
";
        } else { // kemendikbud or kemenag
            $kopHtml = "
<table border='0' cellspacing='0' cellpadding='0' style='width: 100%; border: none; border-collapse: collapse; margin-bottom: 15pt; font-family: \"Times New Roman\", Times, serif;'>
  <tr>
    <td style='width: 15%; border: none; text-align: center; vertical-align: middle; padding-bottom: 5pt;'>
      " . ($logoL ? "<img src=\"{$logoL}\" width=\"60\" style=\"width: 50pt; height: auto;\" />" : "") . "
    </td>
    <td style='width: 85%; border: none; text-align: center; vertical-align: middle; line-height: 1.2; padding-right: 15pt; padding-bottom: 5pt;'>
      <span style='font-size: 11pt; font-weight: bold; text-transform: uppercase;'>{$dinas}</span><br>
      <span style='font-size: 14pt; font-weight: bold; text-transform: uppercase;'>{$sekolah}</span><br>
      <span style='font-size: 9pt; font-style: italic;'>{$alamat}</span>
    </td>
  </tr>
  <tr>
    <td colspan='2' style='border: none; border-top: 3px double #000000; padding: 0; height: 1px; line-height: 1px;'>&nbsp;</td>
  </tr>
</table>
";
        }

        $kopHtml .= "
<h3 style='text-align: center; font-size: 12pt; font-weight: bold; margin-bottom: 10pt; text-transform: uppercase; font-family: \"Times New Roman\", Times, serif;'>{$ujian}<br>TAHUN AJARAN {$ta}</h3>

<table border='1' cellspacing='0' cellpadding='4' style='width: 100%; border-collapse: collapse; border: 1px solid #000000; margin-bottom: 20pt; font-family: \"Times New Roman\", Times, serif; font-size: 10pt;'>
  <tr>
    <td style='width: 15%; font-weight: bold; border: 1px solid #000000;'>Mata Pelajaran</td>
    <td style='width: 35%; border: 1px solid #000000;'>{$request->input('subject')}</td>
    <td style='width: 15%; font-weight: bold; border: 1px solid #000000;'>Nama Siswa</td>
    <td style='width: 35%; border: 1px solid #000000;'>......................................</td>
  </tr>
  <tr>
    <td style='width: 15%; font-weight: bold; border: 1px solid #000000;'>Kelas / Semester</td>
    <td style='width: 35%; border: 1px solid #000000;'>{$request->input('grade')}</td>
    <td style='width: 15%; font-weight: bold; border: 1px solid #000000;'>Nomor Absen</td>
    <td style='width: 35%; border: 1px solid #000000;'>......................................</td>
  </tr>
  <tr>
    <td style='width: 15%; font-weight: bold; border: 1px solid #000000;'>Hari / Tanggal</td>
    <td style='width: 35%; border: 1px solid #000000;'>............................</td>
    <td style='width: 15%; font-weight: bold; border: 1px solid #000000;'>Waktu</td>
    <td style='width: 35%; border: 1px solid #000000;'>{$waktu}</td>
  </tr>
</table>
";

        $prompt = "Buatlah kumpulan soal evaluasi pembelajaran yang profesional dan lengkap dengan kunci jawaban dengan detail berikut:
Mata Pelajaran: {$request->input('subject')}
Topik/Materi: {$request->input('topic')}
Jenjang/Kelas: {$request->input('grade')}
Tingkat Kesulitan: {$difficultyText} (HOTS, MOTS, LOTS, atau Campuran)
Tipe Soal: {$typeText} (Pilihan Ganda biasa/kompleks, Mencocokkan, Essay, dll.)
Jumlah Soal: {$request->input('quantity')} Soal
Indikator Pembelajaran/Kisi-kisi: {$indicatorText}

ATURAN OUTPUT SANGAT KETAT:
1. HANYA keluarkan konten dokumen Soal dalam format Markdown. JANGAN ada teks pengantar atau penutup.
2. JANGAN menuliskan ringkasan metadata (seperti 'Mata Pelajaran', 'Topik/Materi', 'Jenjang/Kelas', 'Tingkat Kesulitan', 'Tipe Soal', 'Jumlah Soal', atau 'Indikator Pembelajaran/Kisi-kisi') di awal dokumen di bawah heading `## SOAL EVALUASI`. Lembar soal harus langsung dimulai dengan butir soal nomor 1.
3. Dokumen harus dibagi menjadi TIGA BAGIAN yang dipisahkan oleh tag `[PAGE_BREAK]` pada baris baru secara berurutan:
   - Bagian 1: `## SOAL EVALUASI` (berisi daftar pertanyaan ujian yang langsung dimulai dengan nomor 1, dst. Tanpa teks ringkasan metadata di awal).
   - `[PAGE_BREAK]`
   - Bagian 2: `## KISI-KISI SOAL` (berisi tabel spesifikasi/kisi-kisi soal dalam bentuk tabel Markdown dengan kolom: `| No. Soal | Indikator Pembelajaran | Level Kognitif | Kunci Jawaban |`).
   - `[PAGE_BREAK]`
   - Bagian 3: `## KUNCI JAWABAN` (berisi daftar kunci jawaban dan pembahasan rinci untuk masing-masing soal).
4. Format penulisan soal harus menggunakan penomoran langsung (misal: '1. Pertanyaan...', '2. Pertanyaan...') dan pilihan ganda menggunakan opsi A, B, C, D di baris baru di bawahnya. JANGAN menuliskan sub-heading seperti '### Soal 1' atau '### Soal 2'. Penomoran harus langsung 1, 2, 3, dst.
   - Jika tipe soal adalah **Pilihan Ganda Dua Tingkat (Two-Tier MCQ)**: Setiap nomor soal harus terdiri dari 2 tingkat berturut-turut. Tingkat 1 adalah pertanyaan pilihan ganda biasa (opsi A, B, C, D). Di bawah pilihan tingkat 1, buat teks `Tingkat 2 (Alasan):` di baris baru, lalu ikuti dengan opsi alasan A, B, C, D di baris baru di bawahnya.
   - Jika tipe soal adalah **Soal Berantai (Chain Problem MCQ)**: Kelompokkan soal berdasarkan stimulus bersama. Tulis stimulus/narasi/data di atas kelompok soal tersebut (misal: `**Konteks Ujian (Soal No. 1-2):** [Teks Narasi/Data]`), kemudian ikuti dengan butir soal bernomor langsung (1, 2, dst) yang saling terikat (jawaban soal berikutnya bergantung pada pengerjaan soal sebelumnya).
   - Jika tipe soal adalah **Pilihan Ganda Matriks (Matrix MCQ)**: Tulis instruksi pertanyaan bernomor langsung, lalu ikuti dengan tabel spesifikasi kecocokan (Matrix) Markdown. Baris tabel mewakili pernyataan/premis, dan kolom mewakili kategori/opsi jawaban, dengan isi sel tabel berupa kotak centang kosong `[ ]` (contoh: `| Pernyataan | Kategori A | Kategori B |`).
5. JANGAN menuliskan simbol matematika menggunakan format LaTeX (seperti wrapping dengan karakter dollar '$' atau perintah LaTeX '\frac', '\neq', '\cdot', '\sqrt', dll). 
   - Tulis rumus matematika dengan teks biasa atau simbol Unicode yang umum dan mudah dibaca di Microsoft Word dan web (misalnya: gunakan pangkat superskrip seperti 'x² + y² = 10', gunakan simbol ketidaksamaan '≠', '≤', '≥', perkalian 'x' atau '*', dan gunakan pecahan biasa seperti '1/2' atau 'y = 1/2x - 2').
   - JANGAN pernah menggunakan karakter '$' untuk membungkus rumus matematika.
6. JANGAN membuat kolom tanda tangan di bagian bawah dokumen.
7. Di bagian `## KISI-KISI SOAL`, buat tabel dengan kolom `| No. Soal | Indikator Pembelajaran | Level Kognitif | Kunci Jawaban |`. Untuk soal Dua Tingkat, kunci jawaban ditulis rangkap (misal: `B & A`), sedangkan untuk soal Matriks ditulis ringkas (misal: `Baris 1-A, Baris 2-B`).
8. Di bagian `## KUNCI JAWABAN`, tulis kunci jawaban dan pembahasan rinci untuk masing-masing soal secara terpisah.
Jawab dalam Bahasa Indonesia dengan format Markdown yang sangat rapi, formal, dan lengkap tanpa terpotong.";

        $text = '';
        $isMock = false;

        if ($useMock) {
            $isMock = true;
            $text = $this->getMockSoal($request);
        } else {
            try {
                $response = Http::timeout(90)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 8192,
                        'temperature' => 0.7
                    ],
                    'safetySettings' => [
                        ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE']
                    ]
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Gagal memproses pembuatan soal.';
                } else {
                    Log::error('Gemini API Error (Generator Soal): ' . $response->body());
                    return response()->json(['error' => 'Gagal menghubungi Gemini API.'], 500);
                }
            } catch (\Exception $e) {
                Log::error('Gemini Connection Error (Generator Soal): ' . $e->getMessage());
                return response()->json(['error' => 'Koneksi ke Gemini API gagal.'], 500);
            }
        }

        // Sanitize mathematical LaTeX symbols
        $text = $this->cleanupMathLaTeX($text);
        $fullContent = $kopHtml . "\n\n" . $text;

        // Save to Database
        $document = Auth::user()->documents()->create([
            'name' => 'Soal ' . $request->input('subject') . ' - ' . $request->input('topic'),
            'type' => 'Soal',
            'status' => 'Selesai',
            'content' => $fullContent,
            'is_mock' => $isMock,
        ]);

        $htmlContent = (new DocumentController())->markdownToHtml($fullContent, true);

        return response()->json([
            'id' => $document->id,
            'content' => $fullContent,
            'html_content' => $htmlContent,
            'is_mock' => $isMock,
        ]);
    }

    public function generateImage(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:255',
        ]);

        $prompt = urlencode($request->input('prompt'));
        $randomSeed = rand(1000, 9999);
        
        // Menggunakan pollinations.ai untuk image generation tanpa API key
        $imageUrl = "https://image.pollinations.ai/prompt/{$prompt}?nologo=true&seed={$randomSeed}";

        return response()->json([
            'success' => true,
            'image_url' => $imageUrl
        ]);
    }

    private function cleanAndParseJson($string)
    {
        if (empty($string)) {
            return null;
        }

        // 1. Try decoding directly
        $decoded = json_decode($string, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // 2. Clean markdown blocks
        $cleaned = preg_replace('/```json\s*/i', '', $string);
        $cleaned = preg_replace('/```/i', '', $cleaned);
        $cleaned = trim($cleaned);

        $decoded = json_decode($cleaned, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // 3. Try regex replacement for unescaped control characters inside strings
        $sanitized = preg_replace_callback('/"([^"\\\\]|\\\\.)*"/', function ($matches) {
            return str_replace(["\n", "\r", "\t"], ["\\n", "\\r", "\\t"], $matches[0]);
        }, $cleaned);

        $decoded = json_decode($sanitized, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // 4. Try extracting only the JSON object { ... } or array [ ... ]
        $firstCurly = strpos($sanitized, '{');
        $lastCurly = strrpos($sanitized, '}');
        
        if ($firstCurly !== false && $lastCurly !== false && $lastCurly > $firstCurly) {
            $jsonCandidate = substr($sanitized, $firstCurly, $lastCurly - $firstCurly + 1);
            $decoded = json_decode($jsonCandidate, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        $firstBracket = strpos($sanitized, '[');
        $lastBracket = strrpos($sanitized, ']');
        
        if ($firstBracket !== false && $lastBracket !== false && $lastBracket > $firstBracket) {
            $jsonCandidate = substr($sanitized, $firstBracket, $lastBracket - $firstBracket + 1);
            $decoded = json_decode($jsonCandidate, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return null;
    }

    private function cleanupMathLaTeX($text)
    {
        // Replace LaTeX symbols
        $replacements = [
            '\\neq' => '≠',
            '\\le' => '≤',
            '\\ge' => '≥',
            '\\times' => '×',
            '\\div' => '÷',
            '\\pm' => '±',
            '\\approx' => '≈',
            '\\infty' => '∞',
            '\\cdot' => '·',
        ];
        $text = str_replace(array_keys($replacements), array_values($replacements), $text);

        // Convert simple fractions: \frac{a}{b} -> (a/b)
        $text = preg_replace('/\\\\frac\{([^}]+)\}\{([^}]+)\}/', '($1/$2)', $text);

        // Convert square roots: \sqrt{a} -> √a
        $text = preg_replace('/\\\\sqrt\{([^}]+)\}/', '√$1', $text);

        // Strip mathematical LaTeX delimiters like $...$ or $$...$$
        $text = preg_replace('/\\\$\$?([^\$]+)\\\$\$?/', '$1', $text);

        // Clean up superscripts like ^2 -> ²
        $superscripts = [
            '^0' => '⁰',
            '^1' => '¹',
            '^2' => '²',
            '^3' => '³',
            '^4' => '⁴',
            '^5' => '⁵',
            '^6' => '⁶',
            '^7' => '⁷',
            '^8' => '⁸',
            '^9' => '⁹',
            '^n' => 'ⁿ',
            '^x' => 'ˣ',
        ];
        $text = str_replace(array_keys($superscripts), array_values($superscripts), $text);

        return $text;
    }

    private function getMockSoal($request)
    {
        $subject = $request->input('subject');
        $topic = $request->input('topic');
        $grade = $request->input('grade');
        $difficulty = $request->input('difficulty');
        $type = $request->input('question_type');
        $quantity = $request->input('quantity');
        $indicator = $request->input('indicator') ?: 'Menjelaskan konsep utama dan menganalisis dampaknya.';

        $soalMarkdown = "## SOAL EVALUASI\n";

        if (str_contains(strtolower($type), 'dua tingkat') || str_contains(strtolower($type), 'two-tier')) {
            // Two-Tier MCQ mock questions
            for ($i = 1; $i <= $quantity; $i++) {
                $soalMarkdown .= "\n{$i}. Bagaimana pergerakan atau karakteristik konsep {$topic} di kelas {$grade} jika dipengaruhi oleh perubahan kondisi lingkungan luar? ({$difficulty})\n";
                $soalMarkdown .= "A. Menunjukkan kestabilan yang konstan dan tidak terpengaruh.\n";
                $soalMarkdown .= "B. Mengalami fluktuasi perubahan laju reaksi secara eksponensial.\n";
                $soalMarkdown .= "C. Menghentikan seluruh aktivitas konseptualnya seketika.\n";
                $soalMarkdown .= "D. Mengurangi kebutuhan energi dasarnya secara drastis.\n";
                $soalMarkdown .= "Tingkat 2 (Alasan):\n";
                $soalMarkdown .= "A. Perubahan kondisi luar mempercepat tumbukan partikel konseptual.\n";
                $soalMarkdown .= "B. Energi aktivasi sistem meningkat akibat suhu sekitar yang dingin.\n";
                $soalMarkdown .= "C. Ketiadaan stimulus luar memicu kemandekan rantai proses utama.\n";
                $soalMarkdown .= "D. Sistem beradaptasi dengan menurunkan laju metabolisme konseptual.\n";
            }
        } elseif (str_contains(strtolower($type), 'berantai') || str_contains(strtolower($type), 'chain')) {
            // Chain Problem MCQ mock questions (requires a shared stimulus context)
            $soalMarkdown .= "\n**Konteks Ujian (Soal No. 1-{$quantity}):**\nDalam sebuah kegiatan investigasi ilmiah mengenai {$topic} pada kelas {$grade}, dilakukan pengamatan intensif selama 3 jam terhadap reaksi spesimen terhadap fluktuasi variabel bebas. Data awal menunjukkan konsentrasi bahan awal adalah 100 unit dengan laju penurunan konstan 10 unit per 30 menit.\n\n";
            for ($i = 1; $i <= $quantity; $i++) {
                if ($i === 1) {
                    $soalMarkdown .= "{$i}. Berdasarkan data awal di atas, berapakah sisa konsentrasi bahan awal setelah berjalan selama 1 jam pertama? ({$difficulty})\n";
                    $soalMarkdown .= "A. 80 unit\n";
                    $soalMarkdown .= "B. 70 unit\n";
                    $soalMarkdown .= "C. 60 unit\n";
                    $soalMarkdown .= "D. 50 unit\n";
                } elseif ($i === 2) {
                    $soalMarkdown .= "{$i}. Menggunakan sisa konsentrasi dari jawaban soal nomor 1, jika laju penurunan tiba-tiba berlipat ganda pada jam kedua, berapa unit sisa bahan di akhir jam kedua? ({$difficulty})\n";
                    $soalMarkdown .= "A. 40 unit\n";
                    $soalMarkdown .= "B. 30 unit\n";
                    $soalMarkdown .= "C. 20 unit\n";
                    $soalMarkdown .= "D. 10 unit\n";
                } else {
                    $soalMarkdown .= "{$i}. Berdasarkan hasil akhir pada soal nomor 2, manakah langkah tindak lanjut yang paling tepat untuk mengembalikan kestabilan {$topic}? ({$difficulty})\n";
                    $soalMarkdown .= "A. Menambahkan konsentrasi awal sebesar 80 unit kembali.\n";
                    $soalMarkdown .= "B. Mengurangi variabel bebas agar laju reaksi melambat.\n";
                    $soalMarkdown .= "C. Mengisolasi spesimen dalam wadah vakum kedap udara.\n";
                    $soalMarkdown .= "D. Menambahkan katalisator alami untuk mempercepat adaptasi.\n";
                }
            }
        } elseif (str_contains(strtolower($type), 'matriks') || str_contains(strtolower($type), 'matrix')) {
            // Matrix MCQ mock questions
            for ($i = 1; $i <= $quantity; $i++) {
                $soalMarkdown .= "\n{$i}. Tentukanlah peranan, klasifikasi, atau kecocokan yang tepat dari komponen-komponen {$topic} ke-{$i} dengan mencentang kotak [ ] pada kolom tabel kategori di bawah ini! ({$difficulty})\n\n";
                $soalMarkdown .= "| Komponen {$topic} | Kategori Utama | Kategori Pendukung | Kategori Distraktor |\n";
                $soalMarkdown .= "| --- | --- | --- | --- |\n";
                $soalMarkdown .= "| Aspek Teori Ke-1 | [ ] | [ ] | [ ] |\n";
                $soalMarkdown .= "| Aspek Teori Ke-2 | [ ] | [ ] | [ ] |\n";
                $soalMarkdown .= "| Aspek Teori Ke-3 | [ ] | [ ] | [ ] |\n";
            }
        } elseif (str_contains(strtolower($type), 'essay') || str_contains(strtolower($type), 'uraian')) {
            // Essay mock questions
            for ($i = 1; $i <= $quantity; $i++) {
                $soalMarkdown .= "\n{$i}. Jelaskan dengan analisis yang mendalam bagaimana konsep penting dari {$topic} memengaruhi kehidupan sehari-hari siswa di lingkungan sekolah maupun rumah, serta berikan 2 contoh konkret penerapan konseptualnya! ({$difficulty})\n";
            }
        } elseif (str_contains(strtolower($type), 'mencocokkan') || str_contains(strtolower($type), 'jodohkan')) {
            // Matching mock questions
            $soalMarkdown .= "\n### Petunjuk:\nHubungkanlah pernyataan di kolom sebelah kiri (Pernyataan A) dengan pasangan yang tepat di kolom sebelah kanan (Jawaban B) berdasarkan konsep {$topic}!\n\n";
            $soalMarkdown .= "| Pernyataan A | Jawaban B |\n| --- | --- |\n";
            for ($i = 1; $i <= $quantity; $i++) {
                $soalMarkdown .= "| {$i}. Deskripsi atau ciri khusus konsep {$topic} ke-{$i} | A. Istilah Konseptual Ke-{$i} |\n";
            }
        } else {
            // Pilihan ganda, kompleks, or campuran (Default)
            for ($i = 1; $i <= $quantity; $i++) {
                $soalMarkdown .= "\n{$i}. Manakah di bawah ini yang merupakan contoh penerapan atau pemahaman yang paling tepat dari materi {$topic} di tingkat kelas {$grade}? ({$difficulty})\n";
                $soalMarkdown .= "A. Penerapan teoritis ke-1 yang umum terjadi di lingkungan.\n";
                $soalMarkdown .= "B. Pilihan jawaban yang merepresentasikan analisis tingkat tinggi.\n";
                $soalMarkdown .= "C. Opsi distraktor yang kurang relevan dengan materi.\n";
                $soalMarkdown .= "D. Jawaban alternatif pendukung lainnya.\n";
            }
        }

        $soalMarkdown .= "\n\n[PAGE_BREAK]\n\n## KISI-KISI SOAL\n\n";
        $soalMarkdown .= "| No. Soal | Indikator Pembelajaran | Level Kognitif | Kunci Jawaban |\n";
        $soalMarkdown .= "| --- | --- | --- | --- |\n";

        if (str_contains(strtolower($type), 'mencocokkan') || str_contains(strtolower($type), 'jodohkan')) {
            $keyVal = 'Pasangan B';
        } elseif (str_contains(strtolower($type), 'essay') || str_contains(strtolower($type), 'uraian')) {
            $keyVal = 'Uraian';
        } elseif (str_contains(strtolower($type), 'dua tingkat') || str_contains(strtolower($type), 'two-tier')) {
            $keyVal = 'B & A';
        } elseif (str_contains(strtolower($type), 'matriks') || str_contains(strtolower($type), 'matrix')) {
            $keyVal = '1-Utama, 2-Pendukung, 3-Utama';
        } else {
            $keyVal = 'B';
        }

        for ($i = 1; $i <= $quantity; $i++) {
            $soalMarkdown .= "| {$i} | Memahami dan menganalisis konsep {$topic} ke-{$i} secara mendalam | {$difficulty} | {$keyVal} |\n";
        }

        $soalMarkdown .= "\n\n[PAGE_BREAK]\n\n## KUNCI JAWABAN\n\n";

        if (str_contains(strtolower($type), 'essay') || str_contains(strtolower($type), 'uraian')) {
            for ($i = 1; $i <= $quantity; $i++) {
                $soalMarkdown .= "{$i}. **Kunci Jawaban**:\nJawaban ideal harus mengandung penjelasan komprehensif mengenai konsep {$topic}, analisis hubungan sebab-akibat dalam kehidupan sehari-hari, serta minimal 2 contoh penerapan yang relevan. Bobot nilai maksimal diberikan apabila analisis logis dan sistematis.\n\n";
            }
        } elseif (str_contains(strtolower($type), 'mencocokkan') || str_contains(strtolower($type), 'jodohkan')) {
            for ($i = 1; $i <= $quantity; $i++) {
                $soalMarkdown .= "**Pernyataan {$i}** berpasangan dengan **Jawaban B (Istilah Konseptual Ke-{$i})**.\n";
            }
        } elseif (str_contains(strtolower($type), 'dua tingkat') || str_contains(strtolower($type), 'two-tier')) {
            for ($i = 1; $i <= $quantity; $i++) {
                $soalMarkdown .= "{$i}. **Tingkat 1: B & Tingkat 2: A**\n(Pembahasan: Opsi B adalah jawaban tingkat pertama yang merepresentasikan analisis tingkat tinggi konsep {$topic}, didukung alasan A karena perubahan kondisi luar terbukti secara ilmiah mempercepat laju reaksi partikel.)\n\n";
            }
        } elseif (str_contains(strtolower($type), 'matriks') || str_contains(strtolower($type), 'matrix')) {
            for ($i = 1; $i <= $quantity; $i++) {
                $soalMarkdown .= "{$i}. **Kunci Jawaban Matriks**:\n- Aspek Teori Ke-1: Kategori Utama (Benar)\n- Aspek Teori Ke-2: Kategori Pendukung (Benar)\n- Aspek Teori Ke-3: Kategori Utama (Benar)\n(Pembahasan: Komponen {$topic} ke-{$i} memiliki peranan terspesialisasi yang diklasifikasikan berdasarkan relevansi fungsionalnya.)\n\n";
            }
        } else {
            for ($i = 1; $i <= $quantity; $i++) {
                $soalMarkdown .= "{$i}. **B** (Pembahasan: Opsi B adalah jawaban yang paling komprehensif dan secara tepat mencakup pilar utama dari {$topic} sesuai indikator pembelajaran.)\n";
            }
        }

        return $soalMarkdown;
    }

    public function generatePPT(Request $request)
    {
        $limitCheck = $this->checkSubscriptionLimits('PPT Pembelajaran');
        if ($limitCheck) return $limitCheck;

        set_time_limit(120);

        $request->validate([
            'subject' => 'required|string|max:255',
            'topic' => 'required|string|max:255',
            'grade' => 'required|string|max:100',
            'difficulty' => 'required|string|max:100',
            'slides_count' => 'required|integer|min:3|max:20',
            'theme' => 'required|string|max:50',
        ]);

        $apiKey = config('services.gemini.api_key');
        $useMock = $request->input('use_mock') === '1' || !$apiKey;

        // Daily limit check
        if (!$useMock) {
            $todayCount = Auth::user()->documents()
                ->where('is_mock', false)
                ->whereDate('created_at', now()->toDateString())
                ->count();

            if ($todayCount >= 10) {
                return response()->json([
                    'error' => 'Anda telah mencapai batas maksimum pembuatan dokumen (10 kali per hari).'
                ], 429);
            }
        }

        $prompt = "Buatlah rancangan slide presentasi pembelajaran (PPT) yang menarik dan profesional berdasarkan detail berikut:
Mata Pelajaran: {$request->input('subject')}
Topik/Materi: {$request->input('topic')}
Jenjang/Kelas: {$request->input('grade')}
Tingkat Kesulitan: {$request->input('difficulty')}
Jumlah Slide: {$request->input('slides_count')} Slide

ATURAN OUTPUT SANGAT KETAT:
1. HANYA keluarkan data dalam format JSON yang valid. JANGAN ada teks pengantar atau penutup.
2. Format JSON harus berupa objek yang memiliki properti 'slides', yang merupakan array dari objek slide.
3. Setiap objek slide harus memiliki properti berikut:
   - 'title': (string) Judul slide yang ringkas dan menarik.
   - 'bullets': (array of strings) Poin-poin materi utama (maksimal 3-4 poin per slide, masing-masing poin pendek dan padat).
   - 'image_keyword': (string) 1-2 kata kunci dalam bahasa Inggris untuk mencari gambar ilustrasi/latar belakang yang relevan (misal: 'photosynthesis', 'water cycle', 'solar system').
   - 'notes': (string) Catatan penjelasan detail untuk dibacakan oleh guru saat presentasi.
4. Pastikan slide disusun terstruktur secara logis:
   - Slide 1: Judul Utama Presentasi & Identitas
   - Slide 2: Pengantar/Apersepsi/Pertanyaan Pemantik
   - Slide 3 s.d. (N-1): Pembahasan materi bertahap. PENTING: Judul pada slide-slide ini HARUS KREATIF dan SPESIFIK sesuai sub-topik yang sedang dibahas (contoh: 'Fase Evaporasi: Menguapnya Air' atau 'Peran Matahari dalam Siklus'). JANGAN menggunakan judul generik seperti 'Pembahasan Bagian 1'.
   - Slide Terakhir (N): Kesimpulan/Refleksi & Kuis singkat atau Penutup.

Contoh struktur format JSON:
{
  \"slides\": [
    {
      \"title\": \"Judul Slide 1\",
      \"bullets\": [
        \"Poin penting 1\",
        \"Poin penting 2\"
      ],
      \"image_keyword\": \"keyword\",
      \"notes\": \"Catatan guru...\"
    }
  ]
}

Jawab dengan JSON yang valid tanpa markdown code block (tanpa ```json ... ```) atau teks lainnya.";

        $text = '';
        $isMock = false;

        if ($useMock) {
            $isMock = true;
            $text = $this->getMockPPT($request);
        } else {
            try {
                // Request Gemini API
                $response = Http::timeout(90)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 8192,
                        'responseMimeType' => 'application/json'
                    ]
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{"slides":[]}';
                } else {
                    Log::error('Gemini API Error (PPT): ' . $response->body());
                    return response()->json(['error' => 'Gagal menghubungi Gemini API.'], 500);
                }
            } catch (\Exception $e) {
                Log::error('Gemini Connection Error (PPT): ' . $e->getMessage());
                return response()->json(['error' => 'Koneksi ke Gemini API gagal.'], 500);
            }
        }

        // Clean JSON formatting if Gemini wrapped it in markdown code block
        $text = trim($text);
        if (str_starts_with($text, '```json')) {
            $text = substr($text, 7);
        }
        if (str_starts_with($text, '```')) {
            $text = substr($text, 3);
        }
        if (str_ends_with($text, '```')) {
            $text = substr($text, 0, -3);
        }
        $text = trim($text);

        // Save to Database (we save JSON string as content)
        $document = Auth::user()->documents()->create([
            'name' => 'PPT ' . $request->input('subject') . ' - ' . $request->input('topic'),
            'type' => 'PPT',
            'status' => 'Selesai',
            'content' => $text,
            'is_mock' => $isMock,
        ]);

        return response()->json([
            'id' => $document->id,
            'content' => $text,
            'is_mock' => $isMock,
        ]);
    }

    private function getMockPPT($request)
    {
        $subject = $request->input('subject');
        $topic = $request->input('topic');
        $grade = $request->input('grade');
        $slidesCount = intval($request->input('slides_count'));

        $slides = [];

        // Slide 1: Title
        $slides[] = [
            'title' => "Mengenal " . $topic,
            'bullets' => [
                "Mata Pelajaran: " . $subject,
                "Kelas: " . $grade,
                "Mari kita eksplorasi bersama asisten cerdas PandAI!"
            ],
            'image_keyword' => "education school",
            'notes' => "Selamat pagi/siang anak-anak. Hari ini kita akan mempelajari topik yang sangat menarik, yaitu " . $topic . ". Harap perhatikan slide dengan seksama."
        ];

        // Slide 2: Intro
        $slides[] = [
            'title' => "Pertanyaan Pemantik",
            'bullets' => [
                "Pernahkah kalian mengamati konsep " . $topic . " di lingkungan sekitar?",
                "Mengapa hal ini penting bagi kehidupan kita sehari-hari?",
                "Tuliskan dugaan awal kalian di selembar kertas!"
            ],
            'image_keyword' => "question mark curiosity",
            'notes' => "Sebelum memulai, Ibu/Bapak ingin bertanya: Apakah ada yang pernah mendengar istilah " . $topic . "? Silakan sampaikan pendapat kalian secara bebas."
        ];

        $creativeTitles = [
            'Memahami Konsep Dasar',
            'Faktor Utama yang Memengaruhi',
            'Studi Kasus di Dunia Nyata',
            'Dampak Jangka Panjang',
            'Solusi dan Tindakan Nyata',
            'Analisis Lebih Mendalam'
        ];

        for ($i = 2; $i < $slidesCount - 1; $i++) {
            $slides[] = [
                'title' => $creativeTitles[($i - 2) % count($creativeTitles)] . ' ' . $topic,
                'bullets' => [
                    "Penjelasan konsep dasar $topic fase " . ($i - 1) . ".",
                    "Faktor utama yang memengaruhi laju reaksi dan kestabilan sistem.",
                    "Contoh kasus nyata di sekitar lingkungan sekolah atau rumah kita."
                ],
                'image_keyword' => 'education',
                'notes' => "Pada slide ini, guru bisa menjelaskan bahwa $topic memiliki berbagai fase penting. Ajak siswa berdiskusi mengenai contoh sehari-hari."
            ];
        }

        // Final slide: Conclusion/Quiz
        $slides[] = [
            'title' => "Kesimpulan & Refleksi",
            'bullets' => [
                "Konsep utama " . $topic . " membantu menjaga kestabilan lingkungan.",
                "Kuis Singkat: Sebutkan 2 faktor utama yang kita pelajari hari ini!",
                "Terima kasih atas partisipasi aktif kalian!"
            ],
            'image_keyword' => "thank you success",
            'notes' => "Sebagai penutup, mari kita simpulkan bersama. " . $topic . " membuktikan betapa indahnya keteraturan alam. Jangan lupa kerjakan tugas mandiri di rumah ya!"
        ];

        // Slice to requested size just in case
        $slides = array_slice($slides, 0, $slidesCount);

        return json_encode(['slides' => $slides], JSON_PRETTY_PRINT);
    }

    public function generateRubrik(Request $request)
    {
        $limitCheck = $this->checkSubscriptionLimits('Rubrik Penilaian');
        if ($limitCheck) return $limitCheck;

        set_time_limit(120);
        try {
            $request->validate([
                'subject' => 'required|string',
                'topic' => 'required|string',
                'task_type' => 'required|string',
                'grade' => 'required|string',
                'scale' => 'required|numeric'
            ]);

            $useMock = $request->boolean('use_mock');
            $subject = $request->input('subject');
            $topic = $request->input('topic');
            $taskType = $request->input('task_type');
            $grade = $request->input('grade');
            $scale = (int)$request->input('scale', 4);
            $studentsRaw = $request->input('students', '');

            // Parse students list
            $students = [];
            if (!empty(trim($studentsRaw))) {
                $lines = explode("\n", $studentsRaw);
                foreach ($lines as $line) {
                    $name = trim($line);
                    if (!empty($name)) {
                        $students[] = $name;
                    }
                }
            }

            if ($useMock || empty(env('GEMINI_API_KEY'))) {
                $jsonResponse = $this->getMockRubrik($subject, $topic, $taskType, $grade, $scale);
            } else {
                $prompt = "Anda adalah AI Asisten Guru yang ahli dalam merancang matriks Rubrik Penilaian sesuai standar Kurikulum Merdeka di Indonesia.\n";
                $prompt .= "Buatlah Rubrik Penilaian untuk tugas berikut:\n";
                $prompt .= "- Mata Pelajaran: " . $subject . "\n";
                $prompt .= "- Topik/Materi: " . $topic . "\n";
                $prompt .= "- Jenis Tugas: " . $taskType . "\n";
                $prompt .= "- Kelas/Jenjang: " . $grade . "\n";
                $prompt .= "- Skala Nilai: 1 sampai " . $scale . "\n\n";

                $prompt .= "Aturan Output (WAJIB dipatuhi):\n";
                $prompt .= "1. Output HARUS murni JSON yang valid tanpa Markdown block (tanpa ```json ... ```).\n";
                $prompt .= "2. JSON harus memiliki struktur persis seperti ini:\n";
                $prompt .= "{\n";
                $prompt .= '  "title": "Rubrik Penilaian [Jenis Tugas]: [Topik]",' . "\n";
                $prompt .= '  "criteria": [' . "\n";
                $prompt .= "    {\n";
                $prompt .= '      "aspect": "Nama Aspek Penilaian (Misal: Penguasaan Materi)",' . "\n";
                $prompt .= '      "levels": [' . "\n";
                $prompt .= '        { "score": "Sangat Baik (' . $scale . ')", "description": "Kriteria untuk nilai tertinggi" },' . "\n";
                $prompt .= '        { "score": "...", "description": "..." }' . "\n";
                $prompt .= "      ]\n";
                $prompt .= "    }\n";
                $prompt .= "  ]\n";
                $prompt .= "}\n";
                $prompt .= "3. Buatlah minimal 3 sampai 5 'aspect' (kriteria) yang paling relevan dengan '$taskType' pada mata pelajaran '$subject'.\n";
                $prompt .= "4. Pada bagian 'levels', pastikan diurutkan dari skor paling tinggi ($scale) turun ke paling rendah (1).\n";
                $prompt .= "5. Deskripsi level harus spesifik, jelas, terukur, dan objektif.\n";

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . env('GEMINI_API_KEY'), [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 8192,
                        'responseMimeType' => 'application/json'
                    ]
                ]);

                if (!$response->successful()) {
                    Log::error('Gemini API Error: ' . $response->body());
                    return response()->json(['status' => 'error', 'message' => 'Gagal menghubungi API Gemini.']);
                }

                $geminiData = $response->json();
                $jsonResponse = $geminiData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            }

            $rubrikData = $this->cleanAndParseJson($jsonResponse);
            if (!$rubrikData || !isset($rubrikData['criteria'])) {
                Log::error('JSON Decode Error (Rubrik): ' . ($rubrikData ? 'Missing criteria key' : 'Invalid JSON format'));
                return response()->json(['status' => 'error', 'message' => 'Format output AI tidak valid.']);
            }

            // Normalization & Fallbacks
            $rubrikData['title'] = $rubrikData['title'] ?? 'Rubrik Penilaian';
            if (is_array($rubrikData['criteria'])) {
                foreach ($rubrikData['criteria'] as &$criterion) {
                    if (is_array($criterion)) {
                        $criterion['aspect'] = $criterion['aspect'] ?? 'Aspek';
                        if (isset($criterion['levels']) && is_array($criterion['levels'])) {
                            foreach ($criterion['levels'] as &$level) {
                                if (is_array($level)) {
                                    $level['score'] = $level['score'] ?? '';
                                    $level['description'] = $level['description'] ?? '';
                                }
                            }
                        } else {
                            $criterion['levels'] = [];
                        }
                    }
                }
            } else {
                $rubrikData['criteria'] = [];
            }

            // Save to database
            $document = Document::create([
                'user_id' => Auth::id(),
                'name' => 'Rubrik: ' . $topic,
                'type' => 'rubrik',
                'status' => 'completed',
                'is_mock' => $useMock,
                'content' => json_encode(['rubrik' => $rubrikData, 'students' => $students])
            ]);

            return response()->json([
                'status' => 'success', 
                'data' => [
                    'rubrik' => $rubrikData,
                    'students' => $students
                ],
                'document_id' => $document->id
            ]);

        } catch (\Exception $e) {
            Log::error('Exception in generateRubrik: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    private function getMockRubrik($subject, $topic, $taskType, $grade, $scale)
    {
        $levels = [];
        for ($i = $scale; $i >= 1; $i--) {
            $label = "Kurang";
            if ($i == $scale) $label = "Sangat Baik";
            else if ($i == $scale - 1) $label = "Baik";
            else if ($i > 1) $label = "Cukup";
            
            $levels[] = [
                'score' => "$label ($i)",
                'description' => "Penjelasan siswa terkait topik $topic pada tingkat ini memiliki kualitas yang $label. Ini adalah teks simulasi."
            ];
        }

        $criteria = [
            [
                'aspect' => 'Penguasaan Materi Dasar',
                'levels' => $levels
            ],
            [
                'aspect' => 'Kreativitas & Orisinalitas',
                'levels' => $levels
            ],
            [
                'aspect' => 'Kerapian / Presentasi',
                'levels' => $levels
            ],
            [
                'aspect' => 'Kerjasama Tim',
                'levels' => $levels
            ]
        ];

        return json_encode([
            'title' => "Rubrik Penilaian $taskType: $topic",
            'criteria' => $criteria
        ]);
    }

    public function generateBahanAjar(Request $request)
    {
        $limitCheck = $this->checkSubscriptionLimits('Bahan Ajar');
        if ($limitCheck) return $limitCheck;

        set_time_limit(120);
        $request->validate([
            'subject' => 'required|string',
            'topic' => 'required|string',
            'grade' => 'required|string',
            'visual_style' => 'required|string',
        ]);

        $subject = $request->input('subject');
        $topic = $request->input('topic');
        $grade = $request->input('grade');
        $visualStyle = $request->input('visual_style');
        $useMock = $request->input('use_mock', false);

        if ($useMock) {
            // Demo Mode
            $mockData = [
                "title" => "Metamorfosis Kupu-Kupu",
                "scenes" => [
                    [
                        "text" => "Pernahkah kamu melihat ulat bulu berubah jadi kupu-kupu cantik?",
                        "narration" => "Halo teman-teman! Tahukah kamu proses luar biasa di balik cantiknya sayap kupu-kupu? Mari kita jelajahi keajaiban alam ini.",
                        "image_keyword" => "caterpillar nature"
                    ],
                    [
                        "text" => "Semuanya dimulai dari telur yang sangat kecil.",
                        "narration" => "Fase pertama, kupu-kupu betina akan bertelur di bawah daun. Telur ini sangat kecil, seukuran ujung jarum!",
                        "image_keyword" => "butterfly egg leaf"
                    ],
                    [
                        "text" => "Ulat rakus yang selalu lapar!",
                        "narration" => "Dari telur menetaslah ulat atau larva. Tugas utamanya hanya satu: MAKAN! Ia mengunyah daun tiada henti untuk tumbuh besar.",
                        "image_keyword" => "caterpillar eating leaf"
                    ],
                    [
                        "text" => "Fase diam dalam kepompong pelindung.",
                        "narration" => "Setelah kenyang, ulat membungkus dirinya menjadi Pupa atau kepompong. Di dalam sana, tubuhnya meleleh dan berubah total secara ajaib.",
                        "image_keyword" => "chrysalis cocoon"
                    ],
                    [
                        "text" => "Kelahiran kembali sebagai Imago!",
                        "narration" => "Akhirnya, kepompong sobek dan keluarlah Imago, si kupu-kupu dewasa bersayap indah yang siap terbang dan mengisap nektar.",
                        "image_keyword" => "butterfly wings beautiful"
                    ]
                ],
                "handout" => "# Metamorfosis Sempurna: Daur Hidup Kupu-Kupu\n\nMetamorfosis adalah proses biologis yang menakjubkan di mana seekor hewan mengalami perubahan bentuk fisik yang drastis setelah menetas atau lahir.\n\n## 4 Tahapan Metamorfosis Sempurna:\n1. **Telur**: Fase awal. Induk kupu-kupu meletakkan telurnya pada daun tanaman inang yang spesifik.\n2. **Larva (Ulat)**: Fase makan aktif. Ulat akan makan terus menerus untuk menyimpan energi cadangan.\n3. **Pupa (Kepompong)**: Fase transformasi. Ulat berhenti makan dan berdiam diri, sementara di dalamnya terjadi perombakan sel menjadi struktur tubuh dewasa.\n4. **Imago (Dewasa)**: Fase reproduksi. Kupu-kupu dewasa memiliki sayap dan siap terbang mencari pasangan untuk memulai siklus kembali."
            ];

            // Save document
            $doc = new Document();
            $doc->user_id = Auth::id() ?? 1;
            $doc->name = "Bahan Ajar: " . $topic;
            $doc->type = 'Bahan Ajar';
            $doc->content = json_encode($mockData);
            $doc->is_mock = true;
            $doc->save();

            return response()->json([
                'status' => 'success',
                'data' => $mockData,
                'document_id' => $doc->id
            ]);
        }

        // Real AI Mode
        $prompt = "Tolong buatkan Bahan Ajar berformat Web Story (animasi vertikal interaktif) dan Ringkasan Handout untuk topik '{$topic}' pada pelajaran {$subject} kelas {$grade}.\n\n";
        $prompt .= "Format output WAJIB berupa JSON dengan struktur persis seperti ini:\n";
        $prompt .= "{\n";
        $prompt .= '  "title": "Judul Bahan Ajar",' . "\n";
        $prompt .= '  "scenes": [' . "\n";
        $prompt .= '    {' . "\n";
        $prompt .= '      "text": "Teks singkat menarik (1-2 kalimat pendek) untuk muncul di layar vertikal (TikTok style).",' . "\n";
        $prompt .= '      "narration": "Narasi lengkap yang akan dibacakan oleh text-to-speech engine (sekitar 10-20 kata per scene). Buat menarik dan interaktif layaknya penyiar edukasi.",' . "\n";
        $prompt .= '      "image_keyword": "1-3 kata kunci bahasa inggris untuk pencarian gambar background, misal: space galaxy" ' . "\n";
        $prompt .= '    }' . "\n";
        $prompt .= "  ],\n";
        $prompt .= '  "handout": "Teks panjang lengkap menggunakan Markdown yang menjelaskan materi secara komprehensif untuk dicetak siswa."' . "\n";
        $prompt .= "}\n\n";
        $prompt .= "Buatkan sekitar 5-8 scenes yang berkesinambungan dan pastikan JSON valid tanpa error format. Jangan gunakan blok ```json di awal/akhir respons, langsung saja kurung kurawal. Jangan ada text lain.";

        try {
            $apiKey = config('services.gemini.api_key');
            $response = Http::timeout(120)->withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 8192,
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API Error: ' . $response->body());
                return response()->json(['status' => 'error', 'message' => 'Gagal menghubungi API Gemini.']);
            }

            $geminiData = $response->json();
            $jsonString = $geminiData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            $data = $this->cleanAndParseJson($jsonString);

            if (!$data || !isset($data['scenes'])) {
                Log::error('Gemini API JSON Error (Bahan Ajar): ' . ($data ? 'Missing scenes key' : 'Invalid JSON format'));
                Log::error('Raw response: ' . $jsonString);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membaca format JSON dari AI. Silakan coba lagi.'
                ], 500);
            }

            // Normalization
            $data['title'] = $data['title'] ?? ($topic ? ucwords($topic) : 'Bahan Ajar');
            $data['handout'] = $data['handout'] ?? '';
            if (is_array($data['scenes'])) {
                foreach ($data['scenes'] as &$scene) {
                    if (is_array($scene)) {
                        $scene['text'] = $scene['text'] ?? '';
                        $scene['narration'] = $scene['narration'] ?? '';
                        $scene['image_keyword'] = $scene['image_keyword'] ?? '';
                    }
                }
            } else {
                $data['scenes'] = [];
            }

            // Save
            $doc = new Document();
            $doc->user_id = Auth::id() ?? 1;
            $doc->name = "Bahan Ajar: " . $topic;
            $doc->type = 'Bahan Ajar';
            $doc->content = json_encode($data);
            $doc->is_mock = false;
            $doc->save();

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'document_id' => $doc->id
            ]);

        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal terhubung ke AI: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateBahanAjarUtama(Request $request)
    {
        $limitCheck = $this->checkSubscriptionLimits('Bahan Ajar Utama');
        if ($limitCheck) return $limitCheck;

        set_time_limit(120);
        $request->validate([
            'subject' => 'required|string',
            'topic' => 'required|string',
            'grade' => 'required|string',
            'depth' => 'required|string',
            'features' => 'nullable|array',
        ]);

        $subject = $request->input('subject');
        $topic = $request->input('topic');
        $grade = $request->input('grade');
        $depth = $request->input('depth');
        $features = $request->input('features', ['materi', 'concept_map', 'lkpd', 'quiz']);
        $useMock = $request->input('use_mock') === '1';

        if ($useMock) {
            $mockData = $this->getMockBahanAjarUtama($subject, $topic, $grade, $depth, $features);

            $doc = new Document();
            $doc->user_id = Auth::id() ?? 1;
            $doc->name = "Bahan Ajar: " . $topic;
            $doc->type = 'Bahan Ajar Utama';
            $doc->content = json_encode($mockData);
            $doc->is_mock = true;
            $doc->save();

            return response()->json([
                'status' => 'success',
                'data' => $mockData,
                'document_id' => $doc->id
            ]);
        }

        // Real AI Mode
        $prompt = "Tolong buatkan Bahan Ajar Komprehensif kelas {$grade} pelajaran {$subject} tentang topik '{$topic}' dengan kedalaman tingkat '{$depth}'.\n\n";
        $prompt .= "Format output WAJIB berupa JSON dengan struktur persis seperti ini:\n";
        $prompt .= "{\n";
        $prompt .= '  "title": "Judul Bahan Ajar terperinci",' . "\n";
        $prompt .= '  "materi": "Rangkuman materi ajar terstruktur mendalam dalam format Markdown yang mencakup konsep penting, definisi, dan contoh nyata (minimal 400 kata).",' . "\n";
        $prompt .= '  "concept_map": "Kode Mermaid.js flowchart (menggunakan graph TD) untuk memetakan keterkaitan konsep-konsep kunci dalam materi ini. Contoh format: graph TD; A[\"Topik\"] --> B[\"Subtopik\"]. WAJIB membungkus semua teks di dalam label node dengan tanda kutip ganda (contoh: A[\"Topik (Sub)\"] atau B{\"Pilihan?\"}). Peta konsep HARUS dirancang agar terstruktur rapi secara vertikal (Top-Down) dan tidak melebar secara horizontal (hindari menyejajarkan terlalu banyak node anak secara datar, lebih baik kelompokkan secara hierarkis ke bawah agar ramping dan sangat terbaca saat dicetak di kertas A4). Pastikan kodenya valid tanpa karakter aneh atau tag HTML.",' . "\n";
        $prompt .= '  "lkpd": "Lembar Kerja Peserta Didik (LKPD) lengkap dalam format Markdown berisi instruksi tugas kelompok/mandiri, 3 pertanyaan diskusi analitis, dan rubrik penilaian kerja sederhana.",' . "\n";
        $prompt .= '  "classroom_activities": [' . "\n";
        $prompt .= '    {' . "\n";
        $prompt .= '      "name": "Nama Aktivitas Pembelajaran",' . "\n";
        $prompt .= '      "duration": "Alokasi Waktu (misal: 30 Menit)",' . "\n";
        $prompt .= '      "objective": "Tujuan utama aktivitas ini bagi siswa",' . "\n";
        $prompt .= '      "steps": ["Langkah 1", "Langkah 2", "Langkah 3"]' . "\n";
        $prompt .= '    }' . "\n";
        $prompt .= "  ],\n";
        $prompt .= '  "quiz": [' . "\n";
        $prompt .= '    {' . "\n";
        $prompt .= '      "question": "Pertanyaan kuis pilihan ganda yang menantang (HOTS jika kedalaman mendalam)",' . "\n";
        $prompt .= '      "options": ["Pilihan A", "Pilihan B", "Pilihan C", "Pilihan D"],' . "\n";
        $prompt .= '      "answer": 0,' . "\n"; // index 0-3
        $prompt .= '      "explanation": "Penjelasan mengapa pilihan tersebut benar."' . "\n";
        $prompt .= '    }' . "\n";
        $prompt .= "  ]\n";
        $prompt .= "}\n\n";
        $prompt .= "Pastikan menghasilkan 5 soal kuis pilihan ganda yang menantang. Seluruh output harus dalam bahasa Indonesia yang baik dan profesional. Jangan gunakan blok ```json di awal/akhir respons, langsung saja kurung kurawal. Jangan ada text lain.";

        try {
            $apiKey = config('services.gemini.api_key');
            if (!$apiKey) {
                return response()->json(['status' => 'error', 'message' => 'API Key Gemini belum dikonfigurasi. Silakan aktifkan Mode Demo untuk mencoba.']);
            }

            $response = Http::timeout(120)->withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 8192,
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API Error (Bahan Ajar Utama): ' . $response->body());
                return response()->json(['status' => 'error', 'message' => 'Gagal menghubungi API Gemini.']);
            }

            $geminiData = $response->json();
            $jsonString = $geminiData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            $data = $this->cleanAndParseJson($jsonString);

            if (!$data || !isset($data['materi'])) {
                Log::error('Gemini API JSON Error (Bahan Ajar Utama): ' . ($data ? 'Missing materi key' : 'Invalid JSON format'));
                Log::error('Raw response: ' . $jsonString);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membaca format JSON dari AI. Silakan coba lagi.'
                ], 500);
            }

            // Normalization
            $data['title'] = $data['title'] ?? ($topic ? ucwords($topic) : 'Bahan Ajar');
            $data['materi'] = $data['materi'] ?? '';
            $data['concept_map'] = $data['concept_map'] ?? '';
            $data['lkpd'] = $data['lkpd'] ?? '';

            if (isset($data['classroom_activities']) && is_array($data['classroom_activities'])) {
                foreach ($data['classroom_activities'] as &$activity) {
                    if (is_array($activity)) {
                        $activity['name'] = $activity['name'] ?? 'Aktivitas';
                        $activity['duration'] = $activity['duration'] ?? '15 Menit';
                        $activity['objective'] = $activity['objective'] ?? '';
                        $activity['steps'] = $activity['steps'] ?? [];
                    }
                }
            } else {
                $data['classroom_activities'] = [];
            }

            if (isset($data['quiz']) && is_array($data['quiz'])) {
                foreach ($data['quiz'] as &$item) {
                    if (is_array($item)) {
                        $item['question'] = $item['question'] ?? 'Pertanyaan';
                        $item['options'] = $item['options'] ?? ['Pilihan A', 'Pilihan B', 'Pilihan C', 'Pilihan D'];
                        $item['answer'] = isset($item['answer']) ? (int)$item['answer'] : 0;
                        $item['explanation'] = $item['explanation'] ?? '';
                    }
                }
            } else {
                $data['quiz'] = [];
            }

            $doc = new Document();
            $doc->user_id = Auth::id() ?? 1;
            $doc->name = "Bahan Ajar: " . $topic;
            $doc->type = 'Bahan Ajar Utama';
            $doc->content = json_encode($data);
            $doc->is_mock = false;
            $doc->save();

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'document_id' => $doc->id
            ]);

        } catch (\Exception $e) {
            Log::error('Gemini API Connection Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal terhubung ke AI: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getMockBahanAjarUtama($subject, $topic, $grade, $depth, $features)
    {
        $topicTitle = ucwords($topic);
        
        $materi = "# Rangkuman Materi: {$topicTitle}\n\n";
        $materi .= "Pelajaran **{$subject}** untuk kelas **{$grade}** dengan kedalaman tingkat **{$depth}**.\n\n";
        $materi .= "## 📌 Pengenalan Konsep Utama\n";
        $materi .= "Topik **{$topicTitle}** merupakan pilar penting dalam memahami bidang {$subject}. Pembelajaran ini dirancang untuk membimbing siswa mengeksplorasi hubungan antar unsur, cara kerja sistem, serta signifikansi praktisnya dalam kehidupan sehari-hari.\n\n";
        $materi .= "## 📖 Pembahasan Materi Terperinci\n";
        $materi .= "Berikut adalah poin-poin krusial yang perlu dipelajari secara mendalam:\n\n";
        $materi .= "1. **Definisi Dasar**: Pemahaman komprehensif mengenai batasan, istilah kunci, dan struktur awal dari {$topicTitle}.\n";
        $materi .= "2. **Prinsip Kerja & Mekanisme**: Bagaimana elemen-elemen di dalamnya saling berinteraksi, bertransformasi, atau berkoordinasi dalam sistem yang utuh.\n";
        $materi .= "3. **Hubungan Sebab Akibat**: Mengapa proses ini terjadi, faktor apa saja yang mempengaruhi laju atau keberhasilan proses tersebut.\n\n";
        $materi .= "> **Kotak Info Penting**: Memahami konsep ini membantu melatih kemampuan bernalar kritis dan analisis mendalam, yang menjadi pondasi keterampilan abad ke-21.\n\n";
        $materi .= "## 💡 Contoh Kasus / Studi Nyata\n";
        $materi .= "Dalam kehidupan sehari-hari, kita dapat mengamati fenomena ini pada:\n";
        $materi .= "* **Studi Kasus A**: Penerapan langsung di tingkat industri atau ekosistem alam.\n";
        $materi .= "* **Studi Kasus B**: Bagaimana variasi kondisi lingkungan dapat memicu perubahan atau penyimpangan dalam sistem ini.\n\n";
        $materi .= "## 📝 Ringkasan Poin Kunci\n";
        $materi .= "* **Poin 1**: Konsep fundamental terletak pada struktur awal dan akhir.\n";
        $materi .= "* **Poin 2**: Interaksi antar komponen bersifat dinamis dan saling mempengaruhi.\n";
        $materi .= "* **Poin 3**: Evaluasi rutin diperlukan untuk mendeteksi anomali pada sistem.";

        $conceptMap = "graph TD\n";
        $conceptMap .= "    A[\"{$topicTitle}\"] --> B[\"Konsep Dasar\"]\n";
        $conceptMap .= "    A --> C[\"Mekanisme\"]\n";
        $conceptMap .= "    A --> D[\"Aplikasi Nyata\"]\n";
        $conceptMap .= "    B --> B1[\"Definisi\"]\n";
        $conceptMap .= "    B --> B2[\"Istilah Kunci\"]\n";
        $conceptMap .= "    C --> C1[\"Tahap Awal\"]\n";
        $conceptMap .= "    C --> C2[\"Tahap Akhir\"]\n";
        $conceptMap .= "    D --> D1[\"Studi Kasus A\"]\n";
        $conceptMap .= "    D --> D2[\"Studi Kasus B\"]";

        $lkpd = "# Lembar Kerja Peserta Didik (LKPD): {$topicTitle}\n\n";
        $lkpd .= "**Mata Pelajaran:** {$subject} | **Kelas:** {$grade}\n";
        $lkpd .= "**Nama Siswa/Kelompok:** _____________________\n\n";
        $lkpd .= "## 🎯 Tujuan Aktivitas\n";
        $lkpd .= "Siswa mampu mengidentifikasi komponen penting dari {$topicTitle}, memetakan hubungan antar elemen, dan mendiskusikan dampaknya secara analitis.\n\n";
        $lkpd .= "## 🚶‍♂️ Langkah Kerja (Instruksi Aktivitas)\n";
        $lkpd .= "1. **Observasi**: Bacalah rangkuman materi utama mengenai {$topicTitle} dengan saksama.\n";
        $lkpd .= "2. **Kolaborasi**: Buat kelompok kecil terdiri dari 3-4 orang siswa.\n";
        $lkpd .= "3. **Pemetaan**: Diskusikan bagan konsep dan tuliskan poin penting masing-masing komponen.\n";
        $lkpd .= "4. **Pelaporan**: Tuliskan hasil analisis kelompok Anda pada lembar jawab yang disediakan.\n\n";
        $lkpd .= "## 💬 Pertanyaan Diskusi Kelas\n";
        $lkpd .= "1. Bagaimana peran masing-masing komponen utama dalam menjaga kestabilan proses {$topicTitle}?\n";
        $lkpd .= "2. Prediksikan apa yang akan terjadi apabila salah satu tahapan dalam proses ini mengalami gangguan atau hambatan!\n";
        $lkpd .= "3. Berikan contoh implementasi praktis lainnya dari konsep ini dalam kehidupan sehari-hari kelompok Anda!\n\n";
        $lkpd .= "## 📊 Rubrik Penilaian Sederhana\n";
        $lkpd .= "| Kriteria | Sangat Baik (3) | Cukup Baik (2) | Perlu Bimbingan (1) |\n";
        $lkpd .= "|---|---|---|---|\n";
        $lkpd .= "| Pemahaman Konsep | Menjelaskan seluruh komponen dengan akurat | Menjelaskan sebagian besar komponen | Penjelasan kurang tepat |\n";
        $lkpd .= "| Keterampilan Analisis | Argumen logis dan disertai contoh | Argumen cukup logis | Argumen tidak relevan |\n";
        $lkpd .= "| Kerja Sama Kelompok | Seluruh anggota aktif berkontribusi | Sebagian anggota aktif | Hanya didominasi satu siswa |";

        $activities = [
            [
                "name" => "Analisis Kritis Kelompok",
                "duration" => "30 Menit",
                "objective" => "Siswa dapat menganalisis kasus nyata secara bergotong royong.",
                "steps" => [
                    "Bagi kelas menjadi kelompok beranggotakan 4 orang.",
                    "Diskusikan contoh kasus nyata yang terdapat dalam materi.",
                    "Presentasikan temuan kelompok di depan kelas selama 3 menit."
                ]
            ],
            [
                "name" => "Membuat Peta Pikiran Mandiri",
                "duration" => "20 Menit",
                "objective" => "Siswa memvisualisasikan pemahaman konsep secara individu.",
                "steps" => [
                    "Sediakan selembar kertas gambar dan spidol warna-warni.",
                    "Buat ringkasan diagram alir berdasarkan penjelasan guru.",
                    "Tempelkan hasil karya pada papan pajangan kelas."
                ]
            ]
        ];

        $quiz = [
            [
                "question" => "Manakah di bawah ini yang merupakan definisi paling tepat dari konsep utama {$topicTitle}?",
                "options" => [
                    "Sistem statis yang tidak mengalami perubahan sama sekali.",
                    "Proses dinamis yang melibatkan koordinasi antar elemen terstruktur.",
                    "Kumpulan fakta acak tanpa hubungan sebab-akibat.",
                    "Metode pengelompokan komponen secara manual."
                ],
                "answer" => 1,
                "explanation" => "Proses ini bersifat dinamis karena komponen di dalamnya saling berinteraksi secara aktif dan terstruktur."
            ],
            [
                "question" => "Apa dampak utama jika salah satu komponen pendukung dalam sistem ini mengalami kegagalan?",
                "options" => [
                    "Sistem akan berjalan lebih cepat tanpa hambatan.",
                    "Seluruh sistem akan terganggu atau mengalami disfungsi.",
                    "Komponen lain otomatis menggantikan fungsi yang rusak secara sempurna.",
                    "Tidak ada efek apa pun terhadap kinerja sistem."
                ],
                "answer" => 1,
                "explanation" => "Karena komponen dalam sistem saling terikat erat, kegagalan pada satu bagian akan mengganggu keseimbangan keseluruhan proses."
            ],
            [
                "question" => "Mengapa studi kasus nyata sangat penting dipelajari dalam topik {$topicTitle} ini?",
                "options" => [
                    "Untuk membuktikan teori di atas kertas dengan kehidupan praktis.",
                    "Hanya untuk memenuhi jam pelajaran kosong.",
                    "Agar siswa dapat menghafal nama-nama tokoh penemu.",
                    "Untuk menggantikan fungsi ujian tertulis."
                ],
                "answer" => 0,
                "explanation" => "Studi kasus menjembatani teori abstrak dengan fenomena nyata, melatih kemampuan aplikatif siswa."
            ]
        ];

        if (str_contains(strtolower($topic), 'kupu') || str_contains(strtolower($topic), 'metamorfosis')) {
            $materi = "# Rangkuman Materi: Metamorfosis Kupu-Kupu (Biologi/IPA)\n\nMetamorfosis adalah proses biologis yang menakjubkan di mana seekor hewan mengalami perubahan bentuk fisik yang drastis setelah menetas atau lahir.\n\n## 🐛 4 Tahapan Metamorfosis Sempurna\n\n1. **Telur (Egg)**\n   Induk kupu-kupu meletakkan telurnya pada permukaan daun. Telur ini sangat kecil dan biasanya dilindungi oleh lapisan lem perekat khusus.\n\n2. **Ulat / Larva**\n   Setelah menetas, ulat akan keluar dan mulai memakan dedaunan secara aktif. Fase ini berfokus pada pertumbuhan ukuran tubuh ulat secara drastis.\n\n3. **Kepompong (Pupa)**\n   Ulat membungkus dirinya dalam cangkang pelindung (krisalis). Di dalam kepompong, terjadi rekonstruksi seluler secara besar-besaran untuk membentuk struktur tubuh dewasa.\n\n4. **Kupu-Kupu Dewasa (Imago)**\n   Kepompong robek dan keluarlah kupu-kupu dengan sayap indah yang siap terbang, mengisap nektar, dan melakukan reproduksi untuk memulai daur hidup kembali.\n\n## 🌸 Peran Ekologis Kupu-Kupu\nKupu-kupu dewasa berperan penting dalam membantu penyerbukan tanaman berbunga, menjaga keanekaragaman flora di alam.";

            $conceptMap = "graph TD\n    A[Metamorfosis Kupu-Kupu] --> B(1. Telur)\n    B --> C(2. Larva / Ulat)\n    C --> D(3. Pupa / Kepompong)\n    D --> E(4. Imago / Dewasa)\n    E -->|Melahirkan Telur| B";

            $lkpd = "# Lembar Kerja Peserta Didik (LKPD): Siklus Hidup Kupu-Kupu\n\n**Mata Pelajaran:** Biologi / IPA | **Kelas:** SMP / SD\n\n## 🎯 Tujuan Pembelajaran\nSiswa mampu menganalisis 4 tahapan daur hidup kupu-kupu secara urut dan menjelaskan fungsi adaptasi di setiap tahap.\n\n## 🚶‍♂️ Langkah Aktivitas Siswa\n1. Amati diagram peta konsep metamorfosis kupu-kupu yang disajikan.\n2. Lakukan diskusi kelompok (3-4 orang) untuk menganalisis mengapa ulat mengalami pertumbuhan yang sangat cepat dibanding fase lainnya.\n3. Jawablah pertanyaan analisis di bawah ini.\n\n## 💬 Pertanyaan Diskusi\n1. Apa perbedaan mendasar antara ulat (larva) dan kepompong (pupa) dalam hal konsumsi energi?\n2. Prediksikan apa yang akan terjadi pada populasi tanaman berbunga di kebun jika populasi kupu-kupu punah akibat penggunaan pestisida secara berlebihan!\n3. Sebutkan contoh hewan lain yang mengalami metamorfosis sempurna serupa dengan kupu-kupu!\n\n## 📊 Rubrik Penilaian Kerja Kelompok\n| Aspek Penilaian | Sangat Baik (3) | Cukup Baik (2) | Perlu Perbaikan (1) |\n|---|---|---|---|\n| Ketepatan Urutan | Mampu mengurutkan daur hidup dengan sempurna | Urutan sebagian besar tepat | Banyak kesalahan urutan |\n| Analisis Ekologis | Argumen logis dan menjelaskan peran polinator | Argumen cukup logis | Argumen tidak berdasar |";

            $activities = [
                [
                    "name" => "Membuat Diorama Siklus Hidup",
                    "duration" => "40 Menit",
                    "objective" => "Siswa dapat menyusun representasi visual 3 dimensi dari 4 tahapan metamorfosis menggunakan kertas dan plastisin.",
                    "steps" => [
                        "Bagi kelas menjadi kelompok beranggotakan 4 siswa.",
                        "Gunakan piring kertas yang dibagi menjadi 4 kuadran (fase telur, ulat, kepompong, kupu-kupu).",
                        "Buat bentuk replika masing-masing fase menggunakan plastisin warna-warni dan tempelkan di kuadran yang tepat.",
                        "Setiap kelompok maju mempresentasikan hasil karya di depan kelas."
                    ]
                ]
            ];

            $quiz = [
                [
                    "question" => "Tahapan daur hidup kupu-kupu yang fokus utamanya adalah menimbun energi dengan makan secara aktif disebut...",
                    "options" => ["Telur", "Larva (Ulat)", "Pupa (Kepompong)", "Imago (Kupu-kupu dewasa)"],
                    "answer" => 1,
                    "explanation" => "Larva (Ulat) memiliki tugas utama memakan dedaunan sebanyak mungkin untuk mempersiapkan energi yang cukup saat memasuki fase diam di kepompong."
                ],
                [
                    "question" => "Fase diam di mana ulat membungkus diri untuk melakukan rekonstruksi organ secara internal disebut...",
                    "options" => ["Telur", "Larva", "Pupa / Kepompong", "Imago"],
                    "answer" => 2,
                    "explanation" => "Pupa (Kepompong) adalah fase transformasi di mana tubuh ulat hancur secara seluler dan dibangun kembali menjadi struktur tubuh kupu-kupu dewasa."
                ],
                [
                    "question" => "Manakah peran ekologis terpenting dari kupu-kupu dewasa (imago) bagi tumbuh-tumbuhan?",
                    "options" => [
                        "Memakan ulat bulu yang merusak tanaman.",
                        "Membantu penyerbukan bunga saat mengisap nektar.",
                        "Melindungi daun dari serangan jamur parasit.",
                        "Menyuburkan tanah melalui kotorannya."
                    ],
                    "answer" => 1,
                    "explanation" => "Kupu-kupu dewasa mengisap nektar dari bunga. Dalam proses ini, serbuk sari menempel pada tubuhnya dan terbawa ke bunga lain, membantu penyerbukan."
                ]
            ];
        }

        return [
            "title" => $topicTitle,
            "materi" => $materi,
            "concept_map" => $conceptMap,
            "lkpd" => $lkpd,
            "classroom_activities" => $activities,
            "quiz" => $quiz
        ];
    }

    public function generateLKPD(Request $request)
    {
        $limitCheck = $this->checkSubscriptionLimits('LKPD Generator');
        if ($limitCheck) return $limitCheck;

        set_time_limit(120);
        try {
            $request->validate([
                'subject' => 'required|string',
                'topic' => 'required|string',
                'grade' => 'required|string',
                'school' => 'required|string',
                'time_allocation' => 'required|string',
                'num_questions' => 'required|numeric'
            ]);

            $useMock = $request->boolean('use_mock');
            $subject = $request->input('subject');
            $topic = $request->input('topic');
            $grade = $request->input('grade');
            $school = $request->input('school');
            $timeAllocation = $request->input('time_allocation');
            $numQuestions = (int)$request->input('num_questions', 3);

            if ($useMock || empty(env('GEMINI_API_KEY'))) {
                $lkpdData = $this->getMockLKPD($subject, $topic, $grade, $school, $timeAllocation, $numQuestions);
            } else {
                $prompt = "Anda adalah AI Asisten Guru yang ahli dalam merancang Lembar Kerja Peserta Didik (LKPD) terstruktur dan interaktif sesuai Kurikulum Merdeka di Indonesia.\n";
                $prompt .= "Buatlah dokumen LKPD yang komprehensif untuk tugas/aktivitas praktikum atau analisis kelas berdasarkan parameter berikut:\n";
                $prompt .= "- Mata Pelajaran: " . $subject . "\n";
                $prompt .= "- Topik/Materi: " . $topic . "\n";
                $prompt .= "- Kelas/Semester: " . $grade . "\n";
                $prompt .= "- Satuan Pendidikan: " . $school . "\n";
                $prompt .= "- Alokasi Waktu: " . $timeAllocation . "\n";
                $prompt .= "- Jumlah Soal Pertanyaan: " . $numQuestions . "\n\n";

                $prompt .= "Aturan Output (WAJIB dipatuhi):\n";
                $prompt .= "1. Output HARUS murni JSON yang valid tanpa Markdown block (tanpa ```json ... ```).\n";
                $prompt .= "2. JSON harus memiliki struktur persis seperti ini:\n";
                $prompt .= "{\n";
                $prompt .= '  "judul": "Judul Aktivitas LKPD yang Menarik dan Kontekstual",\n';
                $prompt .= '  "satuan_pendidikan": "' . $school . '",\n';
                $prompt .= '  "kelas_semester": "' . $grade . '",\n';
                $prompt .= '  "materi_ajar": "' . $topic . '",\n';
                $prompt .= '  "alokasi_waktu": "' . $timeAllocation . '",\n';
                $prompt .= '  "indikator": [\n';
                $prompt .= '    "Tuliskan indikator pencapaian kompetensi 1 yang spesifik untuk materi ini",\n';
                $prompt .= '    "Tuliskan indikator pencapaian kompetensi 2"\n';
                $prompt .= '  ],\n';
                $prompt .= '  "tujuan": [\n';
                $prompt .= '    "Tuliskan tujuan pembelajaran spesifik bagi siswa saat menyelesaikan LKPD ini",\n';
                $prompt .= '    "Tujuan pembelajaran 2"\n';
                $prompt .= '  ],\n';
                $prompt .= '  "petunjuk_belajar": "Berikan panduan belajar dan aturan kerja bagi siswa. Tulis dalam bahasa yang bersahabat dan memotivasi. Boleh gunakan format Markdown untuk list atau penekanan.",\n';
                $prompt .= '  "informasi_pendukung": "Informasi pendukung, dasar teori singkat, atau rangkuman konsep penting yang relevan dengan tugas. Tulis secara informatif agar siswa mendapat bekal teori yang cukup sebelum bekerja. Boleh gunakan format Markdown.",\n';
                $prompt .= '  "langkah_kerja": [\n';
                $prompt .= '    "Langkah 1: Tulis instruksi langkah kerja praktikum/analisis yang detail",\n';
                $prompt .= '    "Langkah 2: ..."\n';
                $prompt .= '  ],\n';
                $prompt .= '  "soal_soal": [\n';
                $prompt .= '    {\n';
                $prompt .= '      "pertanyaan": "Pertanyaan reflektif atau evaluatif berbasis tugas/langkah kerja yang telah dilakukan",\n';
                $prompt .= '      "kunci_jawaban": "Panduan jawaban yang benar atau kriteria penilaian untuk guru"\n';
                $prompt .= '    }\n';
                $prompt .= '  ]\n';
                $prompt .= "}\n";
                $prompt .= "3. Berikan minimal 3-5 indikator pencapaian kompetensi dan tujuan pembelajaran.\n";
                $prompt .= "4. Tulis langkah-langkah kerja secara kronologis, konkret, dan mudah dipahami oleh siswa kelas/jenjang tersebut.\n";
                $prompt .= "5. Buatlah sebanyak " . $numQuestions . " soal/pertanyaan yang mendalam di bagian 'soal_soal' sesuai jumlah yang diminta.\n";
                $prompt .= "6. JANGAN menyertakan prefix nomor atau label seperti 'Langkah X:' atau 'Pertanyaan X:' di awal teks nilai string di dalam array 'langkah_kerja' dan 'soal_soal' karena sistem kami akan memformat penomorannya secara otomatis.\n";

                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . env('GEMINI_API_KEY'), [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 8192,
                        'responseMimeType' => 'application/json'
                    ]
                ]);

                if (!$response->successful()) {
                    \Illuminate\Support\Facades\Log::error('Gemini API Error (LKPD): ' . $response->body());
                    return response()->json(['status' => 'error', 'message' => 'Gagal menghubungi API Gemini.']);
                }

                $geminiData = $response->json();
                $jsonResponse = $geminiData['candidates'][0]['content']['parts'][0]['text'] ?? '';
                $lkpdData = $this->cleanAndParseJson($jsonResponse);
            }

            if (!$lkpdData || !isset($lkpdData['judul'])) {
                \Illuminate\Support\Facades\Log::error('JSON Decode Error (LKPD): ' . ($lkpdData ? 'Missing judul key' : 'Invalid JSON format'));
                return response()->json(['status' => 'error', 'message' => 'Format output AI tidak valid.']);
            }

            // Normalization & Fallbacks
            $lkpdData['judul'] = $lkpdData['judul'] ?? 'Lembar Kerja Peserta Didik (LKPD)';
            $lkpdData['satuan_pendidikan'] = $lkpdData['satuan_pendidikan'] ?? $school;
            $lkpdData['kelas_semester'] = $lkpdData['kelas_semester'] ?? $grade;
            $lkpdData['materi_ajar'] = $lkpdData['materi_ajar'] ?? $topic;
            $lkpdData['alokasi_waktu'] = $lkpdData['alokasi_waktu'] ?? $timeAllocation;
            $lkpdData['indikator'] = $lkpdData['indikator'] ?? [];
            $lkpdData['tujuan'] = $lkpdData['tujuan'] ?? [];
            $lkpdData['petunjuk_belajar'] = $lkpdData['petunjuk_belajar'] ?? '';
            $lkpdData['informasi_pendukung'] = $lkpdData['informasi_pendukung'] ?? '';
            $lkpdData['langkah_kerja'] = $lkpdData['langkah_kerja'] ?? [];
            $lkpdData['soal_soal'] = $lkpdData['soal_soal'] ?? [];

            // Save to database
            $document = Document::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'name' => 'LKPD: ' . $topic,
                'type' => 'lkpd',
                'status' => 'completed',
                'is_mock' => $useMock,
                'content' => json_encode($lkpdData)
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $lkpdData,
                'document_id' => $document->id
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Exception in generateLKPD: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    private function getMockLKPD($subject, $topic, $grade, $school, $timeAllocation, $numQuestions)
    {
        $judul = "Eksperimen Interaktif: Menyelidiki Fotosintesis";
        if (stripos($topic, 'sejarah') !== false || stripos($subject, 'sejarah') !== false) {
            $judul = "Analisis Dokumen Sejarah: Peristiwa Proklamasi Kemerdekaan RI";
        } elseif (stripos($topic, 'matematika') !== false || stripos($subject, 'matematika') !== false) {
            $judul = "Eksplorasi Geometri: Menghitung Luas Permukaan Bangun Ruang";
        }

        $indikator = [
            "Menjelaskan pengertian dan konsep dasar mengenai {$topic}.",
            "Mengidentifikasi bagian-bagian penting dan faktor yang memengaruhi proses {$topic}.",
            "Melakukan penyelidikan kelompok secara ilmiah untuk menganalisis keterkaitan konsep.",
            "Menyusun laporan hasil analisis/kerja kelompok secara sistematis."
        ];

        $tujuan = [
            "Melalui penyelidikan ini, siswa dapat mendeskripsikan mekanisme {$topic} dengan tepat.",
            "Siswa dapat merancang dan melaksanakan eksperimen sederhana terkait {$topic}.",
            "Siswa dapat merumuskan kesimpulan logis berdasarkan data hasil pengamatan.",
            "Siswa dapat mempresentasikan hasil analisis di depan kelas dengan percaya diri."
        ];

        $petunjuk_belajar = "1. Bacalah **Informasi Pendukung** di bawah ini dengan saksama sebelum memulai kegiatan.\n2. Lakukan semua langkah kegiatan secara berurutan bersama kelompokmu.\n3. Diskusikan dan jawab setiap **Soal Pertanyaan** di akhir lembaran ini.\n4. Tanyakan kepada guru jika ada hal yang kurang dipahami.";

        $informasi_pendukung = "### Landasan Teori Singkat\n\nDalam pembelajaran **{$subject}** mengenai **{$topic}**, terdapat beberapa pilar penting yang harus dipahami terlebih dahulu. Pembelajaran ini mengajak kita untuk mengeksplorasi fenomena di sekitar kita secara ilmiah.\n\n* **Konsep Utama**: Konsep ini menjelaskan bagaimana elemen-elemen saling berhubungan secara dinamis.\n* **Faktor yang Memengaruhi**: Suhu, kelembapan, bahan masukan, serta interaksi lingkungan sangat menentukan hasil akhir.\n* **Penerapan**: Pemahaman tentang konsep ini sangat berguna dalam memecahkan masalah praktis kehidupan sehari-hari.";

        $langkah_kerja = [
            "Siapkan semua bahan dan alat yang diperlukan sesuai kelompok masing-masing.",
            "Amati objek penyelidikan dengan saksama selama kurang lebih 10 menit.",
            "Lakukan simulasi/percobaan sesuai dengan instruksi kerja guru di laboratorium atau kelas.",
            "Catat hasil pengamatan kalian ke dalam tabel pengamatan sementara.",
            "Diskusikan hasil catatan tersebut dengan anggota kelompok untuk mencari benang merah."
        ];

        $soal_soal = [];
        for ($i = 1; $i <= $numQuestions; $i++) {
            $soal_soal[] = [
                "pertanyaan" => "Berdasarkan hasil pengamatanmu pada aktivitas di atas, analisislah apa yang akan terjadi jika salah satu variabel penting dalam {$topic} dihilangkan? Jelaskan alasannya secara ilmiah!",
                "kunci_jawaban" => "Panduan jawaban untuk guru: Jawaban harus mencakup analisis variabel, dampak hilangnya variabel tersebut terhadap proses {$topic}, dan penalaran logis ilmiah."
            ];
        }

        return [
            "judul" => $judul,
            "satuan_pendidikan" => $school,
            "kelas_semester" => $grade,
            "materi_ajar" => $topic,
            "alokasi_waktu" => $timeAllocation,
            "indikator" => $indikator,
            "tujuan" => $tujuan,
            "petunjuk_belajar" => $petunjuk_belajar,
            "informasi_pendukung" => $informasi_pendukung,
            "langkah_kerja" => $langkah_kerja,
            "soal_soal" => $soal_soal
        ];
    }

    public function savePromptHistory(Request $request)
    {
        try {
            $request->validate([
                'prompt_type' => 'required|string',
                'generated_prompt' => 'required|string',
                'subject' => 'nullable|string',
                'topic' => 'nullable|string',
                'grade' => 'nullable|string',
                'learning_model' => 'nullable|string',
                'student_profile' => 'nullable|string',
                'additional_notes' => 'nullable|string',
                'cp' => 'nullable|string',
                'tp' => 'nullable|string',
                'p5' => 'nullable|array',
                'supporting_components' => 'nullable|array',
                'theme' => 'nullable|string',
                'time_allocation' => 'nullable|string',
                'teacher_name' => 'nullable|string',
                'teacher_nip' => 'nullable|string',
                'school_name' => 'nullable|string',
                'principal_name' => 'nullable|string',
                'principal_nip' => 'nullable|string',
            ]);

            $promptData = $request->only([
                'prompt_type', 'generated_prompt', 'subject', 'topic', 'grade',
                'learning_model', 'student_profile', 'additional_notes', 'cp', 'tp',
                'p5', 'supporting_components', 'theme', 'time_allocation',
                'teacher_name', 'teacher_nip', 'school_name', 'principal_name', 'principal_nip'
            ]);

            // Update user profile fields in database
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user) {
                $user->update([
                    'name' => $request->input('teacher_name') ?: $user->name,
                    'nip' => $request->input('teacher_nip') ?: $user->nip,
                    'school_name' => $request->input('school_name') ?: $user->school_name,
                    'principal_name' => $request->input('principal_name') ?: $user->principal_name,
                    'principal_nip' => $request->input('principal_nip') ?: $user->principal_nip,
                ]);
            }

            // Save to database
            $document = Document::create([
                'user_id' => $user ? $user->id : \Illuminate\Support\Facades\Auth::id(),
                'name' => 'Prompt Guru: ' . $request->input('prompt_type') . ' - ' . ($request->input('topic') ?: 'Materi'),
                'type' => 'prompt',
                'status' => 'completed',
                'is_mock' => true,
                'content' => json_encode($promptData)
            ]);

            return response()->json([
                'status' => 'success',
                'document_id' => $document->id
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Exception in savePromptHistory: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function recommendCPTP(Request $request)
    {
        try {
            $request->validate([
                'subject' => 'nullable|string',
                'topic' => 'required|string',
                'grade' => 'required|string',
                'use_mock' => 'nullable|boolean'
            ]);

            $useMock = $request->boolean('use_mock') || empty(env('GEMINI_API_KEY'));
            $subject = $request->input('subject') ?: 'Bimbingan Konseling / Umum';
            $topic = $request->input('topic');
            $grade = $request->input('grade');

            if ($useMock) {
                $cp = "Peserta didik dapat memahami konsep utama dari materi " . $topic . " serta mengaitkannya dengan aplikasi praktis dalam kehidupan sehari-hari sesuai dengan jenjang " . $grade . ".";
                $tp = "1. Menjelaskan prinsip dasar dan komponen utama dari " . $topic . ".\n2. Menganalisis peran dan fungsi " . $topic . " dalam konteks nyata.\n3. Memecahkan studi kasus atau melakukan aktivitas mandiri berkaitan dengan " . $topic . ".";
                
                return response()->json([
                    'status' => 'success',
                    'cp' => $cp,
                    'tp' => $tp,
                    'is_mock' => true
                ]);
            }

            $prompt = "Kamu adalah AI ahli kurikulum sekolah Indonesia (Kurikulum Merdeka).\n";
            $prompt .= "Buatlah usulan Capaian Pembelajaran (CP) dan Tujuan Pembelajaran (TP) yang relevan untuk:\n";
            $prompt .= "- Mata Pelajaran: " . $subject . "\n";
            $prompt .= "- Topik/Materi: " . $topic . "\n";
            $prompt .= "- Kelas/Jenjang: " . $grade . "\n\n";
            $prompt .= "Aturan Output (WAJIB dipatuhi):\n";
            $prompt .= "1. Output HARUS berupa JSON yang valid.\n";
            $prompt .= "2. Struktur JSON harus persis seperti ini:\n";
            $prompt .= "{\n";
            $prompt .= '  "cp": "Teks deskripsi Capaian Pembelajaran (CP) yang sesuai dengan materi dan kelas tersebut.",\n';
            $prompt .= '  "tp": "1. Tujuan pembelajaran pertama yang terukur.\\n2. Tujuan pembelajaran kedua yang terukur.\\n3. Tujuan pembelajaran ketiga."\n';
            $prompt .= "}\n";
            $prompt .= "3. JANGAN menyertakan markup markdown penutup seperti ```json ... ``` atau teks penjelasan tambahan di luar JSON.\n";

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . env('GEMINI_API_KEY'), [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if (!$response->successful()) {
                \Illuminate\Support\Facades\Log::error('Gemini API Error (CPTP): ' . $response->body());
                return response()->json(['status' => 'error', 'message' => 'Gagal menghubungi API Gemini.']);
            }

            $geminiData = $response->json();
            $jsonResponse = $geminiData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $parsed = $this->cleanAndParseJson($jsonResponse);

            if (!$parsed || !isset($parsed['cp'])) {
                return response()->json(['status' => 'error', 'message' => 'Format output AI tidak valid.']);
            }

            return response()->json([
                'status' => 'success',
                'cp' => $parsed['cp'],
                'tp' => $parsed['tp'],
                'is_mock' => false
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Exception in recommendCPTP: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
