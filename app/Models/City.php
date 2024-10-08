<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['state_id', 'area_code', 'name'];

    public function state(): BelongsTo {
        return $this->belongsTo(State::class);
    }

    public function employees(): HasMany {
        return $this->hasMany(Employee::class);
    }

    public function servers(): HasMany {
        return $this->hasMany(Server::class);
    }

    public function tests(): HasMany {
        return $this->hasMany(Test::class);
    }

}
