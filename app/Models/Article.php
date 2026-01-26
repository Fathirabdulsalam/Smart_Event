<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'thumbnail_path', 
        'author_id', 'status', 
        'category_id' // Tambahan Baru
    ];
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
