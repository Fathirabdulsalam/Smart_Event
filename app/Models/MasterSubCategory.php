<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSubCategory extends Model
{
    protected $table = 'master_subcategories'; // Definisi eksplisit agar aman
    protected $fillable = ['category_id', 'name'];

    // Relasi ke Parent Category
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
