@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.permission.list_permisstion') }}">Danh sách quyền</a> / {{ $page_title }}
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline" id="form-search">
                    <input type="text" name="search" class="form-control" placeholder='{{trans("backend.enter_code_name_user")}}'>

                    <div class="d-25">
                    <select name="user" class="form-control select2" data-placeholder="-- Người dùng --">
                        <option value=""></option>
                        @foreach($user_added as $user)
                            <option value="{{ $user->user_id }}">{{ $user->lastname .' '. $user->firstname }}</option>
                        @endforeach
                    </select>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="{{ route('backend.permission.detail.create', ['permission_id' => $parent->id]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-times-circle"></i> Xóa</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-width="5%">Mã người dùng</th>
                    <th data-field="name" data-width="20%" data-formatter="name_formatter">Tên người dùng</th>
                    <th data-field="title" data-width="15%">{{ trans('backend.title') }}</th>
                    <th data-field="unit" data-width="15%">{{ trans('backend.unit') }}</th>
                    <th data-field="permission" data-width="25%">Quyền</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.lastname +' '+ row.firstname +'</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: "{{ route('backend.permission.detail.getdata', ['permission_id' => $parent->id]) }}",
            remove_url: "{{ route('backend.permission.detail.remove', ['permission_id' => $parent->id]) }}",
        });
    </script>
@stop
