<?php
namespace Fuel\Migrations;

class Alter_Tasks_Change_Status_To_Int
{
    /**
     * Run the migration to change the status column type to INT
     */
    public function up()
    {
        // Alter the tasks table to change status column from ENUM to INT
        \DBUtil::modify_fields('tasks', array(
            'status' => array(
                'type' => 'int',
                'constraint' => 11,
                'default' => 0, // Default value (e.g., 0 for pending)
                'null' => false,
            ),
        ));

        // Optionally, update existing data to map ENUM to INT
        // Assuming: 'pending' -> 0, 'completed' -> 1
        \DB::query("UPDATE tasks SET status = CASE status WHEN 'pending' THEN 0 WHEN 'in_progress' THEN 1 WHEN 'completed' THEN 2 END")->execute();
    }

    /**
     * Revert the migration to restore the status column to ENUM
     */
    public function down()
    {
        // Revert status column back to ENUM
        \DBUtil::modify_fields('tasks', array(
            'status' => array(
                'type' => 'enum',
                'constraint' => ['pending', 'completed'],
                'default' => 'pending',
                'null' => false,
            ),
        ));

        // Optionally, revert data mapping
        \DB::query("UPDATE tasks SET status = CASE status WHEN 0 THEN 'pending' WHEN 1 THEN 'completed' END")->execute();
    }
}
