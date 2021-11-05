@extends('layouts.backend')

@section('page_title', trans('backend.training_action_teachers'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.training_action') }}">@lang('backend.training_action')</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.training_action.category') }}">{{ $training_action->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.training_action_teachers') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" class="form-control" placeholder="">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;@lang('backend.search')</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success approve-button" data-status="1"><i class="fa fa-check-circle"></i> @lang('backend.approve')</button>
                        <button type="button" class="btn btn-danger approve-button" data-status="0"><i class="fa fa-times-circle"></i> @lang('backend.deny')</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="code" data-width="10%">@lang('backend.code')</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">@lang('backend.name')</th>
                    <th data-sortable="true" data-field="status" data-formatter="status_formatter" data-width="10%" data-align="center">@lang('backend.status')</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ value +'</a>';
        }

        function status_formatter(value, row, index) {
            if (parseInt(value) === 0) {
                return '<span class="text-danger">@lang('backend.deny')</span>';
            }

            if (parseInt(value) === 1) {
                return '<span class="text-success">@lang('backend.approved')</span>';
            }

            return '<span class="text-warning">@lang('backend.not_approved')</span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_action.teachers.getdata', [$training_action]) }}',
            remove_url: '{{ route('module.training_action.teachers.remove', [$training_action]) }}',
        });

        $('.approve-button').on('click', function () {
            var btn = $(this);
            var icon = btn.find('i').attr('class');

            btn.find('i').attr('class', 'fa fa-spinner fa-spin');
            btn.prop("disabled", true);

            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            let status = $(this).data('status');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.training_action.teachers.approve', [$training_action]) }}',
                dataType: 'json',
                data: {
                    'ids': ids,
                    'status': status,
                }
            }).done(function(data) {

                btn.find('i').attr('class', icon);
                btn.prop("disabled", false);

                if (data.status === "error") {
                    show_message(data.message, 'error');
                    return false;
                }

                table.refresh();

                return false;
            }).fail(function(data) {
                btn.find('i').attr('class', icon);
                btn.prop("disabled", false);

                //show_message(langs.data_error, 'error');
                return false;
            });
        });
    </script>
@endsection
