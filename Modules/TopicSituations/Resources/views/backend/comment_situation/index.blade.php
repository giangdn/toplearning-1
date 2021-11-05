@extends('layouts.backend')

@section('page_title', trans('backend.comment_situation'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{route('module.topic_situations')}}">{{$model->name}}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{route('module.situations',['id' => $model->id])}}">{{$situation->name}}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{ trans('backend.comment_situation') }}</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-12 form-inline">
                <form class="form-inline form-search-user w-100 mb-3" id="form-search">
                    <div class="w-25">
                        <input type="text" name="search" value="" class="form-control w-100" placeholder="Nhập Tên/Mã">
                    </div>
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="unit" class="form-control load-unit" data-placeholder="-- {{ trans('backend.unit') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="fullname" data-width="20%">Tên người bình luận</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="title_name" data-width="15%">Chức danh</th>
                    <th data-field="comment" data-width="50%">Bình luận</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.get.comment.situations',['id' => $topic_id, 'situation' => $situation->id]) }}',
        });

    </script>
@endsection
