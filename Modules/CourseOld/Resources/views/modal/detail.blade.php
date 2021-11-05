<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalDetail" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelUpdate">Xem thông tin chi tiết</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <table class="table table-striped table-bordered">
                        @foreach($data as $key => $value)
                        <tr>
                            <td width="150px">
                                {{ $key }}
                            </td>
                            <td>
                                @if(in_array($key, ['Chi phí đi lại','Chi phí lưu trú','Công tác phí','Bình quân CPGV','Chi phí khác','Bình quân CPTC','Bình quân CP Học viên', 'Tổng CP']))
                                    {{ number_format($value) }}
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>
