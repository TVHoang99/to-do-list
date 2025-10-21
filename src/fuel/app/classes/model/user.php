<?php

use Auth\Auth;

class Model_User extends \Orm\Model
{
	protected static $_properties = [
		'id', 'username', 'email', 'password', 'salt', 'last_login', 'login_hash', 'created_at', 'updated_at',
	];

	protected static $_observers = [
		'Orm\Observer_CreatedAt' => [
			'events' => ['before_insert'],
			'property' => 'created_at',
			'mysql_timestamp' => true,
		],
		'Orm\Observer_UpdatedAt' => [
			'events' => ['before_update'],
			'property' => 'updated_at',
			'mysql_timestamp' => true,
		],
		'Orm\Observer_Self' => array(
            'events' => array('before_save'),
            'method' => 'update_last_login',
            'property' => 'last_login',
        ),
	];

	protected static $_table_name = 'users';

	protected static $_primary_key = ['id'];

	protected static $_has_many = [
		'tasks' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Task',
            'key_to' => 'user_id',
            'cascade_save' => true,
            'cascade_delete' => true,
        ),
	];

	protected static $_many_many = [
		'shared_tasks' => array(
            'key_from' => 'id',
            'key_through_from' => 'user_id',
            'table_through' => 'task_shares',
            'key_through_to' => 'task_id',
            'model_to' => 'Model_Task',
            'key_to' => 'id',
        ),
	];

	/**
     * Update last_login before saving
     */
    public function update_last_login()
    {
        if (Auth::check() && $this->id == Auth::get_user_id()[1]) {
            $this->last_login = date('Y-m-d H:i:s'); // Ensure datetime format
        }
    }
}
