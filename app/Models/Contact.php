<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */
class Contact extends Model
{
    use HasFactory;

    protected $casts = [
        'user_id' => 'integer',
    ];

}
