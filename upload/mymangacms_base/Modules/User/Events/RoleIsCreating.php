<?php

namespace Modules\User\Events;

use Modules\User\Contracts\EntityIsChanging;
use Modules\User\Events\AbstractEntityHook;

class RoleIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
