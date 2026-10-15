<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCmsBodyFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pages', [
            'body_text' => ['type' => 'LONGTEXT', 'null' => true, 'after' => 'body_html'],
            'use_cms'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'body_text'],
        ]);
        $this->forge->addColumn('news_posts', [
            'body_text' => ['type' => 'LONGTEXT', 'null' => true, 'after' => 'body_html'],
        ]);
        $this->forge->addColumn('events', [
            'body_text' => ['type' => 'LONGTEXT', 'null' => true, 'after' => 'body_html'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pages', ['body_text', 'use_cms']);
        $this->forge->dropColumn('news_posts', 'body_text');
        $this->forge->dropColumn('events', 'body_text');
    }
}
