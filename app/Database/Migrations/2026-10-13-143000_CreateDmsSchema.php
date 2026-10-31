<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDmsSchema extends Migration
{
    public function up()
    {
        // departments
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'slug'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 191],
            'icon'       => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'group_name' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'Other'],
            'hod'        => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'intro_json' => ['type' => 'LONGTEXT', 'null' => true],
            'faculty_seed_json' => ['type' => 'LONGTEXT', 'null' => true],
            'hidden'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'oric_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'content_updated_at' => ['type' => 'DATE', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('group_name');
        $this->forge->addKey('hidden');
        $this->forge->createTable('departments', true);

        // department_activities
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'department_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'title'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'date_label'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'body'          => ['type' => 'TEXT', 'null' => true],
            'sort_order'    => ['type' => 'INT', 'default' => 0],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('department_id');
        $this->forge->addForeignKey('department_id', 'departments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('department_activities', true);

        // faculty_profiles (BIGINT id matches legacy pre-CI4 staging tables for FK compatibility)
        $this->forge->addField([
            'id'               => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'slug'             => ['type' => 'VARCHAR', 'constraint' => 150],
            'name'             => ['type' => 'VARCHAR', 'constraint' => 191],
            'department_slug'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'department_name'  => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'designation'      => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'photo'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_hod'           => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_public'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'qualifications_json' => ['type' => 'LONGTEXT', 'null' => true],
            'experience_json'     => ['type' => 'LONGTEXT', 'null' => true],
            'skills_json'         => ['type' => 'LONGTEXT', 'null' => true],
            'publications_json'   => ['type' => 'LONGTEXT', 'null' => true],
            'extra_json'          => ['type' => 'LONGTEXT', 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('department_slug');
        $this->forge->addKey('is_public');
        $this->forge->createTable('faculty_profiles', true);

        // faculty_profile_aliases
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'profile_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'alias'      => ['type' => 'VARCHAR', 'constraint' => 150],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('alias');
        $this->forge->addKey('profile_id');
        $this->forge->addForeignKey('profile_id', 'faculty_profiles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('faculty_profile_aliases', true);

        // pages
        $this->forge->addField([
            'id'          => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 120],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'label'       => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'section'     => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'view_key'    => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'body_html'   => ['type' => 'LONGTEXT', 'null' => true],
            'seo_json'    => ['type' => 'TEXT', 'null' => true],
            'status'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'published'],
            'priority'    => ['type' => 'DECIMAL', 'constraint' => '2,1', 'default' => 0.5],
            'changefreq'  => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'monthly'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('status');
        $this->forge->createTable('pages', true);

        // news_posts
        $this->forge->addField([
            'id'           => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'slug'         => ['type' => 'VARCHAR', 'constraint' => 160],
            'title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'excerpt'      => ['type' => 'TEXT', 'null' => true],
            'body_html'    => ['type' => 'LONGTEXT', 'null' => true],
            'category'     => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'cover_image'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'published_at' => ['type' => 'DATETIME', 'null' => true],
            'status'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'published'],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('status');
        $this->forge->createTable('news_posts', true);

        // news_media
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'news_id'    => ['type' => 'BIGINT', 'unsigned' => true],
            'path'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'caption'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sort_order' => ['type' => 'INT', 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('news_id');
        $this->forge->addForeignKey('news_id', 'news_posts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('news_media', true);

        // events
        $this->forge->addField([
            'id'           => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'slug'         => ['type' => 'VARCHAR', 'constraint' => 160],
            'title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'excerpt'      => ['type' => 'TEXT', 'null' => true],
            'body_html'    => ['type' => 'LONGTEXT', 'null' => true],
            'category'     => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'cover_image'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'start_date'   => ['type' => 'DATE', 'null' => true],
            'event_date'   => ['type' => 'DATE', 'null' => true],
            'location'     => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'status'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'published'],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('events', true);

        // event_media
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'event_id'   => ['type' => 'BIGINT', 'unsigned' => true],
            'path'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'caption'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sort_order' => ['type' => 'INT', 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('event_id');
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('event_media', true);

        // settings
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'key'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'value'      => ['type' => 'LONGTEXT', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('key');
        $this->forge->createTable('settings', true);

        // users (schema only for future CMS)
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 191],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'role'          => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'admin'],
            'is_active'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users', true);
    }

    public function down()
    {
        foreach ([
            'event_media', 'events', 'news_media', 'news_posts',
            'faculty_profile_aliases', 'faculty_profiles',
            'department_activities', 'departments',
            'pages', 'settings', 'users',
        ] as $table) {
            $this->forge->dropTable($table, true);
        }
    }
}
