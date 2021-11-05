@extends('layouts.backend')

@section('page_title', trans('backend.capabilities'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.capabilities.review') }}">{{ trans('backend.capabilities') }}</a>
            <i class="uil uil-angle-right"></i>
            {{ trans('backend.assessments') . ': ' . $user->lastname .' '. $user->firstname }}
        </h2>
    </div>
@endsection

@section('content')

    <div role="main" id="capabilities-review">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Nhập tên đánh giá', 'Enter a review name') }}">
                    <button class="btn btn-primary"><i class="fa fa-search"></i> {{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group">
                    @can('capabilities-review-send')
                        <button class="btn btn-success" id="send" disabled><i class="fa fa-send"></i> {{ trans('backend.complete_evaluation') }}</button>
                    @endcan
                    @can('capabilities-review-create')
                        <a href="{{ route('module.capabilities.review.user.create', ['user_id' => $user->user_id]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                    @endcan
                    @can('capabilities-review-delete')
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    @endcan
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter" data-width="30%">{{ trans('backend.review_name') }}</th>
                    <th data-field="fullname" data-formatter="fullname_formatter" data-width="20%">{{ trans('backend.assessor') }}</th>
                    <th data-field="created_date">{{ trans('backend.created_at') }}</th>
                    <th data-field="updated_date">{{ trans('backend.last_updated') }}</th>
                    <th data-field="status" data-formatter="status_formatter" data-align="center">{{ trans('backend.status') }}</th>
                    <th data-field="export" data-formatter="export_formatter" data-align="center">Export</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return row.lastname +' '+ row.firstname;
        }

        function name_formatter(value, row, index) {
            // if (row.status == 0) {
            //     return '<a href="' + row.edit_url + '">'+ value +'</a>';
            // }
            // return '<a href="' + row.view_url + '">'+ value +'</a>';
            return '<a href="' + row.edit_url + '">'+ value +'</a>';
        }

        function status_formatter(value, row, index) {
            if (row.status == 1) {
                return '<span class="text-success">{{ trans('backend.sent') }}</span>';
            }
            return '<span class="text-danger">{{ trans('backend.unsent') }}</span>';
        }

        function export_formatter(value, row, index) {
            return '<a href="' + row.export_url + '"><i class="fa fa-download"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.capabilities.review.user.getdata', ['user_id' => $user->user_id]) }}',
            remove_url: '{{ route('module.capabilities.review.user.remove', ['user_id' => $user->user_id]) }}',
        });

        $("#capabilities-review").on('change', 'input[name=btSelectItem]:checked', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                $("#send").prop('disabled', true);
            }
            else {
                $("#send").prop('disabled', false);
            }
        });

        $("#send").on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                var mess = "{{ data_locale('Vui lòng chọn đánh giá bạn muốn gửi', 'Please select the review you want to submit') }}";
                show_message(mess, 'warning');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('module.capabilities.review.user.send', ['user_id' => $user->user_id]) }}',
                dataType: 'json',
                data: {
                    'ids': ids
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                if (data.status === "success") {
                    table.refresh();
                }
                return false;
            }).fail(function(data) {
                show_message("{{ trans('lageneral.data_error ') }}", 'error');
                return false;
            });
        });

    </script>

@endsection
