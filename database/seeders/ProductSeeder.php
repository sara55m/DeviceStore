<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');

        // [category slug, sku, name en, name ar, description en, description ar, price EGP|null, featured, specs]
        $products = [
            ['ip-cameras', 'CAM-IP-4D', 'Hikvision 4MP IP Dome Camera', 'كاميرا قبة IP هيكفيجن 4 ميجابكسل',
             'Indoor/outdoor dome camera with clear night vision and PoE power.', 'كاميرا قبة للاستخدام الداخلي والخارجي برؤية ليلية واضحة وتعمل بتقنية PoE.',
             1850, true, [['Resolution', '4MP'], ['Lens', '2.8mm'], ['IR range', '30m'], ['Power', 'PoE']]],

            ['ip-cameras', 'CAM-IP-5B', 'Dahua 5MP IP Bullet Camera', 'كاميرا بوليت IP داهوا 5 ميجابكسل',
             'Weatherproof bullet camera with built-in microphone.', 'كاميرا بوليت مقاومة للعوامل الجوية مع ميكروفون مدمج.',
             2400, true, [['Resolution', '5MP'], ['Lens', '3.6mm'], ['IR range', '50m'], ['Protection', 'IP67']]],

            ['analog-cameras', 'CAM-AN-2B', '2MP HD Bullet Camera', 'كاميرا بوليت HD 2 ميجابكسل',
             'Budget-friendly analog HD camera for small shops and homes.', 'كاميرا HD اقتصادية مناسبة للمحلات الصغيرة والمنازل.',
             650, false, [['Resolution', '2MP'], ['Signal', 'AHD/TVI/CVI'], ['IR range', '20m']]],

            ['recorders', 'REC-NVR-8P', '8-Channel PoE NVR', 'جهاز تسجيل NVR بـ 8 قنوات مع PoE',
             'Network video recorder with 8 PoE ports and 4K output.', 'جهاز تسجيل شبكي بـ 8 منافذ PoE وخرج بدقة 4K.',
             4200, false, [['Channels', '8'], ['PoE ports', '8'], ['Max storage', '1 x 10TB']]],

            ['recorders', 'REC-DVR-16', '16-Channel Hybrid DVR', 'جهاز تسجيل DVR هجين بـ 16 قناة',
             'Works with analog HD and IP cameras.', 'يعمل مع كاميرات التناظري HD وكاميرات IP.',
             3100, false, [['Channels', '16'], ['Max resolution', '5MP'], ['Max storage', '2 x 8TB']]],

            ['storage', 'HDD-SV-2T', '2TB Surveillance Hard Drive', 'هارد ديسك مراقبة 2 تيرابايت',
             'Designed for 24/7 recording.', 'مصمم للتسجيل المستمر على مدار الساعة.',
             3100, false, [['Capacity', '2TB'], ['Interface', 'SATA III'], ['Speed', '5400 RPM']]],

            ['laptops', 'LAP-V15-I5', 'Dell Vostro 15 Laptop, Core i5', 'لابتوب ديل فوسترو 15 بمعالج Core i5',
             'Business laptop for everyday office work.', 'لابتوب أعمال مناسب لمهام المكتب اليومية.',
             24900, true, [['Processor', 'Intel Core i5'], ['RAM', '16GB'], ['Storage', '512GB SSD'], ['Screen', '15.6" FHD']]],

            ['desktops', 'DSK-M70', 'Lenovo ThinkCentre M70 Desktop', 'كمبيوتر مكتبي لينوفو ثينك سنتر M70',
             'Compact desktop for offices and reception desks.', 'كمبيوتر مكتبي صغير الحجم للمكاتب ومكاتب الاستقبال.',
             18500, false, [['Processor', 'Intel Core i5'], ['RAM', '8GB'], ['Storage', '256GB SSD']]],

            ['accessories', 'ACC-CAT6-305', 'Cat6 Network Cable, 305m Box', 'كابل شبكة Cat6 علبة 305 متر',
             'Pure copper cable for camera and network installations.', 'كابل نحاس نقي لتركيبات الكاميرات والشبكات.',
             2800, false, [['Length', '305m'], ['Type', 'Cat6 UTP'], ['Conductor', 'Copper']]],

            // No price: the customer asks for a quote
            ['ip-cameras', 'PKG-8CAM', 'Complete 8-Camera CCTV Package', 'باقة مراقبة كاملة بـ 8 كاميرات',
             'Eight IP cameras, an NVR, a hard drive, cables, and installation. Request a quote for your site.', 'ثماني كاميرات IP مع جهاز تسجيل وهارد وكابلات والتركيب. اطلب عرض سعر حسب موقعك.',
             null, true, [['Cameras', '8 x 4MP'], ['Recorder', '8-channel NVR'], ['Storage', '4TB'], ['Installation', 'Included']]],
        ];

        foreach ($products as [$cat, $sku, $nameEn, $nameAr, $descEn, $descAr, $price, $featured, $specs]) {
            Product::firstOrCreate(['sku' => $sku], [
                'category_id' => $categories[$cat],
                'slug'        => Str::slug($nameEn),
                'name'        => ['en' => $nameEn, 'ar' => $nameAr],
                'description' => ['en' => $descEn, 'ar' => $descAr],
                'price'       => $price === null ? null : $price * 100, // stored in piasters
                'specs'       => array_map(fn ($s) => ['key' => $s[0], 'value' => $s[1]], $specs),
                'is_active'   => true,
                'is_featured' => $featured,
            ]);
        }
    }
}
