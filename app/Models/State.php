<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['country_id', 'state_code', 'name'];

    public function country(): BelongsTo{
        return $this->belongsTo(Country::class);
    }

    public function staff(): HasMany{
        return $this->hasMany(Staff::class);
    }

    public function cities(): HasMany{
        return $this->hasMany(City::class);
    }
}
