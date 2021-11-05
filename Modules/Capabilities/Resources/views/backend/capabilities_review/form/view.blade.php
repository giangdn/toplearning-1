@extends('layouts.backend')

@section('page_title', 'Đánh giá khung năng lực')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.capabilities.review') }}">Khung năng lực</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.capabilities.review.user.index', ['user_id' => $user->user_id]) }}">{{trans('backend.student')}}: {{ $user->lastname .' '. $user->firstname }}</a>
            <i class="uil uil-angle-right"></i>
            Đánh giá khung năng lực
        </h2>
    </div>
@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('styles/module/capabilities/css/capabilities.css') }}">
<div role="main" id="capabilities-review">

    <form method="post" action="" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group">
                    <a class="btn btn-info" href="{{ route('module.capabilities.review.user.export', ['user_id' => $user->user_id, 'id' => $model->id]) }}">
                        <i class="fa fa-download"></i> Export
                    </a>
                </div>
                <div class="btn-group act-btns">
                    <a href="{{ route('module.capabilities.review.user.index', ['user_id' => $user->user_id]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> Thoát</a>
                </div>
            </div>
        </div>

        <div class="clear"></div>

        <br>
        <div class="tPanel">

            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('backend.assessments') }}: {{ $user->lastname .' '. $user->firstname }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group row">
                                <label class="control-label col-sm-4"><b>{{ trans('backend.employee_code') }}</b></label>
                                <div class="col-sm-8">
                                    {{ $user->code }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-4"><b>{{ trans('backend.employee_name') }}</b></label>
                                <div class="col-sm-8">
                                    {{ $user->lastname .' '. $user->firstname }}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group row">
                                <label class="control-label col-sm-4"><b>{{ trans('backend.unit') }}</b></label>
                                <div class="col-sm-8">
                                    @if(isset($unit->name)) {{ $unit->name }} @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-4"><b>{{ trans('backend.title') }}</b></label>
                                <div class="col-sm-8">
                                    @if(isset($title->name)) {{ $title->name }} @endif
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-4">
                            <div class="form-group row">
                                <label class="control-label col-sm-4"><b>{{ trans('backend.create_time') }}</b></label>
                                <div class="col-sm-8">
                                    {{ get_date($model->created_at, 'H:i:s d/m/Y') }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-4"><b>{{ $model->status == 1 ? 'Thời gian gửi' : 'Cập nhật lần cuối' }}</b></label>
                                <div class="col-sm-8">
                                    {{ get_date($model->updated_at, 'H:i:s d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 control-label"><b>Tên đánh giá</b></label>
                        <div class="col-sm-6">
                            <input {{ $model->status == 1 ? 'disabled' : '' }} type="text" name="name" id="name" class="form-control" value="{{ $model->name }}" required>
                        </div>
                    </div>

                    <table class="tDefault table table-review">
                        <thead>
                        <tr>
                            <th rowspan="2" width="5%">Nhóm</th>
                            <th rowspan="2">Mã khung năng lực</th>
                            <th rowspan="2">Tên năng lực</th>
                            <th colspan="4">Năng lực chuẩn</th>
                            <th colspan="2">Năng lực thực tế</th>
                            <th rowspan="2" width="5%">Năng lực cần bồi dưỡng</th>
                        </tr>
                        <tr>
                            <th>Trọng số</th>
                            <th>Mức độ quan trọng</th>
                            <th>{{trans('backend.levels')}}</th>
                            <th>{{trans('backend.benchmarks')}}</th>
                            <th>{{trans('backend.levels')}}</th>
                            <th>Điểm thực tế</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $sum_goal1 = 0;
                            $sum_practical_goal1 = 0;
                            $sum_critical_level = 0;
                            $sum_level = 0;
                            $sum_practical_level = 0;
                        @endphp
                        @foreach($group as $item)
                            @php
                                $caps = $capabilities($item->id, $model->id);
                                $count_cap = count($caps);
                            @endphp
                            <tr>
                                <td @if($count_cap > 1) rowspan="{{ $count_cap + 1 }}" @endif >{{ $item->name }}</td>
                                @if($count_cap == 1)
                                    @foreach($caps as $cap)
                                        <td>{{ $cap->code }}</td>
                                        <td><a href="javascript:void(0)" class="load-modal" data-url="{{ route('module.capabilities.review.user.modal_dictionary', ['user_id' => $user->user_id, 'id' => $cap->capabilities_id]) }}">{{ $cap->name }}</a></td>
                                        <td align="center">{{ $cap->weight }} %</td>
                                        <td align="center">{{ $cap->critical_level }}</td>
                                        <td align="center">{{ $cap->level }}</td>
                                        <td align="center">{{ (float) $cap->goal }}</td>
                                        <td align="center" width="10%"><input type="text" name="practical_level_{{ $cap->id }}" class="form-control is-number practical-level" data-id="{{ $cap->id }}" readonly value="{{ (float) $cap->practical_level }}"></td>
                                        <td class="practical-goal">{{ (float) $cap->practical_goal }}</td>
                                        <td class="foster">@if($cap->practical_goal < $cap->goal) <i class="fa fa-times"></i> @endif</td>
                                    @endforeach
                                @endif
                            </tr>
                            @if($count_cap > 1)
                                @foreach($caps as $cap)
                                    <tr>
                                        <td>{{ $cap->code }}</td>
                                        <td><a href="javascript:void(0)" class="load-modal" data-url="{{ route('module.capabilities.review.user.modal_dictionary', ['user_id' => $user->user_id, 'id' => $cap->id]) }}">{{ $cap->name }}</a></td>
                                        <td align="center">{{ $cap->weight }} %</td>
                                        <td align="center">{{ $cap->critical_level }}</td>
                                        <td align="center">{{ $cap->level }}</td>
                                        <td align="center">{{ (float) $cap->goal }}</td>
                                        <td align="center" width="10%"><input type="text" name="practical_level_{{ $cap->id }}" class="form-control is-number practical-level" data-id="{{ $cap->id }}" readonly value="{{ (float) $cap->practical_level }}"></td>
                                        <td class="practical-goal">{{ (float) $cap->practical_goal }}</td>
                                        <td class="foster">@if($cap->practical_goal < $cap->goal) <i class="fa fa-times"></i> @endif</td>
                                    </tr>
                                @endforeach
                            @endif
                            @php
                                $sum_goal = 0;
                                $sum_practical_goal = 0;
                                foreach($caps as $cap) {
                                    $sum_goal += $cap->goal;
                                    $sum_goal1 += $cap->goal;
                                    $sum_critical_level += $cap->critical_level;
                                    $sum_level += $cap->level;
                                    $sum_practical_level += $cap->practical_level;
                                    $sum_practical_goal1 += $cap->practical_goal;
                                    $sum_practical_goal += $cap->practical_goal;
                                }
                            @endphp
                            <tr class="bg-sum">
                                <td colspan="4"><b>Cộng</b></td>
                                <td>{{ count($caps) }}</td>
                                <td>{{ count($caps) }}</td>
                                <td class="text-danger"><b>{{ $sum_goal }}</b></td>
                                <td>{{ count($caps) }}</td>
                                <td class="text-danger"><b>{{ $sum_practical_goal }}</b></td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr class="bg-sum2">
                            <td colspan="4"><b>Tổng cộng</b></td>
                            <td>{{ $sum_critical_level }}</td>
                            <td>{{ $sum_level }}</td>
                            <td><b>{{ $sum_goal1 }}</b></td>
                            <td>{{ $sum_practical_level }}</td>
                            <td><b>{{ $sum_practical_goal1 }}</b></td>
                            <td></td>
                        </tr>
                        <tr class="bg-sum2">
                            <td colspan="8" ><b>Tỷ lệ giữa điểm chuẩn so với điểm thực tế</b></td>
                            <td><b>{{ number_format(($sum_practical_goal1 / $sum_goal1)*100, 0) . ' %' }}</b></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    @php
                        $percent = number_format(($sum_practical_goal1 / $sum_goal1)*100, 0);
                        $convention = $convent($percent);
                        $convent = json_decode($model->convent_id);
                    @endphp
                    <br>
                    @if(isset($convention))
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 control-label"><b>{{ trans('backend.assessments') }}</b></label>
                            <div class="col-sm-6">
                                @foreach($convention as $key => $item)
                                    <div class="custom-control">
                                        <input {{ $model->status == 1 ? 'disabled' : '' }} {{ $convent ? in_array($item->id, $convent) ? 'checked' : '' : ''}} type="checkbox" class="check-convent" name="convent_id[]" value="{{ $item->id }}"> {{ $item->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 control-label"><b>Nhận xét</b></label>
                        <div class="col-sm-6">
                            <textarea {{ $model->status == 1 ? 'disabled' : '' }} name="comment" id="comment" class="form-control">{{ $model->comment }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        $(".practical-level").on('change', function () {
            let capid = $(this).data('id');
            let practical_level = $(this).val();
            let row = $(this).closest('tr');

            $.ajax({
                type: "POST",
                url: "{{ route('module.capabilities.review.user.getpractical', ['user_id' => $user->id]) }}",
                dataType: 'json',
                data: {
                    'captitleid': capid,
                    'practical_level': practical_level
                },
                success: function (result) {
                    row.find('.practical-goal').html(result.practical);
                    row.find('.foster').html((result.foster === "yes" ? "<i class='fa fa-times'></i>" : ""));
                }
            });
        });
    });
</script>
@stop
