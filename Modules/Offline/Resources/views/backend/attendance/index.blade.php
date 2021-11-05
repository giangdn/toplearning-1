@extends('layouts.backend')

@section('page_title', 'Điểm danh')
@section('header')
    <script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
@endsection('header')
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
            <span>{{ trans('backend.attendance') }}</span>
        </h2>
    </div>
    <div role="main" id="attendance">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif

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
                        <a href="{{ route('module.offline.register', ['id' => $course->id]) }}"
                           class="btn btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>{{ trans('backend.register') }}</div>
                        </a>
                    @endcanany
                    @canany(['offline-course-teacher'])
                        <a href="{{ route('module.offline.teacher', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-inbox"></i></div>
                            <div>{{ trans('backend.teacher') }}</div>
                        </a>
                    @endcanany
                        <a href="{{ route('module.offline.monitoring_staff', ['id' => $course->id]) }}"
                           class="btn btn-info">
                            <div><i class="fa fa-user"></i></div>
                            <div>Cán bộ theo dõi</div>
                        </a>
                    @canany(['offline-course-result'])
                        <a href="{{ route('module.offline.result', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-briefcase"></i></div>
                            <div>{{ trans('backend.training_result') }}</div>
                        </a>
                    @endcanany
                    {{--@can('offline-course-rating-result')
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
        <div class="row pb-2">
            <div class="col-md-12">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}"
                                class="form-control load-unit"
                                data-placeholder="-- ĐV cấp {{$i}} --"
                                data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0">
                            </select>
                        </div>
                    @endfor

                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-25">
                        <select name="status" class="form-control select2" data-placeholder="-- {{ trans('backend.status') }} --">
                            <option value=""></option>
                            <option value="0">{{ trans('backend.inactivity') }}</option>
                            <option value="1">{{ trans('backend.doing') }}</option>
                            <option value="2">{{ trans('backend.probationary') }}</option>
                            <option value="3">{{ trans('backend.pause') }}</option>
                        </select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" class="form-control w-100" placeholder="{{ data_locale('Nhập mã / tên nhân viên', 'Enter the staff name / code') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <a class="btn btn-info" id="export_schedule" href="{{ route('module.offline.attendance.export',['id' => $course->id,'schedule' => $schedule ? $schedule : 0]) }}"><i class="fa fa-download"></i> Export</a>
                    <div class="btn-group">
                        {{-- <a class="btn btn-info" href="{{ download_template('mau_import_diem_danh_nhan_vien_khoa_hoc.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a> --}}
                        <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                    </div>
                    <div class="btn-group">
                        <select name="schedules_id" id="schedules_id" class="form-control select2" data-placeholder="-- {{ trans('backend.choose_session') }} --" {{ $course->lock_course == 0 ? '' : 'disabled' }}>
                            <option value=""></option>
                            @if(count($schedules) != 0)
                                @foreach($schedules as $key => $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $schedule ? "selected" : "" }}>
                                        Buổi {{ ($key + 1) .' ('. get_date($item->start_time, 'H:i') }} <i class="uil uil-angle-right"></i> {{ get_date($item->end_time, 'H:i') .' - '. get_date($item->lesson_date, 'd/m/Y') . ')' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
        @if ($schedule>0)
        <div class="row pb-2">
            <div class="col-md-12 pull-right text-right">
                <a href="javascript:void(0)" id="modal_qrcode">{{ trans('backend.get_attendanece_qr') }}</a>
            </div>
        </div>
        @endif
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="code">{{ trans('backend.employee_code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('backend.employee_name') }}</th>
                    <th data-field="email">{{ trans('backend.employee_email') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="parent">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="" data-formatter="type_formatter" data-align="center">
                        {{ trans('backend.join') }} <br> <input type="checkbox" name="checkAllType" class="check-all-type" {{ $course->lock_course == 0 ? '' : 'disabled' }}>
                    </th>
                    <th data-field="percent" data-formatter="percent_formatter">% <br> {{ trans('backend.join') }}</th>
                    <th data-field="note" data-formatter="note_formatter">{{ trans('backend.note') }}</th>
                    <th data-field="reference" data-align="center" data-formatter="reference_formatter">{{ trans('backend.permission_form') }}</th>
                    <th data-field="discipline" data-align="left">Vi phạm</th>
                    <th data-field="absent" data-align="left">Loại nghỉ</th>
                    <th data-field="absent_reason" data-align="left">Lý do vắng</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-qrcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Mã điểm danh QrCode</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            @if ($qrcode_attendance)
                                <div id="qrcode" >
                                {!! QrCode::size(300)->generate($qrcode_attendance); !!}
                                <p>Quét mã để điểm danh.</p>
                                </div>
                            @endif
                                <a href="javascript:void(0)" id="print_qrcode">In QR Code</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                    </div>
                </div>
        </div>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.offline.attendance.import', ['id' => $course->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $course->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.user') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        $('#print_qrcode').on("click", function () {
            $('#qrcode').printThis();
        });
        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }
        function percent_formatter(value, row, index) {
            return '<input type="text" name="percent" {{ $schedule ? '' : 'disabled' }} data-id="'+ row.id +'" ' +
                'class="form-control is-number change-percent" value="' + (row.percent ? row.percent : "") +'" '+ (row.checked == 1 ? '': 'disabled') +' >';
        }
        function note_formatter(value, row, index) {
            return '<textarea type="text" {{ $schedule ? '' : 'disabled'}} name="note" data-id="'+ row.id +'" ' +
                'class="form-control change-note">'+ (row.note ? row.note : "") +'</textarea>';
        }
        function reference_formatter(value, row, index) {
            return '<button type="button" {{ $schedule ? '' : 'disabled'}} class="import-reference btn btn-info" data-id="'+
                row.id +'" ><i class="fa fa-envelope-square" ></i></button> <button type="button" {{ $schedule ? '' :
                'disabled'}} class="btn"><a href="'+ row.download_reference +'" class="download {{ $schedule ? '' :
                'disabled'}}" ><i class="fa fa-download"></i></a></button>';
        }
        function type_formatter(value, row, index) {
            return '<input name="type" {{ $schedule ? '' : 'disabled' }} type="checkbox" class="check-item" value="'+ row.id
                +'" '+ (row.checked == 1 ? "checked": "") +' >';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.get_attendance', ['id' => $course->id]) }}?schedule={{ $schedule }}',
        });
        var ajax_save_all_register = "{{ route('module.offline.save_all_attendance', ['id' => $course->id]) }}";
        var ajax_save_register = "{{ route('module.offline.save_attendance', ['id' => $course->id]) }}";
        var ajax_save_percent = "{{ route('module.offline.save_percent', ['id' => $course->id]) }}?schedule={{ $schedule }}";
        var ajax_attendance_save_note = "{{ route('module.offline.attendance.save_note', ['id' => $course->id]) }}?schedule={{ $schedule }}";
        var ajax_get_reference = "{{ route('module.offline.modal_reference', ['id' => $course->id]) }}?schedule={{ $schedule }}";

        var ajax_save_absent = "{{ route('module.offline.save_absent', ['id' => $course->id]) }}?schedule={{ $schedule }}";
        var ajax_save_discipline = "{{ route('module.offline.save_discipline', ['id' => $course->id]) }}?schedule={{ $schedule }}";
        var ajax_save_absent_reason= "{{ route('module.offline.save_absent_reason', ['id' => $course->id]) }}?schedule={{ $schedule }}";

        function form_reference(form) {
            $("#app-modal #modal-reference").hide();
            window.location = '';
        }

        $('#modal_qrcode').on('click',function () {
            $("#modal-qrcode").modal();
        })

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });
    </script>
    <script src="{{ asset('styles/module/offline/js/attendance.js') }}"></script>
@endsection
