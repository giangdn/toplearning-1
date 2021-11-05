@extends('layouts.backend')

@section('page_title', $title->name)

@section('header')
    <script type="text/javascript">
        var career = {
            'parents_url': '{{ route('module.career_roadmap.getparents', [$title->id]) }}',
            'remove_roadmap_url': '{{ route('module.career_roadmap.title.remove_roadmap', [$title->id]) }}',
            'remove_title_url': '{{ route('module.career_roadmap.title.remove', [$title->id]) }}',
            'edit_career_roadmap':'{{ route('module.career_roadmap.title.edit',[$title->id]) }}',
        };
    </script>
    <script src="{{ asset('modules/career_roadmap/js/backend.js') }}"></script>
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
            <a href="{{ route('module.career_roadmap') }}">@lang('career.career_roadmap')</a>
            <i class="uil uil-angle-right"></i>
            <span class="">{{ $title->name }}</span>
        </h2>
    </div>
    <div role="main">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
            @php
                session()->forget('errors');
            @endphp
        @endif
        <div class="row">
            <div class="col-md-4">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.code_name_title')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{trans('backend.search')}}</button>
                </form>
            </div>
            <div class="col-md-8 text-right act-btns">
                <div class="pull-right">
                    
                    <a class="btn btn-info" href="{{ download_template('mau_import_lo_trinh_nghe_nghiep.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                    <button class="btn btn-info" id="import-roadmap" type="submit" name="task" value="import">
                        <i class="fa fa-upload"></i> {{trans('backend.import_roadmap')}}
                    </button>
                        
                    <div class="btn-group">
                        @can('career-roadmap-create')
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add-modal"><i class="fa fa-plus-circle">
                            </i> @lang('career.add_roadmap')
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        @foreach($roadmaps as $roadmap)
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th style="text-align: left" width="40%">{{ $roadmap->name }} {{$roadmap->primary==1?'(Lộ trình chính)':''}}
                    </th>
                    <th style="text-align: left">{{trans('backend.roadmap')}}</th>
                    <th style="text-align: center">Thâm niên (Năm)</th>
                    <th>
                        <span class="float-right">
                            <a href="javascript:void(0)" class="add-roadmap-title" data-id="{{ $roadmap->id }}">
                                <i class="fa fa-plus"></i> @lang('career.add_title')
                            </a>
                            <a href="javascript:void(0)" class="text-danger delete-roadmap" data-id="{{ $roadmap->id }}" title="@lang('career.delete')">
                                <i class="fa fa-trash"></i> @lang('career.delete')
                            </a>
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
            @php
            $sub_titles = $roadmap->getTitles();
            @endphp
            @foreach($sub_titles as $index => $sub_title)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ str_repeat('-- ', $sub_title->level) . $sub_title->title->name }}
                    </td>
                    <td>
                        <a href="{{route('module.trainingroadmap.detail',['id'=>$sub_title->title_id])}}">{{trans('backend.roadmap')}}</a>
                    </td>
                    <td style="text-align: center">
                        <span>{{ $sub_title->seniority }}</span>
                    </td>
                    <td>
                        <span class="float-right">
                            @if(!isset($sub_titles[$index + 1]))
                                <a href="javascript:void(0)" class="edit-roadmap-title" data-id="{{ $sub_title->id }}" data-type="1">
                                    <i class="fa fa-edit"></i> @lang('app.edit')
                                </a>
                                <a href="javascript:void(0)" class="text-danger delete-roadmap-title" data-id="{{ $sub_title->id }}" title="@lang('career.delete')">
                                    <i class="fa fa-trash"></i> @lang('career.delete')
                                </a>
                            @else
                                <a href="javascript:void(0)" class="edit-roadmap-title" data-id="{{ $sub_title->id }}" data-type="2">
                                    <i class="fa fa-edit"></i> @lang('app.edit')
                                </a>
                            @endif
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endforeach
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.career_roadmap.import',['title_id'=>$title->id]) }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('backend.import_roadmap') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="title_id" value="{{$title->id}}">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('careerroadmap::backend.title.modal.add')

    @include('careerroadmap::backend.title.modal.add_title')

    @include('careerroadmap::backend.title.modal.edit_title')
<script>
    $('#import-roadmap').on('click', function() {
        $('#modal-import').modal();
    });
</script>
@endsection
