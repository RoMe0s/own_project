<?php

namespace App\Classes\OAuth;

use App\Http\Requests\Frontend\Auth\UserRegisterRequest;
/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 11/8/16
 * Time: 6:07 PM
 */
class Vk
{
    public static function response($provider, $code = null)
    {

        if(!$code) {

            $code_url = 'https://oauth.vk.com/authorize?client_id=' . $provider->client_id . '&redirect_uri=' . url($provider->redirect_uri) . '&response_type=' . $provider->response_type . '&display=' . $provider->display . '&scope=email';

            echo "<script> top.location.href='" . $code_url . "'</script>";

        } else {

            $token_url =  'https://oauth.vk.com/access_token?client_id=' . $provider->client_id . '&client_secret=' . $provider->client_secret . '&redirect_uri=' . url($provider->redirect_uri) .  '&response_type=' . $provider->response_type . '&code=' . $code . '';

            $params = json_decode(@file_get_contents($token_url));

            if(!$params) return false;

            $data_url = 'https://api.vk.com/method/users.get?users_id=' . $params->user_id . '&fields=photo_50&access_token=' . $params->access_token . '&v=5.60';

            $data = json_decode(@file_get_contents($data_url));

            return static::prepare(isset($params->email) ? $params->email : null, (array)$data->response[0], $provider->id);

        }

    }

    private static function prepare($email, $data, $provider) {

        if(isset($email)) {
            $data['email'] = $email;
        }

        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];

        $data['avatar'] = $data['photo_50'];

        $data['oauth_id'] = $data['id'];

        $data['provider'] = $provider;

        $data['activated'] = true;

        $data['password'] = str_random(6);

        unset($data['first_name'], $data['last_name'], $data['photo_50'], $data['id']);

        return !empty($data) ? $data : false;
    }
}