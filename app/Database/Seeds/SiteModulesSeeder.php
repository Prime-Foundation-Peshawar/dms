<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SiteModulesSeeder extends Seeder
{
    public function run()
    {
        $this->call('GallerySeeder');
        $now = date('Y-m-d H:i:s');

        // Newsletters are hosted externally (riphahpsh.edu.pk/news-letter.php) — not seeded locally.

        $vacant = [
            'intro'   => 'Riphah International University Rule: Applicant having less than 60% marks in any subject/paper shall not be eligible for migration.',
            'heading' => 'Vacant Seats in Riphah International University - Peshawar Campus',
            'rows'    => [
                ['year' => '1st Year MBBS', 'session' => '2025-26', 'seats' => '0', 'note' => ''],
                ['year' => '2nd Year MBBS', 'session' => '2024-25', 'seats' => '3', 'note' => ''],
                ['year' => '3rd Year MBBS', 'session' => '2023-24', 'seats' => '0', 'note' => ''],
                ['year' => '4th Year MBBS', 'session' => '2022-23', 'seats' => '0', 'note' => ''],
                ['year' => 'Final Year MBBS', 'session' => '2021-22', 'seats' => '0', 'note' => ''],
            ],
            'notices' => [
                'Students are allowed to apply against the notified vacant seats (from 3rd Year MBBS to Final Year MBBS) with immediate effect. As per PM&DC Admission Regulations 2023, no transfer / migration shall be allowed in the first 2 years of MBBS.',
                'Students shall apply online for migration on vacant seats through the designated portal.',
                'Student will be admitted on merit based on average of professional examinations.',
                'As per Riphah International University rules, applicant having less than 60% marks in any subject/paper shall not be eligible for migration.',
                'No transfer shall be accepted by a college to a vacant seat until reviewed by the Authority to ensure transparency.',
            ],
            'last_date'   => '16 January 2026 (Extended)',
            'apply_url'   => 'https://pmc.prime.edu.pk/vacant_admission/',
            'apply_label' => 'Apply Online for Migration',
        ];

        $key   = 'vacant_seats';
        $value = json_encode($vacant, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $existing = $this->db->table('settings')->where('key', $key)->get()->getRowArray();
        if ($existing) {
            $this->db->table('settings')->where('id', $existing['id'])->update([
                'value'      => $value,
                'updated_at' => $now,
            ]);
        } else {
            $this->db->table('settings')->insert([
                'key'        => $key,
                'value'      => $value,
                'updated_at' => $now,
            ]);
        }

        $this->db->table('pages')->where('slug', 'vacant-seats')->update([
            'use_cms'    => 0,
            'view_key'   => 'vacant-seats',
            'updated_at' => $now,
        ]);
    }
}
