<?php
namespace Modules\CourseOld\Imports;
use App\Models\Categories\Subject;

use App\Profile;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Notifications\ImportUnitHasFailed;
use Modules\CourseOld\Entities\CourseOld;
use Modules\MergeSubject\Entities\MergeSubject;
use Modules\MergeSubject\Entities\MergeSubjectUser;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\SubjectComplete\Jobs\Import;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\TrainingProcessLogs;
use PhpOffice\PhpSpreadsheet\Calculation\FormulaParser;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CourseOldImport implements OnEachRow, WithStartRow, WithCalculatedFormulas,  WithChunkReading, ShouldQueue, WithEvents
{
    use Importable;
    public $imported_by;
    public $errors;
    public $arrTitle = [
            'STT',
            'Mã nhân viên',
            'Họ và tên',
            'Email',
            'Khu vực',
            'Điện thoại',
            'Đơn vị trực tiếp',
            'Đơn vị quản lý',
            'Loại đơn vị',
            'Chức vụ',
            'Chức danh',
            trans('lacourse.course_code'),
            trans('lacourse.course_name'),
            'Đơn vị đào tạo',
            'Hình thức đào tạo',
            'Thời lượng khóa học',
            'Từ ngày',
            'Đến ngày',
            'Thời gian',
            'Tổng thời lượng tham gia',
            'Tình trạng',
            'Điểm',
            'Kết quả',
            'Chi phí đi lại',
            'Chi phí lưu trú',
            'Công tác phí',
            'Bình quân CPGV',
            'Chi phí khác',
            'Bình quân CPTC',
            'Bình quân CP Học viên',
            'Tổng CP',
            'Ghi chú'
        ];

    public function __construct(User $user)
    {
        $this->imported_by = $user;
    }

    public function onRow(Row $row){
        $errors = [];
        $error = false;
        $item = $row->toArray();

        if (count($item) == 32){
            $arrTitle = $this->arrTitle;
        }else{
            $arrTitle = array_merge($this->arrTitle, array_fill(32, 225, null));
        }

//dd($arrTitle, $item);
        $index = $item[0];
        $userCode = trim($item[1]);
        $unit = trim($item[6]);
        $title = trim($item[10]);
        $courseCode = trim($item[11]);
        $courseName = trim($item[12]);
        $training_unit = trim($item[13]);
        $courseType = (int)trim($item[14]);
        $course_time = trim($item[15]);

        $startDate=Date::excelToDateTimeObject($item[16]);
        $endDate=Date::excelToDateTimeObject($item[17]);
        $item[16] = $startDate->format('d/m/Y');
        $item[17] = $endDate->format('d/m/Y');
        $start_date = $startDate->format('Y-m-d');
        $end_date = $endDate->format('Y-m-d');

        $data = array_combine($arrTitle, $item);

        $fullName = trim($item[2]);
        $subjectCodeData = $courseCode;
        $user = Profile::where('code','=',$userCode)->first();
        if (!$user){
            $errors[] = '<b> Dòng '. ($index) .'</b>: [Cột mã nhân viên] '. $userCode .' không tồn tại';
            $error = true;
        }
        $subject = Subject::where('code','=',$subjectCodeData)->first();
        if (!$subject) {
            $errors[] = '<b> Dòng '. ($index) .'</b>: [Cột mã khóa học] '.$subjectCodeData.' không tồn tại';
            $error = true;
        }
        $course_old = CourseOld::whereCourseCode($courseCode)
            ->where('user_code', $userCode)
            ->where('start_date', $start_date);
        if ($course_old->exists()){
            $errors[] = 'Mã nhân viên '. $userCode .' thuộc Mã khóa học '.$courseCode.' học ngày '.$item[16].' đã tồn tại';
            $error = true;
        }

        if ($error) {
            $this->imported_by->notify(new \Modules\CourseOld\Notifications\ImportFailed($errors));
            return null;
        }

        if ($courseType== 1 || $courseType== 2){
            // update quá trình đào tạo
            TrainingProcess::updateOrCreate([
                'user_id' => $user->id,
                'course_id' => $subject->id,
                'course_old' => 1,
                'start_date' => $start_date
            ],
            [
                'course_code'=>$subject->code,
                'course_name'=>$subject->name,
                'course_type'=>$courseType,
                'subject_id'=>$subject->id,
                'subject_code'=>$subject->code,
                'subject_name'=>$subject->name,
                'titles_name'=>$title,
                'unit_name'=>$unit,
                'start_date'=>$start_date,
                'end_date'=>$end_date,
                'pass'=>1,
                'status'=>1,
                'process_type'=>6,
                'note'=>'Import khóa học cũ',
                'course_old'=>1,
            ]);

            CourseOld::updateOrCreate([
                'user_code' => $userCode,
                'course_code' => $courseCode,
                'start_date' => $start_date
            ],[
                'full_name'=>$fullName,
                'course_name'=>$courseName,
                'unit'=>$unit,
                'title'=>$title,
                'course_type'=> $courseType,
                'data' => json_encode($data),
                'course_id'=>$subject->id,
                'start_date'=>$start_date,
                'end_date'=>$end_date,
            ]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }
    public function chunkSize(): int
    {
        return 500;
    }
    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
                $this->imported_by->notify(new ImportUnitHasFailed([$event->getException()->getMessage()]));
            },
        ];
    }
}
