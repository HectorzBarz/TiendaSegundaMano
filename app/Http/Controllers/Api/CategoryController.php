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
        $limit = $request->query('limit');

        $query = Category::with('articles')->orderBy('created_at', 'desc');

        if ($limit && is_numeric($limit)) {
            $query->limit((int) $limit);
        }

        return response()->json($query->get());
    }

    // GET /api/categories/{id}
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        return response()->json($category);
    }

    // POST /api/categories/{id} (Actualización)
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $data = $request->validated();

        // Si se sube una nueva imagen
        if ($request->hasFile('image')) {
            // Opcional: Eliminar la imagen antigua si existe
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            // Guardar nueva imagen
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return response()->json($category);
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
    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Not found'], 404);
        }

        // Si guardas imágenes en el servidor, recuerda borrar el archivo físico aquí

        $category->delete();
        return response()->json(['message' => 'Categoría eliminada']);
    }
}