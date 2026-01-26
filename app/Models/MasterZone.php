<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterZone extends Model
{
    protected $fillable = ['name', 'gmt_offset']; // contoh: WIB, +07:00

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
