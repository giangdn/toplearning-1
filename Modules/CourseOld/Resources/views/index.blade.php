{{-- @extends('layouts.backend')

@section('page_title', trans('backend.course_old'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            Tổ chức đào tạo
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.course_old') }}</span>
        </h2>
    </div>
@endsection

@section('content') --}}
    <div role="main">
{{--         @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
            @php
                session()->forget('errors');
            @endphp
        @endif--}}
        @if(isset($notifications))
            @foreach($notifications as $notification)
                @if(@$notification->data['messages'])
                    @foreach($notification->data['messages'] as $message)
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}: {!! $message !!}</div>
                    @endforeach
                @else
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}</div>
                @endif
                @php
                $notification->markAsRead();
                @endphp
            @endforeach
        @endif

        <div class="row">
            <div class="col-md-12 form-inline">
                <form class="form-inline" id="form-search">
                    <input type="text" name="search_user" value="" class="form-control" placeholder="{{ trans('backend.enter_code_name') }} {{ trans('backend.user')}}">
                    <input type="text" name="search_unit" value="" class="form-control" placeholder="{{ trans('backend.filter_unit') }} ">
                    <input type="text" name="search_course" value="" class="form-control" placeholder="{{ trans('backend.enter_code_name') }} {{trans('backend.course')}}">
                    <input name="start_date" type="text" class="datepicker form-control" placeholder="{{ trans('backend.start_date') }}" autocomplete="off">
                    <input name="end_date" type="text" class="datepicker form-control" placeholder="{{ trans('backend.end_date') }}" autocomplete="off">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns mt-2">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ download_template('mau_import_khoa_hoc_cu.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        <button class="btn btn-info" id="import-plan" type="button">
                            <i class="fa fa-upload"></i> Import
                        </button>
                        <a class="btn btn-info" href="javascript:void(0)" id="export-course-old"><i class="fa fa-download"></i> Export</a>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-danger" id="delete-item" ><i class="fa fa-trash"></i> Xoá</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="user_code" >{{ trans('backend.employee_code') }}</th>
                    <th data-sortable="true" data-field="full_name" data-formatter="fullName_formatter">{{ trans('backend.fullname') }}</th>
                    <th data-sortable="true" data-field="unit" >{{ trans('backend.direct_units') }}</th>
                    <th data-sortable="true" data-field="title">{{ trans('backend.title') }}</th>
                    <th data-sortable="true" data-field="course_code">{{ trans('backend.course_code') }}</th>
                    <th data-sortable="true" data-field="course_name">{{ trans('backend.course_name') }}</th>
                    <th data-sortable="true" data-field="start_date">{{ trans('backend.start_date') }}</th>
                    <th data-sortable="true" data-field="end_date">{{ trans('backend.end_date') }}</th>
                    <th data-sortable="true" data-field="course_type" data-formatter="course_type_formatter">{{ trans('backend.type_course') }}</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('module.courseold.import') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.course_old') }}</h5>
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

    </div>

    <script type="text/javascript">
        function fullName_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="text-success view-detail" data-id="'+row.id+'">'+ value +'</a>';
        }
        function course_type_formatter(value, row, index) {
            return value==1?'Online':'Tập trung';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.courseold') }}',
            remove_url: '{{ route('module.courseold.remove') }}',

        });

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        $('#export-course-old').on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{route('module.courseold.export')}}?'+form_search;
        })

        $(document).on('click','.view-detail', function() {
            let id = $(this).data('id');
            let btn = $(this);
            let text = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i>').prop("disabled", true);
            $.ajax({
                url: base_url+'/admin-cp/courseold/show/'+id,
                type: 'get',
                data: {},
                dataType:'html'
            }).done(function(result) {
                $("#app-modal").html(result);
                $("#app-modal #modal-detail").modal();
                btn.html(text).prop("disabled", false);
            }).fail(function(result) {
                show_message('Lỗi hệ thống', 'error');
                btn.html(text).prop("disabled", false);
                return false;
            });
        });
    </script>

{{-- @endsection --}}
