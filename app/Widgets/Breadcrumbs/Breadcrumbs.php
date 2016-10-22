<?php
/**
 * Created by PhpStorm.
 * User: ddiimmkkaass
 * Date: 24.03.16
 * Time: 23:11
 */

namespace App\Widgets\Breadcrumbs;

use Pingpong\Widget\Widget;

/**
 * Class LastNewsWidget
 * @package App\Widgets\LastNews
 */
class Breadcrumbs extends Widget
{

    /**
     * @var string
     */
    protected $view = 'default';

    /**
     * @param null $template
     * @param int  $count
     *
     * @return mixed
     */
    public function index($breadcrumbs)
    {
        return view('widgets.breadcrumbs.'.$this->view.'.index', compact('breadcrumbs'))->render();
    }
}