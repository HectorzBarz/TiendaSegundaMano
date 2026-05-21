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
        if (!$request->user()?->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Se cambia 'image' por 'file' para evitar falsos negativos con el formato de vectores XML (SVG)
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'on_sale' => 'nullable|in:1,0,true,false',
            'item_state' => 'nullable|integer',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'nullable|integer|min:0',
            'images' => 'nullable|array|max:5',
            'images.*' => 'file|mimes:jpeg,png,jpg,webp,svg|max:2048', // Añadido soporte SVG aquí
        ]);

        if (isset($data['on_sale'])) {
            $data['on_sale'] = filter_var($data['on_sale'], FILTER_VALIDATE_BOOLEAN);
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            foreach (array_slice($files, 0, 5) as $file) {
                $imagePaths[] = $file->store('articles', 'public');
            }
        }

        $data['images'] = $imagePaths;

        $article = Article::create($data);

        return response()->json($article, 201);
    }

    // POST /api/articles/{id} (Actualizar)
    public function update(Request $request, $id)
    {
        if (!$request->user()?->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $article = Article::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'on_sale' => 'nullable|boolean',
            'item_state' => 'nullable|integer',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'nullable|integer|min:0',

            // imágenes
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',

            'new_images' => 'nullable|array|max:5',
            'new_images.*' => 'file|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $data['on_sale'] = filter_var($data['on_sale'] ?? false, FILTER_VALIDATE_BOOLEAN);

        /**
         * 1. mantener imágenes existentes seleccionadas
         */
        $finalImages = $data['existing_images'] ?? [];

        /**
         * 2. añadir nuevas imágenes
         */
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $finalImages[] = $file->store('articles', 'public');
            }
        }

        /**
         * 3. borrar del disco las eliminadas
         */
        $oldImages = is_array($article->images) ? $article->images : [];

        foreach ($oldImages as $img) {
            if (!in_array($img, $finalImages)) {
                Storage::disk('public')->delete($img);
            }
        }

        $data['images'] = $finalImages;

        $article->update($data);

        return response()->json($article);
    }

    // DELETE /api/articles/{id} (Eliminar)
    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Artículo no encontrado'], 404);
        }

        if (is_array($article->images)) {
            foreach ($article->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $article->delete();

        return response()->json(['message' => 'Artículo eliminado exitosamente']);
    }

    // POST /api/articles/{id}/buy (Comprar)
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

        if ($article->stock < $quantityToBuy) {
            return response()->json([
                'message' => 'Stock insuficiente',
                'available_stock' => $article->stock
            ], 400);
        }

        $article->decrement('stock', $quantityToBuy);
        $article->increment('sell_count', $quantityToBuy);

        return response()->json([
            'message' => 'Compra realizada con éxito',
            'article' => $article
        ]);
    }
}