<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('user:id,name')->latest()->paginate(10);

        return response()->json($articles);
    }

    public function show(Article $article)
    {
        $article->load('user:id,name');

        return response()->json($article);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'string', Rule::in(['Game', 'Politik', 'Game Politik'])],
            'image_url' => ['required', 'url'],
        ]);

        DB::beginTransaction();
        try {
            $article = Article::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'category' => $validated['category'],
                'image_url' => $validated['image_url'],
                'user_id' => auth('api')->id(),
            ]);

            // Log the creation
            ActivityLogService::logArticleCreated($article);

            DB::commit();

            return response()->json([
                'message' => 'Artikel berhasil dibuat',
                'article' => $article
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create article: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Gagal membuat artikel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Article $article)
    {
        $user = auth('api')->user();
        $isAdmin = $user && (bool) ($user->is_admin ?? false);
        $isOwner = $article->user_id === auth('api')->id();
        if (! $isOwner && ! $isAdmin) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk mengubah artikel ini'
            ], 403);
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'content' => ['sometimes', 'required', 'string'],
            'category' => ['sometimes', 'required', 'string', Rule::in(['Game', 'Politik', 'Game Politik'])],
            'image_url' => ['sometimes', 'required', 'url'],
        ]);

        DB::beginTransaction();
        try {
            // Store old values for logging
            $oldValues = $article->only(['title', 'content', 'category', 'image_url']);

            $article->update($validated);

            // Log the update
            ActivityLogService::logArticleUpdated($article, $oldValues);

            DB::commit();

            return response()->json([
                'message' => 'Artikel berhasil diperbarui',
                'article' => $article
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update article: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Gagal memperbarui artikel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Article $article)
    {

        // Check if article can be deleted (business logic)
        if (!$article->canBeDeleted()) {
            return response()->json([
                'message' => 'Artikel ini tidak dapat dihapus karena memiliki dependensi'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Log the deletion BEFORE deleting (so we still have the data)
            ActivityLogService::logArticleDeleted($article);

            // Permanently delete the article
            $article->forceDelete();

            DB::commit();

            Log::info("Article deleted successfully", [
                'article_id' => $article->id,
                'title' => $article->title,
                'user_id' => auth('api')->id(),
                'deleted_at' => now()
            ]);

            return response()->json([
                'message' => 'Artikel berhasil dihapus secara permanen',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete article: ' . $e->getMessage(), [
                'article_id' => $article->id,
                'user_id' => auth('api')->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $msg = 'Gagal menghapus artikel. ';
            $status = 500;
            if (str_contains($e->getMessage(), 'foreign key') || str_contains($e->getMessage(), 'constraint')) {
                $msg .= 'Artikel terkait data lain sehingga tidak dapat dihapus.';
                $status = 409;
            } else {
                $msg .= 'Silakan coba lagi atau hubungi admin.';
            }

            return response()->json([
                'message' => $msg,
                'error' => config('app.debug') ? $e->getMessage() : null
            ], $status);
        }
    }

    /**
     * Restore a soft-deleted article (optional feature)
     */
    public function restore($id)
    {
        $article = Article::withTrashed()->findOrFail($id);

        if ($article->user_id !== auth('api')->id()) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk mengembalikan artikel ini'
            ], 403);
        }

        DB::beginTransaction();
        try {
            $article->restore();

            // Log the restoration
            ActivityLogService::logArticleRestored($article);

            DB::commit();

            return response()->json([
                'message' => 'Artikel berhasil dikembalikan',
                'article' => $article
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to restore article: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Gagal mengembalikan artikel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activity logs for an article
     */
    public function logs(Article $article)
    {
        $logs = ActivityLogService::getLogsFor($article);

        return response()->json([
            'article_id' => $article->id,
            'logs' => $logs
        ]);
    }
}
