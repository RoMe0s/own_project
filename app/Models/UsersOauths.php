<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersOauths extends Model
{
    public $timestamps = false;

    public $fillable = [
        'user_id',
        'oauth_id',
        'key'
    ];

    public $table = 'users_oauths';

}
