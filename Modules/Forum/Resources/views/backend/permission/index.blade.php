@extends('layouts.backend')

@section('page_title', 'Quản Lý Diễn Đàn')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
@endsection

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.forum.category') }}">{{ trans('backend.forum') }}</a>
            <i class="uil uil-angle-right"></i>
            {{ $cate->name }}
            <i class="uil uil-angle-right"></i>
            <span>{{ trans('backend.permission') }}</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-9">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label>{{ trans('backend.user_belong') }}</label>
                    </div>
                    <div class="col-md-6">
                        <label class="radio-inline"><input type="radio" name="object" value="1" checked> {{ trans('backend.unit') }} </label>
                        <label class="radio-inline"><input type="radio" name="object" value="2"> {{ trans('backend.user') }} </label>
                    </div>
                </div>
                <form method="post" action="{{ route('module.permission.save', ['cate_id' => $cate_id]) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data" data-success="submit_success">
                    <div id="object-unit">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label> {{ trans('backend.unit') }} </label>
                            </div>
                            <div class="col-md-9">
                                <div id="tree-unit" class="tree">
                                    @foreach($corporations as $item)
                                        @php
                                            $count_child = \App\Models\Categories\Unit::countChild($item->code);
                                        @endphp
                                        <div class="item">
                                            <i class="uil uil-plus"></i> <input type="checkbox" name="unit_id[]" data-id="{{ $item->id }}" class="check-unit" value="{{ $item->id }}">
                                            <a href="javascript:void(0)" data-id="{{ $item->id }}" class="tree-item">{{ $item->name .' ('. $count_child . ')' }}</a>
                                        </div>
                                        <div id="list{{ $item->id }}"></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="object-user">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label> {{ trans('backend.user') }} </label>
                            </div>
                            <div class="col-md-9">
                                <select name="user_id[]" id="user_id" class="form-control load-user" data-placeholder="-- {{ trans('backend.choose_user') }} --" multiple></select>
                            </div>
                        </div>
                    </div>
                    <div id="object-table">
                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <button type="submit" class="btn btn-info"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="form-object">
                <div class="text-right">
                    <button id="delete-item" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                </div>
                <p></p>
                <table class="tDefault table table-hover bootstrap-table">
                    <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="profile_code">{{ trans('backend.employee_code') }}</th>
                        <th data-field="profile_name">{{ trans('backend.employee_name') }}</th>
                        <th data-field="email">{{ trans('backend.employee_email') }}</th>
                        <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                        <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

        <script type="text/javascript">
            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: '{{ route('module.permission.getdata', ['cate_id' => $cate_id]) }}',
                remove_url: '{{ route('module.permission.remove', ['cate_id' => $cate_id]) }}'
            });

            function submit_success(form) {
                $("#object-user select[name=user_id\\[\\]]").val(null).trigger('change');
                $('#list1').find('ul').remove();
                $('.check-unit').prop('checked', false);
                $(table.table).bootstrapTable('refresh');
            }

            var object = $("input[name=object]").val();
            if (object == 1) {
                $("#object-unit").show('slow');
                $("#object-user").hide('slow');
            } else {
                $("#object-unit").hide('slow');
                $("#object-user").show('slow');
            }

            $("input[name=object]").on('change', function () {
                var object = $(this).val();
                if (object == 1) {
                    $("#object-unit").show('slow');
                    $("#object-user").hide('slow');
                    $("#object-user select[name=user_id\\[\\]]").val(null).trigger('change');
                } else {
                    $("#object-unit").hide('slow');
                    $("#object-user").show('slow');
                }
            });

            var openedClass = 'uil-minus uil';
            var closedClass = 'uil uil-plus';

            $('#tree-unit').on('click', '.tree-item', function (e) {
                var id = $(this).data('id');

                if ($(this).closest('.item').find('i:first').hasClass(openedClass)){
                    $('#list'+id).find('ul').remove();
                }else{
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('backend.category.unit.tree_folder.get_child') }}",
                        dataType: 'json',
                        data: {
                            id: id
                        }
                    }).done(function(data) {
                        let rhtml = '';

                        rhtml += '<ul>';
                        $.each(data.childs, function (i, item){

                            rhtml += '<li>';
                            rhtml += '<div class="item">';
                            rhtml += '<i class="uil uil-plus"></i> <input type="checkbox" name="unit_id[]" class="check-unit" data-id="'+ item.id +'" value="'+ item.id +'"> ';
                            rhtml += '<a href="javascript:void(0)" data-id="'+ item.id +'" class="tree-item"> ' + item.name + ' (' + data.count_child[item.id] + ') </a>';
                            rhtml += '</div>';
                            rhtml += '<div id="list'+ item.id +'"></div>';
                            rhtml += '</li>';
                        });
                        rhtml += '</ul>';

                        document.getElementById('list'+id).innerHTML = '';
                        document.getElementById('list'+id).innerHTML = rhtml;
                    }).fail(function(data) {
                        show_message('{{ trans('lageneral.data_error ') }}', 'error');
                        return false;
                    });
                }

                if (this == e.target) {
                    var icon = $(this).closest('.item').children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            });

            $('#tree-unit').on('click', '.check-unit', function (e) {
                var id = $(this).data('id');

                if($(this).is(":checked")){
                    console.log('a');
                    $(this).prop('checked', true);
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('module.permission.get_child', ['cate_id' => $cate_id]) }}",
                        dataType: 'json',
                        data: {
                            id: id
                        }
                    }).done(function(data) {
                        $.each(data.childs, function (i, item){
                            $('#list'+id).load(data.page_child[item.id]);
                        });
                    }).fail(function(data) {
                        show_message('{{ trans('lageneral.data_error ') }}', 'error');
                        return false;
                    });
                }else if($(this).is(":not(:checked)")){
                    console.log('b');
                    $(this).prop('checked', false);
                    $('#list'+id).find('.check-unit').attr('checked', false);
                }

                if (this == e.target) {
                    var icon = $(this).closest('.item').children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            });

        </script>
    </div>
@endsection
