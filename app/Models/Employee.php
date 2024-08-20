<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id', 
        'state_id', 
        'city_id',
        'department_id',
        'name',
        'address',
        'phone_number',
        'email',
        'zip_code',
        'birth_date',
        'active',
    ];

    public function country(): BelongsTo {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo {
        return $this->belongsTo(City::class);
    }

    public function department(): BelongsTo {
        return $this->belongsTo(Department::class);
    }
}
