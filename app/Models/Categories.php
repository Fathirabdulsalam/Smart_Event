<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'thumbnail',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'category_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}
