<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:41
 */

namespace App\Services;

use App\Models\News;
use App\Models\Tagged;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class NewsService
 * @package App\Services
 */
class NewsService
{

    /**
     * @param \App\Models\News $model
     */
    public function setExternalUrl(News $model)
    {
        $model->external_url = get_hashed_url($model, 'news');

        $model->save();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getList()
    {
        $list = News::withTranslations()->visible()->publishAtSorted()->positionSorted();

        $list = $this->_implodeFilters($list);

        return $list->paginate(config('news.per_page'));
    }

    /**
     * @param Builder $list
     *
     * @return Builder
     */
    private function _implodeFilters(Builder $list)
    {
        if (request('tag')) {
            $list->whereExists(
                function ($query) {
                    $query
                        ->leftJoin('tags', 'tags.id', '=', 'tagged.tag_id')
                        ->select(DB::raw('1'))
                        ->from('tagged')
                        ->whereRaw(
                            '
                            news.id = tagged.taggable_id AND
                            tagged.taggable_type = \''.str_replace('\\', '\\\\', News::class).'\' AND
                            tags.slug = \''.request('tag').'\''
                        );
                }
            );
        }

        return $list;
    }

    /**
     * @param \App\Models\News $model
     * @param int              $count
     *
     * @return Collection
     */
    public function getRelatedNewsForNews(News $model, $count = 4)
    {

        $result = [];

        if (count($model->visible_tags)) {

            $tags = Tagged::whereIn('tag_id', $model->visible_tags->lists('id')->toArray())->get();

            foreach($tags as $tag) {

                if($tag->taggable_id != $model->id) {
                    $result[] = $tag->taggable_id;
                }

            }

            if (count($result) > $count) {

                $result = array_rand(array_shift($result), $count);

            } else {

                $news_elems = cache('News', 'slug')->where($model->category_id, 'category_id')->get();

                $new_count = $count - count($result);

                if(count($news_elems) > $new_count) {

                    $elems = array_rand($news_elems, $new_count);

                } else {

                    $elems = array_rand($news_elems, count($news_elems));

                }

                foreach($elems as $item)
                {

                    if(!in_array($news_elems[$item]->id, $result) && $news_elems[$item]->id != $model->id) {
                        $result[] = $news_elems[$item]->id;
                    }

                }

            }

        }

        $related_news = [];

        foreach($result as $item) {

            $related_news[] = cache('News','slug')->where($item, 'id')->first();

        }

        return $related_news;

    }

    /**
     * @return array
     */
    public static function proccessArchive($news)
    {
        $result = [];
        foreach ($news as $new) {
            $date = explode('/', Carbon::parse($new->publish_at)->format('F/Y'));
            $date = ['link' => strtolower($date[0]) . '-' . $date[1], 'name' => trans('months.' . strtolower($date[0])) . ' ' . $date[1]];
            if(!in_array($date, $result)) {
                $result[] = $date;
            }
        }
        return $result;
    }

    /**
     * @param $count
     * @param boolean $use_cache
     * @return array
     */
    public static function loadNews($count, $category_id = null, $template = null)
    {

        if(!isset($category_id)) {

            $list = CacheService::init('News', 'slug')->items()->setRange(5, $count)->orderBy(['publish_at' => 'DESC', 'position' => 'ASC'])->get();

        } else {

            $list = CacheService::init('News', 'slug')->items()->where($category_id, 'category_id')->setRange(5, $count)->orderBy(['publish_at' => 'DESC', 'position' => 'ASC'])->get();

        }

        if($template && view()->exists("partials.news.$template")) {

            $view = view("partials.news.$template", compact('list'))->render();

        } else {

            $view = view('partials.news.list', compact('list'))->render();

        }

        if(sizeof($list))
            return ['status' => 'success', 'data' => $view];

            return ['status' => 'error', 'message' => trans('front_messages.there is no more news')];
    }

}
