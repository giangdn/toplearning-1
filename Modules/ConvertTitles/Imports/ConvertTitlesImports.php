<?php
namespace Modules\ConvertTitles\Imports;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Modules\ConvertTitles\Entities\ConvertTitles;
use App\Profile;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ConvertTitlesImports implements ToModel, WithStartRow
{
    public $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    public function model(array $row)
    {
        $error = false;
        $code = $row[1];
        $title_code = $row[2];
        $unit_code = $row[3];
        $unit_receive_code = $row[4];
        $start_date = date_convert($row[5]);
        $end_date = date_convert($row[6], '23:59:59');
        $send_date = $row[7] ? date_convert($row[7]) : null;
        $note = $row[8] ? $row[8] : '';

        $profile = Profile::where('code', '=', $code)->first();
        $title = Titles::where('code', '=', $title_code)->first();
        $unit = Unit::where('code', '=', $unit_code)->first();
        $unit_receive = Unit::where('code', '=', $unit_receive_code)->first();

        if (empty($profile)) {
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if (empty($title)) {
            $this->errors[] = 'Mã chức danh chuyển đổi <b>'. $row[2] .'</b> không tồn tại';
            $error = true;
        }

        if (empty($unit)) {
            $this->errors[] = 'Mã đơn vị tập huấn <b>'. $row[3] .'</b> không tồn tại';
            $error = true;
        }

        if (empty($unit_receive)) {
            $this->errors[] = 'Mã đơn vị nhận <b>'. $row[4] .'</b> không tồn tại';
            $error = true;
        }

        if ($profile){
            $exists1 = ConvertTitles::where('user_id', '=', $profile->user_id)
                ->where('start_date', '<=', $start_date)
                ->where('end_date' , '>=', $start_date)
                ->exists();

            $exists2 = ConvertTitles::where('user_id', '=', $profile->user_id)
                ->where('start_date', '<=', $end_date)
                ->where('end_date' , '>=', $end_date)
                ->exists();

            if ($exists1 || $exists2) {
                $this->errors[] = 'Thời gian chuyển đổi chức danh của <b>'. $profile->lastname . ' ' . $profile->firstname .'</b> đã tồn tại';
                $error = true;
            }
        }

        if($error) {
            return null;
        }

        ConvertTitles::create([
            'user_id' => $profile->user_id,
            'title_id' => $title->id,
            'unit_id' => $unit->id,
            'unit_receive_id' => $unit_receive->id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'send_date' => $send_date,
            'note' => $note,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}