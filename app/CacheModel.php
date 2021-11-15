<?php

namespace App;

use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * App\CacheModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @mixin \Eloquent
 */
class CacheModel extends BaseModel
{
    use QueryCacheable;
    public $cacheFor = 31557600;
    public $cacheDriver = '';
    public $cachePrefix = '';

    public function __construct()
    {
        parent::__construct();

        // binding config cache
        $this->cacheDriver = config('cache.default', 'memcached');

        // default prefix by called-model
        $this->cachePrefix = $this->_friendlyName();
    }

    public function _friendlyName()
    {
        //nomal name
        $name = get_called_class();

        //friendly name
        $name = str_replace('\\', '.', $name);

        return strtolower($name);
    }
}
