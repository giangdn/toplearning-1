@extends('layouts.backend')

@section('page_title', 'Khung năng lực theo chức danh')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a> <i class="uil uil-angle-right"></i> {{trans('backend.framework_title')}}
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">
        @if(isset($errors))

        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach

        @endif
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder='{{trans("backend.enter_capabilities_title_name")}}'>
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="capabilities" class="form-control select2" data-placeholder="-- {{trans('backend.capabilities')}} --">
                        <option value=""></option>
                        @foreach($capabilities as $capability)
                            <option value="{{$capability->id}}">{{ $capability->name }}</option>
                        @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('category-capabilities-title-create')
                    <a class="btn btn-info" href="{{ download_template('mau_import_khung_nang_luc_theo_chuc_danh.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                    <div class="btn-group">
                        <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                        <a class="btn btn-info" href="javascript:void(0)" id="export-excel">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div>
                    @endcan
                    <p></p>
                    <div class="btn-group">
                        @can('category-capabilities-title-create')
                            <button class="btn btn-warning" id="copy">
                                <i class="fa fa-copy"></i> &nbsp;{{trans('backend.copy')}}
                            </button>
                            <a href="{{ route('module.capabilities.title.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('category-capabilities-title-delete')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="number_title" data-width="1%" data-align="center">STT</th>
                    <th data-field="capabilites_code" data-width="10%" data-align="center">{{trans('backend.frame_capacity_code')}}</th>
                    <th data-field="capabilities_name" data-formatter="name_formatter">{{trans('backend.capability_name')}}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="category_name" data-width="15%">{{trans('backend.capacity_category_group')}}</th>
                    <th data-field="weight" data-formatter="weight_formatter" data-width="5%" data-align="center">{{trans('backend.weight')}}</th>
                    <th data-field="critical_level" data-width="5%" data-align="center">{{trans('backend.critical_level')}}</th>
                    <th data-field="level" data-width="5%" data-align="center">{{trans('backend.levels')}}</th>
                    <th data-field="goal" data-width="5%" data-align="center">{{trans('backend.benchmarks')}}</th>
                    <th data-field="course" data-formatter="course_formatter" data-align="center" data-width="5%">{{ trans('backend.all_course') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.capabilities.title.import_capabilities_title') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT {{trans('backend.capacity_frame_category')}}</h5>
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

    <div class="modal fade" id="modal-copy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">{{trans('backend.copy_capacity')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select name="title_id" id="title_id" class="form-control select2" data-placeholder="{{ trans('backend.choose_title') }}">
                        <option value=""></option>
                        @if ($titles)
                            @foreach ($titles as $title)
                                <option value="{{$title->id}}">{{$title->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                    <button type="submit" class="btn btn-primary" id="btn-save-copy">{{trans('backend.save')}}</button>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.capabilities_name +'</a>';
        }

        function course_formatter(value, row, index) {
            return '<a href="'+ row.course_url +'">' + '<i class="fa fa-address-book"></i>' +'</a>';
        }

        function weight_formatter(value, row, index) {
            return value +' %';
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        $('#copy').on('click', function() {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 năng lực', 'error');
                return false;
            }else {
                $('#modal-copy').modal();

                $('#btn-save-copy').on('click', function () {
                    var title_id = $('#title_id option:selected').val();

                    $.ajax({
                        url: '{{ route('module.capabilities.title.copy') }}',
                        type: 'post',
                        data: {
                            ids: ids,
                            title_id: title_id,
                        }
                    }).done(function(data) {
                        $('#modal-copy').hide();
                        show_message(data.message, data.status);
                        window.location = '';
                    }).fail(function(data) {
                        show_message('Lỗi hệ thống', 'error');
                        return false;
                    });
                });
            }
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.capabilities.title.getdata') }}',
            remove_url: '{{ route('module.capabilities.title.remove') }}'
        });

        $("#export-excel").on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.capabilities.title.export_capabilities_title') }}?'+form_search;
        });
    </script>
@endsection
