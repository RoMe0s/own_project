<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Models\News;
use App\Services\NewsService;
use App\Services\CacheService;

/**
 * Class NewsController
 * @package App\Http\Controllers\Frontend
 */
class NewsController extends FrontendController
{

    /**
     * @var string
     */
    public $module = 'news';

    /**
     * @var \App\Services\NewsService
     */
    protected $newsService;

    /**
     * NewsController constructor.
     *
     * @param \App\Services\NewsService $newsService
     */
    public function __construct(NewsService $newsService)
    {
        parent::__construct();

        $this->newsService = $newsService;
    }

    /**
     * @param string $slug
     *
     * @return $this|\App\Http\Controllers\Frontend\NewsController
     */
    public function show($slug = '')
    {
        $query = News::with(['translations', 'visible_category', 'visible_tags', 'visible_category.translations', 'visible_category.parents', 'comments'])->visible()->whereSlug($slug);

        $model = CacheService::init('News', 'slug')->items()->getAndSetIfNotExist($slug, $query);

        abort_if(!$model, 404);

        //$this->newsService->getRelatedNewsForNews($model);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        $this->setBreadcrumbs($model);

        return $this->render($this->module.'.show');
    }


    public function setBreadcrumbs($model)
    {
        if($model->visible_category) {
            foreach ($model->visible_category->getParents() as $item) {
                $this->breadcrumbs(
                    $item['name'],
                    $item['url']
                );
            }
            $this->breadcrumbs(
                $model->category->name,
                $model->category->slug
            );
            $this->breadcrumbs(
                $model->name,
                route('news.show', ['slug' => $model->slug])
            );
        }
    }
}