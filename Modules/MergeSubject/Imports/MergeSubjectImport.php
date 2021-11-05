<?php
namespace Modules\MergeSubject\Imports;
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
use Modules\MergeSubject\Entities\MergeSubject;
use Modules\MergeSubject\Entities\MergeSubjectUser;
use Modules\SubjectComplete\Jobs\Import;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\TrainingProcessLogs;


class MergeSubjectImport implements ToCollection, WithStartRow
{
    use Importable;
    public $imported_by;
    public $errors;
    public function __construct(User $user)
    {
        $this->errors = [];
        $this->imported_by = $user;
    }
    public function collection(Collection $collections)
    {

        foreach ($collections as $index => $item) {
            $error = false;
            $mergerSubject = trim($item[1]);
            $subject_new = trim($item[2]);
            $subject_old_complete = trim($item[3]);
            $note= $item[4];
            $subject_old = explode(',',$mergerSubject);
            $subject = Subject::where('code','=',$subject_new)->first();
            if (!$subject){
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột mã chuyên đề gộp thành] không hợp lệ';
                continue;
            }
            $exists = MergeSubject::where(['subject_new'=>$subject->id,'type'=>1])->exists();
            if (empty($item[1])) {
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột mã chuyên đề cần gộp] không được để trống';
                continue;
            }
            elseif (empty($item[2])) {
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột mã chuyên đề gộp thành] không được để trống';
                continue;
            }
            elseif (empty($item[3])) {
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: Số lượng chuyên đề cần hoàn thành không được để trống';
                continue;
            }
            elseif ($subject_old_complete>count($subject_old)){
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: Số lượng chuyên đề gộp hoàn thành không được lớn hơn chuyên đề cần gộp';
                continue;
            }
            elseif($exists){
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột mã chuyên đề gộp thành] đã tồn tại';
                continue;
            }

            $subject_old_data = Subject::whereIn('code',$subject_old)->get();
            if (count($subject_old_data)<count($subject_old)){
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột mã chuyên đề cần gộp] không hợp lệ';
                continue;
            }

            $subject_arr = [];
            foreach ($subject_old_data as $item){
                $subject_org[] = $item->code;
                $oldSubjectCodeArr[] = array($item->id,1);
                $subject_arr[] = $item->id;
            }

            $user_in_subject = \DB::table('el_training_process')->where('status','=',1)->whereIn('process_type',[1,2,3])->whereIn('subject_id',$subject_arr)
                ->groupBy('user_id')->havingRaw("count(1)>=".count($subject_arr)." and count(pass)>={$subject_old_complete}")->distinct('user_id')->select('user_id')->get();
            $numberMergeSubject = $user_in_subject->count();

            $model = new MergeSubject();
            $model->subject_old_complete = $subject_old_complete;
            $model->subject_old = json_encode((object) $oldSubjectCodeArr);
            $model->subject_new = $subject->id;
            $model->note = $note;
            $model->merge_option = 1;
            $model->number_merge_subject = $numberMergeSubject;
            $model->type = 1;
            $model->pending = 1;
            if ($model->save()) {

                // update user cần merge
                foreach ($user_in_subject as $user) {
                    MergeSubjectUser::updateOrCreate(
                        ['user_id' => $user->user_id, 'merge_subject_id' => $model->id, 'type' => 1]
                    );
                }

                // lưu log
                $action = 'import gộp chuyên đề mã (' . join(',', $subject_org) . ') vào mã ' . $subject_new;
                TrainingProcessLogs::saveLogs($model->id, 'merge_subject', $action, 1);
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
