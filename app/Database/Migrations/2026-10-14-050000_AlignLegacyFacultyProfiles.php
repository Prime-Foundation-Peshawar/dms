<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Legacy staging faculty_profiles predates CI4 CREATE TABLE IF NOT EXISTS.
 * Add missing columns used by FacultyProfileModel.
 */
class AlignLegacyFacultyProfiles extends Migration
{
    public function up()
    {
        $fields = [
            'is_public' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => false,
            ],
            'is_hod' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
            'department_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 191,
                'null'       => true,
            ],
            'department_slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'qualifications_json' => ['type' => 'LONGTEXT', 'null' => true],
            'experience_json'     => ['type' => 'LONGTEXT', 'null' => true],
            'skills_json'         => ['type' => 'LONGTEXT', 'null' => true],
            'publications_json'   => ['type' => 'LONGTEXT', 'null' => true],
            'extra_json'          => ['type' => 'LONGTEXT', 'null' => true],
        ];

        foreach ($fields as $name => $def) {
            if (! $this->db->fieldExists($name, 'faculty_profiles')) {
                $this->forge->addColumn('faculty_profiles', [$name => $def]);
            }
        }

        if ($this->db->fieldExists('department', 'faculty_profiles')
            && $this->db->fieldExists('department_name', 'faculty_profiles')) {
            $this->db->query(
                "UPDATE `faculty_profiles`
                 SET `department_name` = `department`
                 WHERE (`department_name` IS NULL OR `department_name` = '')
                   AND `department` IS NOT NULL"
            );
        }
    }

    public function down()
    {
        // Non-destructive: keep columns on rollback.
    }
}
