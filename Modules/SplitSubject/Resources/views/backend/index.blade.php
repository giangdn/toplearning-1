{{-- @extends('layouts.backend')

@section('page_title', trans('backend.split_subject'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">{{trans('backend.split_subject')}}</span>
        </h2>
    </div>
@endsection

@section('content') --}}

    <div role="main">
        @if(isset($errors))

            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
            {{session()->forget('errors')}}
        @endif
        <div class="row">
            <div class="col-md-6">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="{{trans('backend.merged_subject_search')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{trans('backend.search')}}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group" >
                        <button id="importSplitSubject" type="button" class="btn btn-secondary " >
                            <i class="fa fa-upload">  </i> Import
                        </button>
                        <a class="btn btn-secondary" href="{{ download_template('mau_import_tach_chuyen_de.xlsx') }}"><i class="fa fa-download"> Download file mẫu </i></a>
                    </div>
                    <div class="btn-group">
                        @can('splitsubject-approved')
                            <button class="btn btn-success approve" data-status="1"><i class="fa fa-check-circle"></i> {{trans('backend.approve')}}</button>
                            <button class="btn btn-danger approve" data-status="0"><i class="fa fa-exclamation-circle"></i> {{trans('backend.deny')}}</button>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('splitsubject-create')
                        <a href="{{ route('module.splitsubject.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{trans('backend.add_new')}}</a>
                        @endcan
                        @can('splitsubject-delete')
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
                        @endcan
                    </div>
                    @can('split-subject-watch-log')
                        <a class="btn btn-success" href="{{route('module.splitsubject.logs')}}"><i class="fa fa-check-circle"></i> {{trans('backend.view_logs')}}</a>
                    @endcan
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="name_subject_old" data-formatter="name_subject_old_formatter">{{trans('backend.split_subject_origin')}}</th>
                    <th data-sortable="true" data-field="name_subject_new" data-formatter="name_formatter">{{trans('backend.merge_subject_new')}}</th>
                    <th data-width="8%" data-field="number_merge_subject" data-align="center">{{trans('backend.split_subject_number')}}</th>
                    <th data-width="8%" data-field="number_merge_completed" data-align="center">{{trans('backend.splited_subject_number')}}</th>
                    <th data-width="8%" data-field="status" data-align="center">{{trans('backend.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.splitsubject.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('splitsubject::splitsubject.import_split_subject') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="unit_id" value=" ">
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
            return '<a href="'+ row.edit_url +'">'+ row.name_subject_new+'</a>';
        }
        function name_subject_old_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name_subject_old+'</a>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.splitsubject.getData') }}',
            remove_url: '{{ route('module.splitsubject.remove') }}'
        });
        $('#importSplitSubject').on('click', function() {
            $('#modal-import').modal();
        });
    </script>
    <script src="{{ asset('styles/module/splitsubject/js/splitsubject.js?v=1.2') }}"></script>
{{-- @endsection --}}
