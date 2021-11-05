@extends('layouts.backend')

@section('page_title', 'Khóa học')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.potential.index') }}">Nhân sự tiềm năng</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Khóa học của {{ $profile->lastname . ' ' .$profile->firstname }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-md-3 text-right">
                <a class="btn btn-info" href="{{ route('module.potential.export_course', ['user_id' => $profile->user_id]) }}">
                    <i class="fa fa-download"></i> Export
                </a>
            </div>
        </div>
        <p></p>
        <table class="tDefault table tab-content border">
            <thead class="border">
                <tr>
                    <th>STT</th>
                    <th>Mã học phần</th>
                    <th>Tên học phần</th>
                    <th>{{trans('backend.start_date')}}</th>
                    <th>{{trans('backend.end_date')}}</th>
                    <th>{{ trans('backend.test_score') }}</th>
                    <th>{{ trans('backend.result') }}</th>
                </tr>
            </thead>
            @foreach($subject as $key => $item)
                @php
                    $courses = $course($item->training_program_id, $item->subject_id, $item->training_form, $profile->user_id);
                    if (!is_null($courses)){
                        $result = $result_course($profile->user_id, $courses->id, $courses->course_type);
                    }
                @endphp
                <tr class="text-center">
                    <th>{{ $key + 1 }}</th>
                    <th>{{ $item->code }}</th>
                    <th>{{ $item->name }}</th>
                    <th>{{ $courses ? get_date($courses->start_date) : '' }}</th>
                    <th>{{ $courses ? get_date($courses->end_date) : '' }}</th>
                    <th>{{ isset($result) ? $result->reexamine ? $result->reexamine : $result->grade : '' }}</th>
                    <th>{{ isset($result) ? ($result->result == 1 ? '{{trans("backend.finish")}}' : 'Chưa hoàn thành') : 'Chưa hoàn thành' }}</th>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
