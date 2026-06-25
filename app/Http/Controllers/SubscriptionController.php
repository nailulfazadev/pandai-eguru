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
        $packages = \App\Models\Package::where('is_active', true)->orderBy('price', 'asc')->get();
        return view('upgrade', compact('user', 'packages'));
    }

    public function checkout($paket)
    {
        $user = Auth::user();
        
        $package = \App\Models\Package::where('slug', $paket)->where('is_active', true)->first();
        if (!$package) {
            return redirect()->route('langganan')->with('error', 'Paket tidak valid atau tidak aktif.');
        }

        // Cek jika user sudah punya payment pending
        $hasPending = Payment::where('user_id', $user->id)
                            ->where('status', 'pending')
                            ->exists();

        $nominal = $package->price;

        return view('checkout', compact('paket', 'nominal', 'hasPending', 'package'));
    }

    public function processCheckout(Request $request, $paket)
    {
        $user = Auth::user();

        $package = \App\Models\Package::where('slug', $paket)->where('is_active', true)->first();
        if (!$package) {
            return redirect()->route('langganan')->with('error', 'Paket tidak valid atau tidak aktif.');
        }

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $nominal = $package->price;

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
