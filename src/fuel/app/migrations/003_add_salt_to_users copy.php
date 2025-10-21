<?php
namespace Fuel\Migrations;

class Add_Salt_to_Users
{
    /**
     * Run the migration to add salt column to users table
     */
    public function up()
    {
        \DBUtil::add_fields('users', array(
            'salt' => array('type' => 'varchar', 'constraint' => 255, 'null' => false, 'after' => 'password'),
        ));
    }

    /**
     * Revert the migration to remove salt column
     */
    public function down()
    {
        \DBUtil::drop_fields('users', array('salt'));
    }
}
