<?php
namespace Modules\Capabilities\Exports;

use Maatwebsite\Excel\Events\BeforeSheet;
use Modules\Capabilities\Entities\CapabilitiesConventionPercent;
use Modules\Capabilities\Entities\CapabilitiesReview;
use Modules\Capabilities\Entities\CapabilitiesReviewDetail;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReviewExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $review_id;
    protected $user_id;
    protected $current_category = 0;
    protected $total_group = 0;
    protected $total_row = 0;
    protected $current_row = 0;
    protected $count = ['standard_goal' => 0, 'practical_goal' => 0, 'standard_critical_level' => 0, 'standard_level' => 0, 'practical_level' => 0 , 'standard_weight' => 0];
    protected $count_row = 0;
    protected $sum_goal = 0;
    protected $sum_practical_goal = 0;
    protected $count_convent = 0;
    protected $sum_weight = 0;
    protected $arr = [];

    public function __construct($user_id, $review_id)
    {
        $this->review_id = $review_id;
        $this->user_id = $user_id;
        $this->total_row = $this->query()->count();
    }

    public function map($review): array
    {
        $this->arr[] = $review->group_id;
        $this->current_row += 1;
        if ($this->current_category != $review->group_id) {
            $this->total_group += 1;
        }

        if ($this->current_category == 0) {
            $this->current_category = $review->group_id;
        }

        if ($this->current_category != $review->group_id ) {

            $column = [
                [
                    '',
                    'Cộng',
                    '',
                    $this->count['standard_weight'] . ' %',
                    $this->count_row,
                    $this->count_row,
                    $this->count['standard_goal'],
                    $this->count_row,
                    $this->count['practical_goal'],
                    ''
                ],
            ];

            $this->count['standard_goal'] = 0;
            $this->count['practical_goal'] = 0;
            $this->count['standard_weight'] = 0;
            $this->count_row = 0;

            $column[] = [
                $review->group_name,
                $review->capabilities_code,
                $review->capabilities_name,
                $review->standard_weight. ' %',
                $review->standard_critical_level,
                $review->standard_level,
                $review->standard_goal,
                $review->practical_level,
                $review->practical_goal,
                ($review->practical_level < $review->standard_level ? 'x' : '')
            ];

            $this->count['standard_weight'] += $review->standard_weight;
            $this->count['standard_goal'] += $review->standard_goal;
            $this->count['practical_goal'] += $review->practical_goal;
            $this->count['standard_critical_level'] += $review->standard_critical_level;
            $this->count['standard_level'] += $review->standard_level;
            $this->count['practical_level'] += $review->practical_level;
            $this->count_row += 1;
            $this->sum_goal += $review->standard_goal;
            $this->sum_practical_goal += $review->practical_goal;
            $this->current_category = $review->group_id;
            $this->sum_weight += $review->standard_weight;
        } else {
            $this->current_category = $review->group_id;
            $column = [
                [
                    $review->group_name,
                    $review->capabilities_code,
                    $review->capabilities_name,
                    $review->standard_weight. ' %',
                    $review->standard_critical_level,
                    $review->standard_level,
                    $review->standard_goal,
                    $review->practical_level,
                    $review->practical_goal,
                    ($review->practical_level < $review->standard_level ? 'x' : '')
                ],
            ];

            $this->count['standard_weight'] += $review->standard_weight;
            $this->count['standard_goal'] += $review->standard_goal;
            $this->count['practical_goal'] += $review->practical_goal;
            $this->count['standard_critical_level'] += $review->standard_critical_level;
            $this->count['standard_level'] += $review->standard_level;
            $this->count['practical_level'] += $review->practical_level;
            $this->count_row += 1;
            $this->sum_goal += $review->standard_goal;
            $this->sum_practical_goal += $review->practical_goal;
            $this->sum_weight += $review->standard_weight;
        }

        if ($this->current_row == $this->total_row) {
            $column[] = [
                '',
                'Cộng',
                '',
                $this->count['standard_weight'] . ' %',
                $this->count_row,
                $this->count_row,
                $this->count['standard_goal'],
                $this->count_row,
                $this->count['practical_goal'],
                ''
            ];

            $column[] = [
                'Tổng Cộng',
                '','',
                $this->sum_weight,
                $this->count['standard_critical_level'],
                $this->count['standard_level'],
                (float) $this->sum_goal,
                $this->count['practical_level'],
                (float) $this->sum_practical_goal,
                ''
            ];
            $column[] = [
                'Tỷ lệ giữa điểm chuẩn so với điểm thực tế',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                number_format(($this->sum_practical_goal / $this->sum_goal)*100, 0) . ' %',
                ''
            ];

            $percent = number_format(($this->sum_practical_goal / $this->sum_goal)*100, 0);
            $conventions = CapabilitiesConventionPercent::getConventPercent($percent);
            $convent = json_decode($review->convent_id);

            $this->count_convent = $convent ? count($convent) : 0;

            if ($convent) {
                foreach ($conventions as $convention){
                    if (in_array($convention->id, $convent)){
                        $column[] = [
                            'Đánh giá',
                            $convention->name
                        ];
                    }
                }
            }
            else{
                $column[] = [
                    'Đánh giá'
                ];
            }

            $column[] = [
                'Nhận xét',
                $review->comment,
            ];

        }

        return $column;
    }

    public function query(){

        $query = CapabilitiesReviewDetail::query();
        $query->select([
            'a.*',
            'b.convent_id',
            'b.comment',
            'c.lastname as lastname',
            'c.firstname as firstname',
            'c.code as user_code',
            'd.name as title_name',
            'e.name as unit_name',
            'a.group_id'
        ]);
        $query->from('el_capabilities_review_detail AS a');
        $query->leftJoin('el_capabilities_review AS b', 'b.id', '=', 'a.review_id');
        $query->leftJoin('el_profile AS c', 'c.user_id', '=', 'b.user_id');
        $query->leftJoin('el_titles AS d', 'd.code', '=', 'c.title_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'c.unit_code');
        $query->where('a.review_id', '=', $this->review_id);
        $query->orderBy('a.id', 'ASC');

        return $query;
    }

    public function headings(): array
    {
        $review = CapabilitiesReview::where('user_id', '=', $this->user_id)->first();
        return [
            ['Tên đánh giá ', ''],
            ['Người đánh giá ', '', '', 'Đơn vị', '', '', '', 'Chức danh'],
            ['Mã nhân viên', '', '', 'Đơn vị', '', '', '', 'Thời gian tạo'],
            ['Họ tên nhân viên', '', '', 'Chức danh', '', '', '', ($review->status == 1 ? 'Thời gian gửi' : 'Cập nhật lần cuối')],
            ['Nhóm', 'Mã khung năng lực', 'Tên năng lực', 'Năng lực chuẩn', '', '', '', 'Năng lực thực tế', '', 'Năng lực cần bồi dưỡng'],
            ['', '', '', 'Trọng số', 'Mức độ quan trọng', 'Cấp độ', 'Điểm chuẩn', 'Cấp độ', 'Điểm thực tế', ''],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $query = $this->arr;
                $num = 7;
                foreach ($query as $key => $item) {
                    if ($key == 0) {
                        continue;
                    }
                    if ($query[$key] != $query[$key - 1]) {
                        $event->sheet->getDelegate()->getStyle('A' . ($num + array_count_values($this->arr)[$query[$key - 1]]) . ':J' . ($num +array_count_values($this->arr)[$query[$key - 1]]))
                            ->applyFromArray([
                                'font' => [
                                    'name' => 'Arial',
                                    'size' => 11,
                                    'bold' => true,
                                ],
                            ])
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('FCE850');

                        $num += array_count_values($this->arr)[$query[$key - 1]] + 1;
                    }
                }
                $user = Profile::find($this->user_id);
                $title = Titles::where('code', '=', $user->title_code)->first();
                $unit = Unit::where('code', '=', $user->unit_code)->first();

                $reviewDetail = CapabilitiesReviewDetail::where('review_id', '=', $this->review_id)->get();
                $review = CapabilitiesReview::where('user_id', '=', $this->user_id)->first();

                $user_review = Profile::find($review->created_by);
                $title_review = Titles::where('code', '=', $user_review->title_code)->first();
                $unit_review = Unit::where('code', '=', $user_review->unit_code)->first();
               
                $id = count($reviewDetail) + $this->total_group;

                $count_convent = $this->count_convent;

                $header = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name'      => 'Arial',
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ];
                $event->sheet->getDelegate()->getStyle('A'. (6 + $id) . ':J' .(6 + $id))
                    ->applyFromArray([
                        'font' => [
                            'name' => 'Arial',
                            'size' => 11,
                            'bold' => true,
                        ],
                    ])
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FCE850');

                $event->sheet->getDelegate()->getStyle('A1:A4')->applyFromArray([
                    'font' => [
                        'name'      => 'Arial',
                        'size'      =>  11,
                    ],
                ])
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('D2:D4')->applyFromArray([
                    'font' => [
                        'name'      => 'Arial',
                        'size'      =>  11,
                    ],
                ])
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('H2:H4')->applyFromArray([
                    'font' => [
                        'name'      => 'Arial',
                        'size'      =>  11,
                    ],
                ])
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getRowDimension(6)->setRowHeight(39);

                $event->sheet->getDelegate()->getStyle('A5:J6')->getAlignment()->setWrapText(true);

                $event->sheet->getDelegate()->getStyle('A5:J6')->applyFromArray($header)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:J'.(6 + $id).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('D6:J'.(6 + $id).'')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A'.(6 + $id + 1).':J'.(6 + $id + 1).'')
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'font' => [
                            'name'      => 'Arial',
                            'bold'      =>  true,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER,
                        ],
                    ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A'.(6 + $id + 2).':J'.(6 + $id + 2).'')
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'font' => [
                            'name'      => 'Arial',
                            'bold'      =>  true,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER,
                        ],
                    ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A'.(6 + $id + 3).'')->applyFromArray([
                    'font' => [
                        'name'      => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A'.(6 + $id + 3 + ($count_convent == 0 ?$count_convent + 1 :$count_convent)).'')->applyFromArray([
                    'font' => [
                        'bold'      =>  true,
                        'name'      => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()
                    ->getStyle('A'.(6 + $id + 3 + ($count_convent == 0 ?$count_convent + 1 : $count_convent)).':J'.(6 + $id + 3 + ($count_convent == 0 ?$count_convent + 1 : $count_convent)) .'')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('A'.(6 + $id + 3).':J'.(6 + $id + 2 + ($count_convent == 0?$count_convent + 1 : $count_convent)).'')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                ]);

                $event->sheet->mergeCells('A'.(6 + $id + 3).':A'.(6 + $id + 2 + ($count_convent == 0 ? $count_convent + 1 : $count_convent)).'');

                $event->sheet->mergeCells('B1:J1');
                $event->sheet->mergeCells('B2:C2');
                $event->sheet->mergeCells('B3:C3');
                $event->sheet->mergeCells('B4:C4');

                $event->sheet->mergeCells('E2:G2');
                $event->sheet->mergeCells('E3:G3');
                $event->sheet->mergeCells('E4:G4');

                $event->sheet->mergeCells('I2:J2');
                $event->sheet->mergeCells('I3:J3');
                $event->sheet->mergeCells('I4:J4');

                $event->sheet->mergeCells('A5:A6');
                $event->sheet->mergeCells('B5:B6');
                $event->sheet->mergeCells('C5:C6');
                $event->sheet->mergeCells('J5:J6');
                $event->sheet->mergeCells('D5:G5');
                $event->sheet->mergeCells('H5:I5');

                $event->sheet->mergeCells('A'.(6 + $id + 1).':C'.(6 + $id + 1).'');
                $event->sheet->mergeCells('A'.(6 + $id + 2).':H'.(6 + $id + 2).'');
                $event->sheet->mergeCells('B'.(6 + $id + 3 + ($count_convent == 0 ?$count_convent + 1 : $count_convent)).':J'.(6 + $id + 3 + ($count_convent == 0 ?$count_convent + 1 : $count_convent)).'');

                $event->sheet->setCellValue('B1', $review->name);
                $event->sheet->setCellValue('B2', $user_review->code . ' - ' . $user_review->lastname .' '.$user_review->firstname);
                $event->sheet->setCellValue('B3', $user->code);
                $event->sheet->setCellValue('B4', $user->lastname .' '. $user->firstname);
                $event->sheet->setCellValue('E2', $unit_review->name);
                $event->sheet->setCellValue('E3', $unit->name);
                $event->sheet->setCellValue('E4', $title->name);

                $event->sheet->setCellValue('I2', $title_review->name);
                $event->sheet->setCellValue('I3', get_date($review->created_at, 'H:i:s d/m/Y'));
                $event->sheet->setCellValue('I4', get_date($review->updated_at, 'H:i:s d/m/Y'));

                $event->sheet->getDelegate()->getStyle('B1:B2')->applyFromArray([
                    'font' => [
                        'name'      => 'Arial',
                        'bold'      =>  true,
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('B4')->applyFromArray([
                    'font' => [
                        'name'      => 'Arial',
                        'bold'      =>  true,
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('B3')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                ]);

                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(12);

                $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(13.11);

                $event->sheet->getDelegate()->getColumnDimension('F')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(7);

                $event->sheet->getDelegate()->getColumnDimension('G')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(8);

                $event->sheet->getDelegate()->getColumnDimension('I')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(10);

                $event->sheet->getDelegate()->getColumnDimension('J')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(13);

                $event->sheet->getDelegate()->getColumnDimension('H')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(17);
            }
        ];
    }



}