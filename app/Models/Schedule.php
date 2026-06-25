<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = ['classroom_id', 'day_of_week', 'start_time', 'end_time'];

    public function classroom() { return $this->belongsTo(Classroom::class); }
}