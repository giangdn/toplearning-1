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
        <a href="{{ route('module.quiz.register.user_secondary', ['id' => $quiz_id]) }}">{{ trans('backend.user_secondary') }}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{trans('backend.add_new')}}</span>
    </h2>
</div>
<div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name_block')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="submit" id="button-register-secondary" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.register') }}</button>
                        <a href="{{ route('module.quiz.register.user_secondary', ['id' => $quiz_id]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="code">{{trans('backend.employee_outside_code')}}</th>
                    <th data-field="name">{{ trans('backend.employee_outside_name') }}</th>
                    <th data-field="dob" data-align="center">{{ trans('backend.dob') }}</th>
                    <th data-field="identity_card" data-align="center">{{ trans('backend.identity_card') }}</th>
                    <th data-field="email" data-align="center">Email</th>
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
                                <select name="part" id="part-secondary" class="form-control select2" data-placeholder="-- {{trans('backend.exams')}} --">
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
                        <button type="button" class="btn btn-primary" id="button-part-secondary">{{ trans('backend.choose') }}</button>
                    </div>
                </div>
            </div>
        </div>
    <script type="text/javascript">

        var ajax_get_user_secondary = "{{ route('module.quiz.register.user_secondary.save', ['id' => $quiz_id]) }}";

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.register.user_secondary.getDataNotUserSecondary', ['id' => $quiz_id]) }}',
            field_id: 'id'
        });
    </script>
    <script type="text/javascript" src="{{ asset('styles/module/quiz/js/register.js') }}"></script>

@stop
