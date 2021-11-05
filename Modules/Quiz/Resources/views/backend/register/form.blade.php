@extends('layouts.backend')

@section('page_title', 'Thêm mới')

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
        <a href="{{ route('module.quiz.register', ['id' => $quiz_id]) }}">{{ trans('backend.internal_contestant') }}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{trans('backend.add_new')}}</span>
    </h2>
</div>
<div role="main">
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
                    <div class="btn-group">
                        <button type="submit" id="button-register" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.register') }}</button>
                        <a href="{{ route('module.quiz.register', ['id' => $quiz_id]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="code" data-width="10%">{{trans('backend.employee_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="parent_name">{{ trans('backend.unit_manager') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-part" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{trans('backend.choose_exams')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <select name="part" id="part" class="form-control select2" data-placeholder="-- {{trans('backend.exams')}} --">
                                    <option value=""></option>
                                    @foreach ($quiz_part as $part)
                                        <option value="{{ $part->id }}" >{{ $part->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        <button type="button" class="btn btn-primary" id="button-part">{{ trans('backend.choose') }}</button>
                    </div>
                </div>
            </div>
        </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return row.lastname +' ' + row.firstname;
        }
        var ajax_get_user = "{{ route('module.quiz.register.save', ['id' => $quiz_id]) }}";

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.register.getDataNotRegister', ['id' => $quiz_id]) }}',
            field_id: 'user_id'
        });
    </script>
    <script type="text/javascript" src="{{ asset('styles/module/quiz/js/register.js') }}"></script>

@stop
