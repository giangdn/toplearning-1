<?php

namespace App\Imports;

use App\DonatePoints;
use App\Models\Categories\Area;
use App\User;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;
use App\Notifications\ImportUserHasFailed;
use Modules\User\Entities\WorkingProcess;
use Modules\Notify\Entities\NotifySend;
use Modules\Notify\Entities\NotifySendObject;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserPoint;
use App\DonatePointsHistory;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;


class DonateImport implements OnEachRow, WithStartRow, WithChunkReading, ShouldQueue, WithEvents
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

        $stt = $row[0];
        $user_code = trim($row[1]);
        $score = $row[2];
        $note = $row[3];

        $profile = Profile::where('code','=', $user_code)->first();

        $errors = [];
        if (empty($profile)) {
            $errors[] = 'Dòng '. $row[0] .': Mã nhân viên <b>'. $user_code .'</b> không tồn tại';
            $error = true;
        }
        if (empty($user_code)) {
            $errors[] = 'Dòng '. $row[0] .': Mã nhân viên không trống';
            $error = true;
        }
        if (empty($score)) {
            $errors[] = 'Dòng '. $row[0] .': điểm không được trống';
            $error = true;
        }
        if (empty($note)) {
            $errors[] = 'Dòng '. $row[0] .': ghi chú không được trống';
            $error = true;
        }

        if($error) {
            $this->imported_by->notify(new ImportUserHasFailed($errors));
            return null;
        }

        try {
            $donatePoints = new DonatePoints();
            $donatePoints->user_id = $profile->user_id;
            $donatePoints->score = $score;
            $donatePoints->note = $note;
            $donatePoints->created_by = \Auth::id();
            $donatePoints->updated_by = \Auth::id();            
            $donatePoints->save();
           
            $user_point = PromotionUserPoint::firstOrNew(['user_id' => $profile->user_id]);
            $user_point->point = $user_point->point + $score;
            $user_point->level_id = PromotionLevel::levelUp($user_point->point, $user_point->user_id);
            $user_point->save();

            $query = new Notify();
            $query->user_id = $profile->user_id;
            $query->subject = 'Bạn được nhận '. $donatePoints->score .' điểm';
            $query->content = 'Bạn nhận được quà tặng là '. $donatePoints->score .' điểm. Hãy kiểm tra ngay để không bỏ lỡ. <br> Lý do: '. $donatePoints->note .' <br><br> Bạn đừng quên hãy luôn tích cực tham gia học tập để nhận được nhiều quà tặng bất ngờ nhé.';
            $query->url = '';
            $query->created_by = \Auth::id();
            $query->save();

            $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
            $redirect_url = route('module.notify.view', [
                'id' => $query->id,
                'type' => 1
            ]);
            
            $notification = new AppNotification();
            $notification->setTitle($query->subject);
            $notification->setMessage($content);
            $notification->setUrl($redirect_url);
            $notification->add($profile->user_id);
            $notification->save(); 
            
            $donate_points_history = new DonatePointsHistory();
            $donate_points_history->user_id = $profile->user_id;
            $donate_points_history->score = $score;
            $donate_points_history->save();

        }
        catch (\Exception $exception) {
            $this->imported_by->notify(new ImportUserHasFailed(['Dòng ' . $row[0] . ': ' . $exception->getMessage()]));
        }
    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=',$point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
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
                $this->imported_by->notify(new ImportUserHasFailed([$event->getException()->getMessage()]));
            },
        ];
    }
}
