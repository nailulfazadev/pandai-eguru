<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassroomController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isTrial()) {
            return redirect()->route('langganan')->with('error', 'Fitur Manajemen Kelas & Jurnal khusus untuk pengguna Premium.');
        }

        // Get all classrooms for the user
        $classrooms = $user->classrooms()->withCount('students')->get();
        
        // Find today's day
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $today = $days[now()->dayOfWeek];
        
        // Get ALL schedules for the user's classrooms
        $allSchedules = Schedule::whereIn('classroom_id', $classrooms->pluck('id'))
            ->with('classroom')
            ->orderBy('start_time')
            ->get();

        foreach ($allSchedules as $schedule) {
            $journal = \App\Models\Journal::where('schedule_id', $schedule->id)
                ->where('date', now()->toDateString())
                ->first();
            $schedule->today_journal = $journal;
        }
        
        $schedulesByDay = $allSchedules->groupBy('day_of_week');

        return view('classrooms.index', compact('classrooms', 'schedulesByDay', 'today'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->isTrial()) {
            return redirect()->route('langganan');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
        ]);

        $user->classrooms()->create($request->only(['name', 'subject']));

        return back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function show(Classroom $classroom)
    {
        $user = Auth::user();
        if ($user->isTrial()) {
            return redirect()->route('langganan');
        }
        
        if ($classroom->user_id !== $user->id) {
            abort(403);
        }

        $classroom->load(['students', 'schedules', 'journals' => function ($query) {
            $query->orderBy('date', 'desc');
        }]);

        return view('classrooms.show', compact('classroom'));
    }

    public function storeStudent(Request $request, Classroom $classroom)
    {
        if ($classroom->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:50',
            'gender' => 'nullable|in:L,P',
        ]);

        $classroom->students()->create($request->all());

        return back()->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function importStudents(Request $request, Classroom $classroom)
    {
        if ($classroom->user_id !== Auth::id()) abort(403);

        $request->validate([
            'paste_data' => 'required|string'
        ]);

        $lines = explode("\n", trim($request->paste_data));
        $count = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Pisahkan berdasarkan tab, koma, titik koma, garis vertikal, atau 2 spasi/lebih
            $columns = preg_split("/[\t,;|]+|\s{2,}/", $line);
            
            if (count($columns) >= 3) {
                $name = trim($columns[0]);
                $nisn = trim($columns[1]);
                $genderRaw = strtoupper(trim($columns[2]));
            } elseif (count($columns) == 2) {
                $name = trim($columns[0]);
                // Jika kolom kedua adalah L/P, berarti NISN kosong
                if (preg_match('/^(L|P|LAKI|PEREMPUAN|LAKI-LAKI)$/i', trim($columns[1]))) {
                    $nisn = null;
                    $genderRaw = strtoupper(trim($columns[1]));
                } else {
                    $nisn = trim($columns[1]);
                    $genderRaw = '';
                }
            } else {
                // Jika tidak ada pemisah yang jelas (mungkin dipisah 1 spasi)
                // Coba tebak format: "Nama Lengkap NISN L/P"
                if (preg_match('/^(.*?)\s+([0-9]+)?\s*(L|P|LAKI-LAKI|PEREMPUAN|LAKI)$/i', $line, $matches)) {
                    $name = trim($matches[1]);
                    $nisn = trim($matches[2]) ?: null;
                    $genderRaw = strtoupper(trim($matches[3]));
                } else {
                    // Mentok, jadikan nama saja
                    $name = $line;
                    $nisn = null;
                    $genderRaw = '';
                }
            }

            if (empty($name)) continue;

            $gender = null;
            if (in_array($genderRaw, ['L', 'LAKI-LAKI', 'LAKI'])) {
                $gender = 'L';
            } elseif (in_array($genderRaw, ['P', 'PEREMPUAN'])) {
                $gender = 'P';
            }

            $classroom->students()->create([
                'name' => $name,
                'nisn' => $nisn,
                'gender' => $gender,
            ]);
            $count++;
        }

        return back()->with('success', "$count siswa berhasil diimpor!");
    }

    public function destroyStudent(Student $student)
    {
        $classroom = $student->classroom;
        if ($classroom->user_id !== Auth::id()) abort(403);

        $student->delete();

        return back()->with('success', 'Siswa berhasil dihapus!');
    }

    public function storeSchedule(Request $request, Classroom $classroom)
    {
        if ($classroom->user_id !== Auth::id()) abort(403);

        $request->validate([
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $classroom->schedules()->create($request->all());

        return back()->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function destroySchedule(Schedule $schedule)
    {
        $classroom = $schedule->classroom;
        if ($classroom->user_id !== Auth::id()) abort(403);

        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus!');
    }
}
