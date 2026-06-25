import re

file_path = 'app/Http/Controllers/JournalController.php'
with open(file_path, 'r') as f:
    content = f.read()

helper_method = """
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
        if ($user->isTrial()) return redirect()->route('langganan');

        extract($this->parseReportFilter($request));

        $journals = Journal::whereHas('classroom', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['classroom', 'schedule', 'attendances'])
            ->orderBy('date')
            ->get();

        return view('journals.rekap', compact('journals', 'startDate', 'endDate', 'type', 'printTitle', 'periodText', 'request'));
    }

    public function printRekap(Request $request)
    {
        $user = Auth::user();
        if ($user->isTrial()) return redirect()->route('langganan');

        extract($this->parseReportFilter($request));

        $journals = Journal::whereHas('classroom', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['classroom', 'schedule', 'attendances.student'])
            ->orderBy('date')
            ->get();

        $groupedJournals = $journals->groupBy('date');

        return view('journals.print_rekap', compact('groupedJournals', 'startDate', 'endDate', 'user', 'printTitle', 'periodText'));
    }
}
"""

content = re.sub(r'public function rekap\(Request \$request\).*', helper_method, content, flags=re.DOTALL)

with open(file_path, 'w') as f:
    f.write(content)

print("Updated JournalController with intelligent filter logic")
