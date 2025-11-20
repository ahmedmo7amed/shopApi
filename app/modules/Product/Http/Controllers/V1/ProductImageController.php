<?php

namespace Modules\Product\Http\Controllers\APi\V1;

use App\Http\Controllers\Controller;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_primary' => 'boolean',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $path = $request->file('image')->store('products', 'public');

        // If this is primary, remove primary status from other images
        if ($request->input('is_primary', false)) {
            $product->images()->where('is_primary', true)->update(['is_primary' => false]);
        }

        $image = $product->images()->create([
            'image_path' => $path,
            'is_primary' => $request->input('is_primary', false),
            'alt_text' => $request->input('alt_text'),
            'sort_order' => $product->images()->max('sort_order') + 1,
        ]);

        return response()->json([
            'message' => 'Image uploaded successfully',
            'image' => $image
        ]);
    }

    public function destroy(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }

    public function reorder(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:product_images,id',
            'images.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->images as $imageData) {
            $product->images()
                ->where('id', $imageData['id'])
                ->update(['sort_order' => $imageData['sort_order']]);
        }

        return response()->json(['message' => 'Images reordered successfully']);
    }

    public function setPrimary(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product->images()->where('is_primary', true)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return response()->json(['message' => 'Primary image set successfully']);
    }
}
