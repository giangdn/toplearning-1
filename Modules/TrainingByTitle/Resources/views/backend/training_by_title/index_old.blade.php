@extends('layouts.backend')

@section('page_title', 'Lộ trình đào tạo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Lộ trình đào tạo</span>
        </h2>
    </div>
@endsection

@section('content')
    <style>
        .title_item{
            color: #fff;
            line-height: 20px;
            padding: 8px;
            position: relative;
            text-decoration: none;
            transform: translate(0);
        }
    </style>
    <div role="main">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('training-by-title-create')
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add-training-title"><i class="fa fa-plus-circle">
                        </i> @lang('career.add_title')
                    </button>
                    @endcan
                </div>
            </div>
        </div>
        <p></p>
        <div class="row" id="list-titles">
            @foreach($training_titles as $training_title)
                <div class="col-3 p-2 item">
                    <a href="{{ route('module.training_by_title.detail', ['id' => $training_title->id]) }}" style="cursor: pointer">
                        <div class="title_item w-100" style="background: url({{ image_file($training_title->image) }}) no-repeat; min-height: 150px;">
                            <span class="text-uppercase text-white p-2">
                                {{ $training_title->title_name }}
                            </span>

                            @can('training-by-title-delete')
                            <div class="eps_dots more_dropdown">
                                <a href="javascript:void(0)"><i class='uil uil-ellipsis-v text-white'></i></a>
                                <div class="dropdown-content">
                                    <span href="javascript:void(0)" style="cursor: pointer" class="ml-1 remove-title-item" data-id="{{ $training_title->id }}">
                                        <i class="fas fa-trash mr-2"></i> Xóa
                                    </span>
                                </div>
                            </div>
                            @endcan
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- <div class="row">
            <div class="col-md-12" id="form-internal">
                <div class="form-group row">
                    <div class="col-sm-4 control-label">
                        <label>Chọn chức danh</label>
                    </div>
                    <div class="col-md-8">
                        <select name="title_id" id="title_id" class="form-control select2">
                            <option value="" disabled selected>--Chọn chức danh--</option>
                            @foreach($titles as $title)
                                <option value="{{ $title->id }}"> {{ $title->name }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>

    <div class="modal fade" id="add-training-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content 1">
                <form action="{{ route('module.training_by_title.save') }}" method="post" class="form-ajax">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title2">@lang('career.add_title')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('career.title')</label>
                            <select name="title_id" id="roadmap_titles_id" class="form-control load-title" data-placeholder="--- @lang('career.choose_title') ---"></select>
                        </div>

                        <div class="form-group">
                            <label>{{trans('backend.picture')}} (480 x 320)</label>
                            <div>
                                <a href="javascript:void(0)" id="select-image">{{trans('backend.choose_picture')}}</a>
                                <div id="image-review">
                                </div>
                                <input name="image" id="image-select" type="text" class="d-none" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> @lang('app.save')</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times-circle"></i> @lang('app.close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $("#select-image").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review").html('<img src="'+ path +'" class="w-25 img-responsive">');
                $("#image-select").val(path);
            });
        });

        $('#list-titles').on('click', '.remove-title-item', function () {
            var btn = $(this);
            var id = $(this).data('id');

           $.ajax({
               url: '{{ route('module.training_by_title.remove') }}',
               type: 'post',
               data:{
                  id: id
               },
           }).done(function(data) {
               btn.parents('.item').remove();
           }).fail(function (data) {
               show_message('{{ trans('lageneral.data_error ') }}', 'error');
               return false;
           });
        });

    </script>
@endsection
