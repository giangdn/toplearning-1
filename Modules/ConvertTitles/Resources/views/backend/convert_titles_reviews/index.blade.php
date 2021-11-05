@extends('layouts.backend')

@section('page_title', 'Mẫu đánh giá chuyển đổi chức danh')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.convert_titles') }}">{{trans('backend.convert_titles')}}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.evaluation_form') }}</span>
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
            <div class="col-md-8">
                <form class="form-inline" id="form-search">
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('convert-titles-review-create')
                        <a  href="{{ route('module.convert_titles.reviews.create') }}" class="btn btn-primary" >
                            <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                        </a>
                        @endcan
                        @can('convert-titles-review-delete')
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
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="title_name" data-formatter="title_name_formatter">{{ trans('backend.title') }}</th>
                    <th data-field="file_reviews" >{{ trans('backend.evaluation_form') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">

        function title_name_formatter(value, row, index) {
            return  '<a href="'+ row.edit_url +'"> '+ row.title_name +'</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.convert_titles.reviews.getdata') }}',
            remove_url: '{{ route('module.convert_titles.reviews.remove') }}'
        });
    </script>
@endsection
