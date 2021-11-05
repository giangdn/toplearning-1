@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
    <script type="text/javascript">
        var career = {
            'parents_url': '{{ route('module.career_roadmap.frontend.getparents') }}',
            'remove_roadmap_url': '{{ route('module.career_roadmap.frontend.remove_roadmap') }}',
            'remove_title_url': '{{ route('module.career_roadmap.frontend.remove') }}',
            'edit_career_roadmap':'{{ route('module.career_roadmap.frontend.edit') }}',
        };
    </script>
    <script src="{{ asset('modules/career_roadmap/js/backend.js') }}"></script>
@endsection

@section('page_title', trans('career.career_roadmap'))

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title"><i class="uil uil-apps"></i>
                                        <span class="font-weight-bold">@lang('career.career_roadmap')</span>
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            @if($career_roadmaps)
                                @php
                                    $title_id = \App\Profile::find(Auth::id())->titles->id;
                                    $sub_titles = $career_roadmaps->getTitles();
                                @endphp

                                <div class="text-center">
                                    <img src="{{ asset('themes/mobile/img/check.png') }}" class="img-responsive" style="width: 40px; height: 40px;">
                                    <p style="width: 75px; word-break: break-word">
                                        Bắt đầu
                                    </p>
                                </div>
                                @foreach($sub_titles as $index => $sub_title)
                                    @php
                                        $total_subject = \Modules\CareerRoadmap\Entities\CareerRoadmapTitle::getSubjectRoadmap($sub_title->title->id);
                                        $total_result = 0;
                                        if ((count($total_subject) > 0)){
                                            foreach ($total_subject as $subject){
                                                $subject_course = \App\Models\Categories\Subject::where('code', '=', $subject->subject_code)->first();
                                                if ($subject_course && $subject_course->isCompleted()){
                                                    $total_result += 1;
                                                }
                                            }
                                        }

                                        $percent = ($total_result / ((count($total_subject) > 0) ? count($total_subject) : 1)) * 100;
                                    @endphp
                                    @if($index < count($sub_titles))
                                        <span class="progress progress2" style="flex-basis: 10%; margin-top: 10px;">
                                            <div class="progress-bar" style="width: {{ $percent }}%" role="progressbar" aria-valuemin="0" aria-valuemax="100"> {{ number_format($percent, 2) }}% </div>
                                        </span>
                                    @endif

                                    <div class="text-center">
                                        @if($percent == 100)
                                            <img src="{{ asset('themes/mobile/img/check.png') }}" class="img-responsive" style="width: 40px; height: 40px;">
                                        @else
                                            <img src="{{ asset('themes/mobile/img/padlock.png') }}" class="img-responsive" style="width: 40px; height: 40px;">
                                        @endif
                                        <p style="width: 75px; word-break: break-word">
                                            {{ $sub_title->title->name }}
                                        </p>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div id="tree-unit" class="tree bg-white">
                                    @foreach($roadmaps as $roadmap)
                                        <div class="bg-primary text-white p-2">
                                            {{ $roadmap->name }}
                                        </div>
                                        @php
                                            $title_id = \App\Profile::find(Auth::id())->titles->id;
                                            $sub_titles = $roadmap->getTitles('0');
                                        @endphp
                                        @foreach($sub_titles as $index => $sub_title)
                                            <div class="row item mt-2">
                                                <div class="col-md-10">
                                                    <a href="javascript:void(0)" data-id="{{ $sub_title->id }}" data-type="1" class="tree-item">
                                                        <i class="uil uil-plus"></i> {{ str_repeat('-- ', $sub_title->level) . $sub_title->title->name }}
                                                    </a>
                                                    <span class="seniority_careers_roadmap">Thâm niên (năm): {{ $sub_title->seniority }}</span>
                                                </div>
                                                <div class="col-md-2 text-right">
                                                    <a href="javascript:void(0)" class="btn btn-info view-career"
                                                       data-id="{{ $sub_title->title->id }}" data-name="{{ $sub_title->title->name }}">
                                                        <i class="fa fa-eye"></i> @lang('career.view')
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" id="list{{ $sub_title->id }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach

                                    {{--Của nhân viên--}}
                                    @foreach($roadmaps_user as $roadmap_user)
                                        <div class="bg-primary text-white p-2 mt-2">
                                            {{ $roadmap_user->name }}
                                            <span class="float-right">
                                                <a href="javascript:void(0)" class="add-roadmap-title text-white" data-id="{{ $roadmap_user->id }}">
                                                    <i class="fa fa-plus"></i> @lang('career.add_title')
                                                </a>
                                                <a href="javascript:void(0)" class="text-danger delete-roadmap" data-id="{{ $roadmap_user->id }}">
                                                    <i class="fa fa-trash"></i> @lang('career.delete')
                                                </a>
                                            </span>
                                        </div>
                                        @php
                                            $sub_titles_user = $roadmap_user->getTitles('0');
                                        @endphp
                                        @foreach($sub_titles_user as $index => $sub_title_user)
                                            <div class="row item mt-2">
                                                <div class="col-md-8">
                                                    <a href="javascript:void(0)" data-id="{{ $sub_title_user->id }}" data-type="2" class="tree-item">
                                                        <i class="uil uil-plus"></i> {{ str_repeat('-- ', $sub_title_user->level) . $sub_title_user->title->name }}
                                                    </a>
                                                    <span class="seniority_careers_roadmap">Thâm niên (năm): {{ $sub_title_user->seniority }}</span>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    @if(!isset($sub_titles[$index + 1]))
                                                        <a href="javascript:void(0)" class="btn btn-primary edit-roadmap-title" data-id="{{ $sub_title_user->id }}">
                                                            <i class="fa fa-edit"></i> @lang('app.edit')
                                                        </a>
                                                        <a href="javascript:void(0)" class="btn btn-danger delete-roadmap-title" data-id="{{ $sub_title_user->id }}" title="@lang('career.delete')">
                                                            <i class="fa fa-trash"></i> @lang('career.delete')
                                                        </a>
                                                    @endif
                                                    <a href="javascript:void(0)" class="btn btn-info view-career" data-id="{{ $sub_title_user->title->id }}" data-name="{{ $sub_title_user->title->name }}">
                                                        <i class="fa fa-eye"></i> @lang('career.view')
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" id="list{{ $sub_title_user->id }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add-modal"><i class="fa fa-plus-circle">
                                    </i> @lang('career.add_roadmap')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="course-modal" tabindex="-1" role="dialog" aria-labelledby="course-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title title-name" id="course-modal-label"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table bootstrap-table">
                        <thead>
                        <tr>
                            <th data-width="5%" data-formatter="index_formatter">#</th>
                            <th data-field="subject_code" data-width="10%">@lang('app.subject_code')</th>
                            <th data-field="subject_name">@lang('app.subject')</th>
                            <th data-field="title_name" data-formatter="result_formatter" class="td-title-name" data-align="center"></th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.close')</button>
                </div>
            </div>
        </div>
    </div>

    @include('careerroadmap::frontend.modal.add')

    @include('careerroadmap::frontend.modal.add_title')

    @include('careerroadmap::frontend.modal.edit_title')

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function result_formatter(value, row, index) {
            if (row.result == 1) {
                return '<i class="fa fa-check text-success"></i>';
            }

            return '<i class="fa fa-times text-danger"></i>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.career_roadmap.frontend.get_courses', [0]) }}',
        });

        $('#tree-unit').on('click', '.view-career', function () {
            let title_id = $(this).data('id');
            let title_name = $(this).data('name');
            let new_url = "/career-roadmap/get-course/" + title_id;
            table.refresh({'url': new_url});

            $('.title-name').html(title_name);
            $('.td-title-name').html('<div class="th-inner ">' + title_name + '</div><div class="fht-cell"></div>');
            $('#course-modal').modal();
        });

        var openedClass = 'uil-minus uil';
        var closedClass = 'uil uil-plus';

        $('#tree-unit').on('click', '.tree-item', function (e) {
            var id = $(this).data('id');
            var type = $(this).data('type');

            if ($(this).find('i:first').hasClass(openedClass)) {
                $('#list' + id).find('ul').remove();
            } else {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('module.career_roadmap.frontend.tree_folder.get_child') }}",
                    dataType: 'json',
                    data: {
                        id: id,
                        type: type
                    }
                }).done(function (data) {
                    let rhtml = '';
                    let rhtml_user = '';

                    rhtml += '<ul>';
                    $.each(data.childs, function (i, item) {
                        if (type == 2){
                            rhtml_user += '<a href="javascript:void(0)" class="btn btn-primary edit-roadmap-title mr-1" data-id="' + item.id + '">' +
                                '<i class="fa fa-edit"></i> {{ trans('app.edit') }}' +
                                '</a>' +
                                '<a href="javascript:void(0)" class="btn btn-danger delete-roadmap-title mr-1" data-id="' + item.id + '">' +
                                '<i class="fa fa-trash"></i> {{ trans('career.delete') }}' +
                                '</a>';
                        }

                        rhtml += '<li>';
                        rhtml += '<div class="row item mt-2">';
                        rhtml += '<div class="col-md-8">';
                        rhtml += '<a href="javascript:void(0)" data-id="' + item.id + '" data-type="'+ type +'" class="tree-item"> <i class="uil uil-plus"></i>' + item.title_name + '</a>';
                        rhtml += '<span class="seniority_careers_roadmap">Thâm niên (năm): '+ item.seniority +' </span>';
                        rhtml += '</div>';
                        rhtml += '<div class="col-md-4 text-right pr-0">';
                        rhtml += rhtml_user;
                        rhtml += '<a href="javascript:void(0)" class="btn btn-info view-career" data-id="' + item.title_id + '" data-name="' + item.title_name + '">';
                        rhtml += '<i class="fa fa-eye"></i> @lang('career.view')';
                        rhtml += '</a>';
                        rhtml += '</div>';
                        rhtml += '</div>';
                        rhtml += '<div class="row">' +
                            '<div class="col-md-12 pr-0" id="list' + item.id + '"></div>' +
                            '</div>';
                        rhtml += '</li>';
                    });
                    rhtml += '</ul>';

                    document.getElementById('list' + id).innerHTML = '';
                    document.getElementById('list' + id).innerHTML = rhtml;
                }).fail(function (data) {
                    show_message('{{ trans('lageneral.data_error ') }}', 'error');
                    return false;
                });
            }
            if (this == e.target) {
                var icon = $(this).children('i:first');
                icon.toggleClass(openedClass + " " + closedClass);
                $(this).children().children().toggle();
            }
        });
    </script>
@stop
