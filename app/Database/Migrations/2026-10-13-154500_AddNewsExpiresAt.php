<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNewsExpiresAt extends Migration
{
    public function up()
    {
        $this->forge->addColumn('news_posts', [
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'published_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('news_posts', 'expires_at');
    }
}
