<?php

namespace Modules\ReportNew\Console;

use App\Models\Categories\Subject;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Modules\ReportNew\Entities\BC22;
use Modules\MergeSubject\Entities\MergeSubject;
use function GuzzleHttp\Psr7\str;

class ReportBC22 extends Command
{
    protected $signature = 'report:bc22';

    protected $description = 'Gộp tách chuyên đề BC22 (1 ngày chạy 1 lần 2h tối)';
    protected $expression ="0 2 * * *";
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = \DB::table('el_merge_subject as a')->join('el_profile_view as b','a.created_by','=','b.user_id')
            ->join('el_subject as c','c.id','=','a.subject_new')
            ->join('el_unit_view as d','d.id','=','b.unit_id')
            ->where(['a.status'=>1])->where(function (Builder $query){
                $query->where(['a.flag'=>0]);
                $query->orWhereNull('a.flag');
            })
            ->select('a.id','c.code as subject_code','c.name as subject_name','a.note','a.type','a.subject_old','a.subject_new','a.created_at',
                'b.code as user_code','b.full_name','b.user_id','b.email','b.phone','b.area_code','b.area_name',
                'b.position_code','b.position_name','d.object_id','b.title_code','b.title_name',
                'd.unit1_code','d.unit1','d.unit2_code','d.unit2','d.unit3_code','d.unit3','d.unit4_code','d.unit4','d.unit5_code','d.unit5','d.unit6_code','d.unit6','d.unit7_code',
                'd.unit7','d.unit8_code','d.unit8','d.unit9_code','d.unit9','d.unit10_code','d.unit10','d.object_id','d.area_code','d.area_name')->get();

        foreach ($users as $index => $user) {
            $subject_old_arr = $user->subject_old? json_decode($user->subject_old):[];
            $tmp_oldSubject = '';
            foreach ($subject_old_arr as $key => $subject_old) {
                $oldSubject = Subject::find($subject_old[0]);
                $tmp_oldSubject .= $oldSubject->name.' ('.$oldSubject->code.')'.PHP_EOL;
            }


            $unit = $user->object_id;
            $unit3_code = $user->{'unit'.$unit.'_code'};
            $unit3_name = $user->{'unit'.$unit};
            $unit2_code = @$user->{'unit'.($unit-1).'_code'};
            $unit2_name = @$user->{'unit'.($unit-1)};
            $unit1_code = @$user->{'unit'.($unit-2).'_code'};
            $unit1_name = @$user->{'unit'.($unit-2)};
            $ex=BC22::updateOrCreate(
              ['id'=>$user->id],
              [
                  'type'=>$user->type,
                  'subject_merge_code'=> $user->type==1?$user->subject_code:null,
                  'subject_merge_name'=> $user->type==1?$user->subject_name:null,
                  'subject_merges'=> $user->type==1?$tmp_oldSubject:null,

                  'subject_splits'=> $user->type==2?$tmp_oldSubject:null,
                  'subject_split_code'=> $user->type==2?$user->subject_code:null,
                  'subject_split_name'=> $user->type==2?$user->subject_name:null,

                  'user_id'=>$user->user_id,
                  'user_code'=>$user->user_code,
                  'full_name'=>$user->full_name,
                  'email'=>$user->email,
                  'phone'=>$user->phone,
                  'area_code'=>$user->area_code,
                  'area'=>$user->area_name,
                  'unit1_code'=>$unit1_code,
                  'unit1_name'=>$unit1_name,
                  'unit2_code'=>$unit2_code,
                  'unit2_name'=>$unit2_name,
                  'unit3_code'=>$unit3_code,
                  'unit3_name'=>$unit3_name,
                  'position_code'=>$user->position_code,
                  'position_name'=>$user->position_name,
                  'title_code'=>$user->title_code,
                  'title_name'=>$user->title_name,
                  'note'=> $user->note,
                  'date_action'=> $user->created_at,
              ]
            );
            if ($ex){
                MergeSubject::where('id',$user->id)->update(['flag'=>1]);
            }
        }
        $this->info('Success');
    }
}
