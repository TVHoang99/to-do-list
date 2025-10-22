<?php
namespace Fuel\Migrations;

class Add_role_to_users
{
    public function up()
    {
        \DBUtil::add_fields('users', array(
            'role' => array('type' => 'varchar', 'constraint' => 50, 'default' => 'user'),
        ));
    }

    public function down()
    {
        \DBUtil::drop_fields('users', array('role'));
    }
}
