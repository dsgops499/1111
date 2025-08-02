<?php

namespace Modules\MySpace\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Base\Http\Controllers\FileUploadController;
use Modules\User\Contracts\Authentication;
use Modules\User\Contracts\UserRepository;
use Modules\User\Http\Requests\UpdateProfileRequest;
use Modules\User\Entities\User;

class MySpaceController extends Controller
{
    private $auth;
    private $repo;

    public function __construct(Authentication $auth, UserRepository $repo)
    {
        $this->auth = $auth;
        $this->repo = $repo;
        $this->middleware('permission:user.profile', ['only' => ['edit', 'update']]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($username)
    {
        $user = User::where('username', $username)->first();
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        
        return view(
            'front.themes.' . $theme . '.blocs.user.profil', 
            [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                "user" => $user,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        $user = User::find($this->auth->id());
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        
        return view(
            'front.themes.' . $theme . '.blocs.user.profil_edit', 
            [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                "user" => $user,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateProfileRequest $request)
    {
        $data = clean($request->all());
        $user = $this->auth->user();
        $this->repo->update($user, $data);

        $avatar = $data['cover'];
        $user->avatar = 1;

        if (str_contains($avatar, FileUploadController::$TMP_AVATAR_DIR)) {
            $coverCreated = FileUploadController::createAvatar($avatar, $this->auth->id());
            if (!$coverCreated) {
                $user->avatar = null;
            }
        } else if (is_null($avatar) || $avatar == "") {
            $user->avatar = null;
            // clear avatar directory
            FileUploadController::cleanAvatarDirectory($this->auth->id());
        }

        $user->save();

        return redirect()->back()
            ->withSuccess(trans('messages.admin.settings.update.profile-success'));
    }
}
