<?php

namespace App\Imports;

use App\User;
use App\Profile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;
use App\Notifications\ImportUserHasFailed;
use Modules\User\Entities\TrainingProgramLearned;

class TrainingProgramLearnedImport implements OnEachRow, WithStartRow, WithChunkReading, ShouldQueue, WithEvents
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
        $training_program = ($row[3]);
        $time = ($row[4]);
        $note = $row[5];

        $profile = Profile::where('code','=', $user_code)->first();

        $errors = [];
        if (empty($profile)) {
            $errors[] = 'Dòng '. $row[0] .': Mã nhân viên <b>'. $user_code .'</b> không tồn tại';
            $error = true;
        }

        if($error) {
            $this->imported_by->notify(new ImportUserHasFailed($errors));
            return null;
        }

        try {
            $model = new TrainingProgramLearned();
            $model->user_id = $profile->user_id;
            $model->training_program = $training_program;
            $model->time = $time ? $time : null;
            $model->note = $note ? $note : null;
            $model->save();
        }
        catch (\Exception $exception) {
            $this->imported_by->notify(new ImportUserHasFailed(['Dòng ' . $row[0] . ': ' . $exception->getMessage()]));
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
                $this->imported_by->notify(new ImportUserHasFailed([$event->getException()->getMessage()]));
            },
        ];
    }
}
