<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        // Match live gallery structure (PMC Campus + Facilities), with unique image files.
        $albums = [
            'campus' => [
                'title' => 'PMC Campus',
                'icon'  => 'bi-buildings',
                'items' => [
                    [
                        'src'     => 'assets/images/campus/pmc.jpg',
                        'title'   => 'Main Campus — Warsak Road',
                        'caption' => '25-Kanal campus on Warsak Road, Peshawar',
                    ],
                    [
                        'src'     => 'assets/images/campus/pmc-building.jpg',
                        'title'   => 'Department of Medical Sciences (DMS)',
                        'caption' => 'Modern Architecture',
                    ],
                    [
                        'src'     => 'assets/images/campus/pmc-building2.jpg',
                        'title'   => 'Administrative Block',
                        'caption' => 'Department of Medical Sciences (DMS)',
                    ],
                ],
            ],
            'facilities' => [
                'title' => 'Facilities',
                'icon'  => 'bi-house-heart',
                'items' => [
                    [
                        'src'     => 'assets/images/campus/girls-hostel.png',
                        'title'   => 'Girls Hostel',
                        'caption' => 'On-campus accommodation for female students',
                    ],
                    [
                        'src'     => 'assets/images/campus/gym-pmc.png',
                        'title'   => 'Sports & Gym',
                        'caption' => 'Sports and gymnasium facilities',
                    ],
                    [
                        'src'     => 'assets/images/campus/library-pmc.png',
                        'title'   => 'Library & LRC',
                        'caption' => 'Learning resource centre',
                    ],
                    [
                        'src'     => 'assets/images/campus/cafe.jpg',
                        'title'   => 'Cafeteria',
                        'caption' => 'Student cafeteria',
                    ],
                    [
                        'src'     => 'assets/images/campus/masjid.jpg',
                        'title'   => 'Masjid',
                        'caption' => 'Campus mosque',
                    ],
                    [
                        'src'     => 'assets/images/campus/pmc-transport.jpg',
                        'title'   => 'Transportation',
                        'caption' => 'College transport services',
                    ],
                    [
                        'src'     => 'assets/images/campus/daycare.jpg',
                        'title'   => 'Day Care Center',
                        'caption' => 'Day care facility on campus',
                    ],
                    [
                        'src'     => 'assets/images/campus/first-aid.png',
                        'title'   => 'Counseling & Aid',
                        'caption' => 'Counseling and first-aid services',
                    ],
                ],
            ],
        ];

        $this->db->table('gallery_items')->emptyTable();
        $this->db->table('gallery_albums')->emptyTable();

        $order = 0;
        foreach ($albums as $slug => $album) {
            $this->db->table('gallery_albums')->insert([
                'slug'       => $slug,
                'title'      => $album['title'],
                'icon'       => $album['icon'],
                'sort_order' => $order++,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $albumId = (int) $this->db->insertID();
            foreach (array_values($album['items']) as $i => $item) {
                $this->db->table('gallery_items')->insert([
                    'album_id'   => $albumId,
                    'image_path' => $item['src'],
                    'title'      => $item['title'] ?? null,
                    'caption'    => $item['caption'] ?? null,
                    'span'       => null,
                    'sort_order' => $i,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->db->table('pages')->where('slug', 'gallery')->update([
            'use_cms'    => 0,
            'view_key'   => 'gallery',
            'updated_at' => $now,
        ]);
    }
}
