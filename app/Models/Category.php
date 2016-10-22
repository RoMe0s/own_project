<?php

namespace App\Models;

use App\Contracts\FrontLink;
use App\Contracts\MetaGettable;
use App\Contracts\SearchableContract;
use App\Traits\Models\SearchableTrait;
use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;
use Eloquent;

/**
 * Class Category
 * @package App\Models
 */
class Category extends Eloquent implements FrontLink, SearchableContract, MetaGettable
{
    use Translatable;
    use WithTranslationsTrait;
    use SearchableTrait;

    /**
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'meta_keywords',
        'meta_title',
        'meta_description',
        'content',
        'short_content'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'slug',
        'status',
        'position',
        'name',
        'meta_keywords',
        'meta_title',
        'meta_description',
        'parent_id',
        'content',
        'short_content',
        'image'
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param string $value
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = empty($value) ? str_slug($this->attributes['name']) : $value;
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->whereStatus(true);
    }

    /**
     * @return mixed
     */
    public function parents()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id')->with('parents', 'translations');
    }

    /**
     * @return array
     */
    public function getParents()
    {
        $result = [];

        $obj = $this->parents->toArray();
        //dd($obj);

        while (isset($obj) && !empty($obj)) {
            if($obj['status']) {
                $result[] = ['name' => $obj['name'], 'url' => $obj['slug']];
            }

            $obj = $obj['parents'];
        }
        return array_reverse($result);
    }

    /**
     * @return mixed
     */
    public function childs()
    {
        return $this->hasMany('App\Models\Category', 'parent_id')->with('childs');
    }

    /**
     * @return mixed
     */
    public function news()
    {
        return $this->hasMany('App\Models\News', 'category_id');
    }

    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopePositionSorted($query, $order = 'ASC')
    {
        return $query->orderBy('position', $order);
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->slug;
    }


    /**
     * @return string
     */
    public function getImageForSearchIndex()
    {
        return $this->image;
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getTitleForSearchIndex($locale = null)
    {
        return $locale ? $this->translate($locale)->name : $this->name;
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getDescriptionForSearchIndex($locale = null)
    {
        return $locale ? $this->translate($locale)->meta_description : $this->meta_description;
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaTitleForSearchIndex($locale = null)
    {
        return $locale ? $this->translate($locale)->meta_title : $this->meta_title;
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaDescriptionForSearchIndex($locale = null)
    {
        return $locale ? $this->translate($locale)->meta_description : $this->meta_description;
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaKeywordsForSearchIndex($locale = null)
    {
        return $locale ? $this->translate($locale)->meta_keywords : $this->meta_keywords;
    }


    /**
     * @return string
     */
    public function getMetaImage()
    {
        return url(
            empty($this->image) ? config('seo.share.default_image') : $this->image
        );
    }

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        // TODO: Implement getBreadcrumbs() method.
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return empty($this->meta_title) ? $this->name : $this->meta_title;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return str_limit(
            empty($this->meta_description) ? strip_tags($this->getContent()) : $this->meta_description,
            config('seo.share.meta_description_length')
        );
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }


}
