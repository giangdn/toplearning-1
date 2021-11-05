{{-- @extends('layouts.backend')

@section('page_title', trans('movetrainingprocess::language.move_training_process'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">{{trans('movetrainingprocess::language.move_training_process')}}</span>
        </h2>
    </div>
@endsection

@section('content') --}}
    <div role="main">
        <form class="form-horizontal" id="frm_move_training_process">
        <div class=" ">
            <div class="form-group required row">
                <div class="col-sm-3 control-label text-right">
                    <label for="oldEmployeeCode">Mã nhân viên cần chuyển</label> <span style="color:red"> * </span>
                </div>
                <div class="col-sm-5">
                    <input type="text" name="oldEmployeeCode" required id="oldEmployeeCode" placeholder="Mã nhân viên cần chuyển" class="form-control">
                </div>
            </div>
            <div class="form-group required row">
                <div class="col-sm-3 control-label text-right">
                    <label for="newEmployeeCode">Chuyển qua mã nhân viên</label> <span style="color:red"> * </span>
                </div>
                <div class="col-sm-5">
                    <input type="text" name="newEmployeeCode" required id="newEmployeeCode" placeholder="Chuyển qua mã nhân viên" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="newEmployeeCode"></label>
                </div>
                <div class="col-sm-5">
                    @can('movetrainingprocess-move')
                        <button class="btn btn-primary" data-url="{{ route('module.movetrainingprocess.modal') }}" type="submit" id="modal-move-trainingprocess">
                            <i class="fa fa-share" aria-hidden="true"></i> Chuyển
                        </button>   
                    @endcan
                </div>
            </div>
        </div>
        </form>
        <div class="row">
            <div class="col-md-6">
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
{{--                    <div class="btn-group">--}}
{{--                        <a class="btn btn-info" href="{{ download_template('mau_import_hoan_thanh_qua_trinh_dao_tao.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>--}}
{{--                        <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">--}}
{{--                            <i class="fa fa-upload"></i> Import--}}
{{--                        </button>--}}
{{--                    </div>--}}
                    <div class="btn-group">
                        @can('movetrainingprocess-approved')
                            <button class="btn btn-success approve" data-status="1" href="{{route('module.movetrainingprocess.approved')}}"><i class="fa fa-check-circle"></i> {{trans('backend.approve')}}</button>
                            <button class="btn btn-danger approve" data-status="0" href="{{route('module.movetrainingprocess.approved')}}"><i class="fa fa-check-circle"></i> {{trans('backend.deny')}}</button>
                        @endcan
                    </div>
                    @can('movetrainingprocess-delete')
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
                    @endcan
                    @can('movetrainingprocess-watch-log')
                        <a class="btn btn-success" href="{{route('module.movetrainingprocess.logs')}}"><i class="fa fa-check-circle"></i> {{trans('backend.view_logs')}}</a>
                    @endcan
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-sortable="true" data-align="center" data-formatter="stt_formatter" data-width="50">STT</th>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="created_by" data-width="180px">{{ trans('backend.user_create') }}</th>
                    <th data-field="created_date" data-width="140px">{{ trans('backend.created_at') }}</th>
                    <th data-field="employee_old"   data-width="200px">{{ trans('movetrainingprocess::language.employee_old') }}</th>
                    <th data-field="employee_new"  data-width="200px">{{ trans('movetrainingprocess::language.employee_new') }}</th>
                    <th data-field="detail" data-align="center" data-formatter="detail">{{ trans('movetrainingprocess::language.move_training_process_detail') }}</th>
                    <th data-field="approved_by">{{ trans('backend.approved_by') }}</th>
                    <th data-field="approved_at">{{ trans('backend.approved_date') }}</th>
                    <th data-field="status"  data-with="100px">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.subjectcomplete.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('subjectcomplete::subjectcomplete.import_subject_complete') }}</h5>
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
        $('body').on('click', '#modal-move-trainingprocess', function () {

            let item = $(this);
            let url = $(this).data('url');
            let icon = item.find('i').attr('class');
            let employee_code_old = $('#oldEmployeeCode').val();
            let employee_code_new = $('#newEmployeeCode').val();
            if(employee_code_old.trim()=='' || employee_code_new.trim()==''){
                show_message('{{trans("movetrainingprocess::language.invalid_field_empty")}}','error');
                return false;
            }
            if(employee_code_old.trim()==employee_code_new.trim()){
                show_message('{{trans("movetrainingprocess::language.message_not_match_employee_code")}}','error');
                return false;
            }
            item.find('i').attr('class', 'fa fa-spinner fa-spin');
            item.prop("disabled", true);
            item.addClass('disabled');

            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'html',
                data: {employee_code_old,employee_code_new},
            }).done(function(data) {
                item.find('i').attr('class', icon);
                item.prop("disabled", false);
                item.removeClass('disabled');
                $("#app-modal").html(data);
                $("#app-modal #myModal").modal();
            }).fail(function(data) {
                item.find('i').attr('class', icon);
                item.prop("disabled", false);
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        });
        function stt_formatter(value, row, index) {
            return (index + 1);
        }
        function detail(value, row, index) {
            return '<a href=""><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.movetrainingprocess.getData') }}',
            remove_url: '{{ route('module.movetrainingprocess.remove') }}'
        });
    </script>
    <script src="{{ asset('styles/module/movetrainingprocess/js/movetrainingprocess.js?v=1') }}"></script>

{{-- @endsection --}}
