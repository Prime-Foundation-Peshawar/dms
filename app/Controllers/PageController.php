<?php

namespace App\Controllers;

use App\Models\NewsMediaModel;
use App\Models\NewsPostModel;
use App\Models\PageModel;
use App\Models\SettingModel;
use App\Services\GalleryService;

class PageController extends BaseController
{
    public function redirectMedicalEducation()
    {
        return redirect()->to(site_url('pmc') . '#mbbs');
    }

    public function redirectNewsletter()
    {
        return redirect()->to('https://riphahpsh.edu.pk/news-letter.php');
    }

    public function show(string $slug = '')
    {
        $slug = trim($slug, '/');
        if ($slug === '' || $slug === 'home') {
            return redirect()->to('/');
        }

        if ($slug === 'newsletter') {
            return $this->redirectNewsletter();
        }

        // Events now live in News & Events; keep old and shared links working.
        if ($slug === 'events') {
            return redirect()->to(site_url('all-news'), 301);
        }
        if ($slug === 'event-single') {
            $old = trim((string) ($this->request->getGet('slug') ?: $this->request->getGet('n')));

            return redirect()->to(site_url($old !== '' ? 'single-news' : 'all-news') . ($old !== '' ? '?slug=' . rawurlencode($old) : ''), 301);
        }

        $page = null;
        try {
            $page = model(PageModel::class)->findPublished($slug);
        } catch (\Throwable $e) {
            log_message('error', 'Page lookup failed for {0}: {1}', [$slug, $e->getMessage()]);
        }

        // Allow static view templates before CMS rows are seeded.
        if (! $page) {
            $viewFile = APPPATH . 'Views/pages/' . $slug . '.php';
            if (! is_file($viewFile)) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($slug);
            }
            $page = [
                'slug'        => $slug,
                'title'       => ucwords(str_replace('-', ' ', $slug)),
                'description' => '',
                'view_key'    => $slug,
                'use_cms'     => 0,
            ];
        }

        $data = [
            'page_title'       => $page['title'] ?? ucwords(str_replace('-', ' ', $slug)),
            'page_description' => $page['description'] ?? '',
            'canonical_url'    => site_url($slug),
            'page'             => $page,
        ];

        if ($slug === 'gallery') {
            $data['albums'] = (new GalleryService())->albumsForPublic();
        }

        if ($slug === 'vacant-seats') {
            $data['vacant_seats'] = model(SettingModel::class)->vacantSeats();
        }

        if ($slug === 'all-news') {
            $data['news_posts'] = model(NewsPostModel::class)->published();
        }

        if ($slug === 'single-news') {
            $newsSlug = trim((string) ($this->request->getGet('slug') ?: $this->request->getGet('n')));
            model(NewsPostModel::class)->ensureEventColumns();
            $post     = $newsSlug !== '' ? model(NewsPostModel::class)->findPublishedBySlug($newsSlug) : null;
            if (! $post) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('news');
            }
            $data['news_post']        = $post;
            $data['news_media']       = model(NewsMediaModel::class)->forNews((int) $post['id']);
            $data['page_title']       = $post['title'];
            $data['page_description'] = $post['excerpt'] ?? ($page['description'] ?? '');
            $data['canonical_url']    = site_url('single-news') . '?slug=' . rawurlencode($post['slug']);
        }

        // Gallery, news & events, and vacant seats stay on their templates so
        // album and listing edits are not replaced by a saved HTML snapshot.
        $liveTemplates = ['gallery', 'all-news', 'single-news', 'vacant-seats'];
        if (! in_array($slug, $liveTemplates, true)
            && ! empty($page['use_cms'])
            && (! empty($page['body_html']) || ! empty($page['body_text']))) {
            return view('pages/content_wrap', array_merge($data, [
                'viewKey' => 'cms_content',
            ]));
        }

        $viewKey  = $page['view_key'] ?? $slug;
        $viewFile = APPPATH . 'Views/pages/' . $viewKey . '.php';
        if (is_file($viewFile) && $viewKey !== 'cms_content') {
            return view('pages/content_wrap', array_merge($data, [
                'viewKey' => $viewKey,
            ]));
        }

        if (! empty($page['body_html']) || ! empty($page['body_text'])) {
            return view('pages/content_wrap', array_merge($data, [
                'viewKey' => 'cms_content',
            ]));
        }

        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($slug);
    }
}
