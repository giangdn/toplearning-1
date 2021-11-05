@extends('layouts.backend')

@section('page_title', 'Kết quả đánh giá')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ $type == 1 ? route('module.online.management') : route('module.offline.management') }}">{{ trans("backend.course") }} {{ $type == 1 ? 'offline' : 'tập trung' }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ $type == 1 ? route('module.online.edit', ['id' => $course_id]) : route('module.offline.edit', ['id' => $course_id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans("backend.result_of_evaluation") }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            @if($course->id)
                <div class="col-md-12 text-center">
                    <a href="{{ $type == 1 ? route('module.online.edit', ['id' => $course->id]) : route('module.offline.edit', ['id' => $course->id]) }}" class="btn btn-info">
                        <div><i class="fa fa-edit"></i></div>
                        <div>{{ trans("backend.info") }}</div>
                    </a>
                    @if ($type == 1)
                        <a href="{{ route('module.online.register', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>Ghi danh nội bộ</div>
                        </a>
                        <a href="{{ route('module.online.register_secondary', ['id' => $course->id]) }}" class="btn
                        btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>Ghi danh bên ngoài</div>
                        </a>
                        <a href="{{ route('module.online.result', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-briefcase"></i></div>
                            <div>{{ trans("backend.training_result") }}</div>
                        </a>
                    @endif
                    @if($type == 2)
                        <a href="{{ route('module.offline.register', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-edit"></i></div>
                            <div>{{ trans("backend.register") }}</div>
                        </a>
                        <a href="{{ route('module.offline.teacher', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-inbox"></i></div>
                            <div>{{ trans("backend.teacher") }}</div>
                        </a>
                        <a href="{{ route('module.offline.monitoring_staff', ['id' => $course->id]) }}"
                           class="btn btn-info">
                            <div><i class="fa fa-user"></i></div>
                            <div>Cán bộ theo dõi</div>
                        </a>
                        <a href="{{ route('module.offline.attendance', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-user"></i></div>
                            <div>{{ trans("backend.attendance") }}</div>
                        </a>
                        <a href="{{ route('module.offline.result', ['id' => $course->id]) }}" class="btn btn-info">
                            <div><i class="fa fa-briefcase"></i></div>
                            <div>{{ trans("backend.training_result") }}</div>
                        </a>
                    @endif
                </div>
            @endif
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 ">
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
                            <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                        </div>
                        <div class="w-25">
                            <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
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
            </div>
            <div class="col-md-12 text-right act-btns pr-0">
                <form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
                    @csrf
                    <input type="hidden" name="report" value="BC09">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="course" value="{{ $course->id }}">

                    <button id="btnExport" class="btn btn-primary" name="btnExport">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> {{ trans("backend.report") }}
                    </button>
                </form>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-width="5px" data-field="code" data-formatter="code_formatter">{{ trans("backend.employee_code") }}</th>
                    <th data-width="25%" data-field="profile_name" data-formatter="name_formatter">{{ trans("backend.fullname") }}</th>
                    <th data-field="user_type" data-formatter="user_type_formatter" data-width="10%">Loại nhân viên</th>
                    <th data-field="email" data-formatter="email_formatter">{{ trans("backend.employee_email") }}</th>
                    <th data-field="title_name">{{ trans("backend.title") }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="parent">{{ trans('backend.unit_manager') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function code_formatter(value, row, index) {
            return row.user_type == 1 ? row.code : row.user_secon_code;
        }

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ (row.user_type == 1 ? row.profile_name : row.secondary_name) +'</a>';
        }

        function email_formatter(value, row, index) {
            return row.user_type == 1 ? row.email : row.user_secon_email;
        }

        function user_type_formatter(value, row, index) {
            return value == 1 ? '{{trans("backend.internal")}}' : '{{trans("backend.outside")}}';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating.result.getdata', ['course_id' => $course_id, 'type' => $type]) }}',
        });

    </script>
    <script src="{{asset('styles/module/report/js/bc09.js')}}" type="text/javascript"></script>
@endsection
