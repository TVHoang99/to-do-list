<?php
class Controller_Auth extends Controller
{
    /**
     * Display the login page or handle login form submission
     */
    public function action_login()
    {
        // If user is already logged in, redirect to task index
        if (\Auth::check()) {
            \Response::redirect('task');
        }

        // Handle form submission
        if (\Input::method() == 'POST') {
            $email = \Input::post('email');
            $password = \Input::post('password');
            $remember = \Input::post('remember') === 'on'; // Check if "remember me" is checked

            // Attempt to log in with custom salt
            $user = \Model_User::find('first', array('where' => array('email' => $email)));
            if ($user && $this->verify_password($password, $user->password, $user->salt)) {
                $user->last_login = date('Y-m-d H:i:s'); // Update last_login with datetime format
                if ($remember) {
                    $login_hash = $this->generate_login_hash(); // Generate login hash for "remember me"
                    $user->login_hash = $login_hash;
                    // Store hash in cookie (simplified, adjust for security)
                    \Cookie::set('login_hash', $login_hash, 604800); // 7 days expiration
                }
                $user->save();
                \Auth::force_login($user->id);
                \Session::set_flash('success', 'Login successful.');
                \Response::redirect('task');
            } else {
                \Session::set_flash('error', 'Invalid username or password.');
            }
        }

        // Render the login view
        return \View::forge('auth/login');
    }

    /**
     * Log out the user
     */
    public function action_logout()
    {
        $user = \Auth::get_user();
        if ($user) {
            $user->login_hash = null; // Clear login_hash on logout
            $user->save();
            \Cookie::delete('login_hash'); // Clear cookie
        }
        \Auth::logout();
        \Session::set_flash('success', 'Logged out successfully.');
        \Response::redirect('login');
    }

    /**
     * Display the register page or handle user registration
     */
    public function action_register()
    {
        // If user is already logged in, redirect to task index (optional restriction)
        if (\Auth::check()) {
            \Response::redirect('task');
        }

        // Handle form submission
        if (\Input::method() == 'POST') {
            $username = \Input::post('username');
            $password = \Input::post('password');
            $email = \Input::post('email');

            // Validate input
            if (empty($username) || empty($password) || empty($email)) {
                \Session::set_flash('error', 'All fields are required.');
            } else {
                // Check if username already exists
                $existing_user = \Model_User::find('first', array('where' => array('username' => $username)));
                if ($existing_user) {
                    \Session::set_flash('error', 'Username already exists.');
                } else {
                    // Generate a random salt
                    $salt = bin2hex(random_bytes(16));
                    // Hash password with salt
                    $hashed_password = $this->hash_password($password, $salt);

                    // Create new user
                    $user = \Model_User::forge(array(
                        'username' => $username,
                        'password' => $hashed_password,
                        'salt' => $salt,
                        'email' => $email,
                        'created_at' => date('Y-m-d H:i:s'),
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

        // Render the register view
        return \View::forge('auth/register');
    }

    /**
     * Hash password with salt
     */
    protected function hash_password($password, $salt)
    {
        return hash('sha256', $password . $salt);
    }

    /**
     * Verify password with stored hash and salt
     */
    protected function verify_password($password, $hashed_password, $salt)
    {
        $input_hash = $this->hash_password($password, $salt);
        return $input_hash === $hashed_password;
    }

    /**
     * Generate a random login hash
     */
    protected function generate_login_hash()
    {
        return bin2hex(random_bytes(32)); // 64 characters hex string
    }
}
