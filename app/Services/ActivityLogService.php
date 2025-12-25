<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Article;
use App\Models\User;

class ActivityLogService
{
    public static function logArticleCreated(Article $article): void
    {
        self::log('create', $article, null, $article->only(['title', 'content', 'category', 'image_url']));
    }

    public static function logArticleUpdated(Article $article, array $oldValues): void
    {
        self::log('update', $article, $oldValues, $article->only(['title', 'content', 'category', 'image_url']));
    }

    public static function logArticleDeleted(Article $article): void
    {
        self::log('delete', $article, $article->only(['title', 'content', 'category', 'image_url']), null);
    }

    public static function logArticleRestored(Article $article): void
    {
        self::log('restore', $article, null, $article->only(['title', 'content', 'category', 'image_url']));
    }

    public static function getLogsFor(Article $article)
    {
        return ActivityLog::where('model_type', Article::class)
            ->where('model_id', $article->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public static function logGeneric(string $action, string $modelType, int $modelId, ?array $oldValues, ?array $newValues, ?string $description = null): void
    {
        ActivityLog::create([
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'user_id' => auth('api')->id(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'description' => $description ?? "{$modelType} {$action}",
        ]);
    }

    protected static function log(string $action, Article $article, ?array $oldValues, ?array $newValues): void
    {
        self::logGeneric($action, Article::class, $article->id, $oldValues, $newValues, "Article {$action}");
    }

    public static function logAdminLogin(User $user, string $description): void
    {
        ActivityLog::create([
            'action' => 'admin_login',
            'model_type' => User::class,
            'model_id' => $user->id,
            'user_id' => $user->id,
            'old_values' => null,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'description' => $description,
        ]);
    }
}
