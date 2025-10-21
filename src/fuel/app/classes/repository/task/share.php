<?php

class Repository_Task_Share extends Repository_Base
{
    public function getModel()
    {
        return Model_Task_Share::class;
    }

    /**
     * Get all users shared with a task
     * @param  int  $task_id The ID of the task
     * @return \Model_User[]
     */
    public function getSharedUsers(int $task_id)
    {
        $shares = $this->findAll([
            'where' => [
                ['task_id', '=', $task_id],
            ],
            'related' => ['user'],
        ]);

        return array_map(function($share) {
            return $share->user;
        }, $shares);
    }

    /**
     * Check if a user is shared with a task
     * @param  int  $task_id The ID of the task
     * @param  int  $user_id The ID of the user
     * @return bool
     */
    public function isSharedWithUser(int $task_id, int $user_id)
    {
        $share = $this->findAll([
            'where' => [
                ['task_id', '=', $task_id],
                ['user_id', '=', $user_id],
            ],
        ]);

        return !empty($share);
    }
}
