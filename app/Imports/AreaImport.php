<?php
namespace App\Imports;
use App\Models\Categories\Area;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Notifications\ImportAreaHasFailed;
use Maatwebsite\Excel\Events\ImportFailed;
use Modules\Notify\Entities\NotifySend;
use Modules\Notify\Entities\NotifySendObject;

class AreaImport implements OnEachRow, WithStartRow, ShouldQueue, WithChunkReading, WithEvents
{
    use Importable;
    public $imported_by;

    public function __construct(User $user)
    {
        $this->imported_by = $user;
    }

    public function onRow(Row $row)
    {
        $row      = $row->toArray();
        $error = false;
        $level = Area::getMaxAreaLevel();
        $errors = [];

        for ($i = 1; $i <= $level; $i++){
            if ($i == 1){
                $model = Area::firstOrNew(['code' => trim($row[1]), 'level' => $i]);
                $model->code = trim($row[1]);
                $model->name = trim($row[2]);
                $model->level = $i;
                $model->status = 1;
                $model->save();
            }
            if ($i == 2 && !empty($row[3])){
                $model = Area::firstOrNew(['code' => trim($row[3]), 'level' => $i]);
                $model->code = trim($row[3]);
                $model->name = trim($row[4]);
                $model->parent_code = trim($row[1]);
                $model->level = $i;
                $model->status = 1;
                $model->save();
            }
            if ($i == 3 && !empty($row[5])){
                $model = Area::firstOrNew(['code' => trim($row[5]), 'level' => $i]);
                $model->code = trim($row[5]);
                $model->name = trim($row[6]);
                $model->parent_code = trim($row[3]);
                $model->level = $i;
                $model->status = 1;
                $model->save();
            }
            if ($i == 4 && !empty($row[7])){
                $model = Area::firstOrNew(['code' => trim($row[7]), 'level' => $i]);
                $model->code = trim($row[7]);
                $model->name = trim($row[8]);
                $model->parent_code = trim($row[5]);
                $model->level = $i;
                $model->status = 1;
                $model->save();
            }
            if ($i == 5 && !empty($row[9])){
                $model = Area::firstOrNew(['code' => trim($row[9]), 'level' => $i]);
                $model->code = trim($row[9]);
                $model->name = trim($row[10]);
                $model->parent_code = trim($row[7]);
                $model->level = $i;
                $model->status = 1;
                $model->save();
            }
            if (empty($model->code)) {
                $errors[] = 'Dòng '. $row[0] .': Mã không thể trống';
                $error = true;
                $this->imported_by->notify(new ImportAreaHasFailed($errors));
                return null;
            } else {
                $model->save();
            }
        }
    }

    public function model(array $row)
    {
        $level = Area::getMaxUnitLevel();
        for ($i = 1; $i <= $level; $i++){
            if ($i == 1){
                $model = Area::firstOrNew(['code' => trim($row[1]), 'level' => $i]);
                $model->code = trim($row[1]);
                $model->name = trim($row[2]);
                $model->level = $i;
                $model->status = 1;
                $model->save();
            }
            if ($i == 2 && !empty($row[3])){
                $model = Area::firstOrNew(['code' => trim($row[3]), 'level' => $i]);
                $model->code = trim($row[3]);
                $model->name = trim($row[4]);
                $model->parent_code = trim($row[1]);
                $model->level = $i;
                $model->status = 1;
                $model->save();
            }
            if ($i == 3 && !empty($row[5])){
                $model = Area::firstOrNew(['code' => trim($row[5]), 'level' => $i]);
                $model->code = trim($row[5]);
                $model->name = trim($row[6]);
                $model->parent_code = trim($row[3]);
                $model->level = $i;
                $model->status = 1;
                $model->save();
            }
            if ($i == 4 && !empty($row[7])){
                $model = Area::firstOrNew(['code' => trim($row[7]), 'level' => $i]);
                $model->code = trim($row[7]);
                $model->name = trim($row[8]);
                $model->parent_code = trim($row[5]);
                $model->level = $i;
                $model->status = 1;
                $model->save();
            }
            if ($i == 5 && !empty($row[9])){
                $model = Area::firstOrNew(['code' => trim($row[9]), 'level' => $i]);
                $model->code = trim($row[9]);
                $model->name = trim($row[10]);
                $model->parent_code = trim($row[7]);
                $model->level = $i;
                $model->status = 1;
                $model->save();
            }
        }
    }

    public function startRow(): int
    {
        return 4;
    }
    public function chunkSize(): int
    {
        return 200;
    }
    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
                $this->imported_by->notify(new ImportAreaHasFailed([$event->getException()->getMessage()]));
            },
        ];
    }
}
