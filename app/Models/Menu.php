<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 10.06.15
 * Time: 15:40
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\Cachable as CacheService;

/**
 * Class Menu
 * @package App\Models
 */
class Menu extends Model implements CacheService
{
    /**
     * @var array
     */
    protected $fillable = ['layout_position', 'class', 'name', 'template', 'show_title', 'position', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id')->positionSorted();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visible_items()
    {
        return $this->items()->visible();
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

    public static function getBaseQuery()
    {
        return Menu::with(['visible_items', 'visible_items.translations'])
            ->visible();
    }
}