@extends('layouts.backend')

@section('page_title', 'Kết quả đào tạo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.training_unit') }}">{{ trans('backend.training_unit') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.training_unit.result') }}">{{ trans('backend.training_result') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $profile->lastname. ' ' . $profile->firstname }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12 text-right">
                <a class="btn btn-info" href="{{ route('module.training_unit.result.user.export_result', ['user_id' => $profile->user_id]) }}">
                    <i class="fa fa-download"></i> Export
                </a>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-12">
                @foreach($training_programs as $training_program)
                    <table class="tDefault table table-hover">
                        <thead>
                            <tr>
                                <th rowspan="2">{{ $training_program->name }}</th>
                                <th colspan="5">{{ trans('backend.course') }}</th>
                                <th colspan="5">Đánh giá kỹ năng</th>
                                <th colspan="5">Thi kiến thức</th>
                                <th rowspan="2">Kết quả chung</th>
                            </tr>
                            <tr>
                                <th>Loại</th>
                                <th>{{trans('backend.date_finish')}}</th>
                                <th>{{ trans('backend.score') }}</th>
                                <th>{{ trans('backend.teacher') }}</th>
                                <th>{{ trans('backend.result') }}</th>
                                <th>Loại</th>
                                <th>{{trans('backend.date_finish')}}</th>
                                <th>{{ trans('backend.score') }}</th>
                                <th>{{ trans('backend.teacher') }}</th>
                                <th>{{ trans('backend.result') }}</th>
                                <th>Loại</th>
                                <th>{{trans('backend.date_finish')}}</th>
                                <th>{{ trans('backend.score') }}</th>
                                <th>{{ trans('backend.teacher') }}</th>
                                <th>{{ trans('backend.result') }}</th>
                            </tr>
                        </thead>
                        <tBody>
                            @php
                                $subjectss = $subjects($training_program->training_program_id);
                            @endphp
                            @foreach($subjectss as $subject)
                                @php
                                    $result = $results($profile->user_id, $subject->subject_id);
                                @endphp
                                <tr>
                                    <td>{{ $subject->name }}</td>
                                    @for($i = 1; $i <= 3; $i++)
                                        @php
                                            $teachers = $result ? $teacher($result->{'teacher_'.$i}) : '';
                                            $param = $parameter($i);
                                        @endphp
                                        <td> {{ $result ? $result->{'type_'.$i} == 1 ? 'Offline' : 'Online' : '' }}</td>
                                        <td> {{ $result ? get_date($result->{'date_complete_'.$i}) : '' }} </td>
                                        <td> {{ $result ? $result->{'score_'.$i} : '' }} </td>
                                        <td> {{ $result ? $teachers ? $teachers->name : '' : '' }} </td>
                                        <td> {{ $result ? $result->{'score_'.$i} >= $param->score ? '{{trans("backend.finish")}}' : '{{trans("backend.not_completed")}}' : '' }} </td>
                                    @endfor
                                    <td> {{ $result ? $result->result == 1 ? '{{trans("backend.finish")}}' : '{{trans("backend.not_completed")}}' : '' }}</td>
                                </tr>
                            @endforeach
                        </tBody>
                    </table>
                @endforeach
            </div>
        </div>
    </div>
@endsection
