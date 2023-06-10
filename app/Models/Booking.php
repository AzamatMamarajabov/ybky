<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Room;

class Booking extends Model
{
    use HasFactory;
    protected $dates = [
        'start_datetime',
        'end_datetime',
    ];
  
    protected $fillable = ['room_id', 'start_datetime', 'end_datetime'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
