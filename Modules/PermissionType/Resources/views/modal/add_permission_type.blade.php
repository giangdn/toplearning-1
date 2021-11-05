<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('module.permission.type.save') }}" method="post" class="form-ajax" data-success="success_submit">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="modal-header">
                <h4 class="modal-title">@if($model->name) {{ $model->name }} @else {{trans('backend.add_new')}}  @endif</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label> {{ trans('backend.name') }} <span class="text-danger">*</span></label>
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}">
                </div>

                <div class="form-group">
                    <label>{{trans('backend.description')}}</label>
                    <textarea cols="15" class="form-control" name="description" rows="3">{{$model->description}}</textarea>
                </div>
                <div class="form-group">
                    <label>{{trans('backend.viewable_units')}} <span class="text-danger">*</span></label>
                    <input type="text" id="agentSearch" class="form-control" onkeyup="searchAgent()" placeholder="Nhập tên đơn vị để tìm kiếm" title="Nhập tên đơn vị để tìm kiếm">
                    <div class="list-group checkbox-list-group" style="max-height: 200px;overflow: auto;">
                        @foreach($units as $k=>$v)
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="m-0">
                                        <input type="checkbox" name="unit[]" {{$v->id==$v->unit_id?'checked':''}} value="{{$v->id}}">
                                        <span class="list-group-item-text"><i class="fa fa-fw"></i> {{$v->name}}</span>
                                    </label>
                                </div>
                                <div class="col-md-5 radio--group-inline-container">
                                    <label class="m-0">
                                        <input type="radio" name="type[{{$v->id}}]" value="owner" {{($v->type=='owner') ?'checked="checked"':''}}>
                                        Owner
                                    </label>
                                    <label class="m-0">
                                        <input type="radio" name="type[{{$v->id}}]" value="group-child" {{$v->type=='group-child'?'checked="checked"':''}}>
                                        Group-child
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('backend.add_new')}}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> {{trans('backend.close')}}</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    function searchAgent() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("agentSearch");
        filter = input.value.toUpperCase();
        li = document.getElementsByClassName("list-group-item");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("span")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

    $("input[name=unit\\[\\]]").on('click', function () {
        var unit_id = $(this).val();

        if($(this).is(":checked")){
            $("input[name=type\\["+unit_id+"\\]]").filter("[value=owner]").prop('checked', true);
        }else if($(this).is(":not(:checked)")){
            $("input[name=type\\["+unit_id+"\\]]").prop('checked', false);
        }
    });

</script>
