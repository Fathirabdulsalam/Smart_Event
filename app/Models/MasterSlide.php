<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSlide extends Model
{
    protected $fillable = ['title', 'description', 'image_path', 'link_url', 'order', 'is_active'];
}
