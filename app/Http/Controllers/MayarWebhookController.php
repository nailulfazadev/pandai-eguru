<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MayarWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Log::info('Mayar Webhook Received:', $request->all());

        // Mayar usually sends data in a flattened format or within customer object.
        // We will try to extract it robustly.
        $status = $request->input('status') ?? $request->input('transaction_status');
        
        // Hanya proses jika statusnya berhasil dibayar
        if (strtolower($status) !== 'paid' && strtolower($status) !== 'settlement' && strtolower($status) !== 'success') {
            return response()->json(['message' => 'Status not paid. Ignored.'], 200);
        }

        $email = $request->input('customer.email') ?? $request->input('email');
        $name = $request->input('customer.name') ?? $request->input('name') ?? 'Pengguna Mayar';
        $phone = $request->input('customer.phone') ?? $request->input('customer.mobile') ?? $request->input('phone') ?? $request->input('mobile') ?? '08123456789';
        $amount = $request->input('amount') ?? $request->input('gross_amount') ?? 89000;

        if (!$email) {
            Log::error('Mayar Webhook: No email found in payload.');
            return response()->json(['message' => 'No email provided'], 400);
        }

        // Tentukan paket berdasarkan nominal (89000 = semester, lainnya anggap tahunan)
        $paket = ($amount == 89000) ? 'semester' : 'tahunan';
        $days = ($paket === 'semester') ? 180 : 365;

        $user = User::where('email', $email)->first();

        if ($user) {
            // Skenario A: User sudah ada
            $user->subscription_tier = $paket;
            // Jika masa aktif masih ada, tambahkan dari sisa. Jika sudah habis, dari sekarang.
            if ($user->subscription_ends_at && $user->subscription_ends_at->isFuture()) {
                $user->subscription_ends_at = $user->subscription_ends_at->addDays($days);
            } else {
                $user->subscription_ends_at = now()->addDays($days);
            }
            $user->save();
            Log::info("Mayar Webhook: User $email extended subscription.");
        } else {
            // Skenario B: User belum ada, buat baru
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($phone), // Password menggunakan nomor HP
                'subscription_tier' => $paket,
                'subscription_ends_at' => now()->addDays($days),
                'is_admin' => false,
            ]);
            Log::info("Mayar Webhook: New user $email created.");
        }

        // Catat ke tabel Payment
        Payment::create([
            'user_id' => $user->id,
            'paket' => $paket,
            'nominal' => $amount,
            'bukti_transfer' => 'Via Mayar (Otomatis)',
            'status' => 'approved'
        ]);

        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }
}
