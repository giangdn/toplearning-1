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

class UnitImport implements OnEachRow, WithStartRow, ShouldQueue, WithChunkReading, WithEvents
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
        $checkUnitType1 = '';
        if (isset($row[3])) {
            $checkUnitType1 = UnitType::where('name','like','%'.trim($row[3]).'%')->first();
            if(empty($checkUnitType1)) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }
        $checkUnitType2 = '';
        if (isset($row[7])) {
            $checkUnitType2 = UnitType::where('name','like','%'.trim($row[7]).'%')->first();
            if(empty($checkUnitType2)) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }
        $checkUnitType3 = '';
        if (isset($row[11])) {
            $checkUnitType3 = UnitType::where('name','like','%'.trim($row[11]).'%')->first();
            if(empty($checkUnitType3)) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }
        $checkUnitType4 = '';
        if (isset($row[15])) {
            $checkUnitType4 = UnitType::where('name','like','%'.trim($row[15]).'%')->first();
            if(empty($checkUnitType4)) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }
        $checkUnitType5 = '';
        if (isset($row[19])) {
            $checkUnitType5 = UnitType::where('name','like','%'.trim($row[19]).'%')->first();
            if(empty($checkUnitType5)) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }
        $checkUnitType6 = '';
        if (isset($row[23])) {
            $checkUnitType6 = UnitType::where('name','like','%'.trim($row[23]).'%')->first();
            if(empty($checkUnitType6)) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }
        $checkUnitType7 = '';
        if (isset($row[27])) {
            $checkUnitType7 = UnitType::where('name','like','%'.trim($row[27]).'%')->first();
            if(empty($checkUnitType7)) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }
        $checkUnitType8 = '';
        if (isset($row[31])) {
            $checkUnitType8 = UnitType::where('name','like','%'.trim($row[31]).'%')->first();
            if(empty($checkUnitType8)) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }
        $checkUnitType9 = '';
        if (isset($row[35])) {
            $checkUnitType9 = UnitType::where('name','like','%'.trim($row[35]).'%')->first();
            if(empty($checkUnitType9)) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }
        $checkUnitType10 = '';
        if (isset($row[39])) {
            $checkUnitType10 = UnitType::where('name','like','%'.trim($row[39]).'%')->first();
            if(empty($checkUnitType10)) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }

        // ktra Mã người quản lý
        if (isset($row[4])) {
            $profiles_code = explode(',',$row[4]);
            foreach($profiles_code as $profile_code) {
                $checkProfileCode1 = Profile::where('code', $profile_code)->first();
                if(empty($checkProfileCode1)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Mã người dùng không đúng';
                    $error = true;
                }
            } 
        }
        if (isset($row[8])) {
            $profiles_code = explode(',',$row[8]);
            foreach($profiles_code as $profile_code) {
                $checkProfileCode2 = Profile::where('code', $profile_code)->first();
                if(empty($checkProfileCode2)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Mã người dùng không đúng';
                    $error = true;
                }
            }            
        }
        if (isset($row[12])) {
            $profiles_code = explode(',',$row[12]);
            foreach($profiles_code as $profile_code) {
                $checkProfileCode3 = Profile::where('code', $profile_code)->first();
                if(empty($checkProfileCode3)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Mã người dùng không đúng';
                    $error = true;
                }
            }  
        }
        if (isset($row[16])) {
            $profiles_code = explode(',',$row[16]);
            foreach($profiles_code as $profile_code) {
                $checkProfileCode4 = Profile::where('code', $profile_code)->first();
                if(empty($checkProfileCode4)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Mã người dùng không đúng';
                    $error = true;
                }
            } 
        }
        if (isset($row[20])) {
            $profiles_code = explode(',',$row[20]);
            foreach($profiles_code as $profile_code) {
                $checkProfileCode5 = Profile::where('code', $profile_code)->first();
                if(empty($checkProfileCode5)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Mã người dùng không đúng';
                    $error = true;
                }
            } 
        }
        if (isset($row[24])) {
            $profiles_code = explode(',',$row[24]);
            foreach($profiles_code as $profile_code) {
                $checkProfileCode6 = Profile::where('code', $profile_code)->first();
                if(empty($checkProfileCode6)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Mã người dùng không đúng';
                    $error = true;
                }
            } 
        }
        if (isset($row[28])) {
            $profiles_code = explode(',',$row[28]);
            foreach($profiles_code as $profile_code) {
                $checkProfileCode7 = Profile::where('code', $profile_code)->first();
                if(empty($checkProfileCode7)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Mã người dùng không đúng';
                    $error = true;
                }
            } 
        }
        if (isset($row[32])) {
            $profiles_code = explode(',',$row[32]);
            foreach($profiles_code as $profile_code) {
                $checkProfileCode8 = Profile::where('code', $profile_code)->first();
                if(empty($checkProfileCode8)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Mã người dùng không đúng';
                    $error = true;
                }
            } 
        }
        if (isset($row[36])) {
            $profiles_code = explode(',',$row[36]);
            foreach($profiles_code as $profile_code) {
                $checkProfileCode9 = Profile::where('code', $profile_code)->first();
                if(empty($checkProfileCode9)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Mã người dùng không đúng';
                    $error = true;
                }
            } 
        }
        if (isset($row[40])) {
            $profiles_code = explode(',',$row[40]);
            foreach($profiles_code as $profile_code) {
                $checkProfileCode10 = Profile::where('code', $profile_code)->first();
                if(empty($checkProfileCode10)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Mã người dùng không đúng';
                    $error = true;
                }
            } 
        }
		
		if ($error) {
            $this->imported_by->notify(new ImportUnitHasFailed($errors));
            return null;
        }

        for ($i = 1; $i <= $level; $i++){
            if ($i == 1 && !empty($row[1])){
                $model = Unit::firstOrNew(['code' => trim($row[1]), 'level' => $i]);
                $model->code = trim($row[1]);
                $model->name = trim($row[2]);
                $model->type = !empty($checkUnitType1) ? $checkUnitType1->id : '';
                $model->level = $i;
                $model->status = 1;
                $model->save();
                if (isset($row[4])) {
                    $profiles_code = explode(',',$row[4]);
                    foreach($profiles_code as $profile_code) {
                        $query = UnitManager::firstOrNew(['user_code' => $profile_code, 'unit_code' => trim($row[1])]);
                        $query->unit_code = trim($row[1]);
                        $query->user_code = $profile_code;
                        $query->type = 2;
                        $query->manager_type = 1;
                        $query->save();
                    } 
                }
            }
            if ($i == 2 && !empty($row[5])){
                $model = Unit::firstOrNew(['code' => trim($row[5]), 'level' => $i]);
                $model->code = trim($row[5]);
                $model->name = trim($row[6]);
                $model->type = !empty($checkUnitType2) ? $checkUnitType2->id : '';
                if($row[1]){
                    $model->parent_code = trim($row[1]);
                }
                $model->level = $i;
                $model->status = 1;
                $model->save();
                if (isset($row[8])) {
                    $profiles_code = explode(',',$row[8]);
                    foreach($profiles_code as $profile_code) {
                        $query = UnitManager::firstOrNew(['user_code' => $profile_code, 'unit_code' => trim($row[5])]);
                        $query->unit_code = trim($row[5]);
                        $query->user_code = $profile_code;
                        $query->type = 2;
                        $query->manager_type = 1;
                        $query->save();
                    } 
                }
            }
            if ($i == 3 && !empty($row[9])){
                $model = Unit::firstOrNew(['code' => trim($row[9]), 'level' => $i]);
                $model->code = trim($row[9]);
                $model->name = trim($row[10]);
                $model->type = !empty($checkUnitType3) ? $checkUnitType3->id : '';
                if($row[5]){
                    $model->parent_code = trim($row[5]);
                }
                $model->level = $i;
                $model->status = 1;
                $model->save();
                if (isset($row[12])) {
                    $profiles_code = explode(',',$row[12]);
                    foreach($profiles_code as $profile_code) {
                        $query = UnitManager::firstOrNew(['user_code' => $profile_code, 'unit_code' => trim($row[9])]);
                        $query->unit_code = trim($row[9]);
                        $query->user_code = $profile_code;
                        $query->type = 2;
                        $query->manager_type = 1;
                        $query->save();
                    } 
                }
            }
            if ($i == 4 && !empty($row[13])){
                $model = Unit::firstOrNew(['code' => trim($row[13]), 'level' => $i]);
                $model->code = trim($row[13]);
                $model->name = trim($row[14]);
                $model->type = !empty($checkUnitType4) ? $checkUnitType4->id : '';
                if($row[9]){
                    $model->parent_code = trim($row[9]);
                }
                $model->level = $i;
                $model->status = 1;
                $model->save();
                if (isset($row[16])) {
                    $profiles_code = explode(',',$row[16]);
                    foreach($profiles_code as $profile_code) {
                        $query = UnitManager::firstOrNew(['user_code' => $profile_code, 'unit_code' => trim($row[13])]);
                        $query->unit_code = trim($row[13]);
                        $query->user_code = $profile_code;
                        $query->type = 2;
                        $query->manager_type = 1;
                        $query->save();
                    } 
                }
            }
            if ($i == 5 && !empty($row[17])){
                $model = Unit::firstOrNew(['code' => trim($row[17]), 'level' => $i]);
                $model->code = trim($row[17]);
                $model->name = trim($row[18]);
                $model->type = !empty($checkUnitType5) ? $checkUnitType5->id : '';
                if($row[13]){
                    $model->parent_code = trim($row[13]);
                }
                $model->level = $i;
                $model->status = 1;
                $model->save();
                if (isset($row[20])) {
                    $profiles_code = explode(',',$row[20]);
                    foreach($profiles_code as $profile_code) {
                        $query = UnitManager::firstOrNew(['user_code' => $profile_code, 'unit_code' => trim($row[17])]);
                        $query->unit_code = trim($row[17]);
                        $query->user_code = $profile_code;
                        $query->type = 2;
                        $query->manager_type = 1;
                        $query->save();
                    } 
                }
            }
            if ($i == 6 && !empty($row[21])){
                $model = Unit::firstOrNew(['code' => trim($row[21]), 'level' => $i]);
                $model->code = trim($row[21]);
                $model->name = trim($row[22]);
                $model->type = !empty($checkUnitType6) ? $checkUnitType6->id : '';
                if($row[17]){
                    $model->parent_code = trim($row[17]);
                }
                $model->level = $i;
                $model->status = 1;
                $model->save();
                if (isset($row[24])) {
                    $profiles_code = explode(',',$row[24]);
                    foreach($profiles_code as $profile_code) {
                        $query = UnitManager::firstOrNew(['user_code' => $profile_code, 'unit_code' => trim($row[21])]);
                        $query->unit_code = trim($row[21]);
                        $query->user_code = $profile_code;
                        $query->type = 2;
                        $query->manager_type = 1;
                        $query->save();
                    } 
                }
            }
            if ($i == 7 && !empty($row[25])){
                $model = Unit::firstOrNew(['code' => trim($row[25]), 'level' => $i]);
                $model->code = trim($row[25]);
                $model->name = trim($row[26]);
                $model->type = !empty($checkUnitType7) ? $checkUnitType7->id : '';
                if($row[21]){
                    $model->parent_code = trim($row[21]);
                }
                $model->level = $i;
                $model->status = 1;
                $model->save();
                if (isset($row[28])) {
                    $profiles_code = explode(',',$row[28]);
                    foreach($profiles_code as $profile_code) {
                        $query = UnitManager::firstOrNew(['user_code' => $profile_code, 'unit_code' => trim($row[25])]);
                        $query->unit_code = trim($row[25]);
                        $query->user_code = $profile_code;
                        $query->type = 2;
                        $query->manager_type = 1;
                        $query->save();
                    } 
                }
            }
            if ($i == 8 && !empty($row[29])){
                $model = Unit::firstOrNew(['code' => trim($row[29]), 'level' => $i]);
                $model->code = trim($row[29]);
                $model->name = trim($row[30]);
                $model->type = !empty($checkUnitType8) ? $checkUnitType8->id : '';
                if($row[25]){
                    $model->parent_code = trim($row[25]);
                }
                $model->level = $i;
                $model->status = 1;
                $model->save();
                if (isset($row[32])) {
                    $profiles_code = explode(',',$row[32]);
                    foreach($profiles_code as $profile_code) {
                        $query = UnitManager::firstOrNew(['user_code' => $profile_code, 'unit_code' => trim($row[29])]);
                        $query->unit_code = trim($row[29]);
                        $query->user_code = $profile_code;
                        $query->type = 2;
                        $query->manager_type = 1;
                        $query->save();
                    } 
                }
            }
            if ($i == 9 && !empty($row[33])){
                $model = Unit::firstOrNew(['code' => trim($row[33]), 'level' => $i]);
                $model->code = trim($row[33]);
                $model->name = trim($row[34]);
                $model->type = !empty($checkUnitType9) ? $checkUnitType9->id : '';
                if($row[29]){
                    $model->parent_code = trim($row[29]);
                }
                $model->level = $i;
                $model->status = 1;
                $model->save();
                if (isset($row[36])) {
                    $profiles_code = explode(',',$row[36]);
                    foreach($profiles_code as $profile_code) {
                        $query = UnitManager::firstOrNew(['user_code' => $profile_code, 'unit_code' => trim($row[33])]);
                        $query->unit_code = trim($row[33]);
                        $query->user_code = $profile_code;
                        $query->type = 2;
                        $query->manager_type = 1;
                        $query->save();
                    } 
                }
            }
            if ($i == 10 && !empty($row[37])){
                $model = Unit::firstOrNew(['code' => trim($row[37]), 'level' => $i]);
                $model->code = trim($row[37]);
                $model->name = trim($row[38]);
                $model->type = !empty($checkUnitType10) ? $checkUnitType10->id : '';
                if($row[33]){
                    $model->parent_code = trim($row[33]);
                }
                $model->level = $i;
                $model->status = 1;
                $model->save();
                if (isset($row[40])) {
                    $profiles_code = explode(',',$row[40]);
                    foreach($profiles_code as $profile_code) {
                        $query = UnitManager::firstOrNew(['user_code' => $profile_code, 'unit_code' => trim($row[37])]);
                        $query->unit_code = trim($row[37]);
                        $query->user_code = $profile_code;
                        $query->type = 2;
                        $query->manager_type = 1;
                        $query->save();
                    } 
                }
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
                $this->imported_by->notify(new ImportUnitHasFailed([$event->getException()->getMessage()]));
            },
        ];
    }
}
