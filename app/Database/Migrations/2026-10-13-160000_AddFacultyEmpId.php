<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFacultyEmpId extends Migration
{
    public function up()
    {
        $this->forge->addColumn('faculty_profiles', [
            'emp_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
                'null'       => true,
                'after'      => 'slug',
            ],
        ]);
        $this->db->query('CREATE UNIQUE INDEX faculty_profiles_emp_id_unique ON faculty_profiles (emp_id)');
    }

    public function down()
    {
        try {
            $this->db->query('DROP INDEX faculty_profiles_emp_id_unique');
        } catch (\Throwable $e) {
            // ignore
        }
        $this->forge->dropColumn('faculty_profiles', 'emp_id');
    }
}
