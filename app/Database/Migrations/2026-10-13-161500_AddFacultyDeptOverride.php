<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFacultyDeptOverride extends Migration
{
    public function up()
    {
        $this->forge->addColumn('faculty_profiles', [
            'dept_override' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('faculty_profiles', 'dept_override');
    }
}
