<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\Facades\Cache;
use \Exception as Exception;

class Tracking
{
    static public function put($item, $scope = 'db', $backtrace = false, $stopWatch = true)
    {
        // stop-watch binding
        $stopWatch && self::stopWatch($item);

        // item binding
        $items = self::all($scope);
        $items[] = $item;

        // re-save the new collection
        Cache::put(self::key($scope), $items);

        // backtrace binding
        $backtrace && self::backtrace($item);
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
                isset($item->time) && $totalTime += floatval($item->time);
            }
            self::mark($numItem, $totalTime, $scope);
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
                    isset($item->time) && $item->time > $longerThan
                ) {
                    self::log($item, $scope);
                    $numItem++;
                    isset($item->time) && $totalTime += floatval($item->time);
                }
            }

            self::mark($numItem, $totalTime, $scope, 'slow items');
            $clean && self::clean($scope);
        }
    }

    private static function mark($numItem, $totalTime, $scope, $label = 'items')
    {
        self::bar($scope);
        self::log("# $label $numItem ● ∑ time " . number_format($totalTime, 2) . ' ● @ ' . self::url(), $scope);
        self::bar($scope);
    }

    private static function all($scope)
    {
        return Cache::get(self::key($scope));
    }

    private static function clean($scope = 'db')
    {
        try {
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

    private static function backtrace($item)
    {

        $e = new Exception();
        $walkers = array_reverse(explode("\n", $e->getTraceAsString()));
        if (
            is_array($walkers)
            && count($walkers) > 0
        ) {
            array_shift($walkers);
            $appWalkers = [];
            foreach ($walkers as $step) {
                // $step = str_replace(base_path(), '', $step);
                // prevent some exceptions resources
                !preg_match('/(' . implode(')|(', self::$exs) . ')/', $step) && $appWalkers[] = $step;
            }
            if (count($appWalkers) > 0) {
                // dont need the time property
                if (property_exists($item, 'time')) unset($item->time);

                $item->callstack = print_r($appWalkers, true);
                self::put($item, 'backtrace', false, false);
            }
        }
    }

    private static function url()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    private static function mem(&$item)
    {
        if (!isset($item->memory)) {
            $item->memory = memory_get_peak_usage() - (self::$mem === 0 ? memory_get_peak_usage() : self::$mem);
            self::$mem = memory_get_peak_usage();
        }
    }

    private static function bar($scope, $long = 60)
    {
        // singleton the bar
        if (self::$bar === '') {
            for ($i = 0; $i < $long; $i++) self::$bar .= '#';
        }

        self::log(self::$bar, $scope);
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
    private static $bar = '';
    private static $exs = [
        '\/vendor\/',
        'public\/index\.php',
        'Helpers\/Tracking\.php',
        'Tracking::put'
    ];
}
