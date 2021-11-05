@extends('layouts.backend')

@section('page_title', 'Học phần')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{trans('backend.category')}}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.capabilities.title') }}">{{trans('backend.framework_title')}}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.capabilities.title.edit', ['id' => $model->id]) }}">{{ $title->name . ': ' . $capabi->name }}</a>
            <i class="uil uil-angle-right"></i>
            Học phần
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
        <div class="col-md-8"></div>
        <div class="col-md-4 text-right">
            <div class="btn-group">
            <a class="btn btn-info" href="{{ download_template('mau_import_hoc_phan_khung_nang_luc_theo_chuc_danh.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>

                <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                    <i class="fa fa-upload"></i> Import
                </button>
            </div>
        </div>
    </div>
    <br>
    <form id="form-subject" method="post" action="{{ route('module.capabilities.title.save_course', ['id' => $model->id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data" data-success="submit_success">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{trans('backend.training_program')}}<span style="color:red"> * </span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="training_program_id" id="training_program_id" class="form-control load-training-program" data-placeholder="-- {{trans('backend.training_program')}} --" data-loadsubject="subject_id" required>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="subject_id">Tên học phần</label><span style="color:red"> * </span>
                                </div>
                                <div class="col-md-6">
                                    <select name="subject_id" id="subject_id" class="form-control load-subject" data-placeholder="-- {{trans('backend.choose_subject')}} --" required>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="level_subject">{{trans('backend.levels')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="level_subject" id="level_subject" class="form-control select2" data-placeholder="-- {{trans('backend.choose_levels')}} --" required>
                                        <option value=""></option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-plus-circle"></i> &nbsp;{{ trans('backend.add_new') }} học phần</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <br>
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <form class="form-inline form-search-user mb-3" id="form-search">
                <div class="w-25">
                    <select name="training_program" class="form-control load-training-program" data-placeholder="-- {{trans('backend.training_program')}} --" data-loadsubject="subject"></select>
                </div>
                <div class="w-25">
                    <select name="subject" id="subject" class="form-control load-subject" data-placeholder="-- {{trans('backend.choose_subject')}} --"></select>
                </div>
                <div class="w-25">
                    <select name="level_subject" class="form-control select2" data-placeholder="-- {{trans('backend.choose_levels')}} --" required>
                        <option value=""></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="col-md-4  text-right">
            <div class="pull-right">
                <div class="btn-group">
                    <button id="delete-item" class="btn btn-danger"><i class="fa fa-trash"></i> Xóa học phần</button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <table class="tDefault table table-hover bootstrap-table">
        <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-align="center" data-width="3%" data-formatter="stt_formatter">STT</th>
                <th data-field="training_program_name">{{trans('backend.training_program')}}</th>
                <th data-field="subject_name" data-align="center">Tên học phần</th>
                <th data-sortable="true" data-field="level" data-align="center">{{trans('backend.levels')}}</th>
                <th data-field="action" data-align="center" data-width="5%" data-formatter="action_formatter">{{trans('backend.action')}}</th>
            </tr>
        </thead>
    </table>
</div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.capabilities.title.import_capabilities_title_subject', ['id' => $model->id]) }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT HỌC PHẦN KHUNG NĂNG LỰC THEO DANH MỤC</h5>
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
    function stt_formatter(value, row, index) {
        return (index + 1);
    }

    function action_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="btn btn-danger remove-item" data-id="'+ row.id +'"><i class="fa fa-times"></i></a>';
    }

    $('#import-plan').on('click', function() {
        $('#modal-import').modal();
    });

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.capabilities.title.get_course', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.capabilities.title.remove_course', ['id' => $model->id]) }}',
        sort_name: 'level',
        sort_order: 'asc'
    });
</script>
<script type="text/javascript">
    function submit_success(form) {
        $("#form-subject select[name=training_program_id]").val(null).trigger('change');
        $("#form-subject select[name=subject_id]").val(null).trigger('change');
        $("#form-subject select[name=level_subject]").val(null).trigger('change');
        $(table.table).bootstrapTable('refresh');
    }
</script>

@stop
