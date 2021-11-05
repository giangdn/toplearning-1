@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.subjectcomplete.index') }}">{{$page_title}}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{trans('subjectcomplete::subjectcomplete.approved_subject')}}</span>
        </h2>
    </div>
@endsection

@section('content')
<div role="main">
    <form method="post" action=" " class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                @can('movetrainingprocess-approved')
                    <div class="btn-group act-btns">
                        <button class="btn btn-success approve" data-status="1"><i class="fa fa-check-circle"></i> {{trans('backend.approve')}}</button>
                        <button class="btn btn-danger approve" data-status="0"><i class="fa fa-exclamation-circle"></i> {{trans('backend.deny')}}</button>
                    </div>
                @endcan
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="subject_code" data-width="5%">Mã chuyên đề</th>
                <th data-field="subject_name"  data-width="20%">Tên chuyên đề</th>
                <th data-field="full_name" data-formatter="full_name_formatter" >Nhân viên</th>
                <th data-field="titles_name"  >Chức danh</th>
                <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                <th data-field="parent_unit_name">{{ trans('backend.unit_manager') }}</th>
                <th data-field="note" >Ghi chú</th>
                <th data-field="status" data-with="5%">Trạng thái</th>
            </tr>
            </thead>
        </table>
    </form>
</div>
<script type="text/javascript">
    function full_name_formatter(value, row,index) {
        return row.full_name+' (<b>'+row.code+'</b>)';
    }
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.subjectcomplete.approve.getData') }}',
    });
</script>
<script src="{{ asset('styles/module/subjectcomplete/js/subjectcomplete.js?v=1.2') }}"></script>
@stop
