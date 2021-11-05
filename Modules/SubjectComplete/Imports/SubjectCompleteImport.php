<?php
namespace Modules\SubjectComplete\Imports;
use App\Models\Categories\Subject;
use App\Models\Categories\Unit;

use App\Profile;
use App\User;
use App\UnitView;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Notifications\ImportUnitHasFailed;
use Modules\SubjectComplete\Jobs\Import;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\TrainingProcessLogs;


class SubjectCompleteImport implements ToCollection, WithStartRow, ShouldQueue, WithChunkReading
{
    use Importable;
    public $imported_by;
    public function __construct(User $user)
    {
        $this->imported_by = $user;
    }
    public function collection(Collection $collections)
    {
        foreach ($collections as $index => $item) {
//            dispatch(new Import($this->imported_by));
            $profile = \DB::table('el_profile_view')->where(['code'=>$item[1]])->first();
            $subject = Subject::where(['code'=>$item[3]])->first();
            if ($profile && $subject) {
                $model = TrainingProcess::updateOrCreate(
                    [
                        'user_id' => $profile->user_id, 'subject_id' => $subject->id
                    ],
                    [
                        'subject_code' => $subject->code,
                        'subject_name' => $subject->name,
                        'titles_code' => $profile->title_code,
                        'titles_name' => $profile->title_name,
                        'unit_code' => $profile->unit_code,
                        'unit_name' => $profile->unit_name,
                        'start_date' => date('Y-m-d H:i:s'),
                        'end_date' => date('Y-m-d H:i:s'),
                        'pass' => 1,
                        'process_type' => 2,
                        'note' => $item[5]
                    ]
                );
                // save logs
                $action='Thêm hoàn thành chuyên đề mã '.$subject->code.' cho học viên '.$profile->full_name.' ('.$profile->code.')';
                TrainingProcessLogs::saveLogs($model->id,'insert_subject_completion',$action,3);
            }
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
//    public function registerEvents(): array
//    {
//        return [
//            ImportFailed::class => function(ImportFailed $event) {
//                $this->imported_by->notify(new ImportUnitHasFailed([$event->getException()->getMessage()]));
//            },
//        ];
//    }
}
