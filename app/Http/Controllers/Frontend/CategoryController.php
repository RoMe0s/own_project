<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\FrontendController;
use App\Services\CacheService;
use App\Services\NewsService;
use App\Models\Page;

class CategoryController extends FrontendController
{

    /**
     * @var string
     */
    public $module = 'category';

    public function index(Request $request, $slug, $count = 5) {

        $category = CacheService::init('Category', 'slug')
                ->items()
                ->where($slug)
                ->first();

        abort_if(!$category, 404);

        $this->data('model', $category);

        if($request->ajax()) {
            return NewsService::loadNews($count, $category->id);
        }

        $this->data('count', $count);

        $this->fillMeta($category);

        $this->setBreadcrumbs($category);

        return $this->render($this->module.'.show');

    }

    public function setBreadcrumbs($model)
    {
            $this->breadcrumbs(
              trans('front_labels.categories'),
                route('pages.show', ['slug' => 'categories'])
            );
            foreach ($model->getParents() as $item) {
                $this->breadcrumbs(
                    $item['name'],
                    route('category', ['slug' => $item['url']])
                );
            }
            $this->breadcrumbs(
                $model->name,
                route('category', ['slug' => $model->slug])
            );
    }

}
