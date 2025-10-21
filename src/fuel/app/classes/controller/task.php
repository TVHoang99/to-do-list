<?php

use Auth\Auth;
use Fuel\Core\Controller;
use Fuel\Core\Input;
use Fuel\Core\Request;
use Fuel\Core\Response;
use Fuel\Core\Session;
use Fuel\Core\View;

class Controller_Task extends Controller
{
	protected $user;
	protected $repository_user;
	protected $repository_task;
	protected $repository_task_share;

	public function __construct(Request $request)
	{
		// // Check login
		// $this->checkAuth();
		$user_id = Auth::get_user_id()[1];
        $this->user = $this->repository_user->find($user_id);
		if (!$this->user) {
            throw new \Exception('User not found.');
        }

		$this->repository_user = new Repository_User();
		$this->repository_task = new Repository_Task();
		$this->repository_task_share = new Repository_Task_Share();
	}

	// function before()
    // {
    //     parent::before();

    //     // If user is already logged in, redirect to task index
    //     if (Auth::check()) {
    //         Response::redirect('task');
    //     }
    // }

	public function action_index()
	{
		// Get tasks created by the current user
        $own_tasks = $this->repository_user->findAll([
            'where' => [
                ['user_id', '=', $this->user->id],
            ],
            'order_by' => ['created_at' => 'desc'],
        ]);

        // Get tasks shared with the current user
        $shared_tasks = $this->repository_user->findAll([
            'where' => [
                ['task_shares.user_id', '=', $this->user->id],
            ],
            'related' => ['shared_users'],
            'join' => [
                ['task_shares', 'tasks.id', '=', 'task_shares.task_id'],
            ],
            'order_by' => ['created_at' => 'desc'],
        ]);

        // Prepare data for the view
        $data = [
            'own_tasks' => $own_tasks,
            'shared_tasks' => $shared_tasks,
            'user' => $this->user,
        ];

        // Render the tasks index view
        return View::forge('tasks/index', $data);
	}

	/**
     * View a specific task
     * @param int $task_id The ID of the task
     */
    public function action_view($task_id)
    {
        // Find the task
        $task = $this->repository_task->find($task_id, ['related' => ['shared_users']]);
        if (!$task) {
            Session::set_flash('error', 'Task not found.');
            Response::redirect('task');
        }

        // Check if the user owns the task or is shared with it
        if ($task->user_id != $this->user->id && !$this->repository_task_share->isSharedWithUser($task_id, $this->user->id)) {
            Session::set_flash('error', 'You are not authorized to view this task.');
            Response::redirect('task');
        }

        // Prepare data for the view
        $data = [
            'task' => $task,
            'shared_users' => $this->repository_task_share->getSharedUsers($task_id),
            'status_text' => $task->get_status_text(),
        ];

        // Render the task view
        return View::forge('tasks/view', $data);
    }

	public function action_share($task_id)
	{
		// Get task
		$task = $this->repository_task->find($task_id);
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
		$task_share = $this->repository_task_share->create([
			'task_id' => $task_id,
			'user_id' => $share_user_id,
		]);

		if ($task_share) {
			echo "Task shared!";
		} else {
			echo "An error occured when sharing task!";
		}
	}

	public function action_create()
	{
		// Handle form submission
		if (Input::method() == 'POST') {
			$title = Input::post('title');
			$description = Input::post('description');

			// Create new task
			$task = $this->repository_task->create([
				'user_id' => $this->user->id,
				'title' => $title,
				'description' => $description,
				'status' => 0, // Default to pending
			]);

			if ($task) {
				Session::set_flash('success', 'Task created successfully.');
				Response::redirect('task');
			} else {
				Session::set_flash('error', 'An error occurred while creating the task.');
			}
		}

		// Render the create task view
		return View::forge('tasks/create');
	}

	private function checkAuth()
	{
		// Check login
		if (!Auth::check()) {
			Response::redirect('login');
		}
	}
}
