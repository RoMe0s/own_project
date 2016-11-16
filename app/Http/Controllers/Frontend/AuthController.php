<?php namespace App\Http\Controllers\Frontend;

use App\Events\Frontend\UserRegister;
use App\Exceptions\NotValidImageException;
use App\Http\Requests\Frontend\Auth\UserEmailRequest;
use App\Models\Oauth;
use App\Services\AuthService;
use App\Http\Requests\Frontend\Auth\UserRegisterRequest;
use App\Models\User;
use App\Models\UserInfo;
use App\Services\UserService;
use App\Traits\Controllers\SaveImageTrait;
use Carbon;
use Cartalyst\Sentry\Throttling\UserBannedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserAlreadyActivatedException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use DB;
use Event;
use Exception;
use FlashMessages;
use Illuminate\Http\Request;
use Mail;
use Sentry;

/**
 * Class AuthController
 * @package App\Http\Controllers\Frontend
 */
class AuthController extends FrontendController
{
    
    use SaveImageTrait;
    
    /**
     * @var \App\Services\AuthService
     */
    protected $authService;

    /**
     * @var \App\Services\UserService
     */
    protected $userService;
    
    /**
     * AuthController constructor.
     *
     * @param \App\Services\AuthService $authService
     * @param \App\Services\UserService $userService
     */
    public function __construct(AuthService $authService, UserService $userService)
    {
        parent::__construct();
        
        $this->authService = $authService;
        $this->userService = $userService;

        $this->setRedirectTo();
    }
    
    /**
     * @return mixed
     */
    public function getLogin(Request $request)
    {
        if(Sentry::check()) {
            return redirect($this->getRedirectTo());
        }
        if($request->ajax()) {
            try {
                return [
                    'status' => 'success',
                    'html' => view('partials.popups.auth')->render(),
                ];
            } catch (Exception $e) {
                return [
                    'status' => 'error',
                    'message' => trans('messages.an error has occurred, try_later'),
                ];
            }
        } else {
            \Meta::title(trans('front_labels.sign in'));
            return $this->render('auth.login');
        }
    }
    
    /**
     * @param array $credentials
     *
     * @return mixed
     */
    public function postLogin(Request $request, $credentials = [])
    {

        //Login with oauth password
        if(session()->has('oauth_user')) {
            $credentials = [
                'email' => session()->get('oauth_user')['email'],
                'password' => $request->input('password')
            ];
        }
        //Default login
        $credentials = !empty($credentials) ? $credentials : [
            'email'    => request('email'),
            'password' => request('password'),
        ];
        
        try {
            if ($user = $this->authService->login($credentials)) {

                FlashMessages::add('success', isset($user->name) ? trans('front_messages.hello') . $user->name : trans('front_messages.hello') . $user->email);

                //If oauth then update users_oauths table
                if(session()->has('oauth_user')) {

                    $this->authService->proccessOauth($user, session()->pull('oauth_user'));

                }

                if($request->ajax()) {
                    return ['status' => 'success', 'redirect' => $this->getRedirectTo()];
                } else {
                    return redirect($this->getRedirectTo());
                }
            }
            
            $error = trans('messages.access_denied');
        } catch (LoginRequiredException $e) {
            $error = trans('messages.enter your login');
        } catch (PasswordRequiredException $e) {
            $error = trans('messages.enter your password');
        } catch (WrongPasswordException $e) {
            $error = trans('messages.you have entered a wrong password');
        } catch (UserNotFoundException $e) {
            $error = trans('messages.user with such email was not found');
        } catch (UserNotActivatedException $e) {
            $error = trans('messages.user with such email was not activated');
        } catch (UserSuspendedException $e) {
            $error = trans('messages.user with such email was blocked');

            $user = User::where('email', $credentials['email'])->first();

            $throttle = Sentry::findThrottlerByUserId($user->id);

            $timestamp = strtotime($throttle->suspended_at);
            if ($timestamp) {
                $suspensionTime = $throttle->getSuspensionTime();

                $carbon = Carbon::createFromTimestamp($timestamp)->addMinutes($suspensionTime);

                $error .= ' '.trans('messages.to').' '.$carbon->format('d.m.Y H:i');
            }
        } catch (UserBannedException $e) {
            $error = trans('messages.user with such email was banned');
        } catch (Exception $e) {
            $error = trans('messages.an error has occurred, try_later');
        }
        if($request->ajax()) {
            return ['status' => 'error', 'message' => $error];
        } else {
            return redirect()->back()->withInput($request->all())->withErrors($error);
        }
    }
    
