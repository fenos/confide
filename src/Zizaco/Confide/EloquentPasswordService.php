<?php namespace Zizaco\Confide;

use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * A service that abstracts all user password management related methods
 */
class EloquentPasswordService
{
    /**
     * Laravel application
     *
     * @var Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Create a new PasswordService
     *
     * @param  \Illuminate\Foundation\Application $app Laravel application object
     * @return void
     */
    public function __construct($app = null)
    {
        $this->app = $app ?: app();
    }

    /**
     * Generate a token for password change and saves it in
     * the 'password_reminders' table with the email of the
     * user.
     *
     * @param  RemindableInterface $user     An existent user
     * @return string Password reset token
     */
    public function requestChangePassword(RemindableInterface $user)
    {
        $email = $user->getReminderEmail();
        $token = $this->generateToken();

        $values = array(
            'email'=> $email,
            'token'=> $token,
            'created_at'=> new \DateTime
        );

        $this->app['db']
            ->connection()
            ->table('password_reminders')
            ->insert( $values );

        return $token;
    }
}
