<?php

namespace Modules\User\Events;

use Modules\User\Contracts\EntityIsChanging;
use Modules\User\Events\AbstractEntityHook;

final class UserIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
