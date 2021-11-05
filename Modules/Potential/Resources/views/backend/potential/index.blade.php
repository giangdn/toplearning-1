@extends('layouts.backend')

@section('page_title', 'Nhân sự tiềm năng')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i><span class="font-weight-bold">{{trans('backend.potential')}}</span></h2>
    </div>
@endsection
@section('content')
    <div role="main">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif
        <form class="form" id="form-search">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <select id="cert" class="form-control select2" multiple data-placeholder="-- {{trans('backend.level')}} --">
                                @if(isset($cert))
                                    @foreach($cert as $item)
                                        <option value="{{ $item->id }}"> {{ $item->certificate_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <input type="hidden" name="count-cert" value="{{ $cert ? count($cert) : 0 }}">
                            <input type="hidden" name="cert" class="form-control cert" value="">
                        </div>
                        <div class="col-md-4">
                            <input type="checkbox" id="checkbox" >{{trans("backend.select_all")}}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="" class="col-md-4"> {{trans('backend.experience')}} </label>
                        <div class="col-md-8">
                            <span><input name="from_year" type="text" class="form-control d-inline-block w-25 is-number" placeholder="Năm" autocomplete="off" value=""></span>
                            <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                            <span><input name="to_year" type="text" class="form-control d-inline-block w-25 is-number" placeholder="Năm" autocomplete="off" value=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="" class="col-md-3">{{trans('backend.ratio')}}</label>
                        <div class="col-md-9">
                            <span><input name="from_percent" type="text" class="form-control d-inline-block w-25 is-number" placeholder="%" autocomplete="off" value=""></span>
                            <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                            <span><input name="to_percent" type="text" class="form-control d-inline-block w-25 is-number" placeholder="%" autocomplete="off" value=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-right act-btns">
                    <div class="btn-group">
                        @can('potential-export')
                            <a href="{{ route('module.potential.search') }}" class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm nhân sự tiềm năng</a>
                        @endcan
                    </div>
                </div>
            </div>
{{--            <div class="row">--}}
{{--                <div class="col-md-6">--}}
{{--                    <div class="form-group row">--}}
{{--                        <label for="" class="col-md-4">Thâm niên trong KLB</label>--}}
{{--                        <div class="col-md-8">--}}
{{--                            <span><input name="start_date" type="text" class="form-control d-inline-block w-25 is-number" placeholder="Nhập năm" autocomplete="off" value=""></span>--}}
{{--                            <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>--}}
{{--                            <span><input name="end_date" type="text" class="form-control d-inline-block w-25 is-number" placeholder="Nhập năm" autocomplete="off" value=""></span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-3 text-right act-btns">--}}
{{--                   --}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col">
                            <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_name')}}">
                        </div>
                        <div class="col">
                            <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                        </div>
                        <div class="col">
                            <select name="unit" class="form-control load-unit" data-placeholder="-- {{ trans('backend.unit') }} --"></select>
                        </div>
                        <div class="col">
                            <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                    @can('potential-create')
                        <div class="btn-group">
                            <a class="btn btn-info" href="{{ download_template('mau_import_nhan_su_tiem_nang.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                            <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> Import
                            </button>
                            <a class="btn btn-info" href="javascript:void(0)" id="export-excel">
                                <i class="fa fa-download"></i> Export
                            </a>
                        </div>
                        <a  href="{{ route('module.potential.create') }}" class="btn btn-primary" >
                            <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                        </a>
                    @endcan
                    @can('potential-delete')
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    @endcan
                </div>
            </div>
        </form>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="2%">#</th>
                <th data-field="code" data-width="5%">{{trans('backend.employee_code')}}</th>
                <th data-field="name" data-formatter="name_formatter" data-width="10%">{{ trans('backend.employee_name') }}</th>
                <th data-field="title_name">{{ trans('backend.title') }}</th>
                <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                <th data-field="parent_name">{{ trans('backend.unit_manager') }}</th>
                <th data-field="ratio" data-align="center">{{trans('backend.ratio')}} <br> {{ trans('backend.assessments') }} (%)</th>
                <th data-field="group_percent" data-align="center">{{trans("backend.group")}}</th>
                <th data-field="certificate_name" data-align="center">{{trans('backend.level')}}</th>
{{--                <th data-field="join_company" data-align="center" data-width="5%">{{trans('backend.seniority')}} <br> KLB ({{trans('backend.year')}})</th>--}}
                <th data-field="expbank" data-align="center" data-width="5%">{{trans('backend.experience')}} ({{trans('backend.year')}})</th>
                <th data-field="d1" data-align="center">{{trans("backend.phase")}} 1</th>
                <th data-field="d2" data-align="center">{{trans("backend.phase")}} 2</th>
                <th data-field="d3" data-align="center">{{trans("backend.phase")}} 3</th>
                <th data-field="start_date" data-width="10%">{{trans('backend.start_date')}}</th>
                <th data-field="end_date" data-width="10%">{{trans('backend.end_date')}}</th>
                <th data-field="finish_potencial" data-formatter="finish_potencial" data-align="center" data-width="3%">{{trans("backend.finish")}} <br> {{trans('backend.potential_class')}}</th>
                <th data-field="course" data-width="10%" data-formatter="course_formatter" data-align="center">{{ trans('backend.course') }}</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.potential.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT {{trans('backend.potential')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
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

        function index_formatter(value, row, index) {
            return (index+1);
        }

        function name_formatter(value, row, index) {
            return '<a href="'+row.edit_url+'">' + row.lastname + ' ' + row.firstname + '</a>';
        }

        function finish_potencial(value, row, index) {
            return '<input type="checkbox" disabled '+ ((row.check == 1) ? checked : ' ' )+ ' >';
        }

        function course_formatter(value, row, index) {
            return  '<a href="'+ row.course +'" class="btn btn-info"><i class="fa fa-eye"></i></a> <a href="'+ row.export +'" ' +
                'class="btn btn-primary"><i class="fa fa-download"></i></a>';
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        $("#export-excel").on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.potential.export') }}?'+form_search;
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.potential.getdata') }}',
        });

        var count_cert = $("input[name=count-cert]").val();

        $('#cert').on('change', function () {
            var cert = $("#cert option:selected").map(function(){return $(this).val();}).get();
            $('.cert').val(cert);

            if (cert.length == parseInt(count_cert)) {
                $("#checkbox").prop('checked', true)
            }else {
                $("#checkbox").prop('checked', false)
            }

        });

        $("#checkbox").click(function(){
            if($("#checkbox").is(':checked') ){
                $("#cert > option").prop("selected","selected");
                $("#cert").trigger("change");

                var cert = $("#cert option:selected").map(function(){return $(this).val();}).get();
                $('.cert').val(cert);
            }else{
                $("#cert > option").prop("selected", "");
                $("#cert").trigger("change");
                $('.cert').val('');
                table.refresh();
            }
        });

    </script>
@endsection
