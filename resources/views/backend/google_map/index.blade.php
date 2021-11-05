@extends('layouts.backend')

@section('page_title', 'Địa điểm đào tạo')

@section('header')
    <script src='{{ asset('js/mapbox-gl.js') }}'></script>
    <link href='{{ asset('css/mapbox-gl.css') }}' rel='stylesheet' />
@endsection

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Địa điểm dào tào</span>
        </h2>
    </div>
@endsection

@section('content')
    <style>
        #map {
            width: 100%;
            height: 500px;
        }
        .marker {
            background-image: url('/images/map.png');
            background-repeat:no-repeat;
            background-size:100%;
            width: 50px;
            height: 100px;
            cursor: pointer;
        }
        #info {
            display: table;
            position: absolute;
            margin: 0px 5px;
            word-wrap: anywhere;
            white-space: pre-wrap;
            padding: 10px;
            border: none;
            border-radius: 3px;
            font-size: 12px;
            text-align: center;
            color: #222;
            background: #fff;
            top: 10px
        }
    </style>

    <div class="row mb-3">
        <div class="col-md-5">
            <h2>Google Map</h2>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('backend.google.map.store')}}" method="post" id="boxmap">
                @csrf
                @can('guide-create')
                    <div class="form-group">
                        <label for="title">Tiêu đề</label>
                        <input type="text" name="title" placeholder="Tiêu đề" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="title">Mô tả</label>
                        <input type="text" name="description" placeholder="Mô tả" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="lat">Vĩ độ</label>
                        <input type="text" name="lat" id="lat" placeholder="Vĩ độ" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="lng">Kinh độ</label>
                        <input type="text" name="lng" id="lng" placeholder="Kinh độ" class="form-control"/>
                    </div>
                    <div>
                        <p class="mb-2" style="color: red">(Nhấp vào bản đồ để lấy Vĩ độ, Kinh độ)</p>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" value="Thêm địa điểm" class="btn btn-success"/>
                        <a class="ml-2 btn" href="{{ route('backend.google.map.list') }}">Danh sách địa điểm</a>
                    </div>
                @endcan
            </form>
        </div>
        <div class="col-md-7">
            <div id="map"></div>      
            <pre id="info"></pre>
        </div>
    </div>
    <script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoic2tpcHBlcmhvYSIsImEiOiJjazE2MjNqMjkxMTljM2luejl0aGRyOTAxIn0.Wyvywisw6bsheh7wJZcq3Q';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [106.6860013, 10.762444], //lng,lat 10.818746, 106.629179
            zoom: 12
        });
        var center = map.getCenter();
        var test ='<?php echo $dataArray;?>';  //ta nhận dữ liệu từ Controller
        var dataMap = JSON.parse(test); //chuyển đổi nó về dạng mà Mapbox yêu cầu

        // ta tạo dòng lặp để for ra các đối tượng
        dataMap.features.forEach(function(marker) {

            //tạo thẻ div có class là market, để hồi chỉnh css cho market
            var el = document.createElement('div');
            el.className = 'marker';

            //gắn marker đó tại vị trí tọa độ
            new mapboxgl.Marker(el)
                .setLngLat(marker.geometry.coordinates)
                .setPopup(new mapboxgl.Popup({ offset: 25 }) // add popups
                .setHTML('<h3>' + marker.properties.title + '</h3><p>' + marker.properties.description + '</p>'))
                .addTo(map);
        });
        map.on('mouseup', (e) => {
            document.getElementById('info').innerHTML = `<span>Vĩ đỗ: `+ e.lngLat.lat +`, Kinh độ: `+ e.lngLat.lng +`</span>`
            $('#lat').val(e.lngLat.lat);
            $('#lng').val(e.lngLat.lng);
        });
        map.addControl(new mapboxgl.FullscreenControl());
    </script>
@stop
