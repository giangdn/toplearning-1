@extends('layouts.backend')

@section('page_title', 'Thí sinh nội bộ')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.quiz') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.quiz.manager') }}">{{ trans('backend.quiz_list') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.quiz.edit', ['id' => $quiz_id]) }}">{{ $quiz_name->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{ trans('backend.internal_contestant') }}</span>
        </h2>
    </div>
    <div role="main">
    @if(isset($errors))

    @foreach($errors as $error)
        <div class="alert alert-danger">{!! $error !!}</div>
    @endforeach

    @endif
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
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-25">
                        <select name="part" class="form-control select2" data-placeholder="-- {{trans('backend.exams')}} --">
                            <option value=""></option>
                            @foreach ($quiz_part as $part)
                                <option value="{{ $part->id }}" >{{ $part->name }}</option>
                            @endforeach
                        </select>
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
                        <input type="text" name="search" class="form-control w-100" placeholder="{{ trans('backend.enter_code_name__email_username_employee') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <button type="button" class="btn btn-success" id="send-mail-user-registed"><i class="fa fa-send"></i> Gửi mail báo đã ghi danh</button>

                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ download_template('mau_import_nhan_vien_ghi_danh_ky_thi.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                        <a class="btn btn-info" href="{{ route('module.quiz.register.export_register', ['id' => $quiz_id]) }}">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div>

                    <div class="btn-group">
                        <a href="{{ ($quiz_name->unit == 1) ? route('module.training_unit.quiz.register.create', ['id' => $quiz_id]) : route('module.quiz.register.create', ['id' => $quiz_id]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code">{{trans('backend.employee_code')}}</th>
                    <th data-field="name" data-width="20%" data-formatter="name_formatter">{{ trans('backend.employee_name') }}</th>
                    <th data-field="email">{{ trans('backend.employee_email') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-width="15%" data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-width="15%" data-field="parent_name">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="part_name" data-align="center">{{trans('backend.exams')}}</th>
                    <th data-field="part_date" data-align="center" data-formatter="part_date_formatter" data-width="30%">{{trans('backend.time')}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.quiz.register.import_register', ['id' => $quiz_id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $quiz_name->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT {{trans("backend.user")}}</h5>
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

        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }

        function part_date_formatter(value, row, index) {
            return row.part_start_date + ' => ' + row.part_end_date;
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.register.getdata', ['id' => $quiz_id]) }}',
            remove_url: '{{ route('module.quiz.register.remove', ['id' => $quiz_id]) }}'
        });

        $('#send-mail-user-registed').on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên!', 'error');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('module.quiz.register.send_mail_user_registed', ['id'=>$quiz_id, 'type'=>1]) }}',
                dataType: 'json',
                data: {
                    'ids': ids,
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                table.refresh();
                return false;
            }).fail(function(data) {
                return false;
            });
        })
    </script>
@endsection
