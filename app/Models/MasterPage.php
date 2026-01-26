<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPage extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'is_active'];
}
