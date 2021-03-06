<?php
/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 10/17/16
 * Time: 12:26 AM
 */

namespace App\Services;
use App\Models\News;
use Carbon\Carbon;
use Redis;
use Cache;

class CacheService
{

    protected static $classname = '';

    protected static $data = [];

    protected static $positions = [];

    protected static $keyfield = '';

    /**
     * CacheService constructor.
     * @param string $classname
     * @param string $key_field
     * @param object $query
     */
    function __construct($classname = '', $key_field = '', $query = null)
    {
        static::$classname = $classname;

        static::$keyfield = $key_field;

        if(isset($query)) {
            $this->cacheQuery($query);
        }
    }

    /**
     * @param string $classname
     * @param string $key_field
     * @param object $query
     * @return \App\Services\CacheService
     */
    public static function init($classname, $key_field, $query = null)
    {
        return new static($classname, $key_field, $query);
    }

    /**
     * @param string $classname
     * @param string $key_field
     * @param object $query
     * @return \App\Services\CacheService
     */
    public function setParams($classname, $key_field, $query = null)
    {
        static::$classname = $classname;

        static::$keyfield = $key_field;

        if(isset($query)) {
            $this->cacheQuery($query);
        }

        return $this;
    }

    /**
     * @param boolean $like_array
     * @param array $keys
     * @return array
     */
    public function fetchAll($like_array = false, $keys = [])
    {
        $result = [];
            foreach (Redis::hgetall("laravel:$this->_getClass:items") as $key => $item) {
                $item = unserialize($item);
                $result[(string)$item->{static::$keyfield}] = ($like_array) ? sizeof($keys) ? array_intersect_key($item->toArray(), array_flip($keys)) : $item->toArray() : $item;
        }
        return $result;
    }

    /**
     * @param object \Eloquent\QueryBuilder $query
     * @return array
     */
    public function cacheQuery($query)
    {
        $position_array = [];
        if(!$this->_time())
        {
            Redis::pipeline(function ($pipe) use ($query, &$position_array)
            {
                foreach ($query->get() as $item)
                {
                    $pipe->hset("laravel:$this->_getClass:items", $item->{static::$keyfield}, serialize($item));
                    $position_array[] = $item->{static::$keyfield};
                }
            });
            if($position_array) Redis::hset("laravel:$this->_getClass:positions", 'positions', serialize($position_array));
        }
    }

    /**
     * @param string|int $key
     * @return bool
     */
    public function hasByKey($key)
    {
        return isset(static::$data[$key]);
    }

    /**
     * @param string $module
     * @return bool mixed
     */
    public static function has($module)
    {
        return (bool)count(Redis::keys("laravel:$module:items"));
    }

    /**
     * @param string|int $key
     * @param array $value
     */
    public function update($key, $value)
    {
	$value = is_object($value) ? serialize($value) : $value;

        Redis::hset("laravel:$this->_getClass:items", $key, $value);
    }

    /**
     * @param string|int $key
     */
    public function remove($key)
    {
        Redis::hdel("laravel:$this->_getClass:items", $key);
    }

     /**
     * Remove all cache module cache
     */
    public function removeAll()
    {
        $this->_flush($this->_getClass());
    }

    /**
     * @return $this
     */
    public function items()
    {
        static::$data = $this->fetchAll();

        static::$positions = $this->_getPositions();

        return $this;
    }

    /**
     * @return array
     */
    public function get()
    {
        $result = [];

        array_map(function($pos) use (&$result)
        {
            if(isset(static::$data[$pos]) && $el = static::$data[$pos]) {
                $result[] = $el;
            }
        }
        ,static::$positions);

        return $result;
    }

    /**
     * @return object
     */
    public function first()
    {
        $key = reset(static::$positions) !== false ? reset(static::$positions) : null;

        return isset(static::$data[$key]) ? static::$data[$key] : null;
    }

