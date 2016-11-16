<?php
namespace App\Widgets\OAuth;

use App\Models\Oauth;
use Pingpong\Widget\Widget;

/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 11/8/16
 * Time: 2:42 PM
 */
class OAuthWidget extends Widget
{
    public function index()
    {
        $oauths = Oauth::visible()->get();

        return view('widgets.oauth.index', compact('oauths'));
    }
}