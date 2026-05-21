<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    // GET /api/articles (Obtener todos o solo con stock)
    public function index(Request $request)
    {
        $query = Article::query();

        // Si envías ?in_stock=true en la URL, te devuelve solo los que tienen stock > 0
        if ($request->query('in_stock') === 'true') {
            $query->where('stock', '>', 0);
        }

        return response()->json($query->get());
    }

    // GET /api/articles/{id} (Vista detalle / editar)
    public function show($id)
    {
        $article = Article::with('category')->find($id);

        if (!$article) {
            return response()->json(['message' => 'Artículo no encontrado'], 404);
        }

        return response()->json($article);
    }

    // POST /api/articles (Crear)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'on_sale' => 'boolean',
            'item_state' => 'nullable|integer',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'nullable|integer|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048', // Validación de imágenes
        ]);

        // Manejar subida de hasta 5 imágenes
        $imagePaths = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            // Limitamos a 5 iteraciones máximo
            foreach (array_slice($files, 0, 5) as $file) {
                $imagePaths[] = $file->store('articles', 'public');
            }
        }
        $data['images'] = $imagePaths;

        $article = Article::create($data);

        return response()->json($article, 201);
    }

    // POST /api/articles/{id} (Actualizar - Usamos POST para soportar FormData con imágenes)
    public function update(Request $request, $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Artículo no encontrado'], 404);
        }

        // Validación similar a la de store...
        $data = $request->all(); // Asegúrate de validar en un entorno real (FormRequest)

        // Si se suben nuevas imágenes
        if ($request->hasFile('images')) {
            // Opcional: Eliminar las imágenes antiguas del servidor
            if (is_array($article->images)) {
                foreach ($article->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $imagePaths = [];
            $files = $request->file('images');
            foreach (array_slice($files, 0, 5) as $file) {
                $imagePaths[] = $file->store('articles', 'public');
            }
            $data['images'] = $imagePaths;
        }

        $article->update($data);

        return response()->json($article);
    }

    // DELETE /api/articles/{id} (Eliminar)
    public function destroy($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Artículo no encontrado'], 404);
        }

        // Borrar archivos físicos
        if (is_array($article->images)) {
            foreach ($article->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $article->delete();

        return response()->json(['message' => 'Artículo eliminado exitosamente']);
    }

    // POST /api/articles/{id}/buy (Comprar artículo y reducir stock)
    public function buy(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Artículo no encontrado'], 404);
        }

        $quantityToBuy = $request->input('quantity');

        // Comprobar si hay stock suficiente
        if ($article->stock < $quantityToBuy) {
            return response()->json([
                'message' => 'Stock insuficiente',
                'available_stock' => $article->stock
            ], 400);
        }

        // Reducir stock y aumentar ventas
        $article->decrement('stock', $quantityToBuy);
        $article->increment('sell_count', $quantityToBuy);

        return response()->json([
            'message' => 'Compra realizada con éxito',
            'article' => $article // Devolvemos el artículo actualizado
        ]);
    }
}