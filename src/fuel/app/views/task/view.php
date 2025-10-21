<?php
/**
 * View for displaying a specific task
 */

use Fuel\Core\Session;
use Fuel\Core\Uri;

?>
<h1>Task: <?php echo $task->title; ?></h1>

<?php if (Session::get_flash('error')): ?>
    <p style="color: red;"><?php echo Session::get_flash('error'); ?></p>
<?php endif; ?>

<p>Description: <?php echo $task->description ?: 'No description'; ?></p>
<p>Status: <?php echo $status_text; ?></p>
<p>Deadline: <?php echo $task->deadline ?: 'No deadline'; ?></p>

<h2>Shared with:</h2>
<?php if (empty($shared_users)): ?>
    <p>This task is not shared with anyone.</p>
<?php else: ?>
    <ul>
    <?php foreach ($shared_users as $user): ?>
        <li><?php echo $user->username; ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?php echo Uri::create('task'); ?>">Back to Tasks</a>
