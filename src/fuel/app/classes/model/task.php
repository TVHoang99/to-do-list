<?php

class Model_Task extends \Orm\Model
{
	protected static $_properties = [
		'id', 'user_id', 'title', 'description', 'status', 'deadline', 'created_at', 'updated_at',
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
	];

	protected static $_table_name = 'tasks';

	protected static $_primary_key = ['id'];

	protected static $_many_many = [
		'shared_users' => [
            'key_from' => 'id',
            'key_through_from' => 'task_id',
            'table_through' => 'task_shares',
            'key_through_to' => 'user_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
		],
	];

	protected static $_belongs_to = [
		'user' => [
            'key_from' => 'user_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
		],
	];
}
