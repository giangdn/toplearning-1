@extends('layouts.backend')

@section('page_title', trans('lacore.languages'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.setting') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('lacore.languages') }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-6">
                <form class="form-inline" id="form-search">
                    <input type="text" name="search" class="form-control" value="" placeholder="{{ trans('lacore.type_keyword') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('lacore.search') }}</button>
                    <span><a href="javascript:void(0)" class="btn btn-primary load-modal" data-url="{{ route('backend.languages.get_modal') }}"><i class="fa fa-plus-circle"></i> Tạo nhóm</a></span>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('user-create')
                            <div class="btn-group">
                                <a href="{{ route('backend.languages.synchronize') }}" class="btn btn-info"><i class="fa fa-upload"></i> {{ trans('lacore.synchronized') }}</a>
                            </div>
                        @endcan

						 @can('user-create')
                            <div class="btn-group">
                                <a href="{{ route('backend.languages.export_file') }}" class="btn btn-info"> {{ trans('lacore.export') }}</a>
                            </div>
                        @endcan

                        @can('user-create')
                            <div class="btn-group">
                                <a href="{{ route('backend.languages.export') }}" class="btn btn-info"> {{ trans('lacore.export_excel') }}</a>
                            </div>
                        @endcan

                        @can('user-create')
                            <div class="btn-group">
                                <a href="{{ download_template('mau_import_languages.xlsx') }}" class="btn btn-info"><i class="fa fa-download"></i> {{ trans('lacore.import_template') }}</a>
                            </div>
                        @endcan
                        @can('user-create')
                            <div class="btn-group">
                                <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal-import"><i class="fa fa-upload"></i> {{ trans('lacore.import') }}</a>
                            </div>
                        @endcan

                        @can('feedback-create')
                            <a href="{{ route('backend.languages.create', $id) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('lacore.addnew') }}</a>
                        @endcan
                            @if(\App\Permission::isAdmin())
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('lacore.delete') }}</button>
                             @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-2">
        @foreach($groups as $v)
            <a class="btn{{ $v["id"]==$id?' actived':'' }}" href="{{ route('backend.languages.group', $v["id"]) }}">{{$v["name"]}}</a>
        @endforeach
        </div>

        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-formatter="name_formatter" data-width="360px">{{ trans('lacore.keyword') }}</th>
                    <th data-field="content">{{ trans('lacore.vietnamese_content') }}</th>
                    <th data-field="group_name">{{ trans('lacore.group') }}</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ route('backend.languages.import') }}" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Import</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.pkey +' </a>';
        }
        var table = new LoadBootstrapTable({
            url: '{{ route('backend.languages.getdata', $id) }}',
            remove_url: '{{ route('backend.languages.remove') }}'
        });

        $('#model-list-import').on('click', function () {
            $('#modal-import').modal();
        });

        $('#import-user').on('click', function () {
            $('#modal-import').hide();
            $('#modal-import-user').modal();
        });

        $('.close').on('click', function () {
           window.location = '';
        });
    </script>

@endsection
