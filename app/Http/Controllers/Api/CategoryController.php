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
    // GET /api/categories
    public function index(Request $request)
    {
        // Si 'limit' es enviado, limitamos; si no, trae todas.
        $limit = $request->query('limit');

        $query = Category::query()->orderBy('created_at', 'desc');

        if ($limit && is_numeric($limit)) {
            $query->limit((int) $limit);
        }

        return response()->json($query->get());
    }

    // POST /api/categories
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'img' => 'nullable|image',
        ]);

        $path = null;

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('categories', 'public');
        }

        $category = Category::create([
            'name' => $data['name'],
            'img' => $path,
        ]);

        return response()->json($category, 201);
    }

    // DELETE /api/categories/{id}
    public function destroy(Category $category)
    {
        if ($category->img) {
            Storage::disk('public')->delete($category->img);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}