<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Cache;
use Modules\User\Exceptions\InvalidOrExpiredResetCode;
use Modules\User\Exceptions\UserNotFoundException;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Http\Requests\RegisterRequest;
use Modules\User\Http\Requests\ResetCompleteRequest;
use Modules\User\Http\Requests\ResetRequest;
use Modules\User\Services\UserRegistration;
use Modules\User\Services\UserResetter;
use Modules\User\Contracts\Authentication;

class AuthController extends Controller
{
    use DispatchesJobs;
    protected $auth;

    public function __construct()
    {
        $this->auth = app(Authentication::class);
    }
    
    public function getLogin()
    {
        $settings = Cache::get('options');
        $captcha = json_decode($settings['site.captcha']);
        
        return view('user::front.login', [
            'sitename' => $settings['site.name'],
            'captcha' => $captcha,   
        ]);
    }

    public function postLogin(LoginRequest $request)
    {
        $credentials = [
            'login' => $request->email,
            'password' => $request->password,
        ];

        $remember = (bool) $request->get('remember_me', false);

        $error = $this->auth->login($credentials, $remember);

        if ($error) {
            return redirect()->back()->withInput()->withError($error);
        }

        return redirect()->intended(route('admin.index'));
    }

    public function getRegister()
    {
        $settings = Cache::get('options');
        $captcha = json_decode($settings['site.captcha']);
        
        return view('user::front.register', [
            'sitename' => $settings['site.name'],
            'captcha' => $captcha,   
        ]);
    }

    public function postRegister(RegisterRequest $request)
    {
        $user = app(UserRegistration::class)->register(clean($request->all()));

        if(env('CONFIRM_SEND_MAIL', false)) {
            return redirect()->route('login')
                ->withSuccess(trans('user::messages.front.auth.account_created'));
        }
        
        if(env('CONFIRM_SEND_MAIL', false)==0 && env('CONFIRM_BY_ADMIN', false)==0) {
            $activationCode = $this->auth->createActivation($user);
            return $this->getActivate($user->id, $activationCode);
        }
    }

    public function getLogout()
    {
        $this->auth->logout();

        return redirect()->route('login');
    }

    public function getActivate($userId, $code)
    {
        if ($this->auth->activate($userId, $code)) {
            return redirect()->route('login')
                ->withSuccess(trans('user::messages.front.auth.account activated you can now login'));
        }

        return redirect()->route('register')
            ->withError(trans('user::messages.front.auth.there was an error with the activation'));
    }

    public function getReset()
    {
        $settings = Cache::get('options');
        $captcha = json_decode($settings['site.captcha']);
        
        return view('user::front.reset.begin', [
            'sitename' => $settings['site.name'],
            'captcha' => $captcha,   
        ]);
    }

    public function postReset(ResetRequest $request)
    {
        try {
            app(UserResetter::class)->startReset($request->all());
        } catch (UserNotFoundException $e) {
            return redirect()->back()->withInput()
                ->withError(trans('user::messages.front.auth.no user found'));
        }

        return redirect()->route('reset')
            ->withSuccess(trans('user::messages.front.auth.check email to reset password'));
    }

    public function getResetComplete()
    {
        $settings = Cache::get('options');
        return view('user::front.reset.complete', ['sitename' => $settings['site.name']]);
    }

    public function postResetComplete($userId, $code, ResetCompleteRequest $request)
    {
        try {
            app(UserResetter::class)->finishReset(
                array_merge($request->all(), ['userId' => $userId, 'code' => $code])
            );
        } catch (UserNotFoundException $e) {
            return redirect()->back()->withInput()
                ->withError(trans('user::messages.front.auth.user no longer exists'));
        } catch (InvalidOrExpiredResetCode $e) {
            return redirect()->back()->withInput()
                ->withError(trans('user::messages.front.auth.invalid reset code'));
        }

        return redirect()->route('login')
            ->withSuccess(trans('user::messages.front.auth.password reset'));
    }
}
