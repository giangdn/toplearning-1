<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Area;
use App\Models\Categories\CommitMonth;
use App\Models\Categories\Discipline;
use App\Models\Categories\District;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Position;
use App\Models\Categories\Province;
use App\Models\Categories\StudentCost;
use App\Models\Categories\Subject;
use App\Models\Categories\TeacherType;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingObject;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Models\Categories\UnitType;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Modules\Certificate\Entities\Certificate;
use Modules\Quiz\Entities\QuizType;

class BC14 extends Model
{
    public static function Unit(){
        Unit::addGlobalScope(new DraftScope());
        $query = Unit::query();
        $query->select([
            'el_unit.*',
            'b.name AS parent_name',
            'c.name AS type_name'
        ]);
        $query->leftJoin('el_unit AS b', 'b.code', '=', 'el_unit.parent_code');
        $query->leftJoin('el_unit_type AS c', 'c.id', '=', 'el_unit.type');

        return $query;
    }

    public static function Area(){
        $query = Area::query();
        $query->select([
            'a.*',
            'b.name AS parent_name'
        ]);
        $query->from('el_area AS a');
        $query->leftJoin('el_area AS b', 'b.code', '=', 'a.parent_code');

        return $query;
    }

    public static function UnitType(){

        UnitType::addGlobalScope(new DraftScope());
        $query = UnitType::query();

        return $query;
    }

    public static function Titles(){

        Titles::addGlobalScope(new DraftScope());
        $query = Titles::query();
        $query->select([
            'el_titles.*',
            'unit.name AS unit_name',
            'unit.level AS unit_level',
            'unit.code AS unit_code'
        ]);
        $query->leftJoin('el_unit AS unit', 'unit.id', '=', 'el_titles.unit_id');

        return $query;
    }

    public static function Cert(){

        Certificate::addGlobalScope(new DraftScope());
        $query = Certificate::query();

        return $query;
    }

    public static function Position(){

        Position::addGlobalScope(new DraftScope());
        $query = Position::query();

        return $query;
    }

    public static function TrainingProgram(){

        TrainingProgram::addGlobalScope(new DraftScope());
        $query = TrainingProgram::query();

        return $query;
    }

    public static function LevelSubject(){

        LevelSubject::addGlobalScope(new DraftScope());
        $query = LevelSubject::query();
        $query->select(['el_level_subject.*', 'b.name AS parent_name']);
        $query->from('el_level_subject');
        $query->leftJoin('el_training_program AS b', 'b.id', '=', 'el_level_subject.training_program_id');

        return $query;
    }

    public static function Subject(){

        Subject::addGlobalScope(new DraftScope());
        $query = Subject::query();
        $query->select([
            'el_subject.*',
            'b.name AS parent_name',
            'c.name as level_subject_name',
        ]);
        $query->leftJoin('el_training_program AS b', 'b.id', '=', 'el_subject.training_program_id');
        $query->leftJoin('el_level_subject as c', 'c.id', '=', 'el_subject.level_subject_id');

        return $query;
    }

    public static function TrainingLocation(){

        TrainingLocation::addGlobalScope(new DraftScope());
        $query = TrainingLocation::query()
            ->leftJoin('el_province as b','el_training_location.province_id','=','b.id')
            ->leftJoin('el_district as c','el_training_location.district_id','=','c.id')
            ->select(['el_training_location.*','b.name as province','c.name as district']);

        return $query;
    }

    public static function TrainingForm(){

        TrainingForm::addGlobalScope(new DraftScope());
        $query = TrainingForm::query();

        return $query;
    }

    public static function TrainingType(){
        $query = TrainingType::query();

        return $query;
    }

    public static function TrainingObject(){

        TrainingObject::addGlobalScope(new DraftScope());
        $query = TrainingObject::query();

        return $query;
    }

    public static function Absent(){

        Absent::addGlobalScope(new DraftScope());
        $query = Absent::query();

        return $query;
    }

    public static function Discipline(){

        Discipline::addGlobalScope(new DraftScope());
        $query = Discipline::query();

        return $query;
    }

    public static function AbsentReason(){

        AbsentReason::addGlobalScope(new DraftScope());
        $query = AbsentReason::query();

        return $query;
    }

    public static function QuizType(){

        QuizType::addGlobalScope(new DraftScope());
        $query = QuizType::query();

        return $query;
    }

    public static function TrainingCost(){

        TrainingCost::addGlobalScope(new DraftScope());
        $query = TrainingCost::query();

        return $query;
    }

    public static function StudentCost(){

        StudentCost::addGlobalScope(new DraftScope());
        $query = StudentCost::query();

        return $query;
    }

    public static function CommitMonth(){

        CommitMonth::addGlobalScope(new DraftScope());
        $query = CommitMonth::query();

        return $query;
    }

    public static function TrainingTeacher(){

        TrainingTeacher::addGlobalScope(new DraftScope());
        $query = TrainingTeacher::query();

        return $query;
    }

    public static function TeacherType(){

        TeacherType::addGlobalScope(new DraftScope());
        $query = TeacherType::query();

        return $query;
    }

    public static function TrainingPartner(){

        TrainingPartner::addGlobalScope(new DraftScope());
        $query = TrainingPartner::query();

        return $query;
    }

    public static function Province(){
        $query = Province::query();

        return $query;
    }

    public static function District(){
        $query = District::query();
        $query->select(['el_district.*','b.name as province']);
        $query->join('el_province as b','el_district.province_id','=','b.id');

        return $query;
    }
}
