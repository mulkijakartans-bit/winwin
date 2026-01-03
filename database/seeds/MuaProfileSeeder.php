<?php

use Illuminate\Database\Seeder;
use App\MuaProfile;

class MuaProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat profil WINWIN Makeup
        $muaProfile = MuaProfile::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'WINWIN Makeup',
                'bio' => 'Makeup Artist profesional dengan pengalaman bertahun-tahun dalam berbagai jenis acara. Spesialisasi dalam makeup wedding, prewedding, dan event khusus lainnya. Komitmen kami adalah membuat Anda tampil sempurna di hari spesial Anda.',
                'experience_years' => 5,
                'specialization' => 'Wedding, Prewedding, Event Makeup',
                'rating' => 0.00,
                'total_reviews' => 0,
                'email' => 'info@winwinmakeup.com',
                'phone' => '081234567890',
                'whatsapp' => '081234567890',
                'instagram' => '@winwinmakeup',
                'facebook' => 'WINWIN Makeup',
            ]
        );

        $this->command->info('WINWIN Makeup profile created!');
    }
}
