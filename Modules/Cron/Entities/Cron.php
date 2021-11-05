<?php

namespace Modules\Cron\Entities;

use App\BaseModel;
use Composer\Autoload\ClassMapGenerator;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Cron\Entities\Cron
 *
 * @property int $id
 * @property string $command Tác vụ
 * @property string|null $description
 * @property string|null $minute phút
 * @property string|null $hour giờ
 * @property string|null $day ngày
 * @property string|null $month tháng
 * @property string|null $day_of_week ngày của tuần
 * @property string|null $expression
 * @property string|null $last_run
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Cron newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cron newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cron query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereCommand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereDayOfWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereExpression($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereHour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereLastRun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property int|null $enabled
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron enable()
 * @property string|null $start_time thời gian bắt đầu
 * @property string|null $end_time Thời gian kết thúc
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cron whereStartTime($value)
 */
class Cron extends BaseModel
{
    protected $table='el_cron';
    protected $fillable = [
        'code',
        'command',
        'description',
        'minute',
        'hour',
        'day',
        'month',
        'day_of_week',
        'last_run',
        'expression',
        'start_time',
        'end_time',
        'enabled'
    ];
    public function scopeEnable($query) {
        return $query->where('enabled', '=', 1);
    }
    public static function getAllCommand()
    {
        // command moodule
        $modules = \Module::all();
        foreach ($modules as $index => $item) {
            $module = \Module::find($item->name);
            $class = ClassMapGenerator::createMap($module->getPath().'/Console');
            foreach ($class as $index => $item) {
                $_class = new $index();
                $pros = new \ReflectionProperty($_class,'signature');
                $pros->setAccessible(true);
                $code =$pros->getValue($_class);

                $pros = new \ReflectionProperty($index,'description');
                $pros->setAccessible(true);
                $name = $pros->getValue($_class);

                $obj = new \ReflectionClass($_class);
                if ($obj->hasProperty('expression')) {
                    $pros = new \ReflectionProperty($index, 'expression');
                    $pros->setAccessible(true);
                    $expression = $pros->getValue($_class);
                }else{
                    $expression = '* 1 * * *';
                }

                $pros = new \ReflectionProperty($index,'hidden');
                $pros->setAccessible(true);
                $hidden =$pros->getValue($_class);
                if (!$hidden)
                    $commands_arr[] =(object)['code'=>$code,'name'=>$name,'expression'=>$expression];
            }
        };
        //command app
        $class =ClassMapGenerator::createMap(app_path('Console/Commands/'));
        foreach ($class as $index => $item) {
            $_class = new $index();
            $pros = new \ReflectionProperty($_class,'signature');
            $pros->setAccessible(true);
            $code =$pros->getValue($_class);

            $pros = new \ReflectionProperty($index,'description');
            $pros->setAccessible(true);
            $name = $pros->getValue($_class);

            $obj = new \ReflectionClass($_class);
            if ($obj->hasProperty('expression')) {
                $pros = new \ReflectionProperty($index, 'expression');
                $pros->setAccessible(true);
                $expression = $pros->getValue($_class);
            }else{
                $expression = '* 1 * * *';
            }

            $pros = new \ReflectionProperty($index,'hidden');
            $pros->setAccessible(true);
            $hidden =$pros->getValue($_class);
            if (!$hidden)
                $commands_arr[] =(object)['code'=>$code,'name'=>$name,'expression'=>$expression];
        }
        return collect($commands_arr);
    }
}
