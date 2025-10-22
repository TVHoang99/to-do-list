<?php

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
        parent::__construct($request);

        // Check login using session
        $this->checkAuth();

        // Get user based on session user_id
        $user_id = \Session::get('user_id');
        $this->repository_user = new Repository_User();
        $this->user = $this->repository_user->find($user_id);
        if (!$this->user) {
            throw new \Exception('User not found.');
        }

        $this->repository_task = new Repository_Task();
        $this->repository_task_share = new Repository_Task_Share();
    }

    public function action_index()
    {
        // Get tasks created by the current user

        $tasks = $this->repository_task->findAll([
            'where' => [
                ['user_id', '=', $this->user->id],
            ],
            'related' => ['shared_users'],
            'order_by' => ['created_at' => 'desc'],
        ]);


        // Render the tasks index view
        return View::forge('task/index', [
            'user' => $this->user,
            'tasks' => $tasks,
        ]);
    }

    /**
     * View a specific task
     * @param int $task_id The ID of the task
     */
    public function action_view($task_id)
    {
        // Lấy task với relations
        $tasks = $this->repository_task->findAll([
            'where' => [['id', '=', $task_id]],
            'related' => ['user', 'shared_users'],
            'limit' => 1,
        ]);

        if (empty($tasks)) {
            \Session::set_flash('error', 'Task not found.');
            \Response::redirect('task');
        }
        // echo("<pre>");var_dump($tasks[1]['id']);echo("</pre>"); die;

        $task = $tasks[1];

        // Kiểm tra quyền truy cập
        $is_owner = ($task['user_id'] == $this->user->id);
        $is_shared = $this->repository_task_share->isSharedWithUser($task_id, $this->user->id);

        if (!$is_owner && !$is_shared) {
            \Session::set_flash('error', 'You are not authorized to view this task.');
            \Response::redirect('task');
        }

        // Lấy tất cả users để share (chỉ owner)
        $all_users = [];
        $shareable_users = [];
        if ($is_owner) {
            $all_users = $this->repository_user->findAll([
                'order_by' => ['username' => 'asc'],
            ]);

            // Filter users chưa được share
            $shared_user_ids = array_column($task['shared_users'] ?? [], 'id');
            foreach ($all_users as $user) {
                if ($user['id'] != $this->user->id && !in_array($user['id'], $shared_user_ids)) {
                    $shareable_users[] = $user;
                }
            }
        }

        return View::forge('task/view', [
            'task' => $task,
            'user' => $this->user,
            'is_owner' => $is_owner,
            'is_shared' => $is_shared,
            'all_users' => $all_users,
            'shareable_users' => $shareable_users,
            'status_text' => $task['status'] == 0 ? 'Pending' : ($task['status'] == 1 ? 'In Progress' : 'Completed'),
        ]);
    }

    public function action_share($task_id)
    {
        // Get task
        $task = $this->repository_task->find($task_id);
        if (!$task) {
            Response::redirect('task/notfound');
        }

        // Check authorized based on session user_id
        if ($task->user_id != \Session::get('user_id')) {
            Response::redirect('task/unauthorized');
        }

        // Get user_id from form
        $share_user_id = Input::post('user_id');

        // Create share record
        $task_share = $this->repository_task_share->create([
            'task_id' => $task_id,
            'user_id' => $share_user_id,
        ]);

        if ($task_share) {
            echo "Task shared!";
        } else {
            echo "An error occurred when sharing task!";
        }
    }

    public function action_create()
    {
        if (\Input::method() == 'POST') {
            $validation = \Validation::forge('create_task');
            $validation->add_field('title', 'Title', 'required|max_length[255]');
            $validation->add_field('description', 'Description', 'max_length[1000]');
            $validation->add_field('status', 'Status', 'valid_string[numeric]');
            $validation->add_field('priority', 'Priority', 'valid_string[low,medium,high]');
            // $data = [
            //     'title' => \Input::post('title'),
            //     'description' => \Input::post('description'),
            //     'status' => \Input::post('status', 0),
            //     'priority' => \Input::post('priority', 'medium'),
            //     'due_date' => \Input::post('due_date'),
            // ];

            // Validation
            if ($validation->run()) {
                $task_data = $validation->validated();
                $task_data['user_id'] = $this->user->id;

                if ($this->repository_task->create($task_data)) {
                    \Session::set_flash('success', 'Task created successfully!');
                    \Response::redirect('task');
                } else {
                    \Session::set_flash('error', 'Failed to create task. Please try again.');
                }
            } else {
                $data['errors'] = $validation->error();
            }
        }

        $data = [
            'user' => $this->user,
            'errors' => isset($data['errors']) ? $data['errors'] : [],
        ];

        return \View::forge('task/create', $data);
    }

    public function action_unshare($task_id, $user_id)
    {
        if ($this->user->id != $task['user_id']) {
            \Session::set_flash('error', 'Only owner can unshare.');
            \Response::redirect('task/view/' . $task_id);
        }

        $result = $this->repository_task_share->delete([
            'task_id' => $task_id,
            'user_id' => $user_id,
        ]);

        if ($result) {
            \Session::set_flash('success', 'User removed from sharing successfully.');
        } else {
            \Session::set_flash('error', 'Failed to remove user from sharing.');
        }
        \Response::redirect('task/view/' . $task_id);
    }

    private function checkAuth()
    {
        // Check login using session
        if (!\Session::get('user_id')) {
            Response::redirect('login');
        }
    }
}
