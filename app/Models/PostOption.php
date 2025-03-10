<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostOption extends Model
{
    protected $fillable = [
        'post_id',
        'option',
        'counts'
    ];
}
