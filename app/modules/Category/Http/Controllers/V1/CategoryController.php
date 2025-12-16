<?php

namespace Modules\Category\Http\Controllers\V1;

use App\Filament\Resources\CategoryResource;
use App\Http\Controllers\Controller;
use Modules\Category\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Category\Http\Requests\V1\CategoryRequest;
use Modules\Category\Services\V1\CategoryServices;

class CategoryController extends Controller
{

    protected $categoryServices;
    public function __construct(CategoryServices $categoryServices)
    {
        $this->categoryServices = $categoryServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryServices->getAllCategories();
        return response()->json(CategoryResource::collection($categories)
            ->additional(['meta' => ['total' => $categories->count()]]),
        200);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $categories = Category::pluck('name', 'id')->prepend('None', null);
    //     return view('categories.create', compact('categories'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        // $validated = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'parent_id' => 'nullable|exists:categories,id',
        //     'status' => 'boolean',

        // ]);
        // $validated['slug'] = Str::slug($validated['name']);
        // Category::create($validated);
        // Category::create($request->validated() +
        //  ['slug' => Str::slug($request->name)]);
        $this->categoryServices->createCategory($request->validated() +
         ['slug' => Str::slug($request->name)]);
        return response()->json([
            'message' => 'Category created successfully.'],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->categoryServices->getCategoryById($id);
        return response()->json(new CategoryResource($category), 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->categoryServices->updateCategory($id, $request->all());
        return response()->json([
            'message' => 'Category updated successfully.'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->categoryServices->deleteCategory($id);
        return response()->json([
            'message' => 'Category deleted successfully.'],200);
    }
}
