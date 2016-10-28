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
        $model->related_news = unserialize(Cache::get('related_news_for_news_'.$model->id, false));

        if ($model->related_news === false) {
            if (count($model->tags)) {
                $tagged = Tagged::whereIn('tag_id', array_pluck($model->tags->toArray(), 'tag_id'))
                    ->where('taggable_id', '<>', $model->id)
                    ->whereRaw('taggable_type = \''.str_replace('\\', '\\\\', News::class).'\'')
                    ->get();

                if (count($tagged) > $count) {
                    $tagged = $tagged->random($count);

                    if (count($tagged) == 1) {
                        $tagged = Collection::make([$tagged]);
                    }
                }

                $model->related_news = News::with('translations')
                    ->whereIn('id', array_pluck($tagged->toArray(), 'taggable_id'))
                    ->get();
            } else {
                $model->related_news = [];
            }

            Cache::add('related_news_for_news_'.$model->id,
                serialize($model->related_news),
                config('news.related_news_cache_time')
            );
        }

        return $model->related_news;
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
    public static function loadNews($count, $use_cache = true)
    {
        try {
                if($use_cache) {
                    $list = CacheService::init('News', 'slug')->items()->setRange(5, $count)->orderBy(['publish_at' => 'DESC', 'position' => 'ASC'])->get();
                } else {
                    $list = News::with(['comments', 'translations'])->visible()->publishAtSorted()->positionSorted()->limit(5)->offset($count)->get();
                }
                return ['status' => 'success', 'data' => view('widgets.last_news.templates.index', compact('list'))->render()];
            } catch (\Exception $e) {
                return ['status' => 'error', 'message' => trans('messages.an error has occurred, try_later')];
            }
    }

}
