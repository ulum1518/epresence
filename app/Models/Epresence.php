<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Epresence extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'id_users',
        'type',
        'is_approve',
        'waktu'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
