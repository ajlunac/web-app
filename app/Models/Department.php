<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'extension_phone'];

    public function employees(): HasMany{
        return $this->hasMany(Employee::class);
    }

    public function servers(): HasMany {
        return $this->hasMany(Server::class);
    }

    public function tests(): HasMany {
        return $this->hasMany(Test::class);
    }

}
