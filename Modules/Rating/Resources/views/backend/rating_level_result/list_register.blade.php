@extends('layouts.backend')

@section('page_title', 'Kết quả đánh giá')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ $course_type == 1 ? route('module.online.management') : route('module.offline.management') }}">{{ trans("backend.course") }} {{ $course_type == 1 ? 'online' : 'tập trung' }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ $course_type == 1 ? route('module.online.edit', ['id' => $course_id]) : route('module.offline.edit', ['id' => $course_id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Kết quả đánh giá cấp độ</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            @if($course->id)
                <div class="col-md-12 text-center">
                    <a href="{{ $course_type == 1 ? route('module.online.edit', ['id' => $course->id]) : route('module.offline.edit', ['id' => $course->id]) }}" class="btn btn-info">
                        <div><i class="fa fa-edit"></i></div>
                        <div>{{ trans("backend.info") }}</div>
                    </a>
                    @if ($course_type == 1)
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

                        {{--<a href="{{ route('module.rating.result.index', [$course->id, 1]) }}" class="btn btn-info">
                            <div><i class="fa fa-star"></i></div>
                            <div>{{ trans('backend.result_of_evaluation') }}</div>
                        </a>--}}

                        @can('online-course-rating-level-result')
                            <a href="{{ route('module.rating_level.list_report', [$course->id, 1]) }}" class="btn btn-info">
                                <div><i class="fa fa-star"></i></div>
                                <div>Báo cáo đánh giá</div>
                            </a>
                        @endcan
                    @endif
                    @if($course_type == 2)
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
                        {{--<a href="{{ route('module.rating.result.index', [$course->id, 2]) }}" class="btn btn-info">
                            <div><i class="fa fa-star"></i></div>
                            <div>{{ trans('backend.result_of_evaluation') }}</div>
                        </a>--}}

                        @can('offline-course-rating-level-result')
                            <a href="{{ route('module.rating_level.list_report', [$course->id, 2]) }}" class="btn btn-info">
                                <div><i class="fa fa-star"></i></div>
                                <div>Báo cáo đánh giá</div>
                            </a>
                        @endcan
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
                        <input type="text" name="search" class="form-control w-100" placeholder="{{ data_locale('Nhập mã / tên nhân viên', 'Enter the staff name / code') }}">
                    </div>

                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#templateRatingLevelCourse">
                    Báo cáo
                </button>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="code">{{ trans("backend.employee_code") }}</th>
                    <th data-field="full_name">{{ trans("backend.fullname") }}</th>
                    <th data-field="level1" data-width="10%" data-align="center">Cấp độ 1</th>
                    <th data-field="level2" data-width="10%" data-align="center">Cấp độ 2</th>
                    <th data-field="level3" data-width="10%" data-align="center">Cấp độ 3</th>
                    <th data-field="level4" data-width="10%" data-align="center">Cấp độ 4</th>
                    <th data-field="result" data-width="10%"  data-align="center" data-formatter="result_formatter">Kết quả</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="templateRatingLevelCourse" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mẫu đánh giá cấp độ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(isset($template_rating_level_course))
                        @foreach($template_rating_level_course as $template)
                            <p><a href="{{ route('module.rating_level.report', [$course->id, $course_type, $template->id]) }}">{{ $template->rating_name }}</a></p>
                        @endforeach
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function result_formatter(value, row, index) {
            return '<a href="'+ row.result +'" class="btn btn-info"> <i class="fa fa-eye"></i> Xem </a>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating_level.result.getdata_course_register', [$course_id, $course_type]) }}',
        });
    </script>
@endsection
