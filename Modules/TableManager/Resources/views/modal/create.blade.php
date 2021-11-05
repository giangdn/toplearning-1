<div class="modal fade"  id="modal-create-table" >
    <div class="modal-dialog modal-md" >
        <div class="modal-content">
            <form id="frm-approved-process" class="form-horizontal" method="post" action="{{route('module.tablemanager.save')}}" >
                <div class="modal-header">
                    <h4 class="modal-title">Tạo table</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="form-group">
                            <label>code</label>
                            <input type="text" class="form-control" name="code">
                        </div>
                        <div class="form-group">
                            <label>name</label>
                            <textarea class="form-control" name="name"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('backend.close')}}</button>
                    <button id="save-table-manager" class="btn btn-primary"><i class="fa fa-save"></i> {{trans('backend.save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

</script>
