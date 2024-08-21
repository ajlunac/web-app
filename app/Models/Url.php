<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Url extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'url_principal',
        'url_contingency',
        'date_deployment',
        'active',
    ];

    public function server(): BelongsTo {
        return $this->belongsTo(Server::class);
    }
}
