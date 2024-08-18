<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['country_id', 'state_code', 'name'];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function staff(){
        return $this->hasMany(Staff::class);
    }

    public function cities(){
        return $this->hasMany(City::class);
    }
}
