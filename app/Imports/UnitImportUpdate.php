<?php
namespace App\Imports;
use App\Models\Categories\Unit;

use App\User;
use App\UnitView;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Notifications\ImportUnitHasFailed;
use Maatwebsite\Excel\Events\ImportFailed;
use Modules\Notify\Entities\NotifySend;
use Modules\Notify\Entities\NotifySendObject;
use App\Models\Categories\UnitType;
use App\Profile;
use App\Models\Categories\UnitManager;
use App\Models\Categories\Area;

class UnitImportUpdate implements OnEachRow, WithStartRow, ShouldQueue, WithChunkReading, WithEvents
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
        $level = Unit::getMaxUnitLevel();
        $errors = [];

        if (isset($row[3])) {
            $checkUnitParentCode = Unit::where('code',trim($row[3]))->first();
            if(empty($checkUnitParentCode)) {
                $this->errors[] = 'Dòng '. $row[0] .': Mã đơn vị không đúng';
                $error = true;
            }
        }

        $checkUnitType = '';
        if (isset($row[4])) {
            $checkUnitType = UnitType::where('name','like','%'.trim($row[4]).'%')->first();
            if(empty($checkUnitType)) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }

        if (isset($row[5])) {
            $profiles_code = explode(',',trim($row[5]));
            foreach($profiles_code as $profile_code) {
                $checkProfileCode = Profile::where('code', $profile_code)->first();
                if(empty($checkProfileCode)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Mã người quản lý không đúng';
                    $error = true;
                }
            } 
        }

        if (isset($row[6])) {
            $check_area_2 = Area::where('code',$row[6])->where('level',2)->first();
            if(empty($check_area_2)) {
                $this->errors[] = 'Dòng '. $row[0] .': Mã Miền không đúng';
                $error = true;
            }
        }

        $area_3_id = null;
        if (isset($row[7])) {
            $check_area_3 = Area::where('code',$row[7])->where('level',3)->first();
            if(empty($check_area_3)) {
                $this->errors[] = 'Dòng '. $row[0] .': Mã Khu vực không đúng';
                $error = true;
            } else {
                $area_3_id = $check_area_3->id;
            }
        }

        $model = Unit::firstOrNew(['code' => trim($row[1])]);
        $model->type = !empty($checkUnitType) ? $checkUnitType->id : '0';
        if($row[3]){
            $model->parent_code = trim($row[3]);
        }
        $model->area_id = $area_3_id;
        $model->save();
        if (isset($row[5])) {
            $profiles_code = explode(',',$row[5]);
            foreach($profiles_code as $profile_code) {
                $query = UnitManager::firstOrNew(['user_code' => $profile_code, 'unit_code' => trim($row[1])]);
                $query->unit_code = trim($row[1]);
                $query->user_code = $profile_code;
                $query->type = 2;
                $query->manager_type = 1;
                $query->save();
            } 
        }
       
		if ($error) {
            $this->imported_by->notify(new ImportUnitHasFailed($errors));
            return null;
        }

    }

    public function startRow(): int
    {
        return 3;
    }
    public function chunkSize(): int
    {
        return 200;
    }
    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
                $this->imported_by->notify(new ImportUnitHasFailed([$event->getException()->getMessage()]));
            },
        ];
    }
}
