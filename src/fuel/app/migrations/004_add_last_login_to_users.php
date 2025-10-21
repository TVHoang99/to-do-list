<?php
namespace Fuel\Migrations;

class Add_Last_Login_to_Users
{
    /**
     * Run the migration to add last_login column to users table
     */
    public function up()
    {
        \DBUtil::add_fields('users', array(
            'last_login' => array('type' => 'datetime', 'null' => true, 'after' => 'salt'),
        ));
    }

    /**
     * Revert the migration to remove last_login column
     */
    public function down()
    {
        \DBUtil::drop_fields('users', array('last_login'));
    }
}