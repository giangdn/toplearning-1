@extends('layouts.backend')

@section('page_title', 'Duyệt bài đăng')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.forum.category') }}">{{ trans('backend.forum') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.forum', ['cate_id' => $cate->id]) }}">{{$cate->name}}</a>
            <i class="uil uil-angle-right"></i>
            <span> {{ trans('backend.approve') }}: {{ $forum->name }}</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.search_name_forum')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('forum-approve-post')
                    <div class="btn-group">
                        <!-- @can('forum_thread-create')
                            <a href="{{ route('module.forum.thread.create', ['cate_id' => $cate->id,'forum_id' => $forum->id]) }}" class="btn btn-primary">
                                <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                            </a>
                        @endcan -->
                        @can('forum-approve-post')
                            <button class="btn btn-success publish" ><i class="fa fa-check-square"></i>&nbsp;{{ trans('backend.approve') }}</button>
                        @endcan
                        @can('forum_thread-remove')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                    @endcan
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="2%">#</th>
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="title" data-formatter="title_formatter" >{{ trans('backend.title') }}</th>
                    <th data-field="created_at2" data-align="center" data-width="20%">{{ trans('backend.created_at') }}</th>
                    <th data-field="status" data-align="center" data-width="10%" data-formatter="status_formatter">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">

        function title_formatter(value, row, index) {
            return '<a href="'+ row.edit_thread +'">'+ row.title +'</a>';
        }

        function index_formatter(value, row, index) {
            return (index+1);
        }
        function status_formatter(value, row, index)
        {
            return value == 1 ? '<span class="text-success">{{ trans("backend.approve") }}</span>' : '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.forum.getdatathread', ['cate_id' => $cate->id, 'forum_id' => $forum->id]) }}',
            remove_url: '{{ route('module.forum.thread.remove',['cate_id' => $cate->id, 'forum_id' => $forum->id]) }}'
        });

        var ajax_save_status = "{{ route('module.forum.save_status', ['cate_id' => $cate->id, 'forum_id' => $forum->id]) }}";
    </script>
<script type="text/javascript" src="{{ asset('styles/module/forum/js/forum.js') }}"></script>
@endsection
