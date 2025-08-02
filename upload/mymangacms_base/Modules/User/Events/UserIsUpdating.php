<?php

namespace Modules\User\Events;

use Modules\User\Contracts\EntityIsChanging;
use Modules\User\Events\AbstractEntityHook;
use Modules\User\Contracts\UserInterface;

final class UserIsUpdating extends AbstractEntityHook implements EntityIsChanging
{
    /**
     * @var UserInterface
     */
    private $user;

    public function __construct(UserInterface $user, array $data)
    {
        $this->user = $user;
        parent::__construct($data);
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}
