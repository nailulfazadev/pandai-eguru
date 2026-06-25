<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authenticated user can access and update profile settings.
     */
    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => 'Guru Baru',
            'email' => 'newguru@pandai.com',
            'nip' => '123456',
            'school_name' => 'SD Negeri Baru',
            'principal_name' => 'Kepala Baru',
            'principal_nip' => '654321',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('profile.edit'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Guru Baru',
            'nip' => '123456',
            'school_name' => 'SD Negeri Baru',
        ]);
    }

    /**
     * Test authenticated user can view history.
     */
    public function test_user_can_view_document_history(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('documents.index'));

        $response->assertStatus(200);
        $response->assertViewIs('riwayat');
    }

    /**
     * Test authenticated user can view tools directory.
     */
    public function test_user_can_view_tools_directory(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('tools.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tools.index');
        $response->assertSee('Peralatan Mengajar AI');
    }

    /**
     * Test generating Modul Ajar works (fallback to mock response).
     */
    public function test_generating_modul_ajar_works_with_mock(): void
    {
        $user = User::factory()->create();

        // Ensure the API key config is null to test the mock path reliably
        config(['services.gemini.api_key' => null]);

        $response = $this->actingAs($user)->postJson(route('tools.generate-modul'), [
            'teacher_name' => 'Guru Hebat',
            'teacher_nip' => '123456',
            'school_name' => 'SD 1 Merdeka',
            'principal_name' => 'Kepala Hebat',
            'principal_nip' => '654321',
            'grade' => 'Fase C (Kelas 5-6)',
            'subject' => 'IPA',
            'topic' => 'Siklus Air',
            'method' => 'PBL',
            'duration' => '2 x 35 Menit',
            'p5' => ['Mandiri', 'Bernalar Kritis'],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'content',
            'is_mock',
        ]);
        $this->assertTrue($response->json('is_mock'));
        $this->assertDatabaseHas('documents', [
            'id' => $response->json('id'),
            'type' => 'Modul Ajar',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test downloading a document works.
     */
    public function test_downloading_document_streams_word_file(): void
    {
        $user = User::factory()->create();
        $document = $user->documents()->create([
            'name' => 'Test Modul',
            'type' => 'Modul Ajar',
            'status' => 'Selesai',
            'content' => '## MODUL AJAR',
        ]);

        $response = $this->actingAs($user)->get(route('documents.download', $document->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/octet-stream');
        $this->assertStringContainsString('MODUL AJAR', $response->getContent());
    }

    /**
     * Test printing a document renders print view.
     */
    public function test_printing_document_renders_print_view(): void
    {
        $user = User::factory()->create();
        $document = $user->documents()->create([
            'name' => 'Test Modul',
            'type' => 'Modul Ajar',
            'status' => 'Selesai',
            'content' => '## MODUL AJAR',
        ]);
        $response = $this->actingAs($user)->get(route('documents.print', $document->id));

        $response->assertStatus(200);
        $response->assertViewIs('print-doc');
        $response->assertSee('Cetak ke PDF');
        $response->assertSee('MODUL AJAR');
    }

    /**
     * Test generation limit of 10 documents per day (mock does not count, only real counts).
     */
    public function test_generation_limit_per_day(): void
    {
        $user = User::factory()->create();
        config(['services.gemini.api_key' => null]);

        // Create 5 mock documents (is_mock = true)
        for ($i = 0; $i < 5; $i++) {
            $user->documents()->create([
                'name' => 'Modul Ajar ' . $i,
                'type' => 'Modul Ajar',
                'status' => 'Selesai',
                'content' => 'Content ' . $i,
                'is_mock' => true,
            ]);
        }

        // Create 10 real documents today (is_mock = false)
        for ($i = 5; $i < 15; $i++) {
            $user->documents()->create([
                'name' => 'Modul Ajar ' . $i,
                'type' => 'Modul Ajar',
                'status' => 'Selesai',
                'content' => 'Content ' . $i,
                'is_mock' => false,
            ]);
        }

        // Try to generate a mock document (should succeed even if we have 10 real ones today)
        $responseMock = $this->actingAs($user)->postJson(route('tools.generate-modul'), [
            'teacher_name' => 'Guru Hebat',
            'school_name' => 'SD 1 Merdeka',
            'principal_name' => 'Kepala Hebat',
            'grade' => 'Fase C (Kelas 5-6)',
            'subject' => 'IPA',
            'topic' => 'Siklus Air',
            'method' => 'PBL',
            'duration' => '2 x 35 Menit',
            'use_mock' => '1',
        ]);

        $responseMock->assertStatus(200);
        $this->assertTrue($responseMock->json('is_mock'));

        // Try to generate the 11th real document (should be blocked because we have 10 real ones today)
        config(['services.gemini.api_key' => 'fake-key']);

        $response = $this->actingAs($user)->postJson(route('tools.generate-modul'), [
            'teacher_name' => 'Guru Hebat',
            'school_name' => 'SD 1 Merdeka',
            'principal_name' => 'Kepala Hebat',
            'grade' => 'Fase C (Kelas 5-6)',
            'subject' => 'IPA',
            'topic' => 'Siklus Air',
            'method' => 'PBL',
            'duration' => '2 x 35 Menit',
        ]);

        $response->assertStatus(429);
        $response->assertJson([
            'error' => 'Anda telah mencapai batas maksimum pembuatan dokumen (10 kali per hari).'
        ]);
    }

    /**
     * Test generating Soal works (fallback to mock response).
     */
    public function test_generating_soal_works_with_mock(): void
    {
        $user = User::factory()->create();

        // Ensure the API key config is null to test the mock path reliably
        config(['services.gemini.api_key' => null]);

        $response = $this->actingAs($user)->postJson(route('tools.generate-soal-submit'), [
            'subject' => 'IPAS',
            'topic' => 'Fotosintesis',
            'grade' => 'Kelas 5 SD',
            'difficulty' => 'HOTS (Berpikir Tingkat Tinggi)',
            'question_type' => 'Campuran (Pilihan Ganda & Essay)',
            'quantity' => 10,
            'indicator' => 'Indikator A dan B',
            'kop_dinas' => 'PEMERINTAH KABUPATEN INDRAMAYU<br>DINAS PENDIDIKAN',
            'kop_sekolah' => 'SD NEGERI 1 MERDEKA',
            'kop_alamat' => 'Jl. Ki Hajar Dewantara No. 17, Indramayu',
            'kop_ujian' => 'PENILAIAN HARIAN BERSAMA',
            'kop_ta' => '2026/2027',
            'kop_waktu' => '90 Menit',
            'kop_logo' => 'https://example.com/logo.png',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'content',
            'is_mock',
        ]);
        $this->assertTrue($response->json('is_mock'));
        $this->assertDatabaseHas('documents', [
            'id' => $response->json('id'),
            'type' => 'Soal',
            'user_id' => $user->id,
            'is_mock' => true,
        ]);
    }

    /**
     * Test generating Soal with Yayasan template (2 logos).
     */
    public function test_generating_soal_with_yayasan_template(): void
    {
        $user = User::factory()->create();

        // Ensure the API key config is null to test the mock path reliably
        config(['services.gemini.api_key' => null]);

        $response = $this->actingAs($user)->postJson(route('tools.generate-soal-submit'), [
            'subject' => 'Matematika',
            'topic' => 'Aljabar',
            'grade' => 'Kelas 7 SMP',
            'difficulty' => 'Campuran (LOTS, MOTS, HOTS)',
            'question_type' => 'Pilihan Ganda Biasa',
            'quantity' => 10,
            'indicator' => 'Menyelesaikan persamaan linear satu variabel',
            'kop_dinas' => 'YAYASAN PENDIDIKAN NUSANTARA',
            'kop_sekolah' => 'SMP NUSANTARA',
            'kop_alamat' => 'Jl. Nusantara Raya No. 100',
            'kop_ujian' => 'UJIAN TENGAH SEMESTER (UTS)',
            'kop_ta' => '2026/2027',
            'kop_waktu' => '120 Menit',
            'kop_logo' => 'https://example.com/logo-kiri.png',
            'kop_template' => 'yayasan',
            'kop_logo_kanan' => 'https://example.com/logo-kanan.png',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'content',
            'is_mock',
        ]);
        $this->assertTrue($response->json('is_mock'));
        $this->assertDatabaseHas('documents', [
            'id' => $response->json('id'),
            'type' => 'Soal',
            'user_id' => $user->id,
            'is_mock' => true,
        ]);
    }

    /**
     * Test generating Bahan Ajar Utama works (fallback to mock response).
     */
    public function test_generating_bahan_ajar_utama_works_with_mock(): void
    {
        $user = User::factory()->create();

        // Ensure the API key config is null to test the mock path reliably
        config(['services.gemini.api_key' => null]);

        $response = $this->actingAs($user)->postJson(route('tools.generate-bahan-ajar-utama-submit'), [
            'subject' => 'Biologi',
            'topic' => 'Metamorfosis Kupu-Kupu',
            'grade' => 'SMP Kelas 7',
            'depth' => 'menengah',
            'use_mock' => '1',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'title',
                'materi',
                'concept_map',
                'lkpd',
                'quiz',
            ],
            'document_id',
        ]);
        $this->assertEquals('success', $response->json('status'));
        $this->assertDatabaseHas('documents', [
            'id' => $response->json('document_id'),
            'type' => 'Bahan Ajar Utama',
            'user_id' => $user->id,
            'is_mock' => true,
        ]);
    }

    /**
     * Test generating LKPD works (fallback to mock response).
     */
    public function test_generating_lkpd_works_with_mock(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('tools.generate-lkpd-submit'), [
            'subject' => 'IPAS',
            'topic' => 'Fotosintesis',
            'grade' => 'Kelas IV',
            'school' => 'SD Negeri 1',
            'time_allocation' => '2 JP',
            'num_questions' => 3,
            'use_mock' => '1',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'judul',
                'satuan_pendidikan',
                'kelas_semester',
                'materi_ajar',
                'alokasi_waktu',
                'indikator',
                'tujuan',
                'petunjuk_belajar',
                'informasi_pendukung',
                'langkah_kerja',
                'soal_soal',
            ],
            'document_id',
        ]);
        $this->assertEquals('success', $response->json('status'));
        $this->assertDatabaseHas('documents', [
            'id' => $response->json('document_id'),
            'type' => 'lkpd',
            'user_id' => $user->id,
            'is_mock' => true,
        ]);
    }
}

