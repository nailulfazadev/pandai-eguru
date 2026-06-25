import re

with open('routes/web.php', 'r') as f:
    content = f.read()

routes_to_add = """
    // Manajemen Kelas, Jurnal & Presensi (Premium Only)
    Route::get('/kelas', [\App\Http\Controllers\ClassroomController::class, 'index'])->name('classrooms.index');
    Route::post('/kelas', [\App\Http\Controllers\ClassroomController::class, 'store'])->name('classrooms.store');
    Route::get('/kelas/{classroom}', [\App\Http\Controllers\ClassroomController::class, 'show'])->name('classrooms.show');
    Route::post('/kelas/{classroom}/students', [\App\Http\Controllers\ClassroomController::class, 'storeStudent'])->name('classrooms.students.store');
    Route::delete('/kelas/students/{student}', [\App\Http\Controllers\ClassroomController::class, 'destroyStudent'])->name('classrooms.students.destroy');
    Route::post('/kelas/{classroom}/schedules', [\App\Http\Controllers\ClassroomController::class, 'storeSchedule'])->name('classrooms.schedules.store');
    Route::delete('/kelas/schedules/{schedule}', [\App\Http\Controllers\ClassroomController::class, 'destroySchedule'])->name('classrooms.schedules.destroy');

    Route::get('/kelas/{classroom}/jurnal/create', [\App\Http\Controllers\JournalController::class, 'create'])->name('journals.create');
    Route::post('/kelas/{classroom}/jurnal', [\App\Http\Controllers\JournalController::class, 'store'])->name('journals.store');
    Route::get('/jurnal/{journal}', [\App\Http\Controllers\JournalController::class, 'show'])->name('journals.show');
    Route::get('/jurnal/{journal}/print', [\App\Http\Controllers\JournalController::class, 'print'])->name('journals.print');
"""

# Insert before the last });
content = re.sub(r'(\n\}\);)\n*$', r'\n' + routes_to_add + r'\1\n', content)

with open('routes/web.php', 'w') as f:
    f.write(content)
