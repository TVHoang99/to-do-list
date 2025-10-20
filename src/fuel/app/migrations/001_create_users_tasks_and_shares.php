<?php
namespace Fuel\Migrations;

class Create_Users_Tasks_And_Shares
{
    public function up()
    {
        // Create users table
        \DBUtil::create_table('users', array(
            'id' => array('type' => 'int', 'auto_increment' => true, 'primary_key' => true),
            'username' => array('type' => 'varchar', 'constraint' => 50, 'unique' => true),
            'email' => array('type' => 'varchar', 'constraint' => 100, 'unique' => true),
            'password' => array('type' => 'varchar', 'constraint' => 255),
            'created_at' => array('type' => 'timestamp', 'default' => \DB::expr('CURRENT_TIMESTAMP')),
            'updated_at' => array('type' => 'timestamp', 'default' => \DB::expr('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')),
        ));

        // Create tasks table
        \DBUtil::create_table('tasks', array(
            'id' => array('type' => 'int', 'auto_increment' => true, 'primary_key' => true),
            'user_id' => array('type' => 'int'),
            'title' => array('type' => 'varchar', 'constraint' => 255),
            'description' => array('type' => 'text', 'null' => true),
            'status' => array('type' => 'enum', 'constraint' => ['pending', 'completed'], 'default' => 'pending'),
            'deadline' => array('type' => 'datetime', 'null' => true),
            'created_at' => array('type' => 'timestamp', 'default' => \DB::expr('CURRENT_TIMESTAMP')),
            'updated_at' => array('type' => 'timestamp', 'default' => \DB::expr('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')),
        ));

		// Add foreign key for tasks.user_id
        \DBUtil::add_foreign_key('tasks', array(
            'constraint' => 'fk_tasks_user_id',
            'key' => 'user_id',
            'reference' => array(
                'table' => 'users',
                'column' => 'id',
            ),
            'on_delete' => 'CASCADE',
        ));

        // Create task_shares table
        \DBUtil::create_table('task_shares', array(
            'task_id' => array('type' => 'int'),
            'user_id' => array('type' => 'int'),
            'created_at' => array('type' => 'timestamp', 'default' => \DB::expr('CURRENT_TIMESTAMP')),
        ), array('task_id', 'user_id'), true, 'InnoDB', 'utf8_unicode_ci');

        // Add foreign key for task_shares
        \DBUtil::add_foreign_key('task_shares', array(
            'constraint' => 'fk_task_shares_task_id',
            'key' => 'task_id',
            'reference' => array(
                'table' => 'tasks',
                'column' => 'id',
            ),
            'on_delete' => 'CASCADE',
        ));
        \DBUtil::add_foreign_key('task_shares', array(
            'constraint' => 'fk_task_shares_user_id',
            'key' => 'user_id',
            'reference' => array(
                'table' => 'users',
                'column' => 'id',
            ),
            'on_delete' => 'CASCADE',
        ));

        // Create task_categories table
        \DBUtil::create_table('task_categories', array(
            'id' => array('type' => 'int', 'auto_increment' => true, 'primary_key' => true),
            'name' => array('type' => 'varchar', 'constraint' => 100),
            'created_at' => array('type' => 'timestamp', 'default' => \DB::expr('CURRENT_TIMESTAMP')),
        ));
    }

	// Rollback
    public function down()
    {
        \DBUtil::drop_table('task_shares');
        \DBUtil::drop_table('tasks');
        \DBUtil::drop_table('users');
        \DBUtil::drop_table('task_categories');
    }
}
