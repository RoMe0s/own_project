<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Widgets\RightsideMenu;

use App\Services\CacheService;
use App\Services\NewsService;
use Pingpong\Widget\Widget;


/**
 * Class SearchFormWidget
 * @package App\Widgets\SearchForm
 */
class RightsideMenu extends Widget
{
    function index()
    {

        $news = CacheService::init('News', 'slug')->items()->orderBy('view_count', 'DESC')->setRange(10)->get();

        $tags = CacheService::init('Tag', 'slug')->items()->get();

        $archive = NewsService::proccessArchive(CacheService::init('News', 'slug')->items()->groupBy('publish_at')->orderBy('publish_at', 'DESC')->get());

        return view('widgets.rightside_menu.index')->with(compact('news', 'archive', 'tags'))->render();
    }
}