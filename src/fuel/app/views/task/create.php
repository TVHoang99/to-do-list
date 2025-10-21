<?php
/**
 * View for creating a new task
 */

use Fuel\Core\Session;
use Fuel\Core\Uri;

?>
<h1>Create New Task</h1>

<?php if (Session::get_flash('error')): ?>
    <p style="color: red;"><?php echo Session::get_flash('error'); ?></p>
<?php endif; ?>
<?php if (Session::get_flash('success')): ?>
    <p style="color: green;"><?php echo Session::get_flash('success'); ?></p>
<?php endif; ?>

<form method="post" action="<?php echo Uri::create('task/create'); ?>">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required>
    <br>
    <label for="description">Description:</label>
    <textarea name="description" id="description"></textarea>
    <br>
    <label for="status">Status:</label>
    <select name="status" id="status">
        <option value="0">Pending</option>
        <option value="1">Completed</option>
    </select>
    <br>
    <label for="deadline">Deadline:</label>
    <input type="date" name="deadline" id="deadline">
    <br>
    <button type="submit">Create Task</button>
</form>
<a href="<?php echo Uri::create('task'); ?>">Back to Tasks</a>
