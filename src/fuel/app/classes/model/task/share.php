<?php

class Model_Task_Share extends \Orm\Model
{
	protected static $_properties = [
		'task_id', 'user_id', 'created_at',
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

	protected static $_table_name = 'task_shares';

	protected static $_primary_key = ['task_id', 'user_id'];

	protected static $_belongs_to = [
		'task' => array(
            'key_from' => 'task_id',
            'model_to' => 'Model_Task',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => true,
        ),

		'user' => array(
            'key_from' => 'user_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => true,
        ),
	];
}
