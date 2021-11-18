<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\CacheModel
 */
class UserCache extends Authenticatable
{
    use QueryCacheable;

    // initial propeties for caching stuff
    public $cacheFor = null; // null ~ disable caching
    public $cacheDriver = '';
    public $cachePrefix = '';
    public $cacheTags = [];

    // by default, flush cache on update
    protected static $flushCacheOnUpdate = true;

    public function __construct()
    {
        parent::__construct();
        if (config('app.enable_subtance_caching', true)) {
            $this->cacheFor = 7776000; // 90 days

            // binding config cache
            $this->cacheDriver = config('cache.default', 'memcached');

            // default prefix by called-model
            $this->cachePrefix = $this->_friendlyOwnName();

            // binding table name to the cache tag
            if (
                isset($this->table)
                && is_string($this->table)
            ) {
                $this->cacheTags[] = $this->table;
            }
        }
    }

    /**
     * for doing this thing. We can use Model::flushQueryCache();
     * to flush all the queries cache of the such eloquent model
     *
     * @return array
     */
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
