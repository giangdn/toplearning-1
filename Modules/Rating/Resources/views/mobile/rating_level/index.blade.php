@extends('themes.mobile.layouts.app')

@section('page_title', 'Đánh giá đào tạo')

@section('content')
    <div class="container">
        @if(count($list_rating_levels) > 0 && (\App\Profile::usertype() != 2))
            @foreach($list_rating_levels as $item)
                <div class="row bg-white mb-2">
                    <div class="col-12">
                        <h6 class="mt-1 font-weight-bold">{{ $item->rating_name }}</h6>
                    </div>
                    <div class="col-4 text-center pr-0">
                        <img alt="{{ $item->rating_name }}" class="lazy w-100" src="{{ asset('themes/mobile/img/evaluate_512.png') }}">
                    </div>
                    <div class="col-8 align-self-center">
                        <p style="font-size: 85%">
                            <b>@lang('app.time') : </b> <strong>{{ $item->rating_time }}</strong>
                            <br>
                            <b>@lang('app.status') : </b> <strong>{{ $item->rating_status }}</strong>
                            <br>
                            @if($item->rating_level_url)
                                <a href="{{ $item->rating_level_url }}" class="btn btn-info text-white"> Đánh giá</a>
                            @else
                                <a href="#" class="btn btn-info text-white">Không thể đánh giá</a>
                            @endif
                            <br>
                            @if($item->colleague)
                                <a href="javascript:void(0)" class="btn load-modal text-white" data-url="{{ $item->modal_object_colleague_url }}"> <i class="material-icons">person_add</i></a>
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row">
                <div class="col text-center">
                    <span>@lang('app.not_found')</span>
                </div>
            </div>
        @endif
        {{--<table class="tDefault table table-hover bootstrap-table" id="table-rating-level">
            <thead>
                <tr>
                    <th data-formatter="info_formatter"> @lang('app.info')
                    @if($is_manager)
                        <th data-field="colleague" data-formatter="add_colleague_formatter" data-align="center">Đồng nghiệp</th>
                    @endif
                </tr>
            </thead>
        </table>--}}
    </div>
@stop

@section('footer')
    <script type="text/javascript">
        /*function info_formatter(value, row, index) {
            var course_name = '-';
            var btn_rating_url = '<a href="#" class="btn btn-info text-white">Không thể đánh giá</a>';
            var rating_time = '-';
            if(row.course_name){
                course_name = '<a href="javascript:void(0)" title="'+ row.course_info +'">'+ row.course_name +'</a>';
            }
            if(row.rating_level_url){
                btn_rating_url = '<a href="'+ row.rating_level_url +'" class="btn btn-info text-white"> Đánh giá</a>';
            }
            if(row.rating_time){
                rating_time = row.rating_time;
            }

            return '<h6>'+ row.rating_name + '</h6>' +
                'Khóa: '+ course_name + '<br>' +
                'Thời gian: '+ rating_time + '<br>' +
                'Đối tượng: '+ row.object_rating + '<br>' +
                'Trạng thái: <b>'+ row.rating_status + '</b> <br>' +
                btn_rating_url;
        }

        function add_colleague_formatter(value, row, index) {
            if (row.colleague){
                return '<a href="javascript:void(0)" class="btn load-modal text-white" data-url="'+ row.modal_object_colleague_url +'"> <i class="material-icons">person_add</i></a>';
            }
            return '';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating_level.getdata') }}',
            table: '#table-rating-level',
        });*/

    </script>
@stop
