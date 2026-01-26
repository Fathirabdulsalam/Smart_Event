<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    /**
     * Helper static untuk mengambil value dengan cepat di controller lain/view.
     * Contoh penggunaan: Configuration::getBy('max_ticket_per_trx')
     */
    protected $fillable = ['key', 'value', 'description'];

    public static function getBy($key, $default = null)
    {
        $config = self::where('key', $key)->first();
        return $config ? $config->value : $default;
    }
}
