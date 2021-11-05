@extends('layouts.app')

@section('page_title', 'Hướng dẫn')

@section('content')
<div class="sa4d25">
    <div class="container-fluid guide-container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="ibox-content guide-container">
                    <h2 class="st_title"><i class="uil uil-apps"></i><span class="font-weight-bold">{{ trans('app.user_manual') }}</span></h2>
                    <br>
                    <table class="tDefault table table-hover table-bordered">
                        <thead>
                        <th class="text-center">{{ trans('app.guide') }}</th>
                        <th class="text-center">{{ trans('app.download') }}</th>
                        <th class="text-center">{{ trans('app.watch_online') }}</th>
                        </thead>
                        <tbody>
                        @if ($guides)
                            @foreach ($guides as $guide)
                                <tr>
                                    <td>{{ $guide->name }}</td>
                                    <td style="text-align: center;"><a href="{{ link_download('uploads/'.$guide->attach) }}" target="_blank"><i class="fa fa-download"></i></a></td>
                                    <td style="text-align: center;"><a href="{{ upload_file($guide->attach) }}" target="_blank"><i class="fa fa-eye"></i></a></td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
