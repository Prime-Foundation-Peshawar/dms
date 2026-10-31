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
                'emp_id' => isset($rec['emp_id']) && $rec['emp_id'] !== '' ? (string) $rec['emp_id'] : (isset($rec['empId']) && $rec['empId'] !== '' ? (string) $rec['empId'] : null),
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

        // Pages (CMS body prefilled from static views when use_cms=1)
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
                'body_html' => $p['body_html'] ?? null,
                'body_text' => $p['body_text'] ?? null,
                'use_cms' => (int) ($p['use_cms'] ?? 0),
                'seo_json' => json_encode($p['seo'] ?? [], JSON_UNESCAPED_UNICODE),
                'status' => $p['status'] ?? 'published',
                'priority' => $p['priority'] ?? 0.5,
                'changefreq' => $p['changefreq'] ?? 'monthly',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // News (from legacy static cards)
        $this->db->table('news_media')->emptyTable();
        $this->db->table('news_posts')->emptyTable();
        $newsFile = $dataDir . '/news.json';
        if (is_file($newsFile)) {
            foreach (json_decode((string) file_get_contents($newsFile), true) ?: [] as $n) {
                $this->db->table('news_posts')->insert([
                    'slug' => $n['slug'],
                    'title' => $n['title'],
                    'excerpt' => $n['excerpt'] ?? null,
                    'body_html' => $n['body_html'] ?? null,
                    'body_text' => $n['body_text'] ?? null,
                    'category' => $n['category'] ?? null,
                    'cover_image' => $n['cover_image'] ?? null,
                    'published_at' => $n['published_at'] ?? $now,
                    'expires_at' => $n['expires_at'] ?? null,
                    'status' => $n['status'] ?? 'published',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Events share the news list: add the event details to the matching post, or add a new item.
        $eventsFile = $dataDir . '/events.json';
        if (is_file($eventsFile)) {
            model(\App\Models\NewsPostModel::class)->ensureEventColumns();
            foreach (json_decode((string) file_get_contents($eventsFile), true) ?: [] as $e) {
                $fields = [
                    'start_date' => $e['start_date'] ?? null,
                    'event_date' => $e['event_date'] ?? null,
                    'location' => $e['location'] ?? null,
                ];
                if ($this->db->table('news_posts')->where('slug', $e['slug'])->countAllResults() > 0) {
                    $this->db->table('news_posts')->where('slug', $e['slug'])->update($fields);
                    continue;
                }
                $this->db->table('news_posts')->insert($fields + [
                    'slug' => $e['slug'],
                    'title' => $e['title'],
                    'excerpt' => $e['excerpt'] ?? null,
                    'body_html' => $e['body_html'] ?? null,
                    'body_text' => $e['body_text'] ?? null,
                    'category' => $e['category'] ?? null,
                    'cover_image' => $e['cover_image'] ?? null,
                    'published_at' => $e['event_date'] ?? $now,
                    'status' => $e['status'] ?? 'published',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Newsletters live on the campus site (riphahpsh.edu.pk/news-letter.php).
        $this->db->table('settings')->emptyTable();
        $settings = [
            'site_name' => 'Peshawar Medical College',
            'hub_base_production' => 'https://riphahpsh.edu.pk/',
            'hub_base_staging' => 'https://staging.riphahpsh.edu.pk/riphahpsh/',
            'contact_phone' => '+92-91-5202191',
            'contact_email' => 'info@riphahpsh.edu.pk',
            'hrms_api_url' => 'https://biometric.prime.edu.pk/hrms/apis/getEmployeeInfo.php',
        ];
        foreach ($settings as $key => $value) {
            $this->db->table('settings')->insert([
                'key' => $key,
                'value' => $value,
                'updated_at' => $now,
            ]);
        }

        // Bootstrap admin user
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
