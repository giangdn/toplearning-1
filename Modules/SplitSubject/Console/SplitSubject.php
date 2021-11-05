<?php

namespace Modules\SplitSubject\Console;
use App\Models\Categories\Subject;
use Illuminate\Console\Command;
use Modules\MergeSubject\Entities\MergeSubject as model;
use Modules\MergeSubject\Entities\MergeSubjectUser;
use Modules\User\Entities\TrainingProcess;

class SplitSubject extends Command
{
    protected $signature = 'splitsubject:run';

    protected $description = 'Tách chuyên đề chạy lúc 10h tối (0 22 * * *)';
    protected $expression  = '0 22 * * *';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $splitSubjects = $this->getSplitSubject();
        foreach ($splitSubjects as $index => $splitSubject) {
            $subjects= json_decode($splitSubject->subject_old,true);
            foreach ($subjects as $index => $subject) {
                $rows = $this->getSplitSubjectUser($splitSubject->id,(int)$subject[0]);
                foreach ($rows as $row) {
                    // insert tách chuyên đề
                    TrainingProcess::updateOrCreate(
                        ['user_id' => $row->user_id, 'subject_id' => $row->subject_id, 'process_type' => 5, 'merge_subject_id' => $row->id],
                        [
                            'subject_code' => $row->subject_code,
                            'subject_name' => $row->subject_name,
                            'titles_code' => $row->title_code,
                            'titles_name' => $row->title_name,
                            'unit_code' => $row->unit_code,
                            'unit_name' => $row->unit_name,
                            'pass' => 1,
                            'status' => 1,
                        ]
                    );
                    MergeSubjectUser::where(['user_id' => $row->user_id, 'type' => 2, 'merge_subject_id' => $row->id])->update(['processed' => 1]);
                    $this->info('Tách chuyên đề ' . $row->subject_name . ' user ' . $row->user_id);
                }
            }
        }
        // update số user đã split
        $dataSplit = $this->getSplitSubject();
        foreach ($dataSplit as $index => $item) {
            $countUser = TrainingProcess::where(['merge_subject_id'=>$item->id])->count();
            model::where(['id'=>$item->id])->update(['number_merge_completed'=>$countUser,'pending'=>0]);
        }
        MergeSubjectUser::where(['processed'=>1])->delete();
        $this->info('Success');
    }
    private function getSplitSubject(){
        return \DB::table('el_merge_subject')->where(['pending'=>1,'status'=>1,'type'=>2 ])->get();
    }
    private function getSplitSubjectUser($merge_subject_id,$subject_id){
        $subject = Subject::findOrFail($subject_id);
        return \DB::table('el_merge_subject as a')->join('el_merge_subject_user as b','a.id','=','b.merge_subject_id')
            ->leftJoin("el_profile_view as d",'d.user_id','=','b.user_id')
            ->where([
                 'a.pending'=>1,
                'a.status'=>1,
                'a.type'=>2,
                'b.type'=>2,
                'a.id'=>$merge_subject_id
            ])
            ->selectRaw('a.*,b.user_id , c.id as subject_id , d.title_code , d.title_name , d.unit_code , d.unit_name, '.$subject->code.' as subject_code , '.$subject->name.' as subject_name')
            ->get();
    }
}
