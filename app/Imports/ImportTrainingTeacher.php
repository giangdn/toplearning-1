<?php

namespace App\Imports;

use App\Models\Categories\LevelSubject;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Unit;
use App\Notifications\ImportSubjectHasFailed;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use App\Profile;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;

class ImportTrainingTeacher implements OnEachRow, WithStartRow, WithChunkReading, ShouldQueue, WithEvents
{
    use Importable;
    public $imported_by;

    public function __construct(User $user)
    {
        $this->imported_by = $user;
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $error = false;
        $user_code = trim($row[1]);

        $errors = [];
        if (empty($user_code)) {
            $errors[] = 'Dòng '. $row[0] .': Mã giảng viên không thể trống';
            $error = true;
        }

        $profile = Profile::where('code', '=', $user_code)->first();

        if (empty($profile)){
            $errors[] = 'Dòng '. $row[0] .': Mã giảng viên <b>'. $user_code .' </b> không tồn tại';
            $error = true;
        }

        if($error) {
            $this->imported_by->notify(new ImportSubjectHasFailed($errors));
            return null;
        }

        try {
            $model = TrainingTeacher::firstOrNew(['code' => $user_code]);
            $model->user_id = $profile->user_id;
            $model->code = $profile->code;
            $model->name = $profile->lastname . ' ' . $profile->firstname;
            $model->email = $profile->email;
            if (isset($row[4])) {
                $model->phone = $row[4];
            } else {
                $model->phone = $profile->phone;
            }
            $model->status = 1;
            $model->type = 1;
            $model->save();
        }
        catch (\Exception $exception) {
            $this->imported_by->notify(new ImportSubjectHasFailed(['Dòng ' . $row[0] . ': ' . $exception->getMessage()]));
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
                $this->imported_by->notify(new ImportSubjectHasFailed([$event->getException()->getMessage()]));
            },
        ];
    }
}
