<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oauth extends Model
{
    public $timestamps = false;

    public $fillable = [
        'name',
        'status',
        'client_id',
        'client_secret',
        'redirect_uri',
        'display',
        'response_type'
    ];

    public $table = 'oauths';

    public function scopeVisible($query)
    {
        $query->where('status', true);
    }

    public function users()
    {
        return $this->hasMany('App\Models\UsersOauths', 'oauth_id');
    }
}
