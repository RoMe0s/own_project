<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.11.15
 * Time: 13:53
 */

namespace App\Http\Requests\Frontend\Auth;

use App\Http\Requests\FormRequest;
use App\Models\UserInfo;
use App\Http\Requests\Request;

/**
 * Class UserRegisterRequest
 * @package App\Http\Requests\Frontend\Auth
 */
class UserRegisterRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'                  => 'required_with:password',
            'email'                 => 'required_without:name,password,password_confirmation|email|unique:users',
            //'phone'                 => 'string|regex:/^\+[0-9]+$/|max:17|min:' . config('user.min_phone_length'),
            'password'              => 'required_with:name|confirmed:password_confirmation|min:' .
                config('auth.passwords.min_length'),
            'password_confirmation' => 'required_with:password',
        ];
        
        return $rules;
    }
}