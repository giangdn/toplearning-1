<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use \Memcached as Memcached;

class ProactiveCache
{
    /**
     * cache instance handler
     *
     * @var Memcached
     */
    private $link = null;

    public function __construct($host = '127.0.0.1', $port = '11211', $pid = 'top')
    {
        try {
            $this->link = new Memcached($pid);
            $this->link->addServer($host, $port);
        } catch (\Exception $e) {
            Log::error('MemCached::__construct error. ' . $e->getMessage());
        }
    }

    /**
     * add the value into cache
     *
     * @param string $key
     * @param string $value
     * @param integer $expiration
     * @return bool
     */
    public function add($key, $value, $expiration = 0)
    {
        return !$this->exist($key) && $this->link->add($key, $value, $expiration);
    }

    /**
     * store the value into cache
     *
     * @param string $key
     * @param string $value
     * @param integer $expiration
     * @return bool
     */
    public function set($key, $value, $expiration = 0)
    {
        return $this->link->set($key, $value, $expiration);
    }


    /**
     * replace the stored item with new value and expiration
     * 
     * @param string $key
     * @param string $value
     * @param integer $expiration
     * @return void
     */
    public function replace($key, $value, $expiration = 0)
    {
        return $this->exist($key)
            && $this->link->replace($key, $value, $expiration);
    }

    /**
     * update the expiration of caching item
     *
     * @param string $key
     * @param integer $expiration
     * @return void
     */
    public function touch($key, $expiration = 0)
    {
        return $this->link->touch($key, $expiration);
    }

    /**
     * get the stored value
     *
     * @param string $key
     * @return void
     */
    public function get($key)
    {
        return $this->link->get($key);
    }

    /**
     * check the key is existing on the storage server
     *
     * @param string $key
     * @return void
     */
    public function exist($key)
    {
        $this->link->get($key);
        return $this->link->getResultCode() === Memcached::RES_SUCCESS;
    }

    /**
     * delete item from cache
     *
     * @param string $key
     * @return void
     */
    public function delete($key)
    {
        if (!$this->link->delete($key)) {
            if ($this->link->getResultCode() === Memcached::RES_NOTFOUND) {
                Log::error("MemCache::delete error. the item: $key is not found");
            }
            return false;
        }

        return true;
    }

    /**
     * clear all the saved items
     *
     * @return void
     */
    public function flush()
    {
        return $this->link->flush();
    }

    /**
     * fetch all the items
     *
     * @return array
     */
    public function fetchAll()
    {
        $keys = $this->link->getAllKeys();
        $items = [];
        if (
            is_array($keys)
            && count($keys) > 0
        ) {

            foreach ($keys as $key) {
                $item = (object)["$key" => $this->get($key)];
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * close the current connection
     *
     * @return void
     */
    public function quit()
    {
        return $this->link->quit();
    }


    public static function getInstance($host = null, $port = null, $pid = null)
    {
        if (!$host || !$port || $pid) {
            $pid = config('cache.stores.memcached.persistent_id');
            $host = config('cache.stores.memcached.servers')[0]['host'];
            $host = config('cache.stores.memcached.servers')[0]['port'];
        }

        $key = md5($host . $port . $pid);
        if (!isset(self::$ins[$key])) {
            self::$ins[$key] = new ProactiveCache($host, $port, $pid);
        }

        return self::$ins[$key];
    }

    protected static $ins = [];
}
