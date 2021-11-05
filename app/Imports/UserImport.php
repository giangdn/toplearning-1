<?php

namespace App\Imports;

use App\Models\Categories\Area;
use App\User;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\UserMeta;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;
use App\Notifications\ImportUserHasFailed;

class UserImport implements OnEachRow, WithStartRow, WithChunkReading, ShouldQueue, WithEvents
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
        $username = trim($row[1]);
        $password = '123456789';
        $code = trim($row[4]);
        $lastname = $row[5];
        $firstname = $row[6];
        $email = $row[7];
        $title_code = trim($row[8]);
        $unit_code = trim($row[9]);
        $gender = (int) $row[10];
        $phone = $row[11];
        $dob = (strlen($row[12]) == 4) ? '01/01/'.$row[12] : $row[12];
        $join_company = $row[13];
        $address = $row[14];
        $current_address = $row[15]; //nơi ở hiện tại thuộc bảng user_meta
        $current_address_map = $row[16]; //định vị nơi ở hiện tại thuộc bảng user_meta
        $identity_card = $row[17];
        $date_range = $row[18];
        $issued_by = $row[19];
        $type_labor_contract = $row[20]; // Loại hợp đồng lao động (0: Thời vụ, 1: Thử việc, 2: Có thời hạn, 3: Không thời hạn) thuộc bảng user_meta
        $contract_signing_date = $row[21];
        $effective_date = $row[22];
        $expiration_date = $row[23];
        $status = $row[24];
        $level = $row[25];
        $name_contact_person = $row[26]; //Họ tên người liên hệ thuộc bảng user_meta
        $relationship = $row[27]; //Mối quan hệ thuộc bảng user_meta
        $phone_contact_person = $row[28]; // SĐT người liên hệ thuộc bảng user_meta
        $certificate_code = $row[29];
        $school = $row[30]; // Trường thuộc bảng user_meta
        $majors = $row[31]; //Chuyên ngành thuộc bảng user_meta
        $license = $row[32]; //Chứng chỉ/Bằng lái thuộc bảng user_meta
        $date_off = $row[33];
        $suspension_date = isset($row[34]) ? date_convert($row[34]) : null; // Ngày tạm hoãn thuộc bảng user_meta
        $reason = $row[35]; //Lý do thuộc bảng user_meta
        $commendation = $row[36]; //Khen thưởng thuộc bảng user_meta
        $discipline = $row[37]; //Kỷ luật thuộc bảng user_meta
        $marital_status = $row[38]; //Trình trạng hôn nhân (0: Độc thân, 1: Đã kết hôn) thuộc bảng user_meta
        $special_skills = $row[39]; //Năng khiếu đặc biệt thuộc bảng user_meta
        $note = $row[40]; //Ghi chú thuộc bảng user_meta

        $arr_user_meta = [
            'current_address' => $current_address,
            'current_address_map' => $current_address_map,
            'type_labor_contract' => $type_labor_contract,
            'name_contact_person' => $name_contact_person,
            'relationship' => $relationship,
            'phone_contact_person' => $phone_contact_person,
            'school' => $school,
            'majors' => $majors,
            'license' => $license,
            'suspension_date' => $suspension_date,
            'reason' => $reason,
            'commendation' => $commendation,
            'discipline' => $discipline,
            'marital_status' => $marital_status,
            'special_skills' => $special_skills,
            'note' => $note
        ];

        $title = Titles::where('code', '=', $title_code)->first();
        $unit = Unit::where('code', '=', $unit_code)->first();

        $errors = [];

        if (empty($username)) {
            $errors[] = 'Dòng '. $row[0] .': Tên đăng nhập không thể trống';
            $error = true;
        }

        if (empty($code)) {
            $errors[] = 'Dòng '. $row[0] .': Mã nhân viên không thể trống';
            $error = true;
        }

        if (empty(trim($lastname))) {
            $errors[] = 'Dòng '. $row[0] .': Họ nhân viên không thể trống';
            $error = true;
        }

        if (empty(trim($firstname))) {
            $errors[] = 'Dòng '. $row[0] .': Tên nhân viên không thể trống';
            $error = true;
        }

        if (empty($title)) {
            $errors[] = 'Dòng '. $row[0] .': Mã chức danh <b>'. $row[8] .'</b> không tồn tại';
            $error = true;
        }

        if (empty($unit)) {
            $errors[] = 'Dòng '. $row[0] .': Mã đơn vị <b>'. $row[9] .'</b> không tồn tại';
            $error = true;
        }

        if (!in_array($gender, [1, 0])) {
            $errors[] = 'Dòng '. $row[0] .': Giới tính không tồn tại';
            $error = true;
        }

        if (!in_array($status, [0, 1, 2, 3]) || empty($status)) {
            $errors[] = 'Dòng '. $row[0] .': Trạng thái không tồn tại';
            $error = true;
        }
        if (!in_array($type_labor_contract, [0, 1, 2, 3])){
            $errors[] = 'Dòng '. $row[0] .': Loại Hợp đồng lao động không tồn tại';
            $error = true;
        }
        if (!in_array($marital_status, [0, 1])){
            $errors[] = 'Dòng '. $row[0] .': Tình trạng hôn nhân không tồn tại';
            $error = true;
        }

        /*$check_user = User::where('username', '=', $username)->first();
        if ($check_user){
            $errors[] = 'Dòng '. $row[0] .': Nhân viên đã tồn tại';
            $error = true;
        }*/

        if($error) {
            $this->imported_by->notify(new ImportUserHasFailed($errors));
            return null;
        }

        try {
            $user = User::firstOrNew(['username' => $username]);
            $user->auth = 'manual';
            $user->username = $username;
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            $user->email = isset($email) ? $email : $username;
            $user->firstname = $firstname;
            $user->lastname = $lastname;

            if ($user->save()) {
                $profile = Profile::firstOrNew(['id' => $user->id]);
                $profile->id = $user->id;
                $profile->code = $code;
                $profile->user_id = $user->id;
                $profile->firstname = trim($firstname);
                $profile->lastname = trim($lastname);
                $profile->dob = date_convert($dob);
                $profile->address = $address;
                $profile->email = trim($email);
                $profile->identity_card = $identity_card;
                $profile->date_range = date_convert($date_range);
                $profile->issued_by = $issued_by;
                $profile->gender = $gender;
                $profile->phone = $phone;
                $profile->contract_signing_date = isset($contract_signing_date) ? date_convert($contract_signing_date) : null;
                $profile->effective_date = isset($effective_date) ? date_convert($effective_date) : null;
                $profile->expiration_date = isset($expiration_date) ? date_convert($expiration_date) : null;
                $profile->date_off = isset($date_off) ? date_convert($date_off) : null;
                $profile->join_company = isset($join_company) ? date_convert($join_company) : null;
                $profile->expbank = isset($join_company) && (strlen($join_company) > 5) ? cal_date_by_month(now(), date_convert($join_company)) : null;
                $profile->title_code = $title_code;
                $profile->title_id = $title->id;
                $profile->unit_code = $unit_code;
                $profile->unit_id = $unit->id;
                $profile->level = $level;
                $profile->certificate_code = $certificate_code;
                $profile->status = $status;
                $profile->save();

                foreach ($arr_user_meta as $key => $value){
                    $user_meta = UserMeta::query()->where('user_id', '=', $user->id)->where('key', '=', $key);

                    if ($user_meta->exists()){
                        $user_meta->update([
                            'value' => $value,
                        ]);
                    }else{
                        $user_meta = new UserMeta();
                        $user_meta->user_id = $user->id;
                        $user_meta->key = $key;
                        $user_meta->value = $value;
                        $user_meta->save();
                    }
                    /*UserMeta::updateOrCreate([
                        'user_id' => $user->id,
                        'key' => $key,
                    ],[
                        'value' => $value,
                    ]);*/
                }
            }
        }
        catch (\Exception $exception) {
            $this->imported_by->notify(new ImportUserHasFailed(['Dòng ' . $row[0] . ': ' . $exception->getMessage()]));
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
                $this->imported_by->notify(new ImportUserHasFailed([$event->getException()->getMessage()]));
            },
        ];
    }
}
