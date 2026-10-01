<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class News extends PublicController
{
    public function index(): string
    {
        require_once ROOTPATH . 'legacy/includes/cms-content.php';
        $posts = dms_news_published();
        $posts = array_values(array_filter($posts, static function ($p) {
            $slug = (string) ($p['slug'] ?? '');
            $homeOnly = [
                'mphil-basic-medical-sciences-fall-2026',
                'positions-vacant-sep-2026',
                'explore-open-roles-online',
                '19th-umr-research-conference',
                'sports-society-highlight',
            ];

            return ! in_array($slug, $homeOnly, true);
        }));
        $featured = null;
        foreach ($posts as $p) {
            if (! empty($p['is_featured'])) {
                $featured = $p;
                break;
            }
        }
        if (! $featured && $posts) {
            $featured = $posts[0];
        }
        $cats = [];
        foreach ($posts as $p) {
            $c = (string) ($p['category'] ?? 'general');
            $cats[$c] = ($cats[$c] ?? 0) + 1;
        }
        $total = count($posts);

        return $this->renderPage('pages/all-news', compact('posts', 'featured', 'cats', 'total'));
    }

    public function single()
    {
        require_once ROOTPATH . 'legacy/includes/cms-content.php';

        $slug = trim((string) ($this->request->getGet('slug') ?? $this->request->getGet('n') ?? ''));
        $post = $slug !== '' ? dms_news_by_slug($slug) : null;
        if (! $post) {
            $all = dms_news_published();
            foreach ($all as $p) {
                if (! empty($p['is_featured'])) {
                    $post = $p;
                    break;
                }
            }
            if (! $post && $all) {
                $post = $all[0];
            }
        }
        if (! $post) {
            return $this->redirectLegacy('all-news', 302);
        }

        $related = dms_news_related((string) ($post['category'] ?? 'general'), (int) ($post['id'] ?? 0), 3);
        $page_title = ($post['title'] ?? 'News') . ' | Department of Medical Sciences';
        $page_description = (string) ($post['excerpt'] ?? $page_title);
        $href = dms_cms_public_href($post, 'single-news');
        $gradient = $post['card_gradient'] ?? 'linear-gradient(135deg,#0A1628,#1a3a6b)';
        $icon = $post['card_icon'] ?? 'bi-newspaper';

        return $this->renderPage('pages/single-news', compact(
            'post',
            'related',
            'page_title',
            'page_description',
            'href',
            'gradient',
            'icon'
        ));
    }
}
