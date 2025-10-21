<?php
namespace Fuel\Migrations;

class Add_Login_Hash_to_Users
{
    /**
     * Run the migration to add login_hash column to users table
     */
    public function up()
    {
        \DBUtil::add_fields('users', array(
            'login_hash' => array('type' => 'varchar', 'constraint' => 255, 'null' => true, 'after' => 'last_login'),
        ));
    }

    /**
     * Revert the migration to remove login_hash column
     */
    public function down()
    {
        \DBUtil::drop_fields('users', array('login_hash'));
    }
}