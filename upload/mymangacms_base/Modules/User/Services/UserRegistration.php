<?php

namespace Modules\User\Services;

use Modules\User\Contracts\Authentication;
use Modules\User\Events\UserHasRegistered;
use Modules\User\Contracts\RoleRepository;

class UserRegistration
{
    /**
     * @var Authentication
     */
    private $auth;
    /**
     * @var RoleRepository
     */
    private $role;
    /**
     * @var array
     */
    private $input;

    public function __construct(Authentication $auth, RoleRepository $role)
    {
        $this->auth = $auth;
        $this->role = $role;
    }

    /**
     * @param array $input
     * @return mixed
     */
    public function register(array $input)
    {
        $this->input = $input;

        $user = $this->createUser();

        $this->assignUserToUsersGroup($user);

        if(env('CONFIRM_SEND_MAIL', false)) {
            event(new UserHasRegistered($user));
        }
        
        return $user;
    }

    private function createUser()
    {
        return $this->auth->register((array) $this->input);
    }

    private function assignUserToUsersGroup($user)
    {
        $role = $this->role->find(env('DEFAULT_ROLE', 3));

        $this->auth->assignRole($user, $role);
    }

}
