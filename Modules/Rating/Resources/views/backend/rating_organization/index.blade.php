{{-- @extends('layouts.backend')

@section('page_title', 'Tổ chức đánh giá')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            Đánh giá hiệu quả đào tạo <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Tổ chức đánh giá</span>
        </h2>
    </div>
@endsection

@section('content') --}}

    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder='Nhập tên kỳ đánh giá'>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('rating-levels-create')
                    <div class="btn-group">
                        <button class="btn btn-primary publish" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                        </button>
                        <button class="btn btn-warning publish" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                        </button>
                    </div>
                    @endcan
                    <div class="btn-group">
                        @can('rating-levels-create')
                        <a href="{{ route('module.rating_organization.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('rating-levels-delete')
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
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter">Tên kỳ đánh giá</th>
                    <th data-field="course" data-align="center" data-formatter="course_formatter">Khóa học</th>
                    <th data-field="count_user" data-align="center">{{trans('backend.join')}} / {{trans('backend.object')}}</th>
                    <th data-field="setting" data-align="center" data-formatter="setting_formatter">Thiết lập</th>
                    <th data-field="result" data-align="center" data-formatter="result_formatter">Kết quả</th>
                    <th data-field="register" data-align="center" data-formatter="register_formatter">Học viên</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'" class="text-primary">'+ row.name +'</a>';
        }

        function course_formatter(value, row, index) {
            return '<a href="" title="'+ row.list_course +'">'+ row.course +'</a>';
        }

        function register_formatter(value, row, index) {
            if(row.register_url){
                return '<a href="'+ row.register_url +'"> <i class="fa fa-user"></i></a>';
            }
            return '';
        }

        function setting_formatter(value, row, index) {
            if(row.setting_url){
                return '<a href="'+ row.setting_url +'"> <i class="fa fa-cog"></i></a>';
            }
            return 'Mời thêm nhân viên';
        }

        function result_formatter(value, row, index) {
            if(row.result_url){
                return '<a href="'+ row.result_url +'"> <i class="fa fa-eye"></i></a>';
            }
            return '';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating_organization.getdata') }}',
            remove_url: '{{ route('module.rating_organization.remove') }}'
        });

        $('.publish').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 kỳ đánh giá', 'error');
                return false;
            }

            $.ajax({
                url: '{{ route('module.rating_organization.open') }}',
                type: 'post',
                data: {
                    ids: ids,
                    status: status,
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        });

    </script>
{{-- @endsection --}}
