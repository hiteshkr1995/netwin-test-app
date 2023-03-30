<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    /**
     * Get the time availability for the doctors.
     */
    public function timeAvailabilities()
    {
        return $this->hasMany(TimeAvailability::class);
    }
}
