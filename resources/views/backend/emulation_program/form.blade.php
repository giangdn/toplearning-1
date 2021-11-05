@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
@php
    $tabs = request()->get('tabs', null);
    $armorial_emulation = App\ArmorialEmulationProgram::where('emulation_id','=',$model->id)->get();
    $emulation_object = App\EmulationProgramObject::where('emulation_id','=',$model->id)->get();
    $emulation_condition = App\EmulationProgramCondition::where('emulation_id','=',$model->id)->get();
@endphp
<div role="main">
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            Quà tặng <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.emulation_program') }}">Chương trình thi đua</a>
            <i class="uil uil-angle-right"></i>
            <span>{{ $page_title }}</span>
        </h2>
    </div>
    <div class="row m-2">
        <div class="col-12 steps pt-2">
            <ul class="progressbar">
                <li class="{{ $model->id ? 'active' : ''}}">Chương trình</li>
                <li class="{{ ($model->id && !$armorial_emulation->isEmpty()) ? 'active' : ''}}">Huy hiệu</li>
                <li class="{{ ($model->id && !$armorial_emulation->isEmpty() && !$emulation_object->isEmpty()) ? 'active' : ''}}">Đối tượng</li>
                <li class="{{ ($model->id && !$armorial_emulation->isEmpty() && !$emulation_object->isEmpty() && !$emulation_condition->isEmpty()) ? 'active' : ''}}">Điều kiện</li>
            </ul>
        </div>
    </div>
    <br>
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item">
                <a href="#base"
                    class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif"
                    role="tab"
                    data-toggle="tab">Chương trình thi đua
                </a>
            </li>
            @if($model->id)
                <li class="nav-item">
                    <a href="#armorial"
                        class="nav-link @if($tabs == 'armorial') active @endif"
                        data-toggle="tab">Huy hiệu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#object"
                        class="nav-link @if($tabs == 'object') active @endif"
                        data-toggle="tab">Đối tượng
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#condition"
                        class="nav-link @if($tabs == 'condition') active @endif"
                        data-toggle="tab">Điều kiện
                    </a>
                </li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="base"
                class="tab-pane @if($tabs == 'base' || empty($tabs)) active @endif">
                @include('backend.emulation_program.form.info')
            </div>
            @if($model->id)
                <div id="armorial"
                    class="tab-pane @if($tabs == 'armorial') active @endif">
                    @include('backend.emulation_program.form.armorial')
                </div>
                <div id="object"
                    class="tab-pane @if($tabs == 'object') active @endif">
                    @include('backend.emulation_program.form.object')
                </div>
                <div id="condition"
                    class="tab-pane @if($tabs == 'condition') active @endif">
                    @include('backend.emulation_program.form.condition')
                </div>
            @endif
        </div>
    </div>
</div>
<script type="text/javascript">
    CKEDITOR.replace('description', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>
@stop
