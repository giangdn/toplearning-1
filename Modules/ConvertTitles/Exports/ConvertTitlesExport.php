<?php


namespace Modules\ConvertTitles\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\ConvertTitles\Entities\ConvertTitles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ConvertTitlesExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{

    use Exportable;

    protected $index = 0;
    protected $count = 0;

    public function map($convert_titles): array
    {
        $this->index++;
        return [
            $this->index,
            $convert_titles->code,
            $convert_titles->lastname . ' ' . $convert_titles->firstname,
            get_date($convert_titles->dob, 'Y'),
            $convert_titles->gender == 1 ? 'Nam' : 'Nữ',
            $convert_titles->title_name_1,
            $convert_titles->unit_name_1,
            $convert_titles->title_name_2,
            $convert_titles->unit_name_2,
            $convert_titles->unit_receive_name,
            get_date($convert_titles->start_date),
            get_date($convert_titles->end_date),
            $convert_titles->send_date ? get_date($convert_titles->send_date) : '',
            $convert_titles->note,
        ];
    }

    public function query()
    {
        $query = ConvertTitles::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'b.code',
            'b.dob',
            'b.gender',
            'c.name AS title_name_1',
            'd.name AS unit_name_1',
            'e.name AS title_name_2',
            'f.name AS unit_name_2',
            'g.name AS unit_receive_name',
        ]);
        $query->from('el_convert_titles AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_titles AS e', 'e.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS f', 'f.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS g', 'g.id', '=', 'a.unit_receive_id');
        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Danh sách nhân sự chuyển đổi chức danh'],
            [
                'STT',
                'Mã nhân viên',
                'Tên nhân viên',
                'Năm sinh',
                'Giới tính',
                'Chức danh',
                'Đơn vị',
                'Chức danh chuyển đổi',
                'Đơn vị tập huấn',
                'Đơn vị nhận',
                'Ngày bắt đầu',
                'Ngày kết thúc',
                'Ngày gửi đánh giá',
                'Ghi chú',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:N1');

                $event->sheet->getDelegate()->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:N'.(2 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },

        ];
    }
}