<div class="modal fade"  id="modal-edit-table" >
    <div class="modal-dialog modal-md" >
        <div class="modal-content">
            <form id="frm-approved-process" class="form-horizontal" method="post"  enctype="multipart/form-data" action="{{route('module.tablemanager.update',['id'=>$table->id])}}" >
                @method('put')
                <div class="modal-header">
                    <h4 class="modal-title">Cập nhật table</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="form-group">
                            <label>code</label>
                            <input type="text" class="form-control" name="code" value="{{$table->code}}">
                        </div>
                        <div class="form-group">
                            <label>name</label>
                            <textarea class="form-control" name="name">{{$table->name}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('backend.close')}}</button>
                    <button id="update-table-manager" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('backend.save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
