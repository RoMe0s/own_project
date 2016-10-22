<?php
/**
 * Created by PhpStorm.
 * User: ddiimmkkaass
 * Date: 24.03.16
 * Time: 23:11
 */

namespace App\Widgets\CategoriesMenu;

use League\Flysystem\Exception;
use Pingpong\Widget\Widget;
use App\Models\Category;
use App\Services\CacheService;

/**
 * Class LastNewsWidget
 * @package App\Widgets\LastNews
 */
class CategoriesMenu extends Widget
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $base_query = Category::with(['childs', 'translations'])->visible();//->joinTranslations('categories', 'category_translations');

        $categories = CacheService::init('Category', 'slug', $base_query)->items()->get();

        return view('widgets.categories_menu.index', compact('categories'))->render();
    }
}