@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="forum-container mb-2">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('backend.category.unit_type') }}">{{ trans('backend.unit_type') }}</a>
        <i class="uil uil-angle-right"></i>
        <span class="">{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    <form method="post" action="{{ route('backend.category.unit_type.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['category-unit-type-create', 'category-unit-type-edit'])
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('backend.category.unit_type') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.unit_type') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Mã đơn vị<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="code" type="text" class="form-control" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Mã đơn vị</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="all_unit_type_code">
                                        @if ( !empty($units_type_code) )
                                            @foreach ($units_type_code as $unit_type_code)
                                                <span class="unit_type_code">
                                                    {{ $unit_type_code->code }}
                                                    <span class="delete_code" onclick="deleteUnitCode({{ $unit_type_code->id }})">x</span>
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    function deleteUnitCode(id) {
        $.ajax({
            url: '{{ route('backend.category.unit_type.remove') }}',
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            show_message(data.message, data.status);
            window.location = '';
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    }
</script>
@stop
