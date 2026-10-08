<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['ip-cameras',   'IP cameras',           'كاميرات IP'],
            ['analog-cameras','Analog (HD) cameras', 'كاميرات تناظرية (HD)'],
            ['recorders',    'NVR / DVR recorders',  'أجهزة التسجيل NVR / DVR'],
            ['storage',      'Surveillance hard drives', 'هارديسكات المراقبة'],
            ['laptops',      'Laptops',              'لابتوبات'],
            ['desktops',     'Desktop computers',    'أجهزة كمبيوتر مكتبية'],
            ['accessories',  'Accessories & cables', 'ملحقات وكابلات'],
        ];

        foreach ($categories as $i=>[$slug, $name, $name_ar]) {
            Category::updateOrCreate([
                'slug' => $slug,
            ], [
                'name' => ['en' => $name, 'ar' => $name_ar],
                'sort_order' => $i+1,
            ]);
        }
    }
}
