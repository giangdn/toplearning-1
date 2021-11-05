<?php

namespace App\Imports;

use App\Models\Categories\Area;
use App\User;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;
use App\Notifications\ImportUserHasFailed;
use Modules\User\Entities\WorkingProcess;

class WorkingProcessImport implements OnEachRow, WithStartRow, WithChunkReading, ShouldQueue, WithEvents
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
        $title_code = trim($row[3]);
        $unit_code = trim($row[4]);
        $start_date = $row[5];
        $end_date = $row[6];
        $note = $row[7];

        $profile = Profile::where('code','=', $user_code)->first();
        $title = Titles::where('code', '=', $title_code)->first();
        $unit = Unit::where('code', '=', $unit_code)->first();

        $errors = [];
        if (empty($profile)) {
            $errors[] = 'Dòng '. $row[0] .': Mã nhân viên <b>'. $user_code .'</b> không tồn tại';
            $error = true;
        }

        if (empty($title)) {
            $errors[] = 'Dòng '. $row[0] .': Mã chức danh <b>'. $title_code .'</b> không tồn tại';
            $error = true;
        }

        if (empty($unit)) {
            $errors[] = 'Dòng '. $row[0] .': Mã đơn vị <b>'. $unit_code .'</b> không tồn tại';
            $error = true;
        }

        if($error) {
            $this->imported_by->notify(new ImportUserHasFailed($errors));
            return null;
        }

        try {
            $working_process = new WorkingProcess();
            $working_process->user_id = $profile->user_id;
            $working_process->title_code = $title->code;
            $working_process->unit_code = $unit->code;
            $working_process->start_date = $start_date ? date_convert($start_date) : null;
            $working_process->end_date = $end_date ? date_convert($end_date) : null;
            $working_process->note = $note ? $note : null;
            $working_process->save();
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
