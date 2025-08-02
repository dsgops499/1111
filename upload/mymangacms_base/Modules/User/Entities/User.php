<?php

namespace Modules\User\Entities;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Users\EloquentUser;
use Modules\User\Contracts\UserInterface;
use Illuminate\Notifications\Notifiable;

class User extends EloquentUser implements UserInterface, AuthenticatableContract
{
    use Authenticatable, Notifiable;

    protected $fillable = [
        'email',
        'username',
        'password',
        'permissions',
        'name',
    ];

    /**
     * {@inheritDoc}
     */
    protected $loginNames = ['username', 'email'];

    /**
     * @inheritdoc
     */
    public function hasRoleId($roleId)
    {
        return $this->roles()->whereId($roleId)->count() >= 1;
    }

    /**
     * @inheritdoc
     */
    public function hasRoleSlug($slug)
    {
        return $this->roles()->whereSlug($slug)->count() >= 1;
    }

    /**
     * @inheritdoc
     */
    public function hasRoleName($name)
    {
        return $this->roles()->whereName($name)->count() >= 1;
    }

    /**
     * @inheritdoc
     */
    public function isActivated()
    {
        if (Activation::completed($this)) {
            return true;
        }

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function api_keys()
    {
        return $this->hasMany(UserToken::class);
    }

    /**
     * @inheritdoc
     */
    public function getFirstApiKey()
    {
        $userToken = $this->api_keys->first();

        if ($userToken === null) {
            return '';
        }

        return $userToken->access_token;
    }

    /**
     * @inheritdoc
     */
    public function hasAccess($permission)
    {
        $permissions = $this->getPermissionsInstance();

        return $permissions->hasAccess($permission);
    }
    
    public function manga()
    {
        return $this->hasMany('Modules\Manga\Entities\Manga')->orderBy('slug', 'desc');
    }
	
    public function chapters()
    {
        return $this->hasMany('Modules\Manga\Entities\Chapter')->orderBy('slug', 'desc');
    }
}
