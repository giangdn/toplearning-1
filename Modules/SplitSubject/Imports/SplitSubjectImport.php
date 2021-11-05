<?php
namespace Modules\SplitSubject\Imports;
use App\Models\Categories\Subject;

use App\User;
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


class SplitSubjectImport implements ToCollection, WithStartRow
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
            $splitSubject_code = trim($item[1]);
            $note= $item[3];
            $subject_new = explode(',',trim($item[2]));
            $subject = Subject::where('code','=',$splitSubject_code)->first();
            if (!$subject){
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột mã chuyên đề cần tách] không hợp lệ';
                continue;
            }
            $exists = MergeSubject::where(['subject_new'=>$subject->id,'type'=>2])->exists();
            if (empty($splitSubject_code)) {
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột mã chuyên đề cần tách] không được để trống';
                continue;
            }
            elseif (empty($item[2])) {
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột mã chuyên đề mới] không được để trống';
                continue;
            }
            elseif($exists){
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột mã chuyên đề cần tách] đã tồn tại';
                continue;
            }

            $subject_new_data = Subject::whereIn('code',$subject_new)->get();
            if (count($subject_new_data)<count($subject_new)){
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột mã chuyên đề mới] có mã chuyên đề không hợp lệ';
                continue;
            }

            $subject_arr = [];
            foreach ($subject_new_data as $item){
                $subject_split[] = $item->code;
                $oldSubjectCodeArr[] = array($item->id,1);
                $subject_arr[] = $item->id;
            }

            $user_in_subject = \DB::table('el_training_process')->where('status','=',1)->whereIn('process_type',[1,2,3])->where('subject_id',$subject->id)
                ->where('pass','=',1)->select('user_id')->get();
            $numberMergeSubject = $user_in_subject->count();

            $model = new MergeSubject();
            $model->subject_old = json_encode((object) $oldSubjectCodeArr);
            $model->subject_new = $subject->id;
            $model->note = $note;
            $model->number_merge_subject = $numberMergeSubject;
            $model->type = 2;
            $model->pending = 1;
            if ($model->save()) {

                // update user cần merge
                foreach ($user_in_subject as $user) {
                    MergeSubjectUser::updateOrCreate(
                        ['user_id' => $user->user_id, 'merge_subject_id' => $model->id, 'type' => 2]
                    );
                }

                // lưu log
                $action = 'import tách chuyên đề mã ' . $splitSubject_code. ' thành mã (' . join(',',$subject_split).')';;
                TrainingProcessLogs::saveLogs($model->id, 'split_subject', $action,2);
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