    /**
     * @param string|int $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        static::$data[$key] = $value;

        $this->_set($key, $value);

        return $this;
    }

    /**
     * @param string|array|int $key
     * @return object
     */
    public function where($key, $field = null)
    {

        if(isset($field)) {

            foreach (static::$data as $value) {

                if($value->$field != $key) {

                    unset(static::$positions[array_search($value->{static::$keyfield}, static::$positions)]);

                    unset(static::$data[$key]);

                }

            }

        } elseif (is_array($key)) {

                static::$positions = array_intersect(static::$positions, $key);

        } else {

                static::$positions = [$key];

        }

        return $this;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function setRange($limit, $offset = 0)
    {

        static::$positions = array_slice(static::$positions, $offset, $limit);

        return $this;
    }

    /**
     * @param string|array $key
     * @param string $type = DESC, ASC
     * @return $this
     */
    public function orderBy($key, $type = 'ASC')
    {

        if(!is_array($key)) {
            $keys = $this->_getKeys($key);

                switch (strtolower($type)) {
                    case 'desc':
                        arsort($keys, TRUE);
                        break;
                    default:
                        asort($keys, TRUE);
                        break;
                }

            static::$positions = array_keys($keys);
        } else {

            $data = $this->fetchAll(true, [ implode(',', array_keys($key)), static::$keyfield ]);

            $result = [];

            foreach($key as $field => $type) {

                $order_type = SORT_ASC;

                if(strtolower($type) == 'desc') $order_type = SORT_DESC;

                $result[] = $this->_getKeys($field);

                $result[] = $order_type;
            }

            $result[] = &$data;

            call_user_func_array('array_multisort', $result);

            unset($result);

            $new_keys = [];

            $keyfield = static::$keyfield;

            foreach($data as $item) {
                $new_keys[] = $item["$keyfield"];
            }

            static::$positions = array_intersect($new_keys, static::$positions);
        }

        return $this;
    }

    /**
     * @param string|int $key
     * @return $this
     */
    public function groupBy($key)
    {
        $keys = $this->_getKeys($key);

        static::$positions = array_flip($keys);

        return $this;
    }

    /*public static function test()
    {
        foreach(Redis::keys("laravel:*:lasttime") as $item) {

            $class = "\\App\\Models\\" . preg_replace(['/laravel:/', '/:lasttime/'], '', $item);

            $last_update = Cache::get(class_basename($class) . ':lasttime');

            $last_table_update = $class::visible()->orderBy('updated_at')->first() !== null ? $class::visible()->orderBy('updated_at')->first()->updated_at : null;

            if(!isset($last_table_update) || Carbon::parse($last_table_update) > Carbon::parse($last_update)) {

                static::_flush(class_basename($class));

            }
        }
    }*/


    /**
     * Get base queries on app init
     */
    public function setBasic()
    {
        foreach(config('cacheservice.base') as $model => $keyfield) {

            $this->setParams(class_basename($model), $keyfield, $model::getBaseQuery());

        }
    }

    /**
     * @param string|int $key
     * @param object \Eloquent\QueryBuilder $query
     * @return object|false
     */
    public function getAndSetIfNotExist($key, $query)
    {
        if(!isset(static::$data[$key])) {
            $result = $query->first();
            $this->set($key, $result);
            return $result;
        }
        return static::$data[$key];
    }

    /**
     * private section
     * private section
     * private section
     * private section
     * private section
     * private section
     */

    private function _time()
    {
        if(!Cache::has("$this->_getClass:lasttime")){

                //Delete if something exists
                $this->_flush($this->_getClass);
                //Put new time point
                Cache::forever("$this->_getClass:lasttime", Carbon::now()->toDateTimeString());
            //Return false for proccesing query
            return false;
        }

        return true;
    }

    private function _getPositions()
    {
        $positions = unserialize(Redis::hget("laravel:$this->_getClass:positions", 'positions'));
        return $positions ? $positions : [];
    }

    private function _getKeys($key)
    {
        $result = [];
        foreach (static::$data as $key_field => $position) {
            $result[$key_field] = $position->{$key};
        }
        return $result;
    }

    private function _set($key, $value, $positions)
    {
        Redis::hset("laravel:$this->_getClass:items", $key, $value);

        Redis::hset("laravel:$this->_getClass:positions", 'positions', serialize(static::$positions));
    }

    private function _getClass()
    {
        return static::$classname;
    }

    private function _flush($class)
    {
        foreach(Redis::keys("laravel:$class:*") as $value) {
            Redis::del($value);
        }
    }

    /** Magic */
    public function __get($name)
    {
        if(method_exists($this, $name))
        {
            return call_user_func([$this, $name]);
        }
        return $name;
    }
}
