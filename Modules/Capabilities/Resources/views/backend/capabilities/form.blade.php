@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.capabilities') }}">Khung năng lực</a>
            <i class="uil uil-angle-right"></i>
            {{ $page_title }}
        </h2>
    </div>
@endsection

@section('content')
    @php
        $tabs = request()->get('tabs', null);
    @endphp
<div role="main">

    <form method="post" action="{{ route('module.capabilities.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <input type="hidden" name="dic_id" value="{{ $dictionary ? $dictionary->id : '' }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['category-capabilities-create', 'category-capabilities-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.capabilities') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="clear"></div>

        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#base" class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
                <li class="nav-item"><a href="#dictionary" class="nav-link @if($tabs == 'dictionary') active @endif" data-toggle="tab">Từ điển</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    @include('capabilities::backend.capabilities.form.info')
                </div>
                <div id="dictionary" class="tab-pane">
                    @include('capabilities::backend.capabilities.form.dictionary')
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        var ajax_get_group_name = "{{ route('module.capabilities.ajax_get_group_name') }}";
    </script>
</div>
<script type="text/javascript" src="{{ asset('styles/module/capabilities/js/capabilities.js') }}"></script>
@stop
