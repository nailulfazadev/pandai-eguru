import os

models = {
    'Classroom.php': """<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'name', 'subject'];

    public function user() { return $this->belongsTo(User::class); }
    public function students() { return $this->hasMany(Student::class); }
    public function schedules() { return $this->hasMany(Schedule::class); }
    public function journals() { return $this->hasMany(Journal::class); }
}""",

    'Student.php': """<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['classroom_id', 'name', 'nisn', 'gender'];

    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
}""",

    'Schedule.php': """<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = ['classroom_id', 'day_of_week', 'start_time', 'end_time'];

    public function classroom() { return $this->belongsTo(Classroom::class); }
}""",

    'Journal.php': """<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;
    protected $fillable = ['classroom_id', 'date', 'title', 'content'];

    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
}""",

    'Attendance.php': """<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['journal_id', 'student_id', 'status', 'notes'];

    public function journal() { return $this->belongsTo(Journal::class); }
    public function student() { return $this->belongsTo(Student::class); }
}"""
}

for file_name, content in models.items():
    with open(f"app/Models/{file_name}", "w") as f:
        f.write(content)
    print(f"Updated app/Models/{file_name}")

