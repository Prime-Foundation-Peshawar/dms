<?php

namespace App\Services;

use App\Models\GalleryAlbumModel;

class GalleryService
{
    /**
     * Shape expected by the public gallery view.
     *
     * @return array<string, array{title:string,icon:string,items:list<array{src:string,title:string,caption:string}>}>
     */
    public function albumsForPublic(): array
    {
        $out = [];
        foreach (model(GalleryAlbumModel::class)->orderedWithItems() as $album) {
            $slug = (string) ($album['slug'] ?? '');
            if ($slug === '') {
                continue;
            }
            $items = [];
            foreach ($album['items'] ?? [] as $item) {
                $src = trim((string) ($item['image_path'] ?? ''));
                if ($src === '') {
                    continue;
                }
                $items[] = [
                    'src'     => $src,
                    'title'   => (string) ($item['title'] ?? ''),
                    'caption' => (string) ($item['caption'] ?? ''),
                ];
            }
            $out[$slug] = [
                'title' => (string) ($album['title'] ?? $slug),
                'icon'  => (string) ($album['icon'] ?? 'bi-images'),
                'items' => $items,
            ];
        }

        return $out;
    }
}
