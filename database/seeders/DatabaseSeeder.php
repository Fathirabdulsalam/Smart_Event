<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
    //     $this->call([
    //         UserSeeder::class,
    //     ]);
    // }

    // ... use models ...
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MasterPageSeeder::class,
        ]);

        // Konfigurasi Default
        \App\Models\Configuration::insert([
            ['key' => 'max_ticket_per_trx', 'value' => '5', 'description' => 'Jumlah maksimal tiket per transaksi'],
            ['key' => 'one_email_one_trx', 'value' => 'true', 'description' => 'Batasi 1 email untuk 1 kali transaksi event yang sama'],
            ['key' => 'one_ticket_one_person', 'value' => 'true', 'description' => 'Wajib isi data pemesan berbeda untuk setiap tiket'],
        ]);

        \App\Models\Configuration::insert([
            ['key' => 'sosmed_instagram', 'value' => 'https://instagram.com/smartevent'],
            ['key' => 'sosmed_twitter', 'value' => 'https://twitter.com/smartevent'],
            ['key' => 'sosmed_linkedin', 'value' => 'https://linkedin.com/company/smartevent'],
            ['key' => 'sosmed_facebook', 'value' => 'https://facebook.com/smartevent'],
        ]);

        // Master Tipe
        \DB::table('master_types')->insert([
            ['name' => 'Online'],
            ['name' => 'Offline'],
            ['name' => 'Hybrid']
        ]);

        // Master Zona
        \DB::table('master_zones')->insert([
            ['name' => 'WIB', 'gmt_offset' => '+07:00'],
            ['name' => 'WITA', 'gmt_offset' => '+08:00'],
            ['name' => 'WIT', 'gmt_offset' => '+09:00']
        ]);

        // Master Kategori Tiket
        \DB::table('master_ticket_categories')->insert([
            ['name' => 'Berbayar', 'is_paid' => true],
            ['name' => 'Bayar Sesukamu', 'is_paid' => true],
            ['name' => 'Gratis', 'is_paid' => false],
        ]);
    }
}
