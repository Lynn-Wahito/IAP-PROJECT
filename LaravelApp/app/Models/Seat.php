<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;
    protected $table = 'seats';


    protected $fillable = [
        'event_id', 
        'row_number',
        'seat_number', 
        'seat_type',
        'status',
    ];

    public function event()
{
    return $this->belongsTo(Event::class, 'event_id');
}

}