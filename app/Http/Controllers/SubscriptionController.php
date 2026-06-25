<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class SubscriptionController extends Controller
{
    public function upgrade()
    {
        $user = Auth::user();
        return view('upgrade', compact('user'));
    }

    public function checkout($paket)
    {
        $user = Auth::user();
        
        $validPakets = ['semester', 'tahunan'];
        if (!in_array($paket, $validPakets)) {
            return redirect()->route('langganan')->with('error', 'Paket tidak valid.');
        }

        // Cek jika user sudah punya payment pending
        $hasPending = Payment::where('user_id', $user->id)
                            ->where('status', 'pending')
                            ->exists();

        $nominal = ($paket === 'semester') ? 89000 : 147000;

        return view('checkout', compact('paket', 'nominal', 'hasPending'));
    }

    public function processCheckout(Request $request, $paket)
    {
        $user = Auth::user();

        $validPakets = ['semester', 'tahunan'];
        if (!in_array($paket, $validPakets)) {
            return redirect()->route('langganan')->with('error', 'Paket tidak valid.');
        }

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $nominal = ($paket === 'semester') ? 89000 : 147000;

        // Simpan file bukti
        $file = $request->file('bukti_transfer');
        $fileName = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('bukti_transfer', $fileName, 'public');

        // Buat record payment
        Payment::create([
            'user_id' => $user->id,
            'paket' => $paket,
            'nominal' => $nominal,
            'bukti_transfer' => 'bukti_transfer/' . $fileName,
            'status' => 'pending'
        ]);

        return redirect()->route('dashboard')->with('success', 'Bukti transfer berhasil diunggah! Mohon tunggu, Admin sedang memverifikasi pembayaran Anda.');
    }
}
