@extends('layouts.backend')

@section('page_title', trans('backend.assessments_capability'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.capabilities.review') }}">{{ trans('backend.capabilities') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.capabilities.review.user.index', ['user_id' => $user->user_id]) }}">{{trans('backend.student')}}: {{ $user->lastname .' '. $user->firstname }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.assessments_capability') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('styles/module/capabilities/css/capabilities.css') }}">

<div role="main" id="capabilities-review">
    <form method="post" action="{{ route('module.capabilities.review.user.save', ['user_id' => $user->user_id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4 text-right">
                @canany(['capabilities-review-create', 'capabilities-review-edit'])
                    @if($model->id)
                        <a class="btn btn-info" href="{{ route('module.capabilities.review.user.export', ['user_id' => $user->user_id, 'id' => $model->id]) }}">
                            <i class="fa fa-download"></i> Export
                        </a>
                    @endif
                @endcanany
                <div class="btn-group act-btns">
                    @canany(['capabilities-review-create', 'capabilities-review-edit'])
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.capabilities.review.user.index', ['user_id' => $user->user_id]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                            <input type="text" name="name" id="name" class="form-control" value="{{ $model->name }}" required>
                        </div>
                    </div>

                    <table class="tDefault table table-bordered table-review">
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
                            $total_weight = 0;
                        @endphp
                        @foreach($group as $item)
                            @php
                                //$caps = $capabilities($user->title_id, $item->id);
                                $caps = $capabilities($title->id, $item->id);
                                $count_cap = count($caps);
                            @endphp
                                <tr>
                                    <td @if($count_cap > 1) rowspan="{{ $count_cap + 1 }}" @endif >{{ $item->name }}</td>
                                    @if($count_cap == 1)
                                    @foreach($caps as $cap)
                                        @php
                                        $detail = isset($review_id) ? $review_detail($cap->id, $review_id) : null;
                                        @endphp
                                        <td>{{ $cap->code }}</td>
                                        <td class="text-left"><a href="javascript:void(0)" class="load-modal" data-url="{{ route('module.capabilities.review.user.modal_dictionary', ['user_id' => $user->user_id, 'id' => $cap->capabilities_id]) }}">{{ $cap->name }}</a></td>
                                        <td align="center">{{ $cap->weight }} %</td>
                                        <td align="center">{{ $cap->critical_level }}</td>
                                        <td align="center">{{ $cap->level }}</td>
                                        <td align="center">{{ (float) $cap->goal }}</td>
                                        <td align="center" width="10%">
                                            <select name="practical_level_{{ $cap->id }}" class="form-control practical-level" data-id="{{ $cap->id }}" data-group="{{ $item->id }}" required>
                                                <option value="">{{trans('backend.choose_levels')}}</option>
                                                <option value="1" {{ $detail ? $detail->practical_level == 1 ? 'selected' : '' : '' }}>1</option>
                                                <option value="2" {{ $detail ? $detail->practical_level == 2 ? 'selected' : '' : '' }}>2</option>
                                                <option value="3" {{ $detail ? $detail->practical_level == 3 ? 'selected' : '' : '' }}>3</option>
                                                <option value="4" {{ $detail ? $detail->practical_level == 4 ? 'selected' : '' : '' }}>4</option>
                                            </select>
                                        </td>
                                        <td class="practical-goal item-{{ $item->id }}">{{ $detail ? (float) $detail->practical_goal : '' }}</td>
                                        <td class="foster">{!! $detail ? (floatval($detail->practical_goal) < floatval($cap->goal) ? '<i class="fa fa-times"></i>' : '') : '' !!}</td>
                                    @endforeach
                                    @endif
                                </tr>
                            @if($count_cap > 1)
                                @foreach($caps as $cap)
                                    @php
                                        $detail = isset($review_id) ? $review_detail($cap->id, $review_id) : null;
                                    @endphp
                                    <tr>
                                        <td>{{ $cap->code }}</td>
                                        <td class="text-left"><a href="javascript:void(0)" class="load-modal" data-url="{{ route('module.capabilities.review.user.modal_dictionary', ['user_id' => $user->user_id, 'id' => $cap->capabilities_id]) }}">{{ $cap->name }}</a></td>
                                        <td align="center">{{ $cap->weight }} %</td>
                                        <td align="center">{{ $cap->critical_level }}</td>
                                        <td align="center">{{ $cap->level }}</td>
                                        <td align="center">{{ (float) $cap->goal }}</td>
                                        <td align="center" width="10%">
                                            <select name="practical_level_{{ $cap->id }}" class="form-control practical-level" data-id="{{ $cap->id }}" data-group="{{ $item->id }}" required>
                                                <option value="">{{trans('backend.choose_levels')}}</option>
                                                <option value="1" {{ $detail ? $detail->practical_level == 1 ? 'selected' : '' : '' }}>1</option>
                                                <option value="2" {{ $detail ? $detail->practical_level == 2 ? 'selected' : '' : '' }}>2</option>
                                                <option value="3" {{ $detail ? $detail->practical_level == 3 ? 'selected' : '' : '' }}>3</option>
                                                <option value="4" {{ $detail ? $detail->practical_level == 4 ? 'selected' : '' : '' }}>4</option>
                                            </select>
{{--                                            <input type="text" name="practical_level_{{ $cap->id }}" class="form-control is-number practical-level" data-id="{{ $cap->id }}" data-group="{{ $item->id }}" required value="{{ $detail ? $detail->practical_level : '' }}">--}}
                                        </td>
                                        <td class="practical-goal item-{{ $item->id }}">{{ $detail ? (float) $detail->practical_goal : '' }}</td>
                                        <td class="foster">{!! $detail ? (floatval($detail->practical_goal) < floatval($cap->goal) ? '<i class="fa fa-times"></i>' : '') : '' !!}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @php
                                $sum_goal = 0;
                                $sum_practical_goal = 0;
                                $sum_weight = 0;
                                foreach($caps as $cap) {
                                    $detail = isset($review_id) ? $review_detail($cap->id, $review_id) : null;
                                    $sum_goal += $cap->goal;

                                    $sum_goal1 += $cap->goal;
                                    $sum_critical_level += $cap->critical_level;
                                    $sum_level += $cap->level;
                                    $sum_weight += $cap->weight;

                                    if($detail){
                                        $sum_practical_goal += $detail->practical_goal;
                                        $sum_practical_level += $detail->practical_level;
                                        $sum_practical_goal1 += $detail->practical_goal;
                                    }
                                }
                                $total_weight += $sum_weight;
                            @endphp
                            <tr class="bg-sum">
                                <td colspan="3"><b>Cộng</b></td>
                                <td><b>{{ $sum_weight }}</b></td>
                                <td><b>{{ count($caps) }}</b></td>
                                <td><b>{{ count($caps) }}</b></td>
                                <td class="text-danger"><b>{{ $sum_goal }}</b></td>
                                <td><b>{{ count($caps) }}</b></td>
                                <td class="sum-practical-goal-{{ $item->id }} text-danger"><b>{{ $sum_practical_goal }}</b></td>
                                <td></td>
                            </tr>
                        @endforeach
                        @if(isset($detail))
                            <tr class="bg-sum2">
                                <td colspan="3"><b>Tổng cộng</b></td>
                                <td><b>{{ $total_weight }}</b></td>
                                <td><b>{{ $sum_critical_level }}</b></td>
                                <td><b>{{ $sum_level }}</b></td>
                                <td><b>{{ $sum_goal1 }}</b></td>
                                <td><b>{{ $sum_practical_level }}</b></td>
                                <td><b>{{ $sum_practical_goal1 }}</b></td>
                                <td></td>
                            </tr>
                            <tr class="bg-sum2">
                                <td colspan="8" ><b>Tỷ lệ giữa điểm chuẩn so với điểm thực tế</b></td>
                                <td><b>{{ number_format(($sum_practical_goal1 / $sum_goal1)*100, 0) . ' %' }}</b></td>
                                <td></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    @php
                        if(isset($detail)){
                            $percent = number_format(($sum_practical_goal1 / $sum_goal1)*100, 0);
                            $convention = isset($review_id) ? $convent($percent) : null;
                            $convent = json_decode($model->convent_id);
                        }
                    @endphp
                    <br>
                    @if(isset($convention))
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 control-label"><b>{{ trans('backend.assessments') }}</b></label>
                            <div class="col-sm-6">
                                @foreach($convention as $key => $item)
                                    <div class="custom-control">
                                        <input {{ $convent ? in_array($item->id, $convent) ? 'checked' : '' : ''}} type="checkbox" class="check-convent" name="convent_id[]" value="{{ $item->id }}"> {{ $item->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 control-label"><b>Nhận xét</b></label>
                            <div class="col-sm-6">
                                <textarea name="comment" id="comment" class="form-control">{{ $model->comment }}</textarea>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">

    $(".check-convent").on('change', function () {
       if ($(this).is(':checked')){
           $(this).val();
       }
    });

    $(function () {
        $(".practical-level").on('change', function () {
            let capid = $(this).data('id');
            let practical_level = $(this).val();
            let row = $(this).closest('tr');
            let group = $(this).data('group');

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
                    $(".sum-practical-goal-"+ group).html(totalGroup(group));
                }
            });
        });

        function totalGroup(group) {
            let items = $(".item-"+group);
            let total = 0;
            $.each(items, function(i, item) {
                total += ($(this).text()) ? parseFloat($(this).text()) : 0;
            });

            return total.toFixed(2);
        }
    });
</script>
@stop
