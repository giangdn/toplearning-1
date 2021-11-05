@extends('layouts.backend')

@section('page_title', 'Chi tiết kế hoạch đào tạo tháng')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.training_plan') }}">{{trans('backend.training_plan')}}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{trans('backend.detail_training_program')}}</span>
        </h2>
    </div>
    <div role="main">
        @if(isset($errors))

        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach

        @endif

        <div class="row">
            <div class="col-md-12 form-inline">
                <form class="form-inline form-search-user mb-3  w-100" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name')}}">
                    <div class="w-25">
                        <select name="training_program_id" id="training_program_id" class="form-control load-training-program" data-placeholder="-- Chương trình đào tạo --"></select>
                    </div>
                    <div class="w-25">
                        <select name="level_subject_id" id="level_subject_id" class="form-control load-level-subject" data-training-program="" data-placeholder="-- {{trans('backend.levels')}} --"></select>
                    </div>
                    <div class="w-20">
                        <select name="course_type" id="course_type" class="form-control select2" data-placeholder="-- {{trans('backend.training_program_form')}} --">
                            <option value=""></option>
                            <option value="1"> {{ trans('backend.onlines') }}</option>
                            <option value="2"> {{ trans('backend.offline') }}</option>
                            <option value="3"> {{ trans('backend.self_learning') }}</option>
                        </select>
                    </div>
                    <div class="w-20">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ route('module.training_plan.detail.export_plan', ['id' => $plan_id]) }}"><i class="fa fa-download"></i> Export</a>
                    </div>
                    @can('training-plan-detail-create')
                        <div class="btn-group">
                            <a class="btn btn-info" href="{{ route('module.training_plan.detail.export_template', ['id' => $plan_id]) }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                            <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> Import
                            </button>
                        </div>
                    @endcan
                    <div class="btn-group">
                        @can('training-plan-detail-create')
                        <a href="{{ route('module.training_plan.detail.create', ['id' => $plan_id]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('training-plan-detail-delete')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="table_detail_plan">
            <thead>
                <tr class="tbl-heading">
                    <th rowspan="2" data-field="state" data-checkbox="true"></th>
                    <th rowspan="2" data-field="code">{{ trans('lacourse.course_code') }}</th>
                    <th rowspan="2" data-field="subject_name" data-formatter="name_formatter">Khóa học</th>
                    <th rowspan="2" data-field="program_name">Chương trình đào tạo</th>
                    <th rowspan="2" data-field="course_type">{{ trans('backend.form') }} <br> {{ trans('backend.training') }}</th>
                    <th rowspan="2" data-field="training_form">{{ trans('backend.training_form') }}</th>
                    <th rowspan="2" data-field="exis_training_CBNV">Nhu cầu đào tạo hiện hữu</th>
                    <th rowspan="2" data-field="recruit_training_CBNV">Nhu cầu đào tạo tân tuyển</th>
                    <th rowspan="2" data-field="total_course">Tổng số lớp</th>
                    <th rowspan="2" data-field="periods">Thời lượng <br> đào tạo/Lớp</th>
                    <th colspan="4" >Kế hoạch tổ chức đào tạo <br> (ĐVT: Lớp)</th>
                    <th colspan="{{ $count_type_costs }}">Chi phí đào tạo <br> (ĐVT: VNĐ)</th>
                    <th rowspan="2" data-field="total_type_cost">Tổng chi phí đào tạo</th>
                </tr>
                <tr class="tbl-heading">
                    <th data-field="quarter1">Quý 1</th>
                    <th data-field="quarter2">Quý 2</th>
                    <th data-field="quarter3">Quý 3</th>
                    <th data-field="quarter4">Quý 4</th>
                    @foreach ($type_costs as $key => $type_cost)
                        <th data-field="type_cost_{{ $type_cost->id }}">{{ $type_cost->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.training_plan.detail.import_plan', ['id' => $plan_id]) }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT KẾ HOẠCH ĐÀO TẠO</h5>
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
        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.subject_name+'</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_plan.detail.getdata', ['id' => $plan_id]) }}',
            remove_url: '{{ route('module.training_plan.detail.remove', ['id' => $plan_id]) }}'
        });
    </script>
@endsection
