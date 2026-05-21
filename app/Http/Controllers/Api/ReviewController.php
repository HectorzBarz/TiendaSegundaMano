<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Article $article)
    {
        // Devolvemos las reseñas del artículo junto con los datos públicos del usuario
        return response()->json($article->reviews()->with('user')->get());
    }

    public function store(Request $request, Article $article)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = $request->user()->id; // Protegido mediante Sanctum

        // Validamos si ya comentó
        $exists = Review::where('article_id', $article->id)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Ya has calificado este artículo.'], 422);
        }

        $review = DB::transaction(function () use ($request, $article, $userId) {
            // 1. Guardamos la reseña
            $newReview = Review::create([
                'article_id' => $article->id,
                'user_id' => $userId,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            // 2. Recalculamos acumuladores en el artículo de manera segura
            $stats = Review::where('article_id', $article->id)
                ->selectRaw('COUNT(*) as total_count, AVG(rating) as avg_rating')
                ->first();

            $article->update([
                'product_count' => $stats->total_count,
                'product_avg' => round($stats->avg_rating, 2)
            ]);

            return $newReview;
        });

        return response()->json($review->load('user'), 201);
    }

    public function destroy(Request $request, Review $review)
    {
        // 🛡️ Seguridad: Verificamos si el usuario logueado es el dueño de la reseña
        if ($request->user()->id !== $review->user_id) {
            return response()->json([
                'message' => 'No tienes permisos para eliminar esta reseña.'
            ], 403); // 403 Forbidden
        }

        $review->delete();

        return response()->json([
            'message' => 'Reseña eliminada correctamente.'
        ], 200);
    }
}