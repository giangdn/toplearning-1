@extends('layouts.backend')

@section('page_title', 'Xây dựng kế hoạch đào tạo tháng')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.capabilities.review') }}">Khung năng lực</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.capabilities.review.result.index') }}">Xây dựng kế hoạch đào tạo tháng</a>
            <i class="uil uil-angle-right"></i>
            {{ $model->id ? $model->name  : "Thêm mới" }}
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">
        <form action="{{ route('module.capabilities.review.result.save') }}" method="post" class="form-ajax form-validate">
            <input type="hidden" name="id" id="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4 text-right">
                    <div class="btn-group">
                        @canany(['capabilities-result-create', 'capabilities-result-edit'])
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                        @endcanany
                        <a class="btn btn-warning" href="{{ route('module.capabilities.review.result.index') }}"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                    </div>
                </div>
            </div>
            <br>
            <div class="tPanel">
                <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                    <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="base" class="tab-pane active">
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 control-label">
                                <b>{{trans('backend.plan_name')}}</b>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" name="name" id="name" class="form-control" required value="{{ $model->name }}">
                            </div>
                        </div>

                        <table class="tDefault table table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>{{trans('backend.fullname')}}</th>
                                    <th>{{ trans('backend.title') }}</th>
                                    <th>{{ trans('backend.unit') }}</th>
                                    <th>Chuyên đề đào tạo</th>
                                    <th>Năng lực còn thiếu</th>
                                    <th width="10%">Mức độ ưu tiên</th>
                                    <th>Thời gian đào tạo</th>
                                    <th>{{trans('backend.training_program_form')}}</th>
                                </tr>
                            </thead>
                        <tbody>
                @php
                    $index = 1;
                @endphp
                @foreach($users as $user)
                    @php
                        $subjects = $subject_missing($user->user_id);
                        $count = $subjects->count();
                    @endphp

                    @if($count == 0) @continue @endif
                    @if($count == 1)
                        <tr>
                            <td>{{ $index }}</td>
                            <td>{{ $user->lastname .' '. $user->firstname }}</td>
                            <td>{{ $user->title_name }}</td>
                            <td>{{ $user->unit_name }}</td>
                            @foreach($subjects as $subject)
                                @php
                                    $result_detail = $detail($subject->subject_id, $model->id, $user->user_id);
                                @endphp

                                <td>{{ $subject->subject_name }}</td>
                                <td>{{ $subject->capabilities_name }}</td>
                                <td>
                                    <input type="text" name="priority_level_{{$user->user_id}}_{{ $subject->id }}_{{ $subject->capabilities_id}}_{{ $subject->subject_id }}" value="{{ $result_detail ? $result_detail->priority_level : '' }}"
                                           class="form-control is-number" min="0" required>
                                </td>
                                <td>
                                    <input type="text" name="training_time_{{$user->user_id}}_{{ $subject->id }}_{{ $subject->capabilities_id}}_{{ $subject->subject_id }}" value="{{ $result_detail ? $result_detail->training_time : '' }}"
                                           class="form-control" required>
                                </td>
                                <td>
                                    <select name="training_form_{{$user->user_id}}_{{ $subject->id }}_{{ $subject->capabilities_id }}_{{ $subject->subject_id }}" class="form-control select2" data-placeholder="{{trans('backend.choose_training_program_form')}}" required>
                                        <option value=""></option>
                                        <option value="1" {{ $result_detail ? $result_detail->training_form == 1 ? 'selected' : '' : '' }}>Tự học</option>
                                        <option value="2" {{ $result_detail ? $result_detail->training_form == 2 ? 'selected' : '' : '' }}>TĐV kèm cặp</option>
                                        <option value="3" {{ $result_detail ? $result_detail->training_form == 3 ? 'selected' : '' : '' }}>Đào tạo tại TTĐT nội bộ</option>
                                        <option value="4" {{ $result_detail ? $result_detail->training_form == 4 ? 'selected' : '' : '' }}>Đào tạo tại TTĐT thuê ngoài</option>
                                    </select>
                                </td>
                            @endforeach
                        </tr>
                    @else
                        <tr>
                            <td rowspan="{{ $count+1 }}">{{ $index }}</td>
                            <td rowspan="{{ $count+1 }}">{{ $user->lastname .' '. $user->firstname }}</td>
                            <td rowspan="{{ $count+1 }}">{{ $user->title_name }}</td>
                            <td rowspan="{{ $count+1 }}">{{ $user->unit_name }}</td>
                        </tr>
                        @foreach($subjects as $subject)
                            @php
                                $result_detail = $detail($subject->subject_id, $model->id, $user->user_id);
                            @endphp
                            <tr>
                                <td>{{ $subject->subject_name }}</td>
                                <td>{{ $subject->capabilities_name }}</td>
                                <td>
                                    <input type="text" name="priority_level_{{$user->user_id}}_{{ $subject->id }}_{{ $subject->capabilities_id
                                }}_{{ $subject->subject_id }}" value="{{ $result_detail ? $result_detail->priority_level : '' }}"
                                           class="form-control is-number" min="0" required>
                                </td>
                                <td>
                                    <input type="text" name="training_time_{{$user->user_id}}_{{ $subject->id }}_{{ $subject->capabilities_id
                                }}_{{ $subject->subject_id }}"  value="{{ $result_detail ? $result_detail->training_time : '' }}"
                                           class="form-control" required>
                                </td>
                                <td>
                                    <select name="training_form_{{$user->user_id}}_{{ $subject->id }}_{{ $subject->capabilities_id }}_{{
                                    $subject->subject_id }}" class="form-control select2" data-placeholder="{{trans('backend.choose_training_program_form')}}" required>
                                        <option value=""></option>
                                        <option value="1" {{ $result_detail ? $result_detail->training_form == 1 ? 'selected' : '' : '' }}>Tự học</option>
                                        <option value="2" {{ $result_detail ? $result_detail->training_form == 2 ? 'selected' : '' : '' }}>TĐV kèm cặp</option>
                                        <option value="3" {{ $result_detail ? $result_detail->training_form == 3 ? 'selected' : '' : '' }}>Đào tạo tại TTĐT nội bộ</option>
                                        <option value="4" {{ $result_detail ? $result_detail->training_form == 4 ? 'selected' : '' : '' }}>Đào tạo tại TTĐT thuê ngoài</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @php
                        $index += 1;
                    @endphp
                @endforeach
                </tbody>
            </table>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
