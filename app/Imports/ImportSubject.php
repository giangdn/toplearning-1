<?php

namespace App\Imports;

use App\Models\Categories\LevelSubject;
use App\Models\Categories\Unit;
use App\Notifications\ImportSubjectHasFailed;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use App\Profile;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;

class ImportSubject implements OnEachRow, WithStartRow, WithChunkReading, ShouldQueue, WithEvents
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
        $ctdt_code = trim($row[1]);
        $ctdt_name = trim($row[2]);
        $level_subject_code = trim($row[3]);
        $level_subject_name = trim($row[4]);
        $subject_code = trim($row[5]);
        $subject_name = trim($row[6]);
        $created_date = $row[7];
        $created_by = $row[8];
        $unit_code = $row[9];

        $errors = [];
        if (empty($ctdt_code)) {
            $errors[] = 'Dòng '. $row[0] .': Mã Chủ đề không thể trống';
            $error = true;
        }

        if (empty($ctdt_name)) {
            $errors[] = 'Dòng '. $row[0] .': Tên Chủ đề không thể trống';
            $error = true;
        }

        if (empty($level_subject_code)) {
            $errors[] = 'Dòng '. $row[0] .': Mã Mảng nghiệp vụ không thể trống';
            $error = true;
        }

        if (empty($level_subject_name)) {
            $errors[] = 'Dòng '. $row[0] .': Tên Mảng nghiệp vụ không thể trống';
            $error = true;
        }

        if (empty($subject_code)) {
            $errors[] = 'Dòng '. $row[0] .': Mã Chuyên đề không thể trống';
            $error = true;
        }

        if (empty($subject_name)) {
            $errors[] = 'Dòng '. $row[0] .': Tên Chuyên đề không thể trống';
            $error = true;
        }

        if ($created_by){
            $profile = Profile::where('code', '=', (string)$created_by)->first();

            if (empty($profile)){
                $errors[] = 'Dòng '. $row[0] .': Mã người tạo không tồn tại';
                $error = true;
            }
        }

        if ($unit_code){
            $unit = Unit::where('code', '=', (string)$unit_code)->first();

            if (empty($unit)){
                $errors[] = 'Dòng '. $row[0] .': Mã đơn vị không tồn tại';
                $error = true;
            }
        }

        if($error) {
            $this->imported_by->notify(new ImportSubjectHasFailed($errors));
            return null;
        }

        try {
            $training_program = TrainingProgram::firstOrNew(['code' => $ctdt_code]);
            $training_program->code = $ctdt_code;
            $training_program->name = $ctdt_name;

            if ($training_program->save()) {
                $level_subject = LevelSubject::firstOrNew(['code' => $level_subject_code]);
                $level_subject->code = $level_subject_code;
                $level_subject->name = $level_subject_name;
                $level_subject->status = 1;
                $level_subject->training_program_id = $training_program->id;

                if ($level_subject->save()){
                    $subject = Subject::firstOrNew(['code' => $subject_code]);
                    $subject->code = $subject_code;
                    $subject->name = $subject_name;
                    $subject->status = 1;
                    $subject->level_subject_id = $level_subject->id;
                    $subject->training_program_id = $level_subject->training_program_id;
                    $subject->created_date = $created_date ? date_convert($created_date) : null;
                    $subject->created_by = isset($profile) ? $profile->user_id : null;
                    $subject->unit_id = isset($unit) ? $unit->id : null;
                    $subject->save();
                }
            }
        }
        catch (\Exception $exception) {
            $this->imported_by->notify(new ImportSubjectHasFailed(['Dòng ' . $row[0] . ': ' . $exception->getMessage()]));
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
                $this->imported_by->notify(new ImportSubjectHasFailed([$event->getException()->getMessage()]));
            },
        ];
    }
}
