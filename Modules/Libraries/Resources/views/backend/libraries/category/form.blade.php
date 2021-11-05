@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.library') }}
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.libraries.category') }}">{{ trans('backend.category_library') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')
<div role="main">

    <form method="post" action="{{ route('module.libraries.category.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['libraries-category-create', 'libraries-category-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.libraries.category') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                    <label for="name">{{ trans('backend.category_name') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-7">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="parent_id">{{trans('backend.category_type')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="type" id="type" class="form-control select2" data-placeholder="--{{trans('backend.choose_category_type')}}--">
                                        <option value=""></option>
                                        <option value="1" {{ $model->type == 1 ? 'selected' : '' }}>{{trans('backend.category_book')}}</option>
                                        <option value="2" {{ $model->type == 2 ? 'selected' : '' }}>{{trans("backend.ebook_category")}}</option>
                                        <option value="3" {{ $model->type == 3 ? 'selected' : '' }}>{{trans("backend.document_category")}}</option>
                                        <option value="4" {{ $model->type == 4 ? 'selected' : '' }}>{{ trans('backend.video_category') }}</option>
                                        <option value="5" {{ $model->type == 5 ? 'selected' : '' }}>Danh mục sách nói</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="parent_id">{{trans('backend.father_level')}}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="parent_id" id="parent_id" class="form-control select2" {{ $model->id ? '' : 'disabled' }} data-placeholder="--{{trans('backend.choose_category')}}--">
                                        <option value=""></option>
                                        @foreach($categories as $parent)
                                            <option value="{{ $parent['id']  }}" {{ $model->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

<script type="text/javascript" src="{{ asset('styles/module/news/js/news.js') }}"></script>
<script>
    $('#type').on('change',function() {
        $('#parent_id').attr("disabled", false); 
        var type = $('#type').val();
        $.ajax({
            type: "POST",
            url: "{{ route('module.libraries.category.ajax_load_parent') }}",
            dataType: 'json',
            data: {
                type: type,
            },
            success: function (result) {
                let html = '';
                $.each(result, function (i, item){
                    html+='<option value=""></option>';
                    html+='<option value='+ item.id +'>'+ item.name +'</option>';
                });
                $("#parent_id").html(html);

                show_message(result.message, result.status);
                return false;
            }
        });
    })
</script>
@stop
