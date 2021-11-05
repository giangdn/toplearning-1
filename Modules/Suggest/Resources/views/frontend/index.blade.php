@extends('layouts.app')

@section('page_title', 'Góp ý')

@section('content')
    <div class="container-fluid suggest_container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="ibox-content suggest-container">
                    <h2 class="st_title"><i class="uil uil-apps"></i><span class="font-weight-bold">{{ trans('app.suggest') }}</span></h2>
                    <br>
                    <div class="row search-course pb-2 m-0">
                        <div class="col-12 form-inline p-0">
                            <form class="mb-2 form-inline" id="form-search">
                                <input type="text" name="search" value="" class="form-control search_text mr-1" placeholder="Nhập Tên góp ý">
                                <input name="date_from" type="text" class="datepicker form-control search_start_date mr-1" placeholder="{{trans('backend.start_date')}}" autocomplete="off">
                                <input name="date_to" type="text" class="datepicker form-control search_end_date mr-1" placeholder="{{trans('backend.end_date')}}" autocomplete="off">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-xl-12 col-lg-12 col-md-12 text-right act-btns">
                            <div class="pull-right">
                                {{-- <div class="btn-group">
                                    <button class="btn btn-info" id="create" type="submit" name="task" value="import">
                                        <i class="fa fa-edit"></i> {{ trans('app.create_suggest') }}
                                    </button>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <br>
                    <table class="tDefault table table-hover bootstrap-table" id="table-suggest">
                        <thead>
                        <tr>
                            <th data-field="name">{{ trans('app.suggest') }}</th>
                            <th class="text-center" data-field="created_at2">{{ trans('app.date_created') }}</th>
                            <th class="text-center" data-field="comment_url" data-formatter="comment_url">{{ trans('app.comment') }}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form action="{{ route('module.suggest.save') }}" method="post" class="form-ajax">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('app.add_suggest') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-3 label">
                                <label> {{ trans('app.name_suggest') }}</label>
                            </div>
                            <div class="col-md-9">
                                <input class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 label">
                                <label> {{ trans('app.content') }}</label>
                            </div>
                            <div class="col-md-9">
                                <textarea class="form-control" name="content" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('app.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('app.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">

        function comment_url(value, row, index) {
            return  '<a href="'+ row.modal_comment +'"><i class="uil uil-comment"></i></a>';
        }

        $('#create').on('click', function() {
            $('#modal-create').modal();
        });

        var table = new LoadBootstrapTable({
            url: '{{ route('module.suggest.get_data') }}',
            locale: '{{ data_locale('vi-VN', 'en-US') }}',
        });
    </script>
@endsection
