<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 10.06.15
 * Time: 15:05
 */

namespace App\Widgets\Menu;

use App\Models\Menu;
use App\Services\CacheService;
use Pingpong\Widget\Widget;
/**
 * Class MenuWidget
 * @package App\Widgets\Menu
 */
class MenuWidget extends Widget
{

    /**
     * @param string $position
     *
     * @return $this
     */
    public function index($name)
    {
        $base_query = Menu::with(['visible_items', 'visible_items.translations'])
            ->where('name', $name)
            ->visible();

        $model = CacheService::init('Menu', 'name', $base_query)->items()->first();

        if (view()->exists('widgets.menu.templates.' . $model->name . '.index'))
        {
            return view('widgets.menu.templates.' . $model->name . '.index')->with(compact('model'))->render();
        }
    }
}