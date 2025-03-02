<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['post_id', 'option', 'counts'];
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