    /**
     * @return mixed
     */
    public function getLogout()
    {
        Sentry::logout();
        
        return redirect()->home();
    }
    
    /**
     * @return array
     */
    public function getRegister(Request $request)
    {
        if(Sentry::check()) {
            return redirect($this->getRedirectTo());
        }
/*        $genders = [];
        foreach (UserInfo::$genders as $gender) {
            $genders[$gender] = trans('labels.'.$gender);
        }
        $this->data('genders', $genders);*/

        if($request->ajax()) {
            try {
                return [
                    'status' => 'success',
                    'html' => view('partials.popups.register')->render(),
                ];
            } catch (Exception $e) {
                return [
                    'status' => 'error',
                    'message' => trans('messages.an error has occurred, try_later'),
                ];
            }
        } else {
            \Meta::title(trans('front_labels.sign up'));
            return $this->render('auth.register');
        }
    }
    
    /**
     * @param \App\Http\Requests\Frontend\Auth\UserRegisterRequest $request
     *
     * @return mixed
     */
    public function postRegister(UserRegisterRequest $request)
    {

        $input = [];

        //If default register first step
        if($request->ajax() && $request->has('first_step')) {

            session()->flash('register["email"]', $request->input('email'));

            $html = view('partials.popups.register.second')->render();

            return ['status' => 'success', 'html' => $html];
        }

        //generate full request for register user
        if(session()->has('register["email"]')) {

            $request->merge(['email' => session('register["email"]')]);

            //oauth user
        } elseif(session()->has('oauth_user')) {

            $oauth_user = session()->pull('oauth_user');

            //Remove wrong email from array
            unset($oauth_user['email']);

            $request->merge($oauth_user);

        } /*else {

            if($request->ajax()) {
                return ['status' => 'error', 'message' => trans('messages.user register error')];
            } else {
                \FlashMessages::add('error', trans('messages.user register error'));
                return redirect()->back()->withInput($request->all());
            }

        }*/
        
        DB::beginTransaction();
        
//        try {
            //$this->validateImage('image');
            
            $input = $this->authService->prepareRegisterInput($request);
            
            $user = $this->authService->register($input);

            $this->userService->processUserInfo($user, $input);

            $this->userService->processFields($user);

            $this->authService->proccessOauth($user, $request->only(['oauth_id', 'provider']));
            
            //Event::fire(new UserRegister($user, $input));

            if(isset($input['activated']) && $input['activated']) {

                Sentry::login($user, false);

            } else {

                FlashMessages::add(
                    'success',
                    trans('messages.user register success message')
                );

            }
            
            DB::commit();

            if($request->ajax()) {

                return ['status' => 'success', 'redirect' => $this->getRedirectTo()];

            } else {

                return redirect()->to($this->getRedirectTo());

            }

        /*} catch (NotValidImageException $e) {
            $message = trans('messages.trying to load is too large file or not supported file extension');
        } catch (Exception $e) {
            $message = trans('messages.user register error');
        }*/

        DB::rollBack();
        
        dd('here');

        if($request->ajax()) {
            return ['status' => 'error', 'message' => $message];
        } else {
            return redirect()->back()->withInput($input)->withErrors($message);
        }
    }
    
