<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\User\DataTables\UserDataTable;
use Modules\User\Contracts\Authentication;
use Modules\User\Contracts\RoleRepository;
use Modules\User\Contracts\UserRepository;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Http\Requests\UpdateProfileRequest;
use Modules\Base\Entities\Option;

class UserController extends Controller
{
    private $user;
    private $role;
    private $auth;
    
    /**
     * @param PermissionManager $permissions
     * @param UserRepository    $user
     * @param RoleRepository    $role
     * @param Authentication    $auth
     */
    public function __construct(UserRepository $user, RoleRepository $role, Authentication $auth)
    {
        $this->user = $user;
        $this->role = $role;
        $this->auth = $auth;
        $this->middleware('permission:user.users.index', ['only' => ['index']]);
        $this->middleware('permission:user.users.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user.users.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user.users.destroy', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('user::admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $roles = $this->role->all();
		
        return view('user::admin.users.create', 
            ['roles' => $roles]
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param  CreateUserRequest $request
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $data = clean($request->all());

        $this->user->createWithRoles($data, $request->roles, $request->activated);

        return redirect()->route('admin.user.index')
            ->with('updateSuccess', trans('messages.admin.users.user.create-success'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        if (!$user = $this->user->find($id)) {
            return redirect()->route('admin.user.index')
                ->withError('user not found');
        }
        $roles = $this->role->all();

        $currentUser = $this->auth->user();

        return view('user::admin.users.edit', compact('user', 'roles', 'currentUser'));
    }

    /**
     * Update the specified resource in storage.
     * @param  UpdateUserRequest $request
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $data = clean($request->all());

        $this->user->updateAndSyncRoles($id, $data, $request->roles);

        return redirect()->route('admin.user.index')
            ->with('updateSuccess', trans('messages.admin.users.user.update-success'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->user->find($id);

        $chapters = $user->chapters()->get();
        $mangas = $user->manga()->get();
        
        $admin = $this->user->find(1);
        
        if(count($chapters)>0) {
            foreach ($chapters as $chapter){
                $admin->chapters()->save($chapter);
            }
        }
        
        if(count($mangas)>0) {
            foreach ($mangas as $manga){
                $admin->manga()->save($manga);
            }
        }

        $user->delete();

        return redirect()->route('admin.user.index');
    }
    
    public function showSubscriptionOpt()
    {
        $roles = $this->role->all();
        unset($roles[0]);
        
        $options = Option::pluck('value', 'key');
        $subscription = json_decode($options['site.subscription']);
        
        return view('user::admin.settings.subscription', compact('subscription', 'roles'));
    }
    
    public function saveSubscriptionOpt()
    {
        $input = clean(request()->all());
        unset($input['_token']);

        if($input['default_role']== "" || $input['default_role']== "1") {
            return redirect()->back()
            ->withError(trans('user::messages.admin.settings.default_role_incorrect'));
        }
        
        if(!is_null($input['mailing'])) {setEnvironmentValue('MAIL_DRIVER', $input['mailing']);}
        if(!is_null($input['host'])) {setEnvironmentValue('MAIL_HOST', $input['host']);}
        if(!is_null($input['port'])) {setEnvironmentValue('MAIL_PORT', $input['port']);}
        if(!is_null($input['username'])) {setEnvironmentValue('MAIL_USERNAME', $input['username']);}
        if(!is_null($input['password'])) {setEnvironmentValue('MAIL_PASSWORD', $input['password']);}
        if(!is_null($input['encryption'])) {setEnvironmentValue('MAIL_ENCRYPTION', $input['encryption']);}
        if(!is_null($input['name'])) {setEnvironmentValue('MAIL_FROM_NAME', $input['name']);}
        if(!is_null($input['address'])) {setEnvironmentValue('MAIL_FROM_ADDRESS', $input['address']);}
        if(!is_null($input['admin_confirm'])) {setEnvironmentValue('CONFIRM_BY_ADMIN', $input['admin_confirm']=="true"?1:0);}
        if(!is_null($input['email_confirm'])) {setEnvironmentValue('CONFIRM_SEND_MAIL', $input['email_confirm']=="true"?1:0);}
        if(!is_null($input['default_role'])) {setEnvironmentValue('DEFAULT_ROLE', $input['default_role']);}
        if(!is_null($input['subscribe'])) {setEnvironmentValue('ALLOW_SUBSCRIBE', $input['subscribe']=="true"?1:0);}

        Option::findByKey("site.subscription")
            ->update(
                [
                    'value' => json_encode($input)
                ]
            );

        // clean cache
        Cache::forget('options');
        
        return redirect()->back()
            ->withSuccess(
                trans('messages.admin.settings.update.success')
        );
    }
    
    /**
     * Profile page
     * 
     * @return type
     */
    public function profile()
    {
        $user = $this->auth->user();

        return view('user::admin.settings.profile', compact('user'));
    }

    /**
     * Save Profile settings
     * 
     * @return type
     */
    public function saveProfile(UpdateProfileRequest $request)
    {
        $data = clean($request->all());
        $user = $this->auth->user();

        $this->user->update($user, $data);
        
        return redirect()->back()
            ->withSuccess(trans('messages.admin.settings.update.profile-success'));
    }
}
