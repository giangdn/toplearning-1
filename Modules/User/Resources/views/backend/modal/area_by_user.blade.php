<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin địa điểm làm việc của {{ $user->lastname . ' ' . $user->firstname }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @for($i=1;$i<=$max_area;$i++)
                    @php
                        $level_name_area =  \App\Models\Categories\Area::getLevelName($i);
                    @endphp
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ data_locale($level_name_area->name, $level_name_area->name_en) }}</label>
                        </div>
                        <div class="col-md-9">
                            @if(isset($area[$i]))
                                {{ $area[$i]->name }}
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>

