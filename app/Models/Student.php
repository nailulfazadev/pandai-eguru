<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['classroom_id', 'name', 'nisn', 'gender'];

    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
}