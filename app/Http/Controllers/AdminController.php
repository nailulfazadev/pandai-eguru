<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $totalUsers = \App\Models\User::where('is_admin', false)->count();
        $totalPremium = \App\Models\User::whereNotNull('subscription_tier')->where('is_admin', false)->count();
        
        $pendingPayments = Payment::where('status', 'pending')->count();
        $revenue = Payment::where('status', 'approved')->sum('nominal');

        return view('admin.dashboard', compact('totalUsers', 'totalPremium', 'pendingPayments', 'revenue'));
    }

    public function users()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $users = \App\Models\User::where('is_admin', false)->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function feedbacks()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $feedbacks = \App\Models\Feedback::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.feedbacks', compact('feedbacks'));
    }

    public function replyFeedback(Request $request, $id)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $request->validate([
            'response' => 'required|string',
        ]);

        $feedback = \App\Models\Feedback::findOrFail($id);
        $feedback->update([
            'response' => $request->response,
            'status' => 'Dijawab',
        ]);

        return redirect()->back()->with('success', 'Tanggapan berhasil dikirim!');
    }

    public function index()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $payments = Payment::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.payments', compact('payments'));
    }

    public function approve($id)
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        $payment = Payment::findOrFail($id);
        
        if ($payment->status === 'pending') {
            $payment->status = 'approved';
            $payment->save();

            $user = $payment->user;
            
            $package = \App\Models\Package::where('slug', $payment->paket)->first();
            $days = $package ? $package->duration_days : ($payment->paket === 'semester' ? 180 : 365); // Fallback if package deleted
            
            $user->subscription_tier = $payment->paket;
            $user->subscription_ends_at = now()->addDays($days);
            $user->save();

            return redirect()->back()->with('success', 'Pembayaran berhasil disetujui. Akun pengguna telah diaktifkan.');
        }

        return redirect()->back()->with('error', 'Pembayaran ini tidak dalam status pending.');
    }

    public function reject($id)
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        $payment = Payment::findOrFail($id);
        
        if ($payment->status === 'pending') {
            $payment->status = 'rejected';
            $payment->save();

            return redirect()->back()->with('success', 'Pembayaran ditolak.');
        }

        return redirect()->back()->with('error', 'Pembayaran ini tidak dalam status pending.');
    }
}
