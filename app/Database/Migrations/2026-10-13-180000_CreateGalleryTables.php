<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGalleryTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'slug'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'title'      => ['type' => 'VARCHAR', 'constraint' => 191],
            'icon'       => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'sort_order' => ['type' => 'INT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('gallery_albums', true);

        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'album_id'   => ['type' => 'INT', 'unsigned' => true],
            'image_path' => ['type' => 'VARCHAR', 'constraint' => 255],
            'title'      => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'caption'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'span'       => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'sort_order' => ['type' => 'INT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('album_id');
        $this->forge->addForeignKey('album_id', 'gallery_albums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('gallery_items', true);
    }

    public function down()
    {
        $this->forge->dropTable('gallery_items', true);
        $this->forge->dropTable('gallery_albums', true);
    }
}
