<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'published_date', 'title', 'author', 'content', 'image'
    ];    
}
