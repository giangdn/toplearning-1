<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\Facades\Cache;

class Tracking
{
    static public function put($item, $scope = 'db')
    {
        // stop-watch binding
        self::stopWatch($item);

        $items = self::all($scope);
        $items[] = $item;
        Cache::put(self::key($scope), $items);
    }

    public static function dump($scope = 'db', $clean = false)
    {
        $items = self::all($scope);
        if (
            is_array($items)
            && count($items) > 0
        ) {
            $numItem = 0;
            $totalTime = 0;

            foreach ($items as $item) {
                self::log($item, $scope);
                $numItem++;
                $totalTime += $item->time;
            }
            self::mark("# items: $numItem | ∑ time: " . number_format($totalTime, 2), $scope);
            $clean && self::clean($scope);
        }
    }

    /**
     * dump the slow executions
     *
     * @param string $scope
     * @param integer $longerThan
     * @param boolean $clean
     */
    public static function dumpSlow($scope, $longerThan = 100, $clean = true)
    {
        $items = self::all($scope);
        if (
            is_array($items)
            && count($items) > 0
        ) {
            $numItem = 0;
            $totalTime = 0;
            foreach ($items as $item) {
                if (
                    isset($item->time)
                    && $item->time > $longerThan
                ) {
                    self::log($item, $scope);
                    $numItem++;
                    $totalTime += $item->time;
                }
            }

            self::mark("# slow items: $numItem | ∑ time: " . number_format($totalTime, 2), $scope);
            $clean && self::clean($scope);
        }
    }

    private static function mark($message, $scope)
    {
        self::log('###################################', $scope);
        self::log($message, $scope);
        self::log('###################################', $scope);
    }

    private static function all($scope)
    {
        return Cache::get(self::key($scope));
    }

    private static function clean($scope = 'db')
    {
        try {
            Log::error('cleannnnnnnnn');
            Cache::put(self::key($scope), []);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private static function log($item, $scope = 'db')
    {
        $Logger = new Logger($scope);
        $Logger->pushHandler((new StreamHandler(storage_path("logs/tracking-$scope-" . date('Y-m-d') . ".log")))
            ->setFormatter(new LineFormatter(null, null, true, true)));

        if (is_scalar($item)) {
            $Logger->info($item);
        } else {
            foreach ($item as $key => $value) {
                $Logger->info("$key : $value");
            }
        }
    }

    private static function stopWatch(&$item)
    {
        if (!isset($item->time)) {
            $item->time = number_format((microtime(true) - (self::$stopWatch === 0 ? microtime(true) : self::$stopWatch)) * 1000, 2);
            self::$stopWatch = microtime(true);
        }
    }

    private static function mem(&$item)
    {
        if (!isset($item->memory)) {
            $item->memory = memory_get_peak_usage() - (self::$mem === 0 ? memory_get_peak_usage() : self::$mem);
            self::$mem = memory_get_peak_usage();
        }
    }

    private static function key($scope)
    {
        if (!isset(self::$keys[$scope])) {
            self::$keys[$scope] = md5('Tracking.' . $scope . '.items');
        }

        return self::$keys[$scope];
    }

    private static $keys = [];
    private static $stopWatch = 0;
    private static $mem = 0;
}
