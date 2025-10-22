<?php
class Controller_Auth extends Controller
{
    public function action_login()
    {
        if (\Input::method() == 'POST') {
            $email = \Input::post('email');
            $password = \Input::post('password');

            $user = Model_User::validate_login($email, $password);
            if ($user) {
                \Session::set('user_id', $user->id);
                \Session::set('email', $user->email);
                \Session::set('role', $user->role);
                \Session::set_flash('success', 'Login successful.');
                \Response::redirect('task');
            } else {
                \Session::set_flash('error', 'Invalid email or password.');
            }
        }

        return \View::forge('auth/login');
    }

    public function action_logout()
    {
        \Session::delete('user_id');
        \Session::delete('email');
        \Session::delete('role');
        \Session::set_flash('success', 'Logged out successfully.');
        \Response::redirect('login');
    }

    public function action_register()
    {
        if (\Input::method() == 'POST') {
            $email = \Input::post('email');
            $password = \Input::post('password');
            $username = \Input::post('username'); 

            if (empty($email) || empty($password)) {
                \Session::set_flash('error', 'Email and password are required.');
            } else {
                $existing_user = Model_User::find('first', array('where' => array('email' => $email)));
                if ($existing_user) {
                    \Session::set_flash('error', 'Email already exists.');
                } else {
                    $salt = bin2hex(random_bytes(16));
                    $hashed_password = Model_User::hash_password($password, $salt);

                    $user = Model_User::forge(array(
                        'email' => $email,
                        'username' => $username,
                        'password' => $hashed_password,
                        'salt' => $salt,
                        'role' => 'user',
                        'last_login' => date('Y-m-d H:i:s'),
                    ));
                    if ($user->save()) {
                        \Session::set_flash('success', 'User created successfully.');
                        \Response::redirect('login');
                    } else {
                        \Session::set_flash('error', 'Failed to create user.');
                    }
                }
            }
        }

        return \View::forge('auth/register');
    }

    public function action_check_role()
    {
        $role = \Session::get('role', 'guest');
        return \Response::forge($role);
    }
}
