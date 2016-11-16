<?php

namespace App\Http\Requests\Backend\Oauth;

use App\Http\Requests\FormRequest;

class OauthUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required',
            'status'   => 'required|boolean',
            'client_id'=> 'required',
            'client_secret'=>'required',
            'redirect_uri'=>'required'
        ];
    }
}
