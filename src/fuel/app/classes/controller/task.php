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
        $page = (int) \Input::get('page', 1);
        $per_page = 6; // ✅ 6 TASKS/PAGE

        // Tính offset
        $offset = ($page - 1) * $per_page;

        // Lấy tasks
        $tasks = $this->repository_task->findAll([
            'where' => [['user_id', '=', $this->user->id]],
            'related' => ['user', 'shared_users'],
            'order_by' => [['created_at', 'desc']],
            'limit' => $per_page,
            'offset' => $offset
        ]);

        // Tổng số tasks
        $total_tasks = count($tasks);

        // Tổng số trang
        $total_pages = ceil($total_tasks / $per_page);

        // Giới hạn page
        $page = max(1, min($page, $total_pages));
        $offset = ($page - 1) * $per_page;

        $data = [
            'user' => $this->user,
            'tasks' => $tasks,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_items' => $total_tasks,
                'per_page' => $per_page,
                'from' => $offset + 1,
                'to' => min($offset + $per_page, $total_tasks)
            ]
        ];

        return \View::forge('task/index', $data);
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

        $task = reset($tasks);

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
        try {
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
            $share_user_ids = \Input::post('user_ids');

            // Create share record
            foreach ($share_user_ids as $share_user_id) {
                $this->repository_task_share->create([
                    'task_id' => $task_id,
                    'user_id' => $share_user_id,
                ]);
            }

            \Session::set_flash('success', 'Task shared!');
            \Response::redirect('task/view/' . $task_id);
        } catch (Exception $e) {
            Session::set_flash('error', "An error occurred when sharing task: " . $e->getMessage());
            \Response::redirect('task/view/' . $task_id);
        }
    }

    public function action_create()
    {
        $form_data = [
            'title' => '',
            'description' => '',
            'status' => 0,
            'priority' => 'medium',
            'due_date' => ''
        ];

        if (\Input::method() == 'POST') {
            $validation = \Validation::forge('create_task');

            $validation->add('title', 'Title')->add_rule('required')
                ->add_rule('max_length', 255);
            $validation->add('status', 'Status')
                ->add_rule('required')
                ->add_rule('match_pattern', '/^[0-2]$/');

            $validation->add('priority', 'Priority')
                ->add_rule('required')
                ->add_rule('match_pattern', '/^(low|medium|high)$/');

            $validation->add('description', 'Description')
                ->add_rule('max_length', 1000);

            $validation->add_field('due_date', 'Due Date', 'valid_string[datetime]');

            if ($validation->run()) {
                $task_data = [
                    'title' => \Input::post('title'),
                    'description' => \Input::post('description'),
                    'status' => (int) \Input::post('status'),
                    'priority' => \Input::post('priority'),
                    'user_id' => $this->user->id,
                    'deadline' => \Input::post('due_date'),
                ];

                if ($this->repository_task->create($task_data)) {
                    \Session::set_flash('success', 'Task created successfully!');
                    \Response::redirect('task');
                } else {
                    \Session::set_flash('error', 'Failed to create task. Please try again.');
                }
            } else {
                $form_data = [
                    'title' => \Input::post('title', ''),
                    'description' => \Input::post('description', ''),
                    'status' => \Input::post('status', 0),
                    'priority' => \Input::post('priority', 'medium'),
                    'deadline' => \Input::post('due_date', '')
                ];
                \Session::set_flash('error', 'Please fix the errors below.');
            }
        }

        $data = [
            'user' => $this->user,
            'errors' => isset($validation) ? $validation->error() : [],
            'form_data' => $form_data
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

    public function action_delete($task_id)
    {
        // Tìm task
        $tasks = $this->repository_task->findAll([
            'where' => [['id', '=', $task_id]],
            'related' => ['user'],
            'limit' => 1,
        ]);

        if (empty($tasks)) {
            \Session::set_flash('error', 'Task not found.');
            \Response::redirect('task');
        }

        $task = $tasks[0];

        // Chỉ owner mới được delete
        if ($task['user_id'] != $this->user->id) {
            \Session::set_flash('error', 'You are not authorized to delete this task.');
            \Response::redirect('task');
        }

        // Delete task và tất cả shares
        $this->repository_task_share->delete(['task_id' => $task_id]);
        $result = $this->repository_task->delete($task_id);

        if ($result) {
            \Session::set_flash('success', 'Task deleted successfully!');
        } else {
            \Session::set_flash('error', 'Failed to delete task. Please try again.');
        }

        \Response::redirect('task');
    }

    public function action_delete_ajax($task_id)
    {
        $json = [
            'success' => false,
            'message' => 'Unauthorized'
        ];

        try {
            // Tìm task
            $tasks = $this->repository_task->findAll([
                'where' => [['id', '=', $task_id]],
                'related' => ['user'],
                'limit' => 1,
            ]);

            if (empty($tasks)) {
                $json['message'] = 'Task not found';
                return $this->json_response($json);
            }

            $task = $tasks[1];

            // Chỉ owner mới delete được
            if ($task['user_id'] != $this->user->id) {
                $json['message'] = 'You are not authorized to delete this task';
                return $this->json_response($json);
            }

            // Delete shares trước
            $this->repository_task_share->delete(['task_id' => $task_id]);

            // Delete task
            if ($this->repository_task->delete($task_id)) {
                $json = [
                    'success' => true,
                    'message' => 'Task deleted successfully'
                ];
            } else {
                $json['message'] = 'Failed to delete task';
            }
        } catch (Exception $e) {
            $json['message'] = 'Server error: ' . $e;
        }

        return $this->json_response($json);
    }

    // ✅ HELPER METHOD - TRẢ JSON
    private function json_response($data)
    {
        $response = \Response::forge(\Format::forge($data)->to_json(), 200, [
            'Content-Type' => 'application/json'
        ]);
        return $response;
    }

    private function checkAuth()
    {
        // Check login using session
        if (!\Session::get('user_id')) {
            Response::redirect('login');
        }
    }
}
