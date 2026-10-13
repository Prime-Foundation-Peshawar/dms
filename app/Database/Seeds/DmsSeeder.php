<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Config\Database;

class DmsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $dataDir = APPPATH . 'Database/Seeds/data';

        // Departments + activities
        $depts = json_decode((string) file_get_contents($dataDir . '/departments.json'), true) ?: [];
        $this->db->table('department_activities')->emptyTable();
        $this->db->table('departments')->emptyTable();
        foreach ($depts as $d) {
            $this->db->table('departments')->insert([
                'slug' => $d['slug'],
                'name' => $d['name'],
                'icon' => $d['icon'],
                'group_name' => $d['group_name'],
                'hod' => $d['hod'],
                'intro_json' => json_encode($d['intro'] ?? [], JSON_UNESCAPED_UNICODE),
                'faculty_seed_json' => json_encode($d['faculty'] ?? [], JSON_UNESCAPED_UNICODE),
                'hidden' => (int) ($d['hidden'] ?? 0),
                'oric_id' => $d['oric_id'],
                'content_updated_at' => $d['content_updated_at'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $deptId = (int) $this->db->insertID();
            foreach (array_values($d['activities'] ?? []) as $i => $act) {
                $this->db->table('department_activities')->insert([
                    'department_id' => $deptId,
                    'title' => $act['title'] ?? 'Activity',
                    'date_label' => $act['date'] ?? null,
                    'body' => $act['text'] ?? null,
                    'sort_order' => $i,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Faculty profiles
        $pack = json_decode((string) file_get_contents($dataDir . '/faculty-profiles.json'), true) ?: [];
        $this->db->table('faculty_profile_aliases')->emptyTable();
        $this->db->table('faculty_profiles')->emptyTable();
        foreach ($pack['profiles'] ?? [] as $slug => $rec) {
            if (!is_array($rec)) {
                continue;
            }
            $extra = [];
            foreach (['academic_roles', 'memberships', 'courses', 'books', 'book_chapters', 'registrations', 'research_preferences', 'aliases'] as $k) {
                if (!empty($rec[$k])) {
                    $extra[$k] = $rec[$k];
                }
            }
            $this->db->table('faculty_profiles')->insert([
                'slug' => $rec['slug'] ?? $slug,
                'name' => $rec['name'] ?? $slug,
                'department_slug' => null,
                'department_name' => $rec['department'] ?? null,
                'designation' => $rec['designation'] ?? null,
                'photo' => $rec['photo'] ?? null,
                'is_hod' => !empty($rec['hod']) ? 1 : 0,
                'is_public' => isset($rec['public']) ? (int) (bool) $rec['public'] : 1,
                'qualifications_json' => json_encode($rec['qualifications'] ?? [], JSON_UNESCAPED_UNICODE),
                'experience_json' => json_encode($rec['experience'] ?? [], JSON_UNESCAPED_UNICODE),
                'skills_json' => json_encode($rec['skills'] ?? [], JSON_UNESCAPED_UNICODE),
                'publications_json' => json_encode($rec['publications'] ?? [], JSON_UNESCAPED_UNICODE),
                'extra_json' => $extra ? json_encode($extra, JSON_UNESCAPED_UNICODE) : null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $pid = (int) $this->db->insertID();
            $aliases = $rec['aliases'] ?? [];
            $aliases[] = $rec['slug'] ?? $slug;
            $seen = [];
            foreach ($aliases as $alias) {
                $alias = trim((string) $alias);
                if ($alias === '' || isset($seen[$alias])) {
                    continue;
                }
                $seen[$alias] = true;
                $this->db->table('faculty_profile_aliases')->insert([
                    'profile_id' => $pid,
                    'alias' => $alias,
                ]);
            }
        }

        // Pages
        $pages = json_decode((string) file_get_contents($dataDir . '/pages.json'), true) ?: [];
        $this->db->table('pages')->emptyTable();
        foreach ($pages as $p) {
            $this->db->table('pages')->insert([
                'slug' => $p['slug'],
                'title' => $p['title'],
                'label' => $p['label'],
                'section' => $p['section'],
                'description' => $p['description'],
                'view_key' => $p['view_key'],
                'body_html' => null,
                'seo_json' => json_encode($p['seo'] ?? [], JSON_UNESCAPED_UNICODE),
                'status' => $p['status'] ?? 'published',
                'priority' => $p['priority'] ?? 0.5,
                'changefreq' => $p['changefreq'] ?? 'monthly',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Settings + newsletters
        $newsletters = json_decode((string) file_get_contents($dataDir . '/newsletters.json'), true) ?: [];
        $this->db->table('settings')->emptyTable();
        $settings = [
            'site_name' => 'Peshawar Medical College',
            'hub_base_production' => 'https://riphahpsh.edu.pk/',
            'hub_base_staging' => 'https://staging.riphahpsh.edu.pk/riphahpsh/',
            'contact_phone' => '+92-91-5202191',
            'contact_email' => 'info@riphahpsh.edu.pk',
            'newsletters' => json_encode($newsletters, JSON_UNESCAPED_UNICODE),
            'hrms_api_url' => 'https://biometric.prime.edu.pk/hrms/apis/getEmployeeInfo.php',
        ];
        foreach ($settings as $key => $value) {
            $this->db->table('settings')->insert([
                'key' => $key,
                'value' => $value,
                'updated_at' => $now,
            ]);
        }

        // Bootstrap admin user (no UI yet)
        $email = env('CMS_ADMIN_EMAIL', 'admin@riphahpsh.edu.pk');
        $pass = env('CMS_ADMIN_PASSWORD', 'change_me');
        $this->db->table('users')->emptyTable();
        $this->db->table('users')->insert([
            'email' => $email,
            'password_hash' => password_hash((string) $pass, PASSWORD_DEFAULT),
            'name' => 'CMS Admin',
            'role' => 'admin',
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
