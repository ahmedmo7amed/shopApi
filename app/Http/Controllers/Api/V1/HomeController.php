<?php

namespace App\Http\Controllers\APi\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {   $products = Product::paginate(12);
        $bestSellingProductsLeft = Product::where('is_best_selling', true)->take(4)->get(); // مثال لجلب 4 منتجات من الأفضل مبيعًا
        $categories = Category::with('products')->get();
        $bestSellingProductsRight = Product::where('is_best_selling', true)->take(1)->get(); // منتج واحد فقط من الأفضل مبيعًا
        //$testimonials = Testimonial::all(); // مثال لجلب كل التعليقات من العملاء
        $showSidebar = false;

        return view('index', compact('products','bestSellingProductsLeft','bestSellingProductsRight','categories','showSidebar' ));

    }
    public function home()
    {
//        $tanks = [
//            ['id' => 1, 'name' => 'Tank A', 'description' => 'Description for Tank A', 'price' => 100],
//            ['id' => 2, 'name' => 'Tank B', 'description' => 'Description for Tank B', 'price' => 150],
//            ['id' => 3, 'name' => 'Tank C', 'description' => 'Description for Tank C', 'price' => 200],
//        ];
//        $product = [
//            [
//                'id' => 1,
//                'name' => 'Product A',
//                'description' => 'Description for Product A',
//                'price' => 50,
//                'image_url' => 'https://via.placeholder.com/150?text=Product+A',
//                'capacity' => '400'
//                ,'thickness' => '3',
//                'coating' => "white",
//                'weight' => "500Kgm"
//            ],
//            [
//                'id' => 2,
//                'name' => 'Product B',
//                'description' => 'Description for Product B',
//                'price' => 75,
//                'image_url' => 'https://via.placeholder.com/150?text=Product+B',
//                    'capacity' => '400',
//                    'thickness' => '2' ,
//                    'coating' => "black",
//                'weight' => "500Kgm"
//            ],
//            [
//                'id' => 3,
//                'name' => 'Product C',
//                'description' => 'Description for Product C',
//                'price' => 100,
//                'image_url' => 'https://via.placeholder.com/150?text=Product+C',
//                'capacity' => '500',
//                'thickness' =>'4',
//                'coating'=> "white",
//                'weight' => "500Kgm"
//            ],
//            [
//                'id' => 4,
//                'name' => 'Product D',
//                'description' => 'Description for Product D',
//                'price' => 120,
//                'image_url' => 'https://via.placeholder.com/150?text=Product+D',
//                'capacity' => '600',
//                'thickness' => '3',
//                "coating" => "white",
//                'weight' => "500Kgm"
//            ],
//            [
//                'id' => 5,
//                'name' => 'Product E',
//                'description' => 'Description for Product E',
//                'price' => 200,
//                'image_url' => 'https://via.placeholder.com/150?text=Product+E',
//                'capacity' => '700',
//                'thickness' => '8',
//                "coating" => "white",
//                'weight' => "500Kgm"
//            ],
//
//        ];


        $cartCount = Auth::user() ? Auth::user()->cartItems()->count() : '0';

        $categories = Category::with('products')->get();
        $products_tanks = Product::whereIn('category_id', [4, 5])->get();

        $products = Product::paginate(12);
        $message = "أرغب في طلب المنتج التالي: \n";
        $message .= "السعة: " . $products->get("weight") . " لتر\n";
        $message .= "السماكة: " . $products->get("name") . " مم\n";
        $message .= "الطلاء: " . $products->get("name") . " ريال\n";
        $message .= "الوزن: " . $products->get("name") . " طن";
        $whatsappLink = "https://wa.me/249128436851?text=" . urlencode($message);
        return view('home', compact('categories' , 'products','whatsappLink','cartCount' , 'products_tanks'));
    }
}
