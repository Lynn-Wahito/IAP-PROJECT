<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events_tbl';

    protected $fillable = [

        'event_name', 
        'description', 
        'event_datetime', 
        'venue', 
        'template_path',
        'persons_per_row',
        'vip_seats',
        'regular_seats',
        'vip_prices',
        'regular_prices',
        'user_id',
    ];

    protected $dates = ['event_datetime'];

    protected $casts = [
        'event_datetime' => 'datetime',
    ];
    


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function calculateRemainingTime()
    {
        $eventDateTime = Carbon::parse($this->event_datetime);
        $currentTime = Carbon::now();
    
        // Calculating the difference in terms of days, hours, and minutes
        $diff = $eventDateTime->diff($currentTime);
    
        $remainingTime = '';
    
        if ($diff->d > 0) {
            $remainingTime .= $diff->d . ' days ';
        }
    
        if ($diff->h > 0) {
            $remainingTime .= $diff->h . ' hours ';
        }
    
        if ($diff->i > 0) {
            $remainingTime .= $diff->i . ' minutes ';
        }
    
        return trim($remainingTime);
    }
}