@extends('layouts.app')

@section('page_title', 'Ghi chú')

@section('content')
    <div class="container-fluid note_container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="ibox-content suggest-container">
                    <h2 class="st_title"><i class="uil uil-apps"></i><span class="font-weight-bold">Ghi chú</span></h2>
                    <br>
                    <div class="row">
                        <div class="col-12 col-xl-12 col-lg-12 col-md-12 text-right act-btns">
                            <div class="pull-right">
                                <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <table class="tDefault table table-hover bootstrap-table">
                        <thead>
                            <tr>
                                <th data-field="check" data-checkbox="true"></th>
                                <th data-field="date_time" data-width="20%">Ngày tạo</th>
                                <th data-field="content" >Ghi chú</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function action_formatter(value, row, index) {
            return '<i class="fa fa-eye"></i>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('frontend.get_data.note') }}',
            remove_url: '{{ route('frontend.remove.note') }}'
        });
    </script>
@endsection
