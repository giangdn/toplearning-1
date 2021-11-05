<div role="main">
    <div class="row">
        <div class="col-md-12 ">
            <form class="form-inline form-search-user mb-3" id="form-search">
                @for($i = 1; $i <= 5; $i++)
                    <div class="w-25">
                        <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --"
                                data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0">
                        </select>
                    </div>
                @endfor

                <div class="w-25">
                    <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                </div>

                <div class="w-25">
                    <input type="text" name="search" class="form-control w-100" placeholder="{{ trans('backend.enter_code_name__email_username_employee') }}">
                </div>

                <div class="w-25">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="tDefault table table-hover bootstrap-table" id="table-suggestions">
                <thead>
                <tr>
                    <th data-field="user_code">{{trans('backend.employee_code')}}</th>
                    <th data-field="full_name">{{ trans('backend.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">Đơn vị trực tiếp</th>
                    <th data-field="parent_unit_name">Đơn vị quản lý</th>
                    <th data-field="content">Nội dung góp ý</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<br>

<script type="text/javascript">
    var table_suggestions = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.quiz.edit.get_suggestions', ['id' => $model->id]) }}',
        table: '#table-suggestions'
    });
</script>
