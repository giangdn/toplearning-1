@extends('layouts.backend')

@section('page_title', 'Quản lý giảng viên')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.offline.management') }}">{{ trans('backend.offline_course') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.offline.edit', ['id' => $course->id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <span>Quản lý giảng viên</span>
        </h2>
    </div>
    <div role="main" id="teacher_offline">
        <div class="row">
            @if($course->id)
                <div class="col-md-12 text-center">
                    @canany(['offline-course-create', 'offline-course-edit'])
                        <a href="{{ route('module.offline.edit', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>{{ trans('backend.info') }}</div>
                        </a>
                    @endcanany
                    @canany(['offline-course-register'])
                        <a href="{{ route('module.offline.register', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>{{ trans('backend.register') }}</div>
                        </a>
                    @endcanany
                        <a href="{{ route('module.offline.monitoring_staff', ['id' => $course->id]) }}"
                           class="btn btn-info">
                            <div><i class="fa fa-user"></i></div>
                            <div>Cán bộ theo dõi</div>
                        </a>
                    @canany(['offline-course-attendance'])
                        <a href="{{ route('module.offline.attendance', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-user"></i></div>
                            <div>{{ trans('backend.attendance') }}</div>
                        </a>
                    @endcanany
                    @canany(['offline-course-result'])
                        <a href="{{ route('module.offline.result', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-briefcase"></i></div>
                            <div>{{ trans('backend.training_result') }}</div>
                        </a>
                    @endcanany
                   {{-- @can('offline-course-rating-result')
                        <a href="{{ route('module.rating.result.index', ['course_id' => $course->id, 'type' => 2]) }}" class="btn btn-info">
                            <div><i class="fa fa-star"></i></div>
                            <div>{{ trans('backend.result_of_evaluation') }}</div>
                        </a>
                    @endcan--}}
                    @can('offline-course-rating-level-result')
                        <a href="{{ route('module.offline.rating_level.list_report', [$course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-star"></i></div>
                            <div>Kết quả đánh giá</div>
                        </a>
                    @endcan
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
                        @can('offline-course-teacher-create')
                            @if($course->lock_course == 0)
                            <a href="javascript:void(0)" id="import-teacher" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                            @endif
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
                    <th data-field="note" data-formatter="note_formatter">{{ trans('backend.note') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.offline.save_teacher', ['id' => $course->id]) }}" method="post" class="form-ajax">
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
                        @if($course->lock_course == 0)
                        <button type="submit" class="btn btn-primary">{{trans('backend.save')}}</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.get_teacher', ['id' => $course->id]) }}',
            remove_url: '{{ route('module.offline.remove_teacher', ['id' => $course->id]) }}'
        });

        function note_formatter(value, row, index) {
            return '<textarea type="text" name="note" data-id="'+ row.id +'" class="form-control change-note" {{ $course->lock_course == 0 ? '' : 'readonly' }}>'+ (row.note ? row.note : "") +'</textarea>';
        }

        $('#import-teacher').on('click', function() {
            $('#modal-import').modal();
        });

        $('#teacher_offline').on('change', '.change-note', function() {
            var note = $(this).val();
            var off_teacher_id = $(this).data('id');

            $.ajax({
                url: '{{ route('module.offline.teacher.save_note', ['id' => $course->id]) }}',
                type: 'post',
                data: {
                    note: note,
                    off_teacher_id : off_teacher_id,
                },

            }).done(function(data) {

                return false;

            }).fail(function(data) {

                show_message(
                    'Lỗi hệ thống',
                    'error'
                );
                return false;
            });
        });
    </script>
@endsection
