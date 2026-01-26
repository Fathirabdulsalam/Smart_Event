<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterLocation extends Model
{
    protected $fillable = ['name', 'type']; 

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
