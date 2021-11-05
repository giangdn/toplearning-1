@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.subjectcomplete.index') }}">{{$page_title}}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{ $profile->full_name }}</span>
    </h2>
</div>
<div role="main">
    <form method="post" action=" " class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['subjectcomplete-choose-subject'])
                        <button type="submit" class="btn btn-primary load-modal" data-url="{{ route('module.subjectcomplete.user.get_modal',['user_id'=>$user_id]) }}" data-must-checked="false"><i class="fa fa-check"></i> &nbsp;{{ trans('backend.choose_subject') }}</button>
                    @endcanany
                    <a href="{{ route('module.subjectcomplete.index') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.back') }}</a>
                </div>
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
                <th data-field="titles_name" >Chức danh</th>
                <th data-field="unit_name" >Đơn vị</th>
                <th data-field="course_type" >Loại hình đào tạo</th>
                <th data-field="process_type" >Hình thức</th>
                <th data-field="start_date" >Từ ngày</th>
                <th data-field="end_date">Đến ngày</th>
                <th data-field="result" data-with="5%">Kết quả</th>
                <th data-field="status" data-with="5%">Trạng thái</th>
            </tr>
            </thead>
        </table>
    </form>
</div>
<script type="text/javascript">

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.subjectcomplete.user.getData',['user_id'=>$user_id]) }}',
    });
</script>
@stop
