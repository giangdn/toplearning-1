@extends('layouts.app')

@section('page_title', 'Đánh giá cấp độ')

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title"><i class="uil uil-apps"></i>
                                        <span class="font-weight-bold">Đánh giá hiệu quả đào tạo</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-search mb-2 row w-100" id="form-search">
                                    <div class="col-md-3 col-12 pr-0">
                                        <input type="text" name="search" class="form-control w-100 pr-0" placeholder="{{ trans('app.search') .' '. trans('app.course') }}" value="">
                                    </div>
                                    <div class="col-md-3 col-12 pr-0">
                                        <select name="status" class="form-control w-100">
                                            <option value="">Trạng thái đánh giá</option>
                                            <option value="0"> Chưa đánh giá</option>
                                            <option value="1"> Đã đánh giá</option>
                                            <option value="2"> Kết thúc</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input name="start_date" type="text" class="datepicker form-control search_start_date" placeholder="{{trans('backend.start_date')}}">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input name="end_date" type="text" class="datepicker form-control search_end_date" placeholder="{{trans('backend.end_date')}}">
                                    </div>
                                    <div class="col-md-1 col-12">
                                        <button class="btn btn-info btn-search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                        <br>
                        <div class="row" id="course">
                            <div class="col-md-12">
                                <table class="tDefault table table-hover bootstrap-table" id="table-rating-level">
                                    <thead>
                                    <tr>
                                        <th data-field="rating_url" data-formatter="rating_url_formatter" data-align="center">Đánh giá</th>
                                        <th data-field="rating_name">Tên đánh giá</th>
                                        <th data-field="course_name" data-formatter="course_name_formatter">Khoá học</th>
                                        <th data-field="rating_time">Thời gian đánh giá</th>
                                        <th data-field="object_rating">Đối tượng đánh giá</th>
                                        <th data-field="rating_status" data-align="center">Tình trạng</th>
                                        @if($is_manager)
                                            <th data-field="colleague" data-formatter="add_colleague_formatter" data-align="center">Thêm đồng nghiệp</th>
                                        @endif
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function rating_url_formatter(value, row, index) {
            if(row.rating_level_url){
                return '<a href="'+ row.rating_level_url +'" class="btn btn-info">Đánh giá</a>';
            }
            return 'Đánh giá';
        }

        function course_name_formatter(value, row, index) {
            if(row.course_name){
                return '<a href="javascript:void(0)" title="'+ row.course_info +'">'+ row.course_name +'</a>';
            }
            return '-';
        }

        function add_colleague_formatter(value, row, index) {
            if (row.colleague){
                return '<a href="javascript:void(0)" class="btn load-modal" data-url="'+ row.modal_object_colleague_url +'"> <i class="fa fa-user"></i> </a>';
            }
            return '';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating_level.getdata') }}',
            table: '#table-rating-level',
        });

    </script>
@stop
