<?php

namespace App\Imports;

use App\Languages;
use App\LanguagesGroups;
use App\Profile;
use App\User;
use App\Notifications\ImportSubjectHasFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;

class ImportLanguages implements OnEachRow, WithStartRow, WithChunkReading, ShouldQueue, WithEvents
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
        $pkey = trim($row[1]);

        $errors = [];
        if (empty($pkey)) {
            $errors[] = 'Dòng '. $row[0] .': Từ khóa không thể trống';
        }

/*        $langs = Languages::where('pkey', '=', $pkey)->first();

        if (empty($langs)){
            $errors[] = 'Dòng '. $row[0] .': Từ khóa <b>'. $pkey .' </b> không tồn tại';
            $error = true;
        }*/

        $group = LanguagesGroups::where('name', '=', $row[4])->first();
        if (empty($group)){
            $errors[] = 'Dòng '. $row[0] .': Nhóm <b>'. $row[4] .' </b> không tồn tại';
        }

        if(!empty($errors)) {
            $this->imported_by->notify(new ImportSubjectHasFailed($errors));
            return null;
        }

        try {
            $model = Languages::firstOrNew(['pkey' => $pkey]);
            $model->content =$row[2];
            $model->content_en = $row[3];
            $model->groups_id = $group->id;
            $model->save();
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
