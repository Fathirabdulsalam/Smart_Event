<?php

namespace Database\Seeders;

use App\Models\MasterPage;
use Illuminate\Database\Seeder;

class MasterPageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Syarat & Ketentuan Pembeli
        MasterPage::updateOrCreate(
            ['slug' => 'terms-buyer'],
            [
                'title'     => 'Syarat & Ketentuan Pembeli',
                'content'   => '
                    <h3>1. Pembelian Tiket</h3>
                    <p>Tiket yang sudah dibeli tidak dapat dikembalikan kecuali event dibatalkan.</p>
                    <h3>2. Pembayaran</h3>
                    <p>Pembayaran wajib dilakukan dalam batas waktu yang ditentukan.</p>
                ',
                'is_active' => true
            ]
        );

        // 2. Syarat & Ketentuan Kreator
        MasterPage::updateOrCreate(
            ['slug' => 'terms-creator'],
            [
                'title'     => 'Syarat & Ketentuan Kreator',
                'content'   => '
                    <h3>1. Kewajiban Kreator</h3>
                    <p>Kreator wajib menyelenggarakan event sesuai deskripsi.</p>
                    <h3>2. Pencairan Dana</h3>
                    <p>Dana tiket akan dicairkan H+3 setelah event selesai.</p>
                ',
                'is_active' => true
            ]
        );
    }
}
