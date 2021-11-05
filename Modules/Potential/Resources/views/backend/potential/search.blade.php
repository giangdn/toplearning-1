@extends('layouts.backend')

@section('page_title', 'Tìm kiếm nhân sự tiềm năng')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.potential.index') }}">Nhân sự tiềm năng</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Tìm kiếm nhân sự tiềm năng</span>
        </h2>
    </div>
@endsection
@section('content')
    <div role="main">
            <form class="form" id="form-search">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <select id="cert" class="form-control select2" multiple data-placeholder="-- Trình độ --">
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
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="" class="col-md-4"> Thâm niên trong nghề </label>
                            <div class="col-md-8">
                                <span><input name="from_year" type="text" class="form-control d-inline-block w-25 is-number" placeholder="Nhập năm" autocomplete="off" value=""></span>
                                <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                <span><input name="to_year" type="text" class="form-control d-inline-block w-25 is-number" placeholder="Nhập năm" autocomplete="off" value=""></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 text-right act-btns">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                        <a class="btn btn-info" href="javascript:void(0)" id="export-excel">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label for="" class="col-md-3">{{trans('backend.ratio')}}</label>
                            <div class="col-md-9">
                                <span><input name="from_percent" type="text" class="form-control d-inline-block w-25 is-number" placeholder="Nhập %" autocomplete="off" value=""></span>
                                <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                <span><input name="to_percent" type="text" class="form-control d-inline-block w-25 is-number" placeholder="Nhập %" autocomplete="off" value=""></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
{{--                        <div class="form-group row">--}}
{{--                            <label for="" class="col-md-4">Thâm niên trong KLB</label>--}}
{{--                            <div class="col-md-8">--}}
{{--                                <span><input name="start_date" type="text" class="form-control d-inline-block w-25 is-number" placeholder="Nhập năm" autocomplete="off" value=""></span>--}}
{{--                                <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>--}}
{{--                                <span><input name="end_date" type="text" class="form-control d-inline-block w-25 is-number" placeholder="Nhập năm" autocomplete="off" value=""></span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </form>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="2%">#</th>
                    <th data-field="code">{{trans('backend.employee_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="parent_name">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="ratio" data-align="center">Tỷ lệ đánh giá</th>
                    <th data-field="group_percent" data-align="center">Nhóm</th>
                    <th data-field="certificate_name" data-align="center">Trình độ</th>
{{--                    <th data-field="join_company" data-align="center" data-width="5%">Thâm niên KLB (Năm)</th>--}}
                    <th data-field="expbank" data-align="center" data-width="5%">Thâm niên trong nghề (Năm)</th>
                    <th data-field="d1" data-align="center">Đợt 1</th>
                    <th data-field="d2" data-align="center">Đợt 2</th>
                    <th data-field="d3" data-align="center">Đợt 3</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }
        function name_formatter(value, row, index) {
            return row.lastname + ' ' + row.firstname;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.potential.getdata.search') }}',
        });

        $("#export-excel").on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.potential.export_search') }}?'+form_search;
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
