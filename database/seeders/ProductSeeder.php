<?php

namespace Database\Seeders;

use Modules\Product\Models\Product;
use Modules\Category\Models\Category;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Ensure categories exist before using their IDs, if they don't exist, create them
       $laptopsCategory = Category::firstOrCreate(
           ['name' => 'Laptops'],
           ['slug' => 'laptops', 'description' => 'Laptops and related accessories.',
               'is_active' => true, 'created_at' => now(), 'updated_at' => now()]
       );

       $accessoriesCategory = Category::firstOrCreate(
           ['name' => 'Computer Accessories'],
           ['slug' => 'computer-accessories', 'description' => 'Accessories for computers and peripherals.', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]
       );

       $monitorsCategory = Category::firstOrCreate(
           ['name' => 'Monitors'],
           ['slug' => 'monitors', 'description' => 'Monitors and display devices.', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]
       );

       $networkingCategory = Category::firstOrCreate(
           ['name' => 'Networking'],
           ['slug' => 'networking', 'description' => 'Networking products and accessories.', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]
       );

       $products = [
           [
               'name' => '4K Monitor 77"',
               'slug' => '4k-monitor-77',
               'sku' => 'MON-4K-7',
               'barcode' => '789123456',
               'description' => '27-inch 4K Ultra HD monitor with HDR support',
               'price' => 399.99,
               'discount_price' => 349.99,
               'cost' => 299.99,
               'brand' => 'Samsung',
               'weight' => 5.5,
               'dimensions' => '24 x 14 x 2 inches',
               'tags' => json_encode(['4k', 'monitor', 'hdr']),
               'views_count' => 150,
               'is_hot' => true,
               'featured' => true,
               'rating' => 4,
               'stock' => 50,
               'status' => true,
               'category_id' => $monitorsCategory->id,
               'expires_at' => Carbon::now()->addYear(),
               'stock_status' => 'In Stock',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now(),
           ],
           [
               'name' => 'Gaming_Laptop 16.6"',
               'slug' => 'gaming-laptop-16-6',
               'sku' => 'LAP-GAM-5',
               'barcode' => '123456789',
               'description' => '15.6-inch gaming laptop with Intel i7 processor and GTX 1660',
               'price' => 1299.99,
               'discount_price' => 1199.99,
               'cost' => 1000.00,
               'brand' => 'Dell',
               'weight' => 2.3,
               'dimensions' => '14 x 9.5 x 0.8 inches',
               'tags' => json_encode(['gaming', 'laptop', 'intel']),
               'views_count' => 200,
               'is_hot' => false,
               'featured' => true,
               'rating' => 5,
               'stock' => 30,
               'status' => true,
               'category_id' => $laptopsCategory->id,
               'expires_at' => Carbon::now()->addMonths(6),
               'stock_status' => 'In Stock',
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now(),
           ],
       ];

       foreach ($products as $product) {
           // Insert the product without the 'image' column
           $createdProduct = Product::create($product);

           // Insert the associated image into the product_images table
           DB::table('product_images')->insert([
               'product_id' => $createdProduct->id,  // Linking image to the product
               'image_path' => 'product-images/' . strtolower(str_replace(' ', '_', $createdProduct->name)) . '.jpg', // Example image path
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now(),
           ]);
       }
    }
}
