@extends('layouts.app')

@section('page_title', 'Bảng điểu khiển')

@section('breadcrumb')
    <li class="breadcrumb-item"><span tabindex="0">Bảng điểu khiển</span></li>
@endsection

@section('content')
    <style type="text/css">
        .table{
            font-size: 13px;
            margin-bottom: 10px;
            width: 100%;
        }
        .table-responsive{
            width: 100%;
            margin-bottom: 15px;
            overflow-x: hidden;
            overflow-y: auto;
        }
        .table-responsive .class-khm {
            background: #3c4d5c;
            color: white;
            padding: 3px;
            font-size: 1em;
            margin-bottom: 0;
        }
        .bold {
            font-weight: bold;
        }

        table tbody .custom-center{
            text-align: center;
        }

        table>thead {
            text-align: center;
            font-weight: bold;
        }

    </style>
    <div class="container-fluid">
        <ol class="breadcrumb" style="background: white;margin-bottom: 0;">
            <li><a href="/"><i class="glyphicon glyphicon-home"></i> &nbsp;Trang chủ</a></li>
            <li style="padding-left: 5px; color: #717171; padding-right:5px; font-weight: 700;"> &raquo; </li>
            <li><span>Bảng điểu khiển</span></li>
        </ol>

        <div class="row">
            <div class="col-md-6">
                <h5 class="text-center bold"> KHÓA HỌC OFFLINE</h5>
                <div class="table-responsive">
                    <div class="class-khm"> Khóa học chưa đăng ký</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>{{ trans('backend.course_code') }}</td>
                                <td>{{ trans('backend.course_name') }}</td>
                                <td>{{trans('backend.time')}}</td>
                                <td>Hạn đăng ký</td>
                                <td>Xem thêm</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($online_object as $object)
                                <tr>
                                    <td>{{ $object->code }}</td>
                                    <td>{{ $object->name }}</td>
                                    <td>{{ get_date($object->start_date) }} <i class="fa fa-long-arrow-right"></i> {{ get_date($object->end_date) }}</td>
                                    <td>{{ get_date($object->register_deadline) }}</td>
                                    <td class="custom-center"><a href="{{ route('module.online.detail_online', ['id' => $object->id]) }}">Chi tiết</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $online_object->links() }}
                </div>
                <p></p>
                <div class="table-responsive">
                    <div class="class-khm"> Khóa học đã đăng ký</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>{{ trans('backend.course_code') }}</td>
                                <td>{{ trans('backend.course_name') }}</td>
                                <td>{{trans('backend.time')}}</td>
                                <td>Hạn đăng ký</td>
                                <td>Xem thêm</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($online_register as $register)
                            <tr>
                                <td>{{ $register->code }}</td>
                                <td>{{ $register->name }}</td>
                                <td>{{ get_date($register->start_date) }} <i class="fa fa-long-arrow-right"></i> {{ get_date($register->end_date) }}</td>
                                <td>{{ get_date($register->register_deadline) }}</td>
                                <td class="custom-center"><a href="{{ route('module.online.detail_online', ['id' => $register->id]) }}">Chi tiết</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $online_register->links() }}
                </div>
            </div>
            <p></p>
            <div class="col-md-6">
                <h5 class="text-center bold"> {{ trans('backend.offline_course') }}</h5>
                <div class="table-responsive">
                    <div class="class-khm"> Khóa học chưa đăng ký</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>{{ trans('backend.course_code') }}</td>
                                <td>{{ trans('backend.course_name') }}</td>
                                <td>{{trans('backend.time')}}</td>
                                <td>Hạn đăng ký</td>
                                <td>Xem thêm</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offline_object as $object)
                                <tr>
                                    <td>{{ $object->code }}</td>
                                    <td>{{ $object->name }}</td>
                                    <td>{{ get_date($object->start_date) }} <i class="fa fa-long-arrow-right"></i> {{ get_date($object->end_date) }}</td>
                                    <td>{{ get_date($object->register_deadline) }}</td>
                                    <td class="custom-center"><a href="{{ route('module.offline.detail', ['id' => $object->id]) }}">Chi tiết</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $offline_object->links() }}
                </div>
                <p></p>
                <div class="table-responsive">
                    <div class="class-khm"> Khóa học đã đăng ký</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>{{ trans('backend.course_code') }}</td>
                                <td>{{ trans('backend.course_name') }}</td>
                                <td>{{trans('backend.time')}}</td>
                                <td>Hạn đăng ký</td>
                                <td>Xem thêm</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($offline_register as $register)
                            <tr>
                                <td>{{ $register->code }}</td>
                                <td>{{ $register->name }}</td>
                                <td>{{ get_date($register->start_date) }} <i class="fa fa-long-arrow-right"></i> {{ get_date($register->end_date) }}</td>
                                <td>{{ get_date($register->register_deadline) }}</td>
                                <td class="custom-center"><a href="{{ route('module.offline.detail', ['id' => $register->id]) }}">Chi tiết</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $offline_register->links() }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <h5 class="bold class-khm"> <i class="fa fa-bell"></i> Thông báo</h5>
                    <table class="table" id="notify">
                        <thead>
                            <tr>
                                <td>{{trans('backend.titles')}}</td>
                                <td>{{trans('backend.time')}}</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($messages as $message)
                            @php
                                if ($message->type == 2){
                                    $notify = \Modules\Notify\Entities\NotifySend::find($message->id);
                                }else{
                                    $notify = \Modules\Notify\Entities\Notify::find($message->id);
                                }
                            @endphp
                            <tr>
                                <td><a href="{{ route('module.notify.goto', ['url_encode' => \Crypt::encryptString($message->url)]) }}"
                                       target="_blank" class="view" data-id="{{ $message->id .'_'. $message->type }}" style="{{ ($notify->viewed == 1) ? 'color: #000' : '' }}">{{ $message->subject }}</a></td>
                                <td class="custom-center">{{ get_date($message->created_at) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('#notify').on('click', '.view', function () {
            var id = $(this).data('id');

            $.ajax({
                url: "{{ route('module.notify.view') }}",
                type: 'post',
                data: {
                    id: id,
                },
            }).done(function(data) {
                window.location = '';
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });
    </script>
@endsection

