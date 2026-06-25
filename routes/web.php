<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\MayarWebhookController;

// Webhook
Route::post('/api/mayar-webhook', [MayarWebhookController::class, 'handleWebhook'])->name('mayar.webhook');

// Public Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // Count documents
        $totalDocuments = $user->documents()->count();
        
        // Count Modul Ajar documents
        $totalModulAjar = $user->documents()->where('type', 'Modul Ajar')->count();
        
        // Count total questions dynamically across all relevant document types
        $totalQuestions = 0;
        $docs = $user->documents()->get();
        foreach ($docs as $doc) {
            $typeLower = strtolower($doc->type);
            if ($typeLower === 'soal') {
                // Count lines starting with a number followed by a dot
                $count = preg_match_all('/^\d+\.\s+/m', $doc->content);
                $totalQuestions += $count > 0 ? $count : 5; // Fallback to 5
            } elseif ($typeLower === 'bahan ajar utama') {
                $data = json_decode($doc->content, true);
                if ($data && isset($data['quiz']) && is_array($data['quiz'])) {
                    $totalQuestions += count($data['quiz']);
                }
            } elseif ($typeLower === 'lkpd') {
                $data = json_decode($doc->content, true);
                if ($data && isset($data['soal_soal']) && is_array($data['soal_soal'])) {
                    $totalQuestions += count($data['soal_soal']);
                }
            }
        }

        // Mock credit logic (premium Duolingo-like credit countdown)
        $sisaKredit = max(100, 5000 - ($totalDocuments * 125));
        
        // Get last 5 activities
        $recentActivity = $user->documents()->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard', compact('totalDocuments', 'totalModulAjar', 'totalQuestions', 'sisaKredit', 'recentActivity'));
    })->name('dashboard');

    Route::post('/chat', [GeminiController::class, 'chat'])->name('chat');

    // Profile Settings
    Route::get('/pengaturan', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/pengaturan', [ProfileController::class, 'update'])->name('profile.update');

    // Subscription
    Route::get('/langganan', [SubscriptionController::class, 'upgrade'])->name('langganan');
    Route::get('/checkout/{paket}', [SubscriptionController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/{paket}', [SubscriptionController::class, 'processCheckout'])->name('checkout.process');

    // Feedback
    Route::get('/bantuan', [App\Http\Controllers\FeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/bantuan', [App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');

    // Admin
    Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/pengguna', [\App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/pembayaran', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.payments');
    Route::post('/admin/pembayaran/{id}/approve', [\App\Http\Controllers\AdminController::class, 'approve'])->name('admin.payments.approve');
    Route::post('/admin/pembayaran/{id}/reject', [\App\Http\Controllers\AdminController::class, 'reject'])->name('admin.payments.reject');
    Route::get('/admin/masukan', [\App\Http\Controllers\AdminController::class, 'feedbacks'])->name('admin.feedbacks');
    Route::post('/admin/masukan/{id}/balas', [\App\Http\Controllers\AdminController::class, 'replyFeedback'])->name('admin.feedbacks.reply');
    Route::resource('/admin/packages', \App\Http\Controllers\PackageController::class)->names('admin.packages');
    // Document Archiving & Downloading
    Route::get('/riwayat', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{id}/download', [DocumentController::class, 'downloadDoc'])->name('documents.download');
    Route::get('/documents/{id}/print', [DocumentController::class, 'printDoc'])->name('documents.print');

    // Modul Ajar Generator (Gemini)
    Route::post('/tools/generate-modul', [GeminiController::class, 'generateModulAjar'])->name('tools.generate-modul');

    // Generator Soal (placeholder)
    Route::get('/generator-soal', function () {
        return view('tools.generator-soal');
    })->name('tools.generator-soal');

    // Semua Tools Directory
    Route::get('/tools', function () {
        return view('tools.index');
    })->name('tools.index');

    // Modul Ajar Workspace Form
    Route::get('/modul-ajar', function () {
        return view('tools.modul-ajar');
    })->name('tools.modul-ajar');

    // Generator Soal Submit
    Route::post('/tools/generate-soal', [GeminiController::class, 'generateSoal'])->name('tools.generate-soal-submit');

    // Generate Image On-Demand
    Route::post('/tools/generate-image', [GeminiController::class, 'generateImage'])->name('tools.generate-image');

    // PPT Pembelajaran Workspace View
    Route::get('/ppt-pembelajaran', function (\Illuminate\Http\Request $request) {
        $document = null;
        if ($request->has('document_id')) {
            $document = \Illuminate\Support\Facades\Auth::user()->documents()->find($request->query('document_id'));
        }
        return view('tools.ppt-pembelajaran', compact('document'));
    })->name('tools.ppt-pembelajaran');

    // Rubrik Penilaian Workspace View
    Route::get('/rubrik-penilaian', function (\Illuminate\Http\Request $request) {
        $document = null;
        if ($request->has('document_id')) {
            $document = \Illuminate\Support\Facades\Auth::user()->documents()->find($request->query('document_id'));
        }
        return view('tools.rubrik-penilaian', compact('document'));
    })->name('tools.rubrik-penilaian');

    // Rubrik Penilaian Submit
    Route::post('/tools/generate-rubrik', [GeminiController::class, 'generateRubrik'])->name('tools.generate-rubrik-submit');

    // Bahan Ajar AI Workspace View (Hidden, redirected to Utama)
    Route::get('/bahan-ajar', function (\Illuminate\Http\Request $request) {
        return redirect()->route('tools.bahan-ajar-utama');
    })->name('tools.bahan-ajar');

    // Bahan Ajar AI Submit
    Route::post('/tools/generate-bahan-ajar', [GeminiController::class, 'generateBahanAjar'])->name('tools.generate-bahan-ajar-submit');

    // Bahan Ajar Utama Workspace View
    Route::get('/bahan-ajar-utama', function (\Illuminate\Http\Request $request) {
        $document = null;
        if ($request->has('document_id')) {
            $document = \Illuminate\Support\Facades\Auth::user()->documents()->find($request->query('document_id'));
        }
        return view('tools.bahan-ajar-utama', compact('document'));
    })->name('tools.bahan-ajar-utama');

    // Bahan Ajar Utama Submit
    Route::post('/tools/generate-bahan-ajar-utama', [GeminiController::class, 'generateBahanAjarUtama'])->name('tools.generate-bahan-ajar-utama-submit');

    // LKPD Generator Workspace View
    Route::get('/lkpd-generator', function (\Illuminate\Http\Request $request) {
        $document = null;
        if ($request->has('document_id')) {
            $document = \Illuminate\Support\Facades\Auth::user()->documents()->find($request->query('document_id'));
        }
        return view('tools.lkpd-generator', compact('document'));
    })->name('tools.lkpd-generator');

    // LKPD Generator Submit
    Route::post('/tools/generate-lkpd', [GeminiController::class, 'generateLKPD'])->name('tools.generate-lkpd-submit');

    // CORS-safe Image Proxy for PowerPoint export
    Route::get('/api/image-proxy', function (\Illuminate\Http\Request $request) {
        $url = $request->query('url');
        if (!$url) {
            return response('Missing URL', 400);
        }
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)->get($url);
            if ($response->successful()) {
                return response($response->body())
                    ->header('Content-Type', $response->header('Content-Type') ?: 'image/jpeg')
                    ->header('Access-Control-Allow-Origin', '*');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Image proxy failed: ' . $e->getMessage());
        }
        return response('Failed to fetch image', 500);
    })->name('image-proxy');

    // PPT Pembelajaran Submit
    Route::post('/tools/generate-ppt', [GeminiController::class, 'generatePPT'])->name('tools.generate-ppt-submit');

    // Prompt Guru routes
    Route::get('/prompt-guru', function (\Illuminate\Http\Request $request) {
        $document = null;
        if ($request->has('document_id')) {
            $document = \Illuminate\Support\Facades\Auth::user()->documents()->find($request->query('document_id'));
        }
        return view('tools.prompt-guru', compact('document'));
    })->name('tools.prompt-guru');

    Route::post('/tools/save-prompt-history', [GeminiController::class, 'savePromptHistory'])->name('tools.save-prompt-history');
    Route::post('/tools/recommend-cptp', [GeminiController::class, 'recommendCPTP'])->name('tools.recommend-cptp');

    // Manajemen Kelas, Jurnal & Presensi (Premium Only)
    Route::get('/kelas', [\App\Http\Controllers\ClassroomController::class, 'index'])->name('classrooms.index');
    Route::post('/kelas', [\App\Http\Controllers\ClassroomController::class, 'store'])->name('classrooms.store');
    Route::get('/kelas/{classroom}', [\App\Http\Controllers\ClassroomController::class, 'show'])->name('classrooms.show');
    Route::post('/kelas/{classroom}/students', [\App\Http\Controllers\ClassroomController::class, 'storeStudent'])->name('classrooms.students.store');
    Route::post('/kelas/{classroom}/students/import', [\App\Http\Controllers\ClassroomController::class, 'importStudents'])->name('classrooms.students.import');
    Route::delete('/kelas/students/{student}', [\App\Http\Controllers\ClassroomController::class, 'destroyStudent'])->name('classrooms.students.destroy');
    Route::post('/kelas/{classroom}/schedules', [\App\Http\Controllers\ClassroomController::class, 'storeSchedule'])->name('classrooms.schedules.store');
    Route::delete('/kelas/schedules/{schedule}', [\App\Http\Controllers\ClassroomController::class, 'destroySchedule'])->name('classrooms.schedules.destroy');

    Route::get('/kelas/{classroom}/jurnal/create', [\App\Http\Controllers\JournalController::class, 'create'])->name('journals.create');
    Route::post('/kelas/{classroom}/jurnal', [\App\Http\Controllers\JournalController::class, 'store'])->name('journals.store');
    Route::get('/kelas/{classroom}/jurnal/{journal}/edit', [\App\Http\Controllers\JournalController::class, 'edit'])->name('journals.edit');
    Route::put('/kelas/{classroom}/jurnal/{journal}', [\App\Http\Controllers\JournalController::class, 'update'])->name('journals.update');
    
    Route::get('/jurnal/rekap', [\App\Http\Controllers\JournalController::class, 'rekap'])->name('journals.rekap');
    Route::get('/jurnal/rekap/print', [\App\Http\Controllers\JournalController::class, 'printRekap'])->name('journals.rekap.print');
    
    Route::get('/jurnal/{journal}', [\App\Http\Controllers\JournalController::class, 'show'])->name('journals.show');
    Route::get('/jurnal/{journal}/print', [\App\Http\Controllers\JournalController::class, 'print'])->name('journals.print');
});
