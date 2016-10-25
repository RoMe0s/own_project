<?php
/**
 * Created by PhpStorm.
 * User: ddiimmkkaass
 * Date: 24.03.16
 * Time: 23:11
 */

namespace App\Widgets\LastNews;

use App\Models\News;
use App\Services\CacheService;
use Pingpong\Widget\Widget;

/**
 * Class LastNewsWidget
 * @package App\Widgets\LastNews
 */
class LastNewsWidget extends Widget
{
    /**
     * @var string
     */
    protected $view = 'default';

    /**
     * @param null $template
     * @param int  $count
     * @return mixed
     */
    public function index($template = null, $count = 5)
    {

        dd(CacheService::init('News', 'slug')->items()->orderBy(['publish_at' => 'DESC', 'position' =>
        'ASC']));

        $list = CacheService::init('News', 'slug')->items()->setRange($count)->get();

        if (view()->exists('widgets.last_news.templates.'.$template.'.index')) {

            $this->view = $template;

        }

        return view('widgets.last_news.templates.'.$this->view.'.index')->with('list', $list)->render();
    }
}