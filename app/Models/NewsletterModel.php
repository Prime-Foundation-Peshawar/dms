<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsletterModel extends Model
{
    protected $table         = 'newsletters';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'title', 'date_label', 'pdf_url', 'cover_url', 'published_at', 'sort_order', 'is_active',
    ];

    /** @return list<array<string,mixed>> */
    public function activeOrdered(): array
    {
        return $this->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('published_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();
    }
}
