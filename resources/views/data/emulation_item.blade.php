@php
    $url = route('frontend.emulation_program.detail', ['id' => $item->id]);
@endphp
<div class="fcrse_1 my-3">
    <a href="{{ $url }}" class="fcrse_img">
        <img class="picture_course" src="{{ image_file($item->image) }}" alt="">
    </a>
    <div class="fcrse_content">
        <div class="course_names">
            <a href="{{ $url }}" class="crse14s course_name">{{ $item->name }}</a>
            <span class="hidden_name">{{ $item->name }}</span>
        </div>
        <div class="vdtodt">
            <span class="vdt14"><b>Mã Chương trình:</b> {{$item->code}}</span>
        </div>
        <div class="vdtodt">
            <span class="vdt14"><b>@lang('app.time'):</b> {{ get_date($item->time_start) }} @if($item->time_end) @lang('app.to') {{ get_date($item->time_end) }} @endif</span>
        </div>
        {{-- <div class="vdtodt" onclick="openModalObject({{$item->id}})" style="cursor: pointer">
            <p class="cr1fot import-plan"><b>Đối tượng:</b> <i class="uil uil-info-circle" title=""></i></p>
        </div> --}}
    </div>
</div>

{{-- MOdal SHOW ĐỐI TƯỢNG --}}
<div class="modal fade" id="modal_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="" method="post" class="form-ajax">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel_{{$item->id}}">Đối tượng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="tDefault table table-hover bootstrap-table" id="table_object_{{$item->id}}">
                        <thead>
                            <tr>
                                <th data-align="center" data-width="3%" data-formatter="stt_formatter">STT</th>
                                <th data-field="title_name">{{trans('backend.title')}}</th>
                                <th data-field="unit_name">{{trans('backend.unit')}}</th>
                                <th data-align="center" data-field="type" data-width="10%" data-formatter="type_formatter">{{trans('backend.type_object')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function type_formatter(value, row, index) {
        return value == 1 ? 'Bắt buộc' : '{{ trans("backend.register") }}';
    }

    function stt_formatter(value, row, index) {
        return (index + 1);
    }

    function openModalObject(id) {
        $('#modal_'+id).modal();
        var url = "{{ route('module.online.get_object', ':id') }}";
        url = url.replace(':id',id);
        var table_object = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: url,
            table: '#table_object_'+id,
        });
    }
    
    function closeModal(id) {
        $('#referer_'+id).val('');
        var form =  $('#frm-course-'+id);
        form.submit();
    }
</script>
