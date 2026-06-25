<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Journal;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function create(Request $request, Classroom $classroom)
    {
        $user = Auth::user();
        if ($user->isTrial()) {
            return redirect()->route('langganan');
        }
        
        if ($classroom->user_id !== $user->id) abort(403);

        $classroom->load('students');
        
        $schedule = null;
        if ($request->has('schedule_id')) {
            $schedule = Schedule::find($request->schedule_id);
            if ($schedule && $schedule->classroom_id !== $classroom->id) {
                $schedule = null;
            }
        }
        
        $schedules = $classroom->schedules()->orderBy('day_of_week')->orderBy('start_time')->get();

        return view('journals.create', compact('classroom', 'schedule', 'schedules'));
    }

    public function store(Request $request, Classroom $classroom)
    {
        if ($classroom->user_id !== Auth::id()) abort(403);

        $request->validate([
            'date' => 'required|date',
            'schedule_id' => 'required|exists:schedules,id',
            'meeting_number' => 'nullable|string',
            'title' => 'required|string|max:255',
            'competency' => 'nullable|string',
            'activity' => 'nullable|string',
            'notes' => 'nullable|string',
            'content' => 'required|string',
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:hadir,sakit,izin,alpa',
        ]);

        $journal = $classroom->journals()->create([
            'date' => $request->date,
            'schedule_id' => $request->schedule_id,
            'meeting_number' => $request->meeting_number,
            'title' => $request->title,
            'competency' => $request->competency,
            'activity' => $request->activity,
            'content' => $request->content,
            'notes' => $request->notes,
        ]);

        foreach ($request->attendances as $studentId => $data) {
            $journal->attendances()->create([
                'student_id' => $studentId,
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
            ]);
        }

        return redirect()->route('classrooms.show', $classroom)->with('success', 'Jurnal dan presensi berhasil disimpan!');
    }

    public function edit(Classroom $classroom, Journal $journal)
    {
        if ($classroom->user_id !== Auth::id() || $journal->classroom_id !== $classroom->id) abort(403);

        $classroom->load('students');
        $journal->load('attendances');

        $schedule = $journal->schedule;

        return view('journals.edit', compact('classroom', 'journal', 'schedule'));
    }

    public function update(Request $request, Classroom $classroom, Journal $journal)
    {
        if ($classroom->user_id !== Auth::id() || $journal->classroom_id !== $classroom->id) abort(403);

        $request->validate([
            'date' => 'required|date',
            'meeting_number' => 'nullable|string',
            'title' => 'required|string|max:255',
            'competency' => 'nullable|string',
            'activity' => 'nullable|string',
            'notes' => 'nullable|string',
            'content' => 'required|string',
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:hadir,sakit,izin,alpa',
        ]);

        $journal->update([
            'date' => $request->date,
            'meeting_number' => $request->meeting_number,
            'title' => $request->title,
            'competency' => $request->competency,
            'activity' => $request->activity,
            'content' => $request->content,
            'notes' => $request->notes,
        ]);

        foreach ($request->attendances as $studentId => $data) {
            $journal->attendances()->updateOrCreate(
                ['student_id' => $studentId],
                ['status' => $data['status'], 'notes' => $data['notes'] ?? null]
            );
        }

        return back()->with('success', 'Jurnal berhasil diperbarui!');
    }

    public function show(Journal $journal)
    {
        $classroom = $journal->classroom;
        if ($classroom->user_id !== Auth::id()) abort(403);

        $journal->load(['attendances.student']);

        return view('journals.show', compact('journal', 'classroom'));
    }

    public function print(Journal $journal)
    {
        $classroom = $journal->classroom;
        if ($classroom->user_id !== Auth::id()) abort(403);

        $journal->load(['attendances.student']);

        return view('journals.print', compact('journal', 'classroom'));
    }

    private function parseReportFilter(Request $request)
    {
        $type = $request->input('report_type', 'mingguan');
        $printTitle = 'AGENDA / JURNAL MINGGUAN GURU';
        $periodText = '';
        
        $startDate = now()->startOfWeek()->toDateString();
        $endDate = now()->endOfWeek()->toDateString();

        if ($type === 'harian') {
            $startDate = $request->input('date', now()->toDateString());
            $endDate = $startDate;
            $printTitle = 'AGENDA / JURNAL HARIAN GURU';
            $periodText = \Carbon\Carbon::parse($startDate)->locale('id')->isoFormat('D MMMM YYYY');
        } elseif ($type === 'mingguan') {
            $startDate = $request->input('start_date', now()->startOfWeek()->toDateString());
            $endDate = $request->input('end_date', now()->endOfWeek()->toDateString());
            $printTitle = 'AGENDA / JURNAL MINGGUAN GURU';
            $periodText = \Carbon\Carbon::parse($startDate)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d/m/Y');
        } elseif ($type === 'bulanan') {
            $month = $request->input('month', now()->month);
            $year = $request->input('year', now()->year);
            $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
            $endDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
            
            $months = ['', 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'];
            $monthName = $months[(int)$month] ?? '';
            $printTitle = "AGENDA / JURNAL BULANAN GURU ($monthName $year)";
            $periodText = "Bulan $monthName $year";
        } elseif ($type === 'semesteran') {
            $semester = $request->input('semester', 'ganjil');
            $year = $request->input('school_year', now()->year);
            
            if ($semester === 'ganjil') {
                $startDate = \Carbon\Carbon::create($year, 7, 1)->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::create($year, 12, 31)->endOfMonth()->toDateString();
                $printTitle = "AGENDA / JURNAL SEMESTER GANJIL GURU";
            } else {
                $startDate = \Carbon\Carbon::create($year + 1, 1, 1)->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::create($year + 1, 6, 30)->endOfMonth()->toDateString();
                $printTitle = "AGENDA / JURNAL SEMESTER GENAP GURU";
            }
            $periodText = "Semester " . ucfirst($semester) . " TP $year/" . ($year + 1);
        } elseif ($type === 'tahunan') {
            $year = $request->input('school_year', now()->year);
            $startDate = \Carbon\Carbon::create($year, 7, 1)->startOfMonth()->toDateString();
            $endDate = \Carbon\Carbon::create($year + 1, 6, 30)->endOfMonth()->toDateString();
            $printTitle = "AGENDA / JURNAL TAHUNAN GURU";
            $periodText = "Tahun Ajaran $year/" . ($year + 1);
        }

        return compact('type', 'startDate', 'endDate', 'printTitle', 'periodText');
    }

    
    public function rekap(Request $request)
    {
        $user = Auth::user();
        if ($user->isTrial()) {
            return redirect()->route('langganan');
        }

        extract($this->parseReportFilter($request));

        $query = Journal::whereHas('classroom', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereBetween('date', [$startDate, $endDate]);

        if ($request->has('classroom_id')) {
            $query->where('classroom_id', $request->input('classroom_id'));
        }

        $journals = $query->with(['classroom', 'schedule', 'attendances'])
            ->orderBy('date')
            ->get();

        return view('journals.rekap', compact('journals', 'startDate', 'endDate', 'type', 'printTitle', 'periodText', 'request'));
    }

    public function printRekap(Request $request)
    {
        $user = Auth::user();
        if ($user->isTrial()) {
            return redirect()->route('langganan');
        }

        extract($this->parseReportFilter($request));

        $query = Journal::whereHas('classroom', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereBetween('date', [$startDate, $endDate]);

        if ($request->has('classroom_id')) {
            $query->where('classroom_id', $request->input('classroom_id'));
        }

        $journals = $query->with(['classroom', 'schedule', 'attendances.student'])
            ->orderBy('date')
            ->get();

        $groupedJournals = $journals->groupBy('date');

        return view('journals.print_rekap', compact('groupedJournals', 'startDate', 'endDate', 'user', 'printTitle', 'periodText'));
    }
}
