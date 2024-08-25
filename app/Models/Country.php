<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['country_code', 'area_code', 'name'];

    public function states(): HasMany {
        return $this->hasMany(State::class);
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
