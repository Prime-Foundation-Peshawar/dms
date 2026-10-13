<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsPostModel extends Model
{
    protected $table         = 'news_posts';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'slug', 'title', 'excerpt', 'body_html', 'body_text', 'category', 'cover_image',
        'published_at', 'expires_at', 'status', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;

    /** Apply public visibility: published and not past expiry. */
    protected function applyPublicScope()
    {
        $now = date('Y-m-d H:i:s');

        return $this->where('status', 'published')
            ->groupStart()
                ->where('expires_at', null)
                ->orWhere('expires_at >', $now)
            ->groupEnd();
    }

    public static function isExpired(?array $row): bool
    {
        if (! $row || empty($row['expires_at'])) {
            return false;
        }

        return strtotime((string) $row['expires_at']) <= time();
    }

    /** Status shown in ACP: published + past expires_at ⇒ expired. */
    public static function effectiveStatus(?array $row): string
    {
        $status = (string) ($row['status'] ?? 'draft');
        if ($status === 'published' && self::isExpired($row)) {
            return 'expired';
        }

        return $status !== '' ? $status : 'draft';
    }

    /** @return list<array<string,mixed>> */
    public function published(int $limit = 0): array
    {
        $builder = $this->applyPublicScope()->orderBy('published_at', 'DESC');
        if ($limit > 0) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /** @return array<string,mixed>|null */
    public function findPublishedBySlug(string $slug): ?array
    {
        $row = $this->applyPublicScope()->where('slug', $slug)->first();

        return $row ?: null;
    }
}
