<?php

namespace Modules\MergeSubject\Console;
use Modules\MergeSubject\Entities\MergeSubject as model;
use App\Automail;
use App\CourseResultStatistic;
use Illuminate\Console\Command;
use Modules\MergeSubject\Entities\MergeSubjectUser;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseComplete;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineResult;
use Modules\Online\Events\CourseCompleted;
use Modules\Rating\Entities\RatingCourse;
use Modules\User\Entities\TrainingProcess;

class MergeSubject extends Command
{
    protected $signature = 'mergesubject:run';

    protected $description = 'Gộp chuyên đề chạy lúc 10h tối (0 22 * * *)';

    protected $expression ='0 22 * * *';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $rows = $this->getMergeSubjectUser();
        foreach ($rows as $row) {
            // insert gộp chuyên đề
            TrainingProcess::updateOrCreate(
                ['user_id'=>$row->user_id,'subject_id'=>$row->subject_id,'process_type'=>4,'merge_subject_id'=>$row->id],
                [
                    'subject_code'=>$row->subject_code,
                    'subject_name'=>$row->subject_name,
                    'titles_code'=>$row->title_code,
                    'titles_name'=>$row->title_name,
                    'unit_code'=>$row->unit_code,
                    'unit_name'=>$row->unit_name,
                    'pass'=>1,
                    'status'=>1,
                ]
            );
            MergeSubjectUser::where(['user_id'=>$row->user_id,'type'=>1,'merge_subject_id'=>$row->id])->update(['processed'=>1]);
            $this->info('Gộp chuyên đề ' . $row->subject_name . ' user ' . $row->user_id);
        }
        // update số user đã merge
        $mergeSubject = $this->getMergeSubject();
        foreach ($mergeSubject as $index => $item) {
            $countUser = TrainingProcess::where(['merge_subject_id'=>$item->id])->count();
            model::where(['id'=>$item->id])->update(['number_merge_completed'=>$countUser,'pending'=>0]);
        }
        // xoa user đã merge
        MergeSubjectUser::where(['processed'=>1])->delete();
        $this->info('Success');
    }
    private function getMergeSubject(){
        return \DB::table('el_merge_subject')->where(['pending'=>1,'status'=>1,'type'=>1 ])->get();
    }
    private function getMergeSubjectUser(){
        return \DB::table('el_merge_subject as a')->join('el_merge_subject_user as b','a.id','=','b.merge_subject_id')
            ->Join('el_subject as c','a.subject_new','=','c.id')
            ->leftJoin("el_profile_view as d",'d.user_id','=','b.user_id')
            ->where([
                 'a.pending'=>1,
                'a.status'=>1,
                'a.type'=>1,
                'b.type'=>1
            ])
            ->select('a.*','b.user_id','c.name as subject_name','c.code as subject_code','c.id as subject_id','d.title_code','d.title_name','d.unit_code','d.unit_name')
            ->get();
    }
}
