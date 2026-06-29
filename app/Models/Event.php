<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'category_id',
        'capacity',
        'organizer_id',
    ];

    protected function casts(): array 
    {
        return[
            'date' => 'datetime',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function getRegisteredCountAttribute()
    {
        return $this->registrations()->count();
    }

    public function getAvailableSpotsAttribute()
    {
        return $this->capacity - $this->registered_count;
    }

    public function isFull()
    {
        return $this->available_spots <= 0;
    }
}
