<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Http\Controllers\Controller;

class ConfigurationController extends Controller
{
     public function index()
    {
        // Ambil semua konfigurasi dan jadikan Key-Value array agar mudah dipanggil di View
        // Hasilnya: ['max_ticket_per_trx' => '5', 'one_email_one_trx' => 'true', ...]
        $configs = Configuration::pluck('value', 'key')->toArray();

        return view('admin.configuration.index', compact('configs'));
    }

    public function update(Request $request)
    {
        // ... validasi yang lama ...

        // List key yang diizinkan untuk diupdate (TAMBAHKAN SOSMED DISINI)
        $keys = [
            'max_ticket_per_trx',
            'one_email_one_trx',
            'one_ticket_one_person',
            // Key Baru:
            'sosmed_instagram',
            'sosmed_twitter',
            'sosmed_linkedin',
            'sosmed_facebook'
        ];

        // Loop untuk update database
        foreach ($keys as $key) {
            if ($request->has($key)) {
                Configuration::updateOrCreate(
                    ['key' => $key], 
                    ['value' => $request->input($key)]
                );
            }
        }

        return redirect()->route('configuration.index')->with('success', 'Konfigurasi sistem berhasil diperbarui!');
    }
}
