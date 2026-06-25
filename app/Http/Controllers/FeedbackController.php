<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('feedback.index', compact('feedbacks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'Menunggu Tanggapan',
        ]);

        return redirect()->back()->with('success', 'Laporan/Masukan Anda berhasil dikirim! Admin akan segera meninjaunya.');
    }
}
