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

    // initial propeties for caching stuff
    public $cacheFor = 31557600; // a year
    public $cacheDriver = '';
    public $cachePrefix = '';

    // by default, flush cache on update
    protected static $flushCacheOnUpdate = true;

    public function __construct()
    {
        parent::__construct();

        // binding config cache
        $this->cacheDriver = config('cache.default', 'memcached');

        // default prefix by called-model
        $this->cachePrefix = $this->_friendlyOwnName();
    }

    protected function getCacheBaseTags(): array
    {
        return [$this->_friendlyOwnName()];
    }

    private function _friendlyOwnName(): string
    {
        //php class name
        $name = get_called_class();

        //friendly readable name
        $name = str_replace('\\', '.', $name);

        // lower the name
        return strtolower($name);
    }
}
