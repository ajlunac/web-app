<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['country_code', 'area_code', 'name'];

    public function states(){
        return $this->hasMany(State::class);
    }

    public function staff(){
        return $this->hasMany(Staff::class);
    }

}
