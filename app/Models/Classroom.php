<?php
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
}