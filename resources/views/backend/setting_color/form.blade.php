@extends('layouts.backend')

@section('page_title', 'Chỉnh màu nút')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Chỉnh màu nút</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <form method="post" action="{{ route('backend.setting_color.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
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
                        <div class="form-group row" id="select_posts">
                            <div class="col-md-3 control-label">
                                <label for="content">Màu nút nhấn</label>
                            </div>
                            <div class="col-md-6">
                                <input type="color" name="color_button" class="avatar avatar-40 shadow-sm change-color" value="{{ $model ? $model->value : 'fff' }}"> {{ data_locale('Chọn màu', 'Choose color') }}
                            </div>
                        </div>
                        <div class="form-group row" id="select_posts">
                            <div class="col-md-3 control-label">
                                <label for="content">Màu rê chuột vào nút nhấn</label>
                            </div>
                            <div class="col-md-6">
                                <input type="color" name="hover_color_button" class="avatar avatar-40 shadow-sm change-hover-color" value="{{ $hover_color_button ? $hover_color_button->value : 'fff' }}"> {{ data_locale('Chọn màu', 'Choose color') }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="title"></label>
                            </div>
                            <div class="col-sm-6">
                                <button id="button_test" type="button" class="btn">test</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
@section('footer')
    <script type="text/javascript">
        var get_button_color = '{{ $model ? $model->value : '' }}';
        var get_hover_button_color = '{{ $hover_color_button ? $hover_color_button->value : '' }}';
        if(get_button_color) {
            $('#button_test').attr('style', 'background: '+ get_button_color +' !important');
        }
        if (get_hover_button_color) {
            var btn = document.getElementById('button_test');
            btn.onmouseover = function() {
                this.setAttribute('style', 'background: '+ get_hover_button_color +' !important');
            }
            btn.onmouseout = function() {
                this.setAttribute('style', 'background: '+ get_button_color +'!important');
            }
        }

        var golbal_color = '';
        $('.change-color').on('change', function () {
            var set_color = $(this).val();
            golbal_color = set_color;
            $('#button_test').attr('style', 'background: '+ set_color +' !important');
            var btn = document.getElementById('button_test');
            btn.onmouseout = function() {
                this.setAttribute('style', 'background: '+ set_color +'!important');
            }
        });
        $('.change-hover-color').on('change', function () {
            if(!golbal_color) {
                golbal_color = get_button_color;
            }
            console.log(golbal_color);
            var set_color = $(this).val();
            var btn = document.getElementById('button_test');
            btn.onmouseover = function() {
                this.setAttribute('style', 'background: '+ set_color +' !important');
            }
            btn.onmouseout = function() {
                this.setAttribute('style', 'background: '+ golbal_color +'!important');
            }
        });
    </script>
@endsection