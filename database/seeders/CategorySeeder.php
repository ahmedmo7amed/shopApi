<?php

namespace Database\Seeders;

use Modules\Category\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // حذف جميع البيانات السابقة مع تعطيل قيود المفتاح الأجنبي مؤقتًا
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // إدخال الفئات الرئيسية أولًا
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'All electronic products and accessories.',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accessories = Category::create([
            'name' => 'Accessories',
            'slug' => 'accessories',
            'description' => 'Computer and phone accessories.',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // إدخال الفئات الفرعية مع استخدام الـ parent_id
        Category::create([
            'name' => 'Monitors',
            'slug' => 'monitors',
            'description' => 'Monitors and display devices.',
            'parent_id' => $electronics->id, // تعيين Electronics كفئة رئيسية
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
