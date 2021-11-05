@extends('layouts.backend')

@section('page_title', trans('backend.user_management'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{route('module.dashboard')}}">{{ trans('backend.dashboard') }} <i class="uil uil-angle-right"></i></a>
            <a href="{{route('module.userpermission')}}"><span class="font-weight-bold"> {{ trans('backend.user') }}</span></a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold"> {{ trans('backend.permission') }}</span>
        </h2>
    </div>
@endsection
@section('content')
<div role="main" id="rolepermission">
    <div class="row">
        <div class="col-md-8 ">
            <form class="form-inline form-search mb-3" id="form-search">
                <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Nhập mã / tên quyền','') }}">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
            </form>
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                <button  class="btn btn-primary save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;@lang('backend.save')</button>
                <a href="{{ route('backend.roles') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> @lang('backend.cancel')</a>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <br>
    <form>
        <table id="table" class="tDefault table table-hover bootstrap-table"
            data-detail-view="true"
            data-detail-formatter="detailFormatter"
        >
            <thead>
            <tr>
                <th data-field="id" data-width="80px" class="text-center">#</th>
                <th data-field="name" data-width="400px">{{trans('backend.name')}}</th>
                <th data-field="description">{{trans('backend.description')}}</th>
                <th data-width="250px" data-field="id" data-formatter="permission_formatter" class="text-center">
                    {{ trans('backend.permission_group') }}
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
        var html = [], str ='', i="";
        $.each(row.permission,function (i,e){
            var checked = (e.id==e.permission_id)?'checked':'';
            str+='<div class="col-md-12 ml-lg-4 1"><label><input type="checkbox" '+checked+' name="btSelectItem" value="'+e.id+'"> '+e.description+':</label></div>';
        });
        html.push(`<label><input type="checkbox" name="select-all" class="select_all" /> Select All </label>
                    <div class="row ml-lg-8">
                        <div class="col-md-8 checkDetail">` + str + `</div>
                    </div>`
                );
        return html.join('')
    }

    function permission_formatter(value, row, index) {
        var html ='<select class="form-control" name="group-permission['+row.id+']"><option value="0">--{{ trans("backend.permission_group") }}--</option>';
        $.each(row.permission_type,function (i,e) {
            var select = e.id==row.permission_type_id?'selected':'';
            html+='<option '+select+' value='+e.id+'>'+e.name+'</option>';
        });
        html+="</select>";
        return html;
    }
    var ajax_save = "{{ route('module.userpermission.save', ['user_id' => $user_id]) }}";
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.userpermission.getpermission',['user_id'=>$user_id]) }}'
    });

    $('#table').on('post-body.bs.table', function (e, d) {
        $("#table").bootstrapTable("expandAllRows");
    });

    $(document).ready(function(){
        $(document).on('change', '.select_all', function() {
            let objCheckBox = $(this).closest('tr').find('div.checkDetail input');
            if($(this).is(':checked'))
                $.each(objCheckBox,function (i,e) {
                    $(e).attr('checked',true);
                });
            else{
                $.each(objCheckBox,function (i,e) {
                    $(e).attr('checked',false);
                });
            }
        });
    });

    function uncheckAll(divid) {
        var checks = document.querySelectorAll('#' + divid + ' input[type="checkbox"]');
        for(var i =0; i< checks.length;i++){
            console.log(i);
        }
    }



</script>
<script src="{{ asset('styles/module/user_permission/js/userpermission.js') }}"></script>
@endsection
