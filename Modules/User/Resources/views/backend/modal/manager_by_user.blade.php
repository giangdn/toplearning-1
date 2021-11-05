<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin cấp quản lý của {{ $user->lastname . ' ' . $user->firstname }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @if(count($manager_level) > 0)
                    @foreach($manager_level as $key => $item)
                        @php
                            $profile = $manager($item->user_manager_id);
                        @endphp
                        <div class="form-group row">
                            <div class="col-sm-6 control-label">
                                Quản lý thứ {{ ($key + 1) . ': ' . ($profile ? ($profile->lastname . ' ' . $profile->firstname) : '') }}
                            </div>
                            <div class="col-md-6">
                            {{trans('backend.time')}}: {{ get_date($item->start_date) }} @if ($item->end_date) {{ ' đến ' . get_date($item->end_date) }} @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <b> Không có dữ liệu </b>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

