<?php
namespace Modules\CareerRoadmap\Imports;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
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
use Modules\CareerRoadmap\Entities\CareerRoadmap;
use Modules\CareerRoadmap\Entities\CareerRoadmapTitle;
use Modules\SubjectComplete\Jobs\Import;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\TrainingProcessLogs;


class RoadmapImport implements ToModel, WithStartRow
{
//    use Importable;
    public $imported_by;
    public $errors =[];
    public $parent_id = '';
    public function __construct($title_id)
    {
        $this->errors = [];
        $this->title_id = $title_id;
    }


    // public function collection(Collection $collections)
    // {
    //     foreach ($collections as $index => $item) {
    //         dd($item);
    //         if (!$item[0])
    //             break;
    //         $title_primary = Titles::where('code',trim($item[2]))->first();
    //         if ($title_primary) {
    //             $model = new CareerRoadmap();
    //             $model->primary = (int)$item[0];
    //             $model->name = trim($item[1]);
    //             $model->title_id = $title_primary->id;
    //             if($model->save()){
    //                 $i=2;
    //                 while (isset($item[$i])){
    //                     $title_id = Titles::where(['code'=>trim($item[$i])])->value('id');
    //                     if ($title_id) {
    //                         $roadmapTitle = new CareerRoadmapTitle();
    //                         $roadmapTitle->career_roadmap_id = $model->id;
    //                         $roadmapTitle->title_id = $title_id;
    //                         $roadmapTitle->parent_id = $i == 2 ? null : CareerRoadmapTitle::find($return_id)->id;
    //                         $roadmapTitle->level = $i - 2;
    //                         $roadmapTitle->save();
    //                         $return_id = $roadmapTitle->id;
    //                         $i++;
    //                     }else{
    //                         $this->errors[] = 'Mã chức danh '.$item[$i].' của lộ trình ('.$item[1].') không tồn tại !';
    //                         break;
    //                     }
    //                 }
    //             }
    //         }
    //         else{
    //             $this->errors[] ='Mã chức danh '.$item[2].' của lộ trình ('.$item[1].') không tồn tại !';
    //         }
    //     }
    // }

    public function model(array $row)
    {
        $error = false;
        $index = (int) $row[0];
        if($index){
            $model = CareerRoadmap::firstOrNew(['name' => trim($row[1])]);
            $model->primary = $row[2];
            $model->name = trim($row[1]);
            $model->title_id = $this->title_id;
            $model->save();
            $this->parent_id = $model->id;
            $roadmapTitle = new CareerRoadmapTitle();
            $roadmapTitle->career_roadmap_id = $model->id;
            $roadmapTitle->title_id = $this->title_id;
            $roadmapTitle->level = 0;
            $roadmapTitle->save();
        } else {
            $title_id = Titles::where(['code'=>trim($row[1])])->value('id');
            if ($title_id) {
                $getCareerRoadmapTitles = CareerRoadmapTitle::where('career_roadmap_id',$this->parent_id)->get();
                foreach ($getCareerRoadmapTitles as $key => $getCareerRoadmapTitle) {
                    if (($getCareerRoadmapTitle->level + 1) == $row[3]) {
                        $roadmapTitle = new CareerRoadmapTitle();
                        $roadmapTitle->career_roadmap_id = $this->parent_id;
                        $roadmapTitle->title_id = $title_id;
                        $roadmapTitle->parent_id = $getCareerRoadmapTitle->id;
                        $roadmapTitle->level = $row[3];
                        $roadmapTitle->save();
                    } 
                }
            } else {
                $this->errors[] ='Mã chức danh chức danh '.$row[1].' không hợp lệ!';
            }
        }
    }

    public function startRow(): int
    {
        return 3;
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
