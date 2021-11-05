@extends('layouts.backend')

@section('page_title', trans('cron::language.edit'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.cron') }}">{{trans('backend.schedule_task')}}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.edit') }}</span>
        </h2>
    </div>
@endsection

@section('content')
<div role="main">
    <form method="post" action="{{route('module.cron.update',['id'=>$model->id])}}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['training-plan-create', 'training-plan-edit'])
                        <button type="submit" class="btn btn-primary"  data-must-checked="false"><i class="fa fa-check"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                </div>
                <a href="{{ route('module.cron') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.back') }}</a>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="form-group row">
            <div class="col-sm-4 control-label text-right">
                <label>Chọn tác vụ</label><span style="color:red"> * </span>
            </div>
            <div class="col-md-6">
                <select name="command" class="form-control" disabled id="command" >
                    @foreach($commands as $key=>$value)
                        <option value="{{$value->code}}" {{$value->code==$model->code?'selected':''}}>{{$value->name}} - ({{$value->code}})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4 control-label text-right">
                <label>Phút</label><span style="color:red"> * </span>
            </div>
            <div class="col-md-6">
                <input type="text" name="minute" class="form-control" value="{{$model->minute}}" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4 control-label text-right">
                <label>Giờ</label><span style="color:red"> * </span>
            </div>
            <div class="col-md-6">
                <input type="text" name="hour" class="form-control" value="{{$model->hour}}" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4 control-label text-right">
                <label>Ngày</label><span style="color:red"> * </span>
            </div>
            <div class="col-md-6">
                <input type="text" name="day" class="form-control" value="{{$model->day}}" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4 control-label text-right">
                <label>Tháng</label><span style="color:red"> * </span>
            </div>
            <div class="col-md-6">
                <input type="text" name="month" class="form-control" value="{{$model->month}}" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4 control-label text-right">
                <label>Ngày trong tuần</label><span style="color:red"> * </span>
            </div>
            <div class="col-md-6">
                <input type="text" name="day_of_week" class="form-control" value="{{$model->day_of_week}}" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4 control-label text-right">
                <label>Bật/tắt</label>
            </div>
            <div class="col-md-6">
                <input type="checkbox" name="enabled" {{$model->enabled==1?'checked':''}}  />
            </div>
        </div>
        <div class="clear"></div>
        <br>
    </form>
</div>
<script type="text/javascript">

</script>
@stop
