<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'schedule_id',
        'date',
        'meeting_number',
        'title',
        'competency',
        'activity',
        'content',
        'notes',
    ];

    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function schedule() { return $this->belongsTo(Schedule::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
}