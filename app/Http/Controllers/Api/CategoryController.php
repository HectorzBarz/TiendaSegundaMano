<?php

// app/Http/Controllers/Api/CategoryController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::latest()->get();
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $category = Category::create($data);

        return response()->json($category, 201);
    }

    public function show($id)
    {
        return Category::findOrFail($id);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('image')) {

            if ($category->image) {
                $old = str_replace('/storage/', '', $category->image);
                Storage::disk('public')->delete($old);
            }

            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $category->update($data);

        return response()->json($category->fresh());
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image) {
            $old = str_replace('/storage/', '', $category->image);
            Storage::disk('public')->delete($old);
        }

        $category->delete();

        return response()->json(['message' => 'Categoría eliminada']);
    }
}