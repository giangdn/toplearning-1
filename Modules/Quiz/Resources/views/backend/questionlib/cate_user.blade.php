@extends('layouts.backend')

@section('page_title', 'Phân quyền')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('lamanager.quiz_manager') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.quiz.questionlib') }}">{{ trans('backend.questionlib') }}</a> <i class="uil uil-angle-right"></i>
        <span>{{ trans('backend.permission') }}: {{ $category->name }}</span>
    </h2>
</div>
<div role="main">
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                <form action="{{ route('module.quiz.questionlib.save_cate_user', ['id' => $category->id]) }}" method="post" class="form-ajax" data-success="add_unit_success">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row" id="object-unit">
                                <label class="col-sm-3 control-label">{{ trans('backend.unit') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
{{--                                    <select name="unit_id[]" id="unit_id" class="form-control load-unit select2"--}}
{{--                                            data-placeholder="-- {{ trans('backend.choose_unit') }} --" multiple>--}}
{{--                                        <option value=""></option>--}}
{{--                                        @foreach ($users as $user)--}}
{{--                                            <option value="{{$user->user_id}}">{{ $user->lastname . ' ' . $user->firstname }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                    <select name="parent_id" id="unit-1" class="form-control load-unit" data-placeholder="-- Chi nhánh - khối --" data-level="1" data-loadchild="unit-2"></select>--}}
                                    <select name="unit_id[]" multiple {{-- id="unit-2"--}} class="form-control load-unit" data-placeholder="-- {{ trans('backend.unit') }} --" {{--data-level="2"--}}></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-5">
                            <div class="btn-group act-btns">
                                <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-plus-circle"></i> &nbsp;{{ trans('backend.add_new') }} {{ trans('backend.unit') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <br>
                </form>
                <p></p>
                <div class="row">
                    <div class="col-md-10"></div>
                    <div class="col-md-2 text-right">
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
                <p></p>
                <div class="row">

                    <div class="col-md-12">
                        <table class="tDefault table table-hover bootstrap-table">
                            <thead>
                                <tr>
                                    <th data-field="state" data-checkbox="true"></th>
                                    <th data-field="unit_code" data-align="center">{{ trans('backend.unit_code') }}</th>
                                    <th data-field="unit_name" data-align="center">{{ trans('backend.unit') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.quiz.questionlib.get_cate_user', ['id' => $category->id]) }}',
        remove_url: '{{ route('module.quiz.questionlib.remove_cate_user', ['id' => $category->id]) }}'
    });

    function add_unit_success(form) {
        $("#object-unit select[name=unit_id\\[\\]]").val(null).trigger('change');
        $("#object-unit select[name=parent_id]").val(null).trigger('change');
        table.refresh();
    }

</script>
@endsection
