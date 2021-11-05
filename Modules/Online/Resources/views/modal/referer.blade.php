<div class="modal fade modal-add-activity" id="modal-referer">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('backend.add_presenter') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('module.online.register_course', ['id' => $item->id]) }}" id="frm-course" method="post" class="form-ajax">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 block-left">
                        <label>{{ trans('backend.presenter_code') }}</label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="referer" id="referer" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 block-left">
                    </div>
                    <div class="col-md-7">
                        <a href="javascript:void(0)" id="referer_modal" class="load-modal" data-url="{{ route('frontend.online.referer.show_modal',[$item->id] ) }}"><img src="{{asset('images/qr-code.svg')}}" width="30px" /> {{ trans('backend.scan_referrer_code') }}</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id=""><i class="fa fa-plus-circle"></i> {{ trans('backend.add_presenter') }}</button>
                <button type="button" id="btn_closed" class="btn btn-danger" ><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL LINK SHARE --}}
<div class="modal fade modal-add-activity" id="modal-share">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Share link khóa học</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modal-body-share">
                {{-- @if($promotion_share)
                    <b>Link share:</b> {{ route('module.online.detail', ['id' => $item->id]).'?share_key='. $promotion_share->share_key }}
                @endif --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="copyShare({{$item->id}})">Copy</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>


{{-- MODAL HƯỚNG DẪN HỌC --}}
<div class="modal fade modal-add-activity" id="modal-tutorial">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Hướng dẫn học: {{$item->type_tutorial ? ($item->type_tutorial == 1 ? 'Bài viết' : 'File đính kèm') : ''}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            {{-- <form action="{{ route('module.online.view_pdf', ['id' => $item->id]) }}" id="form_tutorial" method="get" class="form-ajax"> --}}
            <div class="modal-body">
                @if (!empty($item->tutorial))
                    @if ($item->type_tutorial == 1)
                        <span>{!! $item->tutorial !!}</span>
                    @else
                        @php
                            $files_tutorial = json_decode($item->tutorial);
                        @endphp
                        @foreach ($files_tutorial as $key => $file_tutorial)
                            <div class="row mb-2">
                                <div class="col-10">
                                    <span>{{ basename($file_tutorial) }}</span>
                                </div>
                                <div class="col-2">
                                    <a href="{{ route('module.online.tutorial.view_pdf', ['id' => $item->id,'key'=>$key]) }}" 
                                        target="_blank" 
                                        class="btn btn_adcart click-view-doc" 
                                        onclick="viewPdf({{$key}})">
                                        <i class="fa fa-eye"></i> Xem
                                    </a>
                                </div>
                                <input type="hidden" name="path" id="path_{{$key}}" value="{{$file_tutorial}}">
                            </div>
                        @endforeach
                    @endif
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
            </div>
            {{-- </form> --}}
        </div>
    </div>
</div>
<script>
    $('#btn_closed').on('click',function (e) {
        $('#referer').val('');
        var form =  $('#frm-course');
        form.submit();
        /*var formData = new FormData(form[0]);
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            dataType: 'json',
            data: formData,
            cache:false,
            contentType: false,
            processData: false
        })
            .done(function(data) {
            show_message(
                data.message,
                data.status
            );

            if (data.redirect) {
                setTimeout(function () {
                    window.location = data.redirect;
                }, 1000);
                return false;
            }

            btnsubmit.find('i').attr('class', currentIcon);
            btnsubmit.prop("disabled", false);

            if (data.status === "error") {
                return false;
            }

            if (submitSuccess) {
                eval(submitSuccess)(form);
            }

            return false;
        }).fail(function(data) {
            btnsubmit.find('i').attr('class', currentIcon);
            btnsubmit.prop("disabled", false);

            show_message('{{ trans('lageneral.data_error ') }}', 'error');
            return false;
        });
        form.submit();*/
    })
    function viewPdf(key) {
        var getPath = $('#path_'+key).val();
    }
</script>
