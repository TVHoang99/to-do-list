<?php
/**
 * View for displaying the list of tasks
 */

use Fuel\Core\Uri;

?>
<h1>Tasks for <?php echo $user->username; ?></h1>

<h2>Your Tasks</h2>
<?php if (empty($own_tasks)): ?>
    <p>No tasks created yet.</p>
<?php else: ?>
    <ul>
    <?php foreach ($own_tasks as $task): ?>
        <li>
            <a href="<?php echo Uri::create('task/view/' . $task->id); ?>">
                <?php echo $task->title; ?>
            </a>
            (Status: <?php echo $task->get_status_text(); ?>)
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h2>Tasks Shared with You</h2>
<?php if (empty($shared_tasks)): ?>
    <p>No tasks shared with you.</p>
<?php else: ?>
    <ul>
    <?php foreach ($shared_tasks as $task): ?>
        <li>
            <a href="<?php echo Uri::create('task/view/' . $task->id); ?>">
                <?php echo $task->title; ?>
            </a>
            (Status: <?php echo $task->get_status_text(); ?>)
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?php echo Uri::create('task/create'); ?>">Create New Task</a>
