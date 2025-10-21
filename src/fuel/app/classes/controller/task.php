<?php

use Auth\Auth;
use Fuel\Core\Controller;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\View;

class Controller_Task extends Controller
{
	protected $user;
	protected $task;
	protected $task_share;

	public function __construct(User_Repository $user ,Task_Repository $task, Task_Share_Repository $task_share)
	{
		$this->user = $user;
		$this->task = $task;
		$this->task_share = $task_share;
	}

	public function action_index()
	{
		// $data["subnav"] = array('index' => 'active');
		// $this->title = 'Task &raquo; Index';
		// $this->content = View::forge('task/index', $data);
	}

	public function action_share($task_id)
	{
		// Check login
		if (!Auth::check()) {
			Response::redirect('login');
		}

		// Get task
		$task = $this->task->find($task_id);
		if (!$task) {
			Response::redirect('task/notfound');
		}

		// check authorized
		if ($task->user_id != Auth::get_user_id()[1]) {
			Response::redirect('task/unauthorized');
		}

		// Get user_id from form
		$share_user_id = Input::post('user_id');

		// Create share record.
		$task_share = $this->task_share::forge(array(
			'task_id' => $task_id,
			'user_id' => $share_user_id,
		));

		if ($task_share->save()) {
			echo "Task shared!";
		} else {
			echo "An error occured when sharing task!";
		}
	}
}
