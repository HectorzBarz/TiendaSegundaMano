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
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        $path = null;

        if ($request->hasFile('image')) {
            // 1. Guardar el archivo en 'storage/app/public/categories'
            // store() devuelve solo el path relativo: 'categories/nombre-archivo.jpg'
            $path = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create([
            'name' => $data['name'],
            // 2. Guardamos solo el path relativo, no la URL completa
            'image' => $path,
        ]);

        return response()->json($category, 201);
    }

    // DELETE /api/categories/{id}
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Not found'], 404);
        }

        // Si guardas imágenes en el servidor, recuerda borrar el archivo físico aquí

        $category->delete();
        return response()->json(['message' => 'Categoría eliminada']);
    }
}