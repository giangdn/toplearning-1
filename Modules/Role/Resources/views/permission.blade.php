<div role="main" id="rolepermission">
    <div class="row">
        <div class="col-md-8">
            <form class="form-inline form-search mb-3" id="form-search">
                <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Nhập mã / tên quyền','') }}">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
            </form>
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['role-edit|role-create'])
                <button  class="btn btn-primary save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{trans('backend.save')}}</button>
                @endcanany
                <a href="{{ route('backend.roles') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{trans('backend.cancel')}}</a>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <br>
    <form>
        <table id="table" class="tDefault table table-hover bootstrap-table"
            data-detail-view="true"
            data-detail-formatter="detailFormatter"
            data-page-list="[25,50]"
        >
            <thead>
            <tr>
                <th data-field="id" data-width="80px" class="text-center">#</th>
                <th data-field="name" data-width="400px">{{trans('backend.name')}}</th>
                <th data-field="description">{{trans('backend.description')}}</th>
                <th data-width="250px" data-field="id" data-formatter="permission_formatter" class="text-center">
                    {{trans('backend.permission_group')}}
                </th>
            </tr>
            </thead>
        </table>
    </form>
</div>
<script type="text/javascript">

    function index_formatter(value, row, index) {
        return (index+1);
    }

    function name_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="edit-item" data-id="'+ row.id +'">'+ value +'</a>';
    }

    function detailFormatter(index, row) {
        var html = [], str ='';
        $.each(row.permission,function (i,e){
            var checked = (e.id==e.permission_id)?'checked':'';
            str+='<div class="col-md-12 ml-lg-4"><label><input type="checkbox" '+checked+' class="btnselect" name="btSelectItem" value="'+e.id+'"> '+e.description+':</label></div>';
        });
        html.push(`<label><input type="checkbox" name="`+row.name+`" class="select_all" onclick="select_all('`+row.name+`')"/> {{ trans("backend.select_all") }} </label>
                    <div class="row ml-lg-8">
                        <div class="col-md-8 ` + row.name + `" >` + str + `</div>
                    </div>`
                );
        return html.join('')
    }

    function permission_formatter(value, row, index) {
        var html ='<select class="form-control" name="group-permission['+row.id+']"><option value="0">--{{ trans("backend.permission_group") }}--</option>';
        $.each(row.permission_type,function (i,e) {
            var select = e.id==row.permission_type_id?'selected':'';
            if (e.id==row.group_permission)
                html+='<option '+select+' value='+e.id+'>'+e.name+'</option>';
        });
        html+="</select>";
        return html;
    }
    var ajax_save = "{{ route('backend.roles.ajax_save', ['role' => $role->id]) }}";
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('backend.roles.getpermission',['role'=>$role->id]) }}'
    });

    $('#table').on('post-body.bs.table', function (e, d) {
        $("#table").bootstrapTable("expandAllRows");
    });

    function select_all(permissonName){
        if ( $('input[name="' + permissonName + '"]:checked').length > 0) {
            $('.' + permissonName + '').find(".btnselect").attr("checked", true);
        } else {
            $('.' + permissonName + '').find(".btnselect").attr("checked", false);
            console.log('uncheck');
        }
    }

</script>
