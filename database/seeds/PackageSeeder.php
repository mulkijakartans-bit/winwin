<?php

use Illuminate\Database\Seeder;
use App\MuaProfile;
use App\MakeupPackage;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $muaProfile = MuaProfile::getWinwinProfile();

        $packages = [
            [
                'name' => 'Paket Basic',
                'description' => 'Paket makeup untuk acara casual atau sehari-hari. Cocok untuk acara santai, gathering, atau makeup sehari-hari yang ingin terlihat fresh dan natural. Makeup artist profesional akan membantu Anda tampil percaya diri dengan look yang sesuai dengan kepribadian Anda.',
                'price' => 500000,
                'duration' => 60,
                'includes' => '• Makeup wajah lengkap (base, concealer, foundation, powder, blush on, highlighter, contouring)
• Eye makeup (eyeshadow, eyeliner, mascara, eyebrow)
• Lip makeup (lip liner, lipstick, lip gloss)
• Styling rambut sederhana
• Konsultasi look sebelum makeup
• Produk makeup berkualitas premium',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Paket Wedding',
                'description' => 'Paket lengkap untuk pengantin dengan touch up. Paket spesial untuk hari pernikahan Anda yang sempurna. Dilengkapi dengan konsultasi mendalam, trial makeup, dan touch up selama acara berlangsung. Makeup artist akan memastikan Anda tampil memukau sepanjang hari pernikahan.',
                'price' => 2500000,
                'duration' => 180,
                'includes' => '• Makeup pengantin lengkap (base, concealer, foundation HD, powder, blush on, highlighter, contouring, setting spray)
• Eye makeup lengkap (eyeshadow premium, eyeliner, false lashes, mascara, eyebrow)
• Lip makeup (lip liner, lipstick matte, lip gloss)
• Styling rambut pengantin (updo atau down style sesuai permintaan)
• Touch up selama acara (1x touch up)
• Konsultasi mendalam sebelum acara
• Trial makeup (1x sesi trial)
• Produk makeup waterproof dan long lasting
• Aksesoris rambut dasar (jika diperlukan)',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Paket Prewedding',
                'description' => 'Paket makeup untuk sesi foto prewedding. Didesain khusus untuk sesi foto prewedding Anda agar hasil foto terlihat sempurna. Makeup artist akan menciptakan beberapa look berbeda yang sesuai dengan konsep foto dan lokasi pemotretan.',
                'price' => 1500000,
                'duration' => 120,
                'includes' => '• Makeup lengkap untuk prewedding (base, concealer, foundation HD, powder, blush on, highlighter, contouring)
• Eye makeup lengkap (eyeshadow, eyeliner, false lashes, mascara, eyebrow)
• Lip makeup (lip liner, lipstick, lip gloss)
• Styling rambut (2x ganti look/style)
• Touch up makeup (2x touch up untuk ganti look)
• Konsultasi konsep makeup sebelum sesi
• Produk makeup HD dan camera ready
• Bantuan styling aksesoris',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Paket Event',
                'description' => 'Paket makeup untuk acara formal atau event khusus. Ideal untuk acara penting seperti seminar, presentasi, pesta, atau acara formal lainnya. Makeup artist akan membantu Anda tampil profesional dan elegan sesuai dengan tema acara.',
                'price' => 800000,
                'duration' => 90,
                'includes' => '• Makeup wajah lengkap (base, concealer, foundation, powder, blush on, highlighter, contouring)
• Eye makeup lengkap (eyeshadow, eyeliner, mascara, eyebrow)
• Lip makeup (lip liner, lipstick, lip gloss)
• Styling rambut (updo atau down style)
• Konsultasi look sebelum makeup
• Produk makeup long lasting
• Touch up ringan jika diperlukan',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Paket Engagement',
                'description' => 'Paket makeup untuk acara lamaran atau tunangan. Momen spesial lamaran Anda layak diabadikan dengan tampilan yang sempurna. Makeup artist akan menciptakan look yang romantis dan elegan untuk momen berharga Anda.',
                'price' => 1200000,
                'duration' => 90,
                'includes' => '• Makeup lengkap untuk engagement (base, concealer, foundation HD, powder, blush on, highlighter, contouring)
• Eye makeup lengkap (eyeshadow, eyeliner, false lashes, mascara, eyebrow)
• Lip makeup (lip liner, lipstick, lip gloss)
• Styling rambut (updo atau down style)
• Konsultasi look sebelum acara
• Produk makeup HD dan camera ready
• Touch up selama acara',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => 'Paket Photoshoot',
                'description' => 'Paket makeup khusus untuk photoshoot profesional. Didesain untuk menghasilkan tampilan yang sempurna di depan kamera. Cocok untuk photoshoot model, portfolio, atau kebutuhan komersial lainnya.',
                'price' => 1000000,
                'duration' => 90,
                'includes' => '• Makeup lengkap untuk photoshoot (base, concealer, foundation HD, powder, blush on, highlighter, contouring)
• Eye makeup lengkap (eyeshadow, eyeliner, false lashes, mascara, eyebrow)
• Lip makeup (lip liner, lipstick, lip gloss)
• Styling rambut (sesuai konsep photoshoot)
• Konsultasi konsep makeup
• Produk makeup HD dan camera ready
• Touch up selama photoshoot',
                'is_active' => true,
                'order' => 6,
            ],
        ];

        foreach ($packages as $packageData) {
            MakeupPackage::firstOrCreate(
                [
                    'mua_profile_id' => $muaProfile->id,
                    'name' => $packageData['name'],
                ],
                $packageData
            );
        }

        $this->command->info('Packages seeder completed successfully!');
        $this->command->info('Total packages: ' . count($packages));
    }
}
