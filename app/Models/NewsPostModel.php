<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsPostModel extends Model
{
    protected $table         = 'news_posts';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'slug', 'title', 'excerpt', 'body_html', 'body_text', 'category', 'cover_image', 'link_url', 'link_label',
        'published_at', 'expires_at', 'status', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;

    /** Adds the homepage button columns (link_url, link_label) when missing. */
    public function ensureLinkColumns(): void
    {
        try {
            $db = db_connect();
            if ($db->tableExists($this->table) && ! $db->fieldExists('link_url', $this->table)) {
                $db->query("ALTER TABLE `{$this->table}` ADD COLUMN `link_url` VARCHAR(500) NULL AFTER `cover_image`, ADD COLUMN `link_label` VARCHAR(60) NULL AFTER `link_url`");
            }
        } catch (\Throwable $e) {
            log_message('error', 'Could not ensure ' . $this->table . ' link columns: ' . $e->getMessage());
        }
    }

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

        $out = [];
        foreach ($builder->findAll() as $row) {
            $out[(string) $row['slug']] ??= $row;
        }

        return array_values($out);
    }

    /** @return array<string,mixed>|null */
    public function findPublishedBySlug(string $slug): ?array
    {
        $row = $this->applyPublicScope()->where('slug', $slug)->first();

        return $row ?: null;
    }
}
