<?php

namespace App\Console\Commands;
use App\CourseRegisterView;
use App\CourseView;
use App\HasChange;
use App\Models\Categories\Area;
use App\Models\Categories\Position;
use App\Models\Categories\Subject;
use App\Models\Categories\TeacherType;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Unit;
use App\ProfileStatus;
use App\ProfileView;
use App\UnitView;
use Google\Model;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\MergeSubject\Entities\MergeSubjectUser;
use Modules\Offline\Entities\OfflineCourseView;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Online\Entities\OnlineCourseView;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineRegisterView;
use Modules\User\Entities\TrainingProcess;

class SyncMasterdata extends Command
{
    protected $signature = 'sync:masterdata';

    protected $description = 'Đồng bộ data chạy lúc 01h tối (0 1 * * *)';
    protected $expression ='0 1 * * *';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
         $this->syncHasChange();
         $this->info('Success');
    }
    private function syncHasChange(){
        $recordHasChange = HasChange::all();
        foreach ($recordHasChange as $index => $item) {
            if ($item->table_name==app(Unit::class)->getTable()){
                $this->updateUnit($item->record_id,$item->type);
            }elseif($item->table_name == app(Area::class)->getTable()){
                $this->updateArea($item->record_id,$item->type);
            }elseif ($item->table_name == app(Titles::class)->getTable()){
                $this->updateTitle($item->record_id,$item->type);
            }elseif ($item->table_name == app(Position::class)->getTable()){
                $this->updatePosition($item->record_id,$item->type);
            }elseif($item->table_name == app(ProfileStatus::class)->getTable()){
                $this->updateStatusProfile($item->record_id,$item->type);
            }elseif($item->table_name == app(TrainingProgram::class)->getTable()){
                $this->updateTrainingProgram($item->record_id,$item->type);
            }elseif($item->table_name == app(Subject::class)->getTable()){
                $this->updateSubject($item->record_id,$item->type);
            }elseif($item->table_name == app(TrainingLocation::class)->getTable()){
                $this->updateTrainingLocation($item->record_id,$item->type);
            }elseif($item->table_name == app(TrainingForm::class)->getTable()){
                $this->updateTrainingFrom($item->record_id,$item->type);
            }elseif($item->table_name == app(TrainingType::class)->getTable()){
                $this->updateTrainingType($item->record_id,$item->type);
            }elseif($item->table_name == app(TeacherType::class)->getTable()){
                $this->updateTeacherType($item->record_id,$item->type);
            }elseif($item->table_name == app(TrainingPartner::class)->getTable()){
                $this->updateTrainingPartner($item->record_id,$item->type);
            }
            $this->deleteHasChange($item->id);
        }
    }
    private function deleteHasChange($id){
        HasChange::destroy($id);
    }
    private function updateUnit($id,$type=1){
        if ($type==1){ // update
            $unit=Unit::findOrFail($id);
        }else{ // delete
             $unit = new \stdClass();
             $unit->code=null;
             $unit->name = null;
             $unit->parent_unit_code=null;
             $unit->parent_unit_name=null;
        }
        ProfileView::where(['unit_id'=>$id])->update(['unit_code'=>$unit->code,'unit_name'=>$unit->name]);
        ProfileView::where(['parent_unit_id'=>$id])->update(['parent_unit_code'=>$unit->code,'parent_unit_name'=>$unit->name]);

        OfflineRegisterView::where(['unit_id'=>$id])->update(['unit_code'=>$unit->code,'unit_name'=>$unit->name]);
        OfflineRegisterView::where(['parent_unit_id'=>$id])->update(['parent_unit_code'=>$unit->parent_unit_code,'parent_unit_name'=>$unit->parent_unit_name]);

        OnlineRegisterView::where(['unit_id'=>$id])->update(['unit_code'=>$unit->code,'unit_name'=>$unit->name]);
        OnlineRegisterView::where(['parent_unit_id'=>$id])->update(['parent_unit_code'=>$unit->parent_unit_code,'parent_unit_name'=>$unit->parent_unit_name]);

        CourseRegisterView::where(['unit_id'=>$id])->update(['unit_code'=>$unit->code,'unit_name'=>$unit->name]);
        CourseRegisterView::where(['parent_unit_id'=>$id])->update(['parent_unit_code'=>$unit->parent_unit_code,'parent_unit_name'=>$unit->parent_unit_name]);
    }
    private function updateArea($id,$type=1){
        if ($type==1){ // update
            $area=Area::findOrFail($id);
        }else{ // delete
            $area = new \stdClass();
            $area->code=null;
            $area->name = null;
        }
        ProfileView::where(['area_id'=>$id])->update(['area_code'=>$area->code,'area_name'=>$area->name]);
        UnitView::where(['area_id'=>$id])->update(['area_code'=>$area->code,'area_name'=>$area->name]);
    }
    private function updateTitle($id,$type=1){
        if ($type==1){ // update
            $title=Titles::findOrFail($id);
        }else{ // delete
            $title = new \stdClass();
            $title->code=null;
            $title->name = null;
        }
        ProfileView::where(['title_id'=>$id])->update(['title_code'=>$title->code,'title_name'=>$title->name]);
        OfflineRegisterView::where(['title_id'=>$id])->update(['title_code'=>$title->code,'title_name'=>$title->name]);
        OnlineRegisterView::where(['title_id'=>$id])->update(['title_code'=>$title->code,'title_name'=>$title->name]);
        CourseRegisterView::where(['title_id'=>$id])->update(['title_code'=>$title->code,'title_name'=>$title->name]);

    }
    private function updatePosition($id,$type=1){
        if ($type==1){ // update
            $position=Position::findOrFail($id);
        }else{ // delete
            $position = new \stdClass();
            $position->code=null;
            $position->name = null;
        }
        ProfileView::where(['position_id'=>$id])->update(['position_code'=>$position->code,'position_name'=>$position->name]);
        OfflineRegisterView::where(['position_id'=>$id])->update(['position_code'=>$position->code,'position_name'=>$position->name]);
        OnlineRegisterView::where(['position_id'=>$id])->update(['position_code'=>$position->code,'position_name'=>$position->name]);
        CourseRegisterView::where(['position_id'=>$id])->update(['position_code'=>$position->code,'position_name'=>$position->name]);

    }
    private function updateStatusProfile($id,$type=1){
        if ($type==1){ // update
            $profileStatus=ProfileStatus::findOrFail($id);
        }else{ // delete
            $profileStatus = new \stdClass();
            $profileStatus->name = null;
        }
        ProfileView::where(['status_id'=>$id])->update(['status_name'=>$profileStatus->name]);
    }
    private function updateTrainingProgram($id,$type=1){
        if ($type==1){ // update
            $trainingProgram=TrainingProgram::findOrFail($id);
        }else{ // delete
            $trainingProgram = new \stdClass();
            $trainingProgram->code = null;
            $trainingProgram->name = null;
        }
        OnlineCourseView::where(['training_program_id'=>$id])->update(['training_program_code'=>$trainingProgram->code,'training_program_name'=>$trainingProgram->name]);
        OfflineCourseView::where(['training_program_id'=>$id])->update(['training_program_code'=>$trainingProgram->code,'training_program_name'=>$trainingProgram->name]);
        CourseView::where(['training_program_id'=>$id])->update(['training_program_code'=>$trainingProgram->code,'training_program_name'=>$trainingProgram->name]);

    }
    private function updateSubject($id,$type=1){
        if ($type==1){ // update
            $subject=Subject::findOrFail($id);
        }else{ // delete
            $subject = new \stdClass();
            $subject->code = null;
            $subject->name = null;
        }
        OnlineCourseView::where(['subject_id'=>$id])->update(['subject_code'=>$subject->code,'subject_name'=>$subject->name]);
        OfflineCourseView::where(['subject_id'=>$id])->update(['subject_code'=>$subject->code,'subject_name'=>$subject->name]);
        CourseView::where(['subject_id'=>$id])->update(['subject_code'=>$subject->code,'subject_name'=>$subject->name]);

    }
    private function updateTrainingLocation($id,$type=1){
        if ($type==1){ // update
            $trainingLocation=TrainingLocation::findOrFail($id);
        }else{ // delete
            $trainingLocation = new \stdClass();
            $trainingLocation->name = null;
        }
        OnlineCourseView::where(['training_location_id'=>$id])->update(['training_location_name'=>$trainingLocation->name]);
        OfflineCourseView::where(['training_location_id'=>$id])->update(['training_location_name'=>$trainingLocation->name]);
        CourseView::where(['training_location_id'=>$id])->update(['training_location_name'=>$trainingLocation->name]);
    }
    private function updateTrainingFrom($id,$type=1){
        if ($type==1){ // update
            $trainingForm=TrainingForm::findOrFail($id);
        }else{ // delete
            $trainingForm = new \stdClass();
            $trainingForm->name = null;
        }

        OnlineCourseView::where(['training_form_id'=>$id])->update(['training_form_name'=>$trainingForm->name]);
        OfflineCourseView::where(['training_form_id'=>$id])->update(['training_form_name'=>$trainingForm->name]);
        CourseView::where(['training_form_id'=>$id])->update(['training_form_name'=>$trainingForm->name]);
    }
    private function updateTrainingType($id,$type=1){
        if ($type==1){ // update
            $trainingType=TrainingType::findOrFail($id);
        }else{ // delete
            $trainingType = new \stdClass();
            $trainingType->name = null;
        }
        OfflineCourseView::where(['training_type_id'=>$id])->update(['training_type_name'=>$trainingType->name]);
        CourseView::where(['training_type_id'=>$id])->update(['training_type_name'=>$trainingType->name]);
    }
    private function updateTeacherType($id,$type=1){
        if ($type==1){ // update
            $teacherType=TeacherType::findOrFail($id);
        }else{ // delete
            $teacherType = new \stdClass();
            $teacherType->name = null;
        }
        OfflineCourseView::where(['teacher_type_id'=>$id])->update(['teacher_type_name'=>$teacherType->name]);
        CourseView::where(['teacher_type_id'=>$id])->update(['teacher_type_name'=>$teacherType->name]);
    }
    private function updateTrainingPartner($id,$type=1){
        if ($type==1){ // update
            $trainingPartner=TrainingPartner::findOrFail($id);
        }else{ // delete
            $trainingPartner = new \stdClass();
            $trainingPartner->name = null;
        }
        OfflineCourseView::where(['training_partner_id'=>$id])->update(['training_partner_name'=>$trainingPartner->name]);
        CourseView::where(['training_partner_id'=>$id])->update(['training_partner_name'=>$trainingPartner->name]);
    }
}
