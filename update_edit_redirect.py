import re

file_path = 'resources/views/journals/edit.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

# Add success message below header
old_header = """    </div>
</div>

<form action="{{ route('journals.update', [$classroom, $journal]) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">"""

new_header = """    </div>
</div>

@if(session('success'))
<div class="bg-duo-green-light/30 border border-duo-green text-duo-green px-4 py-3 rounded-xl mb-6 flex justify-between items-center">
    <span>{{ session('success') }}</span>
    <a href="{{ route('classrooms.index') }}" class="btn-3d-primary text-xs px-4 py-1.5 shadow-sm">Kembali ke Dashboard</a>
</div>
@endif

<form action="{{ route('journals.update', [$classroom, $journal]) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">"""

content = content.replace(old_header, new_header)

with open(file_path, 'w') as f:
    f.write(content)

file_path2 = 'app/Http/Controllers/JournalController.php'
with open(file_path2, 'r') as f:
    content2 = f.read()

content2 = content2.replace(
    "return redirect()->route('classrooms.show', $classroom)->with('success', 'Jurnal berhasil diperbarui!');",
    "return back()->with('success', 'Jurnal berhasil diperbarui!');"
)

with open(file_path2, 'w') as f:
    f.write(content2)

print("Updated edit redirect and added success message.")
