@extends('layouts.backend')

@section('page_title', 'Cài đặt điểm giới thiệu')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Cài đặt điểm giới thiệu</span>
        </h2>
    </div>
@endsection

@section('content')
<div role="main">

    <form method="post" action="{{ route('backend.config.refer.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <div class="col-md-5 text-right">
                        <label>Điểm số người giới thiệu</label>
                    </div>
                    <div class="col-md-5">
                        <input type="number" class="form-control is-number" value="{{$grade_refer}}" name="grade_refer">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5 text-right">
                        <label>Điểm số người được giới thiệu</label>
                    </div>
                    <div class="col-md-5">
                        <input type="number" class="form-control is-number" value="{{$grade_refered}}" name="grade_refered">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5 text-right">
                    </div>
                    <div class="col-md-5">

                        <div class="btn-group act-btns">
                            @canany(['category-capabilities-create', 'category-capabilities-edit'])
                            <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                            @endcanany
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@stop
