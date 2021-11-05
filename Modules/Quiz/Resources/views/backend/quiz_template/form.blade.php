@extends('layouts.backend')

@section('page_title', $page_title)
@section('header')
    <script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
@endsection('header')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('lamanager.quiz_manager') }}
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.quiz_template.manager') }}">Cơ cấu đề thi</a>
        <i class="uil uil-angle-right"></i>
        <span>{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    @if($model->id)
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6 text-right">
                <a href="{{ route('module.quiz_template.question', ['id' => $model->id]) }}" class="btn btn-primary"> <i class="fa fa-question-circle"></i> {{ trans('backend.question') }}</a>
            </div>
        </div>
        <p></p>
    @endif
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            @if($model->id)
                <li><a href="#rank" class="nav-link" role="tab" data-toggle="tab">{{ trans("backend.classification") }}</a></li>
                <li><a href="#setting1" class="nav-link" role="tab" data-toggle="tab">{{trans('backend.custom')}}</a></li>
            @endif
        </ul>

        <div class="tab-content">
            <div id="base" class="tab-pane active">
                @include('quiz::backend.quiz_template.form.info')
            </div>

            @if($model->id)
                <div id="rank" class="tab-pane">
                    @include('quiz::backend.quiz_template.form.rank')
                </div>

                <div id="setting1" class="tab-pane">
                    @include('quiz::backend.quiz_template.form.setting')
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('footer')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#paper_exam').on('click', function () {
                if ($(this).is(':checked')) {
                    $(this).closest('.form-check').find('.check-paper-exam').val(1);
                } else {
                    $(this).closest('.form-check').find('.check-paper-exam').val(0);
                }
            });

            $('input[name=webcam_require]').on('click', function () {
                if ($(this).is(':checked')) {
                    $(this).val(1);
                    $('input[name=times_shooting_webcam]').prop('disabled', false);
                } else {
                    $(this).val(0);
                    $('input[name=times_shooting_webcam]').prop('disabled', true);
                }
            });
            $('input[name=question_require]').on('click', function () {
                if ($(this).is(':checked')) {
                    $(this).val(1);
                    $('input[name=times_shooting_question]').prop('disabled', false);
                } else {
                    $(this).val(0);
                    $('input[name=times_shooting_question]').prop('disabled', true);
                }
            });

            $('.load-unit-quiz').select2({
                allowClear: true,
                dropdownAutoWidth: true,
                width: '100%',
                placeholder: function (params) {
                    return {
                        id: null,
                        text: params.placeholder,
                    }
                },
                ajax: {
                    method: 'GET',
                    url: '{{ route('module.quiz_template.edit.getunit', ['id' => $model->id]) }}',
                    dataType: 'json',
                    data: function (params) {

                        var query = {
                            search: $.trim(params.term),
                            page: params.page,
                        };

                        return query;
                    }
                }
            });

        });

        $("#select-image").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review").html('<img src="'+ path +'" class="w-50">');
                $("#image-select").val(path);
            });
        });
    </script>
@endsection
