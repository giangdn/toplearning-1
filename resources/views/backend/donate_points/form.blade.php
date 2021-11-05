@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.study_promotion_program') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.donate_points') }}">{{ trans('backend.donate_points') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <form method="post" action="{{ route('backend.donate_points.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        @canany(['donate-point-create','donate-point-edit'])
                            <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                        @endcan
                        <a href="{{ route('backend.donate_points') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                    </div>
                </div>
            </div>

            <div class="clear"></div>

            <br>
            <div class="tPanel">
                <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                    <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="base" class="tab-pane active">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="user_id">{{ trans('backend.receiver') }}</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="user_id" id="user_id" class="load-user form-control" data-placeholder="{{ trans('backend.receiver') }}" {{ $model->id ? 'disabled' : '' }}>
                                    <option value=""></option>
                                    @if($model->id)
                                        <option value="{{ $model->user_id }}" selected>{{ $profile->code .' - '. $profile->lastname .' '. $profile->firstname }}</option>
                                    @endif
                                </select>
                                @if($model->id)
                                    <input type="hidden" name="user_id" value="{{ $model->user_id }}">
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="">{{ trans('backend.title') }}</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" value="{{ $model->id ? $title->name : '' }}" title="{{ $model->id ? $title->name : '' }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="">{{ trans('backend.unit') }}</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="unit" value="{{ $model->id ? $unit->name : '' }}" title="{{ $model->id ? $unit->name : '' }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="score">{{ trans('backend.score') }}</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control is-number" name="score" value="{{ $model->score }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="note">{{ trans('backend.reason') }}</label>
                            </div>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="note" rows="5">{{ $model->note }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

<script type="text/javascript">
    $('#user_id').on('change', function () {
       var user_id = $(this).val();

       $.ajax({
           url: "{{ route('backend.donate_points.get_title_unit') }}",
           type: "POST",
           data: {
               user_id: user_id,
           }
       }).done(function(data) {
            $('#title').val(data.title);
            $('#unit').val(data.unit);

           return false;
       }).fail(function(data) {
           show_message('Lỗi hệ thống', 'error');
           return false;
       });
    });
</script>
@stop
