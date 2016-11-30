<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:01
 */

namespace App\Services;

use App\Http\Requests\Frontend\Auth\UserRegisterRequest;
use App\Models\Athlete;
use App\Models\Oauth;
use App\Models\User;
use App\Models\UsersOauths;
use Carbon;
use Cartalyst\Sentry\Users\UserInterface;
use ImageUploader;
use Request;
use Sentry;
use App\Classes\OAuth\Vk;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService
{

    /**
     * @param array $credentials
     *
     * @return bool|UserInterface
     */
    public function login($credentials = [])
    {

        if (empty($credentials)) {
            return false;
        }

        $user = Sentry::authenticate($credentials, true);
        if (!$user) {
            return false;
        }

        $throttle = Sentry::findThrottlerByUserId($user->id);
        if ($throttle->isSuspended() || $throttle->isBanned()) {
            Sentry::logout();

            return false;
        }

        return $user;
    }

    /**
     * @param array $input
     *
     * @return UserInterface
     */
    public function register($input)
    {
        return Sentry::register($input, isset($input['activated']) ? $input['activated'] : false);
    }

    /**
     * @param UserRegisterRequest $request
     *
     * @return array
     */
    public function prepareRegisterInput(UserRegisterRequest $request)
    {
        $input = $request->only(['name', 'email', 'phone', 'password']);

        $avatar = null;

        if($request->has('avatar') && !$request->file('avatar')){
            $avatar = $request->input('avatar');
        } elseif($request->file('avatar')) {
            $avatar = ImageUploader::upload($request->file('avatar'), 'user');
        }

        $input['avatar'] = $avatar;

        $input['birthday'] = Carbon::now()->format('d-m-Y');

        $input['activated'] = $request->has('activated') ? $request->get('activated') : false;

        $input['ip_address'] = !empty($input['ip_address']) ? $input['ip_address'] : Request::getClientIp();

        return $input;
    }

    public function oauth(Oauth $provider, $code = null)
    {

        switch (strtolower($provider->name)){
            case 'vk':
                $data = Vk::response($provider, $code);
                break;
        }

        if($data === FALSE) {

            return false;

        }

        $user = User::rightJoin('users_oauths', 'users_oauths.user_id', '=', 'users.id')
            ->where('users_oauths.key', $data['oauth_id'])
            ->select('users.*')
            ->first();

        return isset($user) ? $user : $data;

    }

    public function proccessOauth($user, $data)
    {
        if(isset($data['provider']) && isset($data['oauth_id'])) {
            UsersOauths::create([
                'user_id' => $user->id,
                'oauth_id' => $data['provider'],
                'key' => $data['oauth_id']
            ]);
        }
    }


}