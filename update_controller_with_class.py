import re

file_path = 'app/Http/Controllers/JournalController.php'
with open(file_path, 'r') as f:
    content = f.read()

rekap_method = """
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
}"""

content = re.sub(
    r"public function rekap\(Request \$request\).*?\}",
    rekap_method,
    content,
    flags=re.DOTALL
)

with open(file_path, 'w') as f:
    f.write(content)

print("Updated JournalController with classroom_id filter")
