<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.9-dev
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

return array(
	'_root_' => 'task/index', // Default route
    'login' => 'auth/login', // Route for login page
    'logout' => 'auth/logout', // Route for logout
    'task' => 'task/index', // Route for task index
    'task/create' => 'task/create', // Route for creating a task
    'task/view/(:segment)' => 'task/view/$1', // Route for viewing a task
	'register' => 'auth/register', // Route for user registration
    'task/share/(:segment)' => 'task/share/$1',
    'task/unshare/(:segment)/(:segment)' => 'task/unshare/$1/$2',
    'task/delete/(:segment)' => 'task/delete/$1',
    'task/delete_ajax/(:segment)' => 'task/delete_ajax/$1',
);
