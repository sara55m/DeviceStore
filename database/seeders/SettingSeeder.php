<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // About
            'about.title' => [
                'en' => 'Your trusted partner for security and computing',
                'ar' => 'شريكك الموثوق في الأمن والحاسب',
            ],
            'about.story' => [
                'en' => 'We supply and install CCTV cameras, recorders, and computers for homes, shops, and companies. From a single camera to a full multi-site system, we help you choose the right equipment and set it up properly.',
                'ar' => 'نوفّر ونركّب كاميرات المراقبة وأجهزة التسجيل وأجهزة الكمبيوتر للمنازل والمحلات والشركات. من كاميرا واحدة إلى نظام متكامل لعدة مواقع، نساعدك على اختيار الأجهزة المناسبة وتركيبها بشكل صحيح.',
            ],
            'about.mission' => [
                'en' => 'To make reliable security and computing equipment easy to choose, buy, and install.',
                'ar' => 'أن نجعل اختيار وشراء وتركيب أجهزة الأمن والحاسب الموثوقة أمرًا سهلًا.',
            ],
            'about.vision' => [
                'en' => 'To be the first name people think of when they need cameras or computers.',
                'ar' => 'أن نكون الاسم الأول الذي يخطر ببال الناس عند الحاجة إلى كاميرات أو أجهزة كمبيوتر.',
            ],

            // Contact (replace these placeholders with your real details)
            'contact.phone'    => '+20 100 000 0000',
            'contact.whatsapp' => '+20 100 000 0000',
            'contact.email'    => 'info@example.com',
            'contact.maps_url' => null,
            'contact.address'  => [
                'en' => '12 Example Street, Damietta, Egypt',
                'ar' => '12 شارع المثال، دمياط، مصر',
            ],
            'contact.hours' => [
                'en' => 'Sat–Thu, 10:00 AM – 9:00 PM',
                'ar' => 'السبت – الخميس، 10:00 ص – 9:00 م',
            ],
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
