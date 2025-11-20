<?php

namespace Modules\Product\Http\Controllers\V1;

use Illuminate\Http\Request;
use Modules\Product\Models\Product;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Modules\Category\Models\Category;
use ProductServices;

class ProductController extends Controller
{
    protected ProductServices $productServices;

    public function __construct(ProductServices $productServices)
    {
        $this->productServices = $productServices;
    }
    public function index(Request $request)
    {
       $products = Product::with('options.values')->paginate(12);
       $products = Product::paginate(12);
        $products = Product::with('options.values')
            ->when($request->category_id, function ($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })
            ->when($request->option_id, function ($query) use ($request) {
                return $query->whereHas('options', function ($query) use ($request) {
                    $query->where('id', $request->option_id);
                });
            })
            ->paginate(12);
        $categories = Category::all();


        return view('products.index', compact('products', 'categories'));
        
   
    }
      public function search(Request $request)
    {
        $query = $request->input('query');

        // Search products by name or description
        $products = Product::where('name', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->paginate(10);

        return view('products.index', compact('products'));
    }
    public function show(Product $product)
    {
        $product = Product::findOrFail($product->id);
        $product->load('options.values');
        $category = $product->category;        // Fetch related products (example logic)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
        return view('products.show', compact('product' , 'relatedProducts', 'category'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
    public function isInWishlist($productId)
    {
        return Auth::user()->wishlists()->where('product_id', $productId)->exists();
    }
    public function byCategory($categoryId)
    {
        // Fetch the category by its slug
        $category = Category::where('slug', $categoryId)->firstOrFail();

        // Fetch products by category ID
        $products = Product::where('category_id', $category->id)->paginate(10);

        // Return the view with the products and category
        return view('products.by_category', compact('products', 'category'));
    }




}
