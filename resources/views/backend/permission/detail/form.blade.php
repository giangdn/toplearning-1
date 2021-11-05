@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.permission.list_permisstion') }}">Danh sách quyền</a> / <a href="{{ route('backend.permission.detail', ['permission_id' => $permission->id]) }}">{{ $permission->name }}</a> / {{ $page_title }}
        </h2>
    </div>
@endsection

@section('content')
<div role="main">
    <form method="post" action="{{ route('backend.permission.detail.save', ['permission_id' => $permission->id]) }}" class="form-validate form-ajax " role="form" enctype="multipart/form-data">


        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>

                    <a href="{{ route('backend.permission.detail', ['permission_id' => $permission->id]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="clear"></div>

        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="user_id">Người dùng <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-sm-7">
                                    <select @if(!isset($profile)) name="user_id[]" @endif id="user_id" class="form-control load-user" data-placeholder="-- Người dùng --" required @if(isset($profile)) disabled @else multiple @endif>
                                        @if(isset($profile))
                                            <option value="{{ $profile->user_id }}" selected>{{ $profile->lastname .' '. $profile->firstname }}</option>
                                        @endif
                                    </select>
                                </div>
                                @if(isset($profile))
                                    <input type="hidden" name="user_id" value="{{ $profile->user_id }}">
                                @endif
                            </div>

                            @if ($permission->unit_permission == 1)
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="unit_id">{{ trans('backend.unit') }}</label>
                                </div>
                                <div class="col-sm-7">
                                    <select @if(!isset($profile)) name="unit_id" @else disabled @endif id="unit_id" class="form-control load-unit" data-placeholder="-- {{ trans('backend.unit') }} --">
                                        @if(isset($unit))
                                            <option value="{{ $unit->id }}" selected>{{ $unit->name }}</option>
                                        @endif
                                    </select>
                                </div>

                                @if(isset($unit))
                                    <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                                @endif
                            </div>
                            @endif

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="user_id">Quyền<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-sm-7">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="60%">Tên quyền</th>
                                            <th class="text-center">Cho phép (<a href="javascript:void(0)" id="all-enable">Tất cả</a>)</th>
                                            <th class="text-center">Không cho phép (<a href="javascript:void(0)" id="all-disable">Tất cả</a>)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $index = 1;
                                        @endphp
                                        @foreach($permission_child as $item)
                                            @php
                                            $unit_id = isset($unit->id) ? $unit->id : 0;
                                            $check = isset($profile) ? $haspermission($item->code, $profile->user_id, $unit_id) : false;
                                            @endphp
                                            <tr>
                                                <td>{{ $index }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td align="center"><input type="radio" name="permission_{{ $item->id }}" data-id="{{ $item->id }}" class="permission-enable {{ $item->parent_id ? 'child-' . $item->parent_id : '' }}" value="1" {{ isset($profile) ? ($check ? 'checked' : '') : '' }}></td>
                                                <td align="center"><input type="radio" name="permission_{{ $item->id }}" data-id="{{ $item->id }}" class="permission-disable {{ $item->parent_id ? 'child-' . $item->parent_id : '' }}" value="0" {{ isset($profile) ? ($check ? '' : 'checked') : 'checked' }}></td>
                                            </tr>
                                            @php
                                            $index++;
                                            @endphp
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $("#all-enable").on('click', function () {
        $(".permission-disable").prop('checked', false);
        $(".permission-enable").prop('checked', true);
    });

    $("#all-disable").on('click', function () {
        $(".permission-enable").prop('checked', false);
        $(".permission-disable").prop('checked', true);
    });

    $(".permission-enable, .permission-disable").on('change', function () {
        let id = $(this).data('id');
        let val = $(this).val();

        if (val == 1) {
            $(".permission-disable.child-"+id).prop('checked', false);
            $(".permission-enable.child-"+id).prop('checked', true);
        }
        else {
            $(".permission-disable.child-"+id).prop('checked', true);
            $(".permission-enable.child-"+id).prop('checked', false);
        }
    });
</script>
@stop
