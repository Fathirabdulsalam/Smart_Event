<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTicketCategory extends Model
{
    protected $fillable = ['name', 'type']; // type: Physical / Virtual

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