    /**
     * @param string $email
     * @param string $code
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getActivate($email, $code)
    {
        try {
            $user = Sentry::findUserByLogin($email);
            
            if ($user->attemptActivation($code)) {
                FlashMessages::add(
                    'success',
                    trans('messages.congratulations, you have successfully activate your account')
                );
                
                return redirect()->home();
            } else {
                $error = trans('messages.user activation failed, wrong activation code');
            }
        } catch (UserNotFoundException $e) {
            $error = trans('messages.user with such email was not found');
        } catch (UserAlreadyActivatedException $e) {
            $error = trans('messages.user with such email already activated');
        } catch (Exception $e) {
            $error = trans('messages.user activation failed, try again later');
        }
        
        FlashMessages::add('error', $error);
        
        return redirect()->home();
    }
    
    /**
     * @param Request $request
     *
     * @return $this
     */
    public function postRestore(Request $request)
    {
        $email = $request->get('email');
        
        try {
            $user = Sentry::findUserByLogin($email);
            
            if ($user->activated) {
                Mail::queue(
                    'emails.auth.restore',
                    ['email' => $email, 'token' => $user->getResetPasswordCode()],
                    function ($message) use ($user) {
                        $message->to($user->email, $user->getFullName())
                            ->subject(trans('labels.password_restore_subject'));
                    }
                );
                
                return [
                    'status'  => 'success',
                    'message' => trans('messages.password restore message'),
                ];
            }
            
            $error = trans('messages.user with such email was not activated');
        } catch (UserNotFoundException $e) {
            $error = trans('messages.user with such email was not found');
        } catch (Exception $e) {
            $error = trans('messages.an error has occurred, try_later');
        };
        
        return [
            'status'  => 'error',
            'message' => $error,
        ];
    }
    
    /**
     * @param string $email
     * @param string $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getReset($email = '', $token = '')
    {
        try {
            $user = Sentry::findUserByLogin($email);
            
            if ($user->checkResetPasswordCode($token)) {
                $password = str_random(6);
                
                if ($user->attemptResetPassword($token, $password)) {
                    Mail::queue(
                        'emails.auth.reset',
                        ['email' => $email, 'password' => $password],
                        function ($message) use ($user) {
                            $user = User::find($user->id);
                            
                            $message->to($user->email, $user->getFullName())
                                ->subject(trans('labels.password_reset_success_subject'));
                        }
                    );
                    
                    FlashMessages::add(
                        'success',
                        trans('messages.password restore success message')
                    );
                    
                    return redirect()->home();
                } else {
                    $error = trans('messages.you have entered an invalid code');
                }
            } else {
                $error = trans('messages.you have entered an invalid code');
            }
        } catch (UserNotFoundException $e) {
            $error = trans('messages.user with such email was not found');
        } catch (Exception $e) {
            $error = trans('messages.an error has occurred, try_later');
        }
        
        FlashMessages::add('error', $error);
        
        return redirect()->home();
    }

    public function oauth(Request $request, $provider)
    {

        if(Sentry::check()) {

            return redirect($this->getRedirectTo());

        }

        session()->forget('oauth_user');

        $provider = Oauth::visible()->where('name', $provider)->first();

        if(!$provider) {
            \App::abort(404);
        }

        $code = $request->has('code') ? $request->get('code') : null;

        $result = $this->authService->oauth($provider, $code);

        //user already exists
        if(is_object($result)) {

            Sentry::login($result, false);

            return redirect()->to($this->getRedirectTo());

            //have to generate new user
        } elseif(is_array($result)) {

            session()->put('oauth_user', $result);

            if(isset($result['email']))
            {

                $user = User::where('email', $result['email'])->first();

                //email is empty
                if(!$user) {

                    $userRegisterRequest = new UserRegisterRequest();

                    $userRegisterRequest->merge(['email' => $result['email']]);

                    return $this->postRegister($userRegisterRequest);

                    //email is not empty
                } else {

                    \Meta::title(trans('front_labels.email exist'));

                    return $this->render('partials.popups.register.email_exist',
                        ['result' => $result,
                            'user' => $user,
                            'message' => trans('front_messages.that is not my email message'),
                            'post_register' => route('auth.post.register')
                        ]);

                }

            }

            \Meta::title(trans('front_labels.registration completion'));

            return $this->render('partials.popups.register.oauth', ['result' => $result]);

        } elseif($code) {

            $error = trans('messages.an error has occurred, try_later');

            FlashMessages::add('error', $error);

            return redirect()->home();
        }

    }
    
    /**
     * set redirect after register login
     */
    private function setRedirectTo()
    {
        if (
            url()->previous() !== url()->current() &&
            strpos(url()->previous(), '/auth/') === false &&
            strpos(url()->previous(), '/profiles/') &&
            check_local()
        ) {
            session()->put('returnTo', url()->previous());
        }
    }
    
    /**
     * @return string
     */
    private function getRedirectTo()
    {
        $url = session('returnTo', false);
        
        if ($url) {
            session()->forget('returnTo');
        }
        
        return localize_url($url ? : url('/'));
    }
}