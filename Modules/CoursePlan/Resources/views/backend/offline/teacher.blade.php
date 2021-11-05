@extends('layouts.backend')

@section('page_title', 'Quản lý giảng viên')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.course_plan.management') }}">Kế hoạch đào tạo tháng</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.course_plan.edit', ['course_type' => $course_type, 'id' => $course->id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Quản lý giảng viên</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">
        <div class="row">
            @if($course->id)
                <div class="col-md-12 text-center">
                    <a href="{{ route('module.course_plan.edit', ['course_type' => $course_type, 'id' => $course->id]) }}" class="btn btn-info">
                        <div><i class="fa fa-edit"></i></div>
                        <div>{{ trans('backend.info') }}</div>
                    </a>
                </div>
            @endif
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_teacher_name') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('course-plan-add-teacher')
                            <a href="javascript:void(0)" id="import-teacher" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('course-plan-delete-teacher')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="teacher_name">{{ trans('backend.fullname') }}</th>
                    <th data-field="teacher_email" >Email</th>
                    <th data-field="teacher_phone">{{ trans('backend.phone') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.course_plan.save_teacher', ['course_type' => $course_type, 'id' => $course->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $course->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> {{ trans('backend.teacher') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('backend.teacher') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select name="teacher_id" id="teacher_id" class="form-control select2" data-placeholder="{{ trans('backend.choose_teacher') }}" required>
                                    <option value=""></option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}"> {{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{trans('backend.save')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.course_plan.get_teacher', ['course_type' => $course_type, 'id' => $course->id]) }}',
            remove_url: '{{ route('module.course_plan.remove_teacher', ['course_type' => $course_type, 'id' => $course->id]) }}'
        });

        $('#import-teacher').on('click', function() {
            $('#modal-import').modal();
        });

    </script>
@endsection
