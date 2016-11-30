<?php
/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 10/24/16
 * Time: 10:17 PM
 */

return array (

    'prefix' => 'laravel',

    /**
     * Leave base query like (Model => keyfield)
     */
    'base' => array(

        'App\Models\News' => 'slug',

        'App\Models\Category' => 'slug',

        'App\Models\Menu' => 'name',

        'App\Models\Tag' => 'slug'
    ),

);