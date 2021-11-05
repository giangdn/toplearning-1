@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.news') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.news.category') }}">{{trans("backend.category_post")}}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    <form method="post" action="{{ route('module.news.category.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['news-category-create', 'news-category-edit'])
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                        <a href="{{ route('module.news.category') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                    <label for="name">{{ trans('backend.category_post_name') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                </div>
                            </div>

                            {{-- DANH MỤC CHA --}}
                            <div class="form-group row" id="category_parent_id">
                                <div class="col-sm-3 control-label">
                                    <label for="parent_id">{{ trans('backend.father_level') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="parent_id" id="parent_id" class="form-control select2" data-placeholder="--{{trans('backend.choose_category_parent')}}--" >
                                        <option value=""></option>
                                        @foreach($categories as $parent)
                                            <option value="{{ $parent['id'] }}" {{ $model->parent_id == $parent->id ? 'selected' : '' }} >{{ $parent['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row"  id="stt_sort_category_parent">
                                <div class="col-sm-3 control-label">
                                    <label for="stt_sort_parent">Sắp xếp cấp cha</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="stt_sort_parent" id="stt_sort_parent" class="form-control" placeholder="Nhập số thứ tự sắp xếp cấp cha" value="{{ $model->stt_sort_parent ? $model->stt_sort_parent : '' }}">
                                </div>
                            </div>

                            <div class="form-group row"  id="status_category_parent">
                                <div class="col-sm-3 control-label">
                                    <label for="status">Hiện trên trang chủ</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="checkbox" {{ $model->status == 1 ? 'checked' : ''}} id="status" name="status" value="1">
                                </div>
                            </div>

                            {{-- DANH MỤC CON --}}
                            <div class="form-group row" id="stt_sort_category">
                                <div class="col-sm-3 control-label">
                                    <label for="stt_sort">Số thứ tự</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="stt_sort" id="stt_sort" class="form-control" placeholder="Nhập số thứ tự sắp xếp" value="{{ $model->stt_sort ? $model->stt_sort : '' }}">
                                </div>
                            </div>

                            <div class="form-group row" id="sort_category">
                                <div class="col-sm-3 control-label">
                                    <label for="sort">Sắp xếp bên phải</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="checkbox" {{ $model->sort == 2 ? 'checked' : ''}} id="sort" name="sort" value="2">
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
    var check_parent_id = $('#parent_id').val();
    if (check_parent_id && !check_stt_sort_parent) {
        $('#sort_category').show();
        $('#stt_sort_category').show();
        $('#stt_sort_category_parent').hide();
        $('#stt_sort_parent').val('');
        $('#status_category_parent').hide();
    } else {
        $('#sort_category').hide();
        $('#stt_sort_category').hide();
        $('#stt_sort_category_parent').show();
        $('#status_category_parent').show();
    }

    $('#parent_id').on('change',function() {
        var parent_id = $('#parent_id').val();
        if (parent_id) {
            $('#sort_category').show();
            $('#stt_sort_category').show();
            $('#stt_sort_category_parent').hide();
            $('#stt_sort_parent').val('');
            $('#status_category_parent').hide();
        } else {
            $('#sort_category').hide();
            $('#stt_sort_category').hide();
            $('#stt_sort_category_parent').show();
            $('#status_category_parent').show();
        }
    })

    var check_stt_sort_parent = $('#stt_sort_parent').val();
    if (check_stt_sort_parent) {
        $('#category_parent_id').hide();
    }
    
</script>

@stop
