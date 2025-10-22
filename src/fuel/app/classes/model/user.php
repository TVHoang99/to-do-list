<?php
class Model_User extends \Orm\Model
{
    protected static $_properties = [
        'id',
        'username',
        'email',
        'password',
        'salt',
        'last_login' => array('default' => null),
        'login_hash' => array('default' => null),
        'created_at' => array('default' => null),
        'updated_at' => array('default' => null),
        'role' => array('default' => 'user'), // Đã thêm cột role
    ];

    protected static $_observers = [
        'Orm\Observer_Self' => array(
            'events' => array('before_save'),
            'method' => 'update_last_login',
            'property' => 'last_login',
        ),
        'Orm\Observer_CreatedAt' => [
            'events' => ['before_insert'],
            'property' => 'created_at',
            'mysql_timestamp' => true,
        ],
        'Orm\Observer_UpdatedAt' => [
            'events' => ['before_update'],
            'property' => 'updated_at',
            'mysql_timestamp' => true,
        ],
    ];

    protected static $_table_name = 'users';

    protected static $_primary_key = ['id'];

    protected static $_has_many = [
        'tasks' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Task',
            'key_to' => 'user_id',
            'cascade_save' => true,
            'cascade_delete' => true,
        ),
    ];

    protected static $_many_many = [
        'shared_tasks' => array(
            'key_from' => 'id',
            'key_through_from' => 'user_id',
            'table_through' => 'task_shares',
            'key_through_to' => 'task_id',
            'model_to' => 'Model_Task',
            'key_to' => 'id',
        ),
    ];

    /**
     * Update last_login before saving
     */
    public function update_last_login()
    {
        // Kiểm tra session dựa trên user_id
        if (\Session::get('user_id') && $this->id == \Session::get('user_id')) {
            $this->last_login = date('Y-m-d H:i:s'); // Ensure datetime format
        }
    }

    /**
     * Validate login credentials using email
     */
    public static function validate_login($email, $password)
    {
        $user = static::query()->where('email', $email)->get_one();
        if ($user && self::verify_password($password, $user->password, $user->salt)) {
            $user->last_login = date('Y-m-d H:i:s');
            $user->save();
            return $user;
        }
        return false;
    }

    /**
     * Verify password with stored hash and salt
     */
    public static function verify_password($password, $hashed_password, $salt)
    {
        return hash('sha256', $password . $salt) === $hashed_password;
    }

    /**
     * Hash password with salt
     */
    public static function hash_password($password, $salt)
    {
        return hash('sha256', $password . $salt);
    }
}
