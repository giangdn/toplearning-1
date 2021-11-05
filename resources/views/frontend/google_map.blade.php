@extends('layouts.app')

@section('page_title', 'Địa điểm đào tạo')

@section('header')
    <script src='{{ asset('js/mapbox-gl.js') }}'></script>
    <link href='{{ asset('css/mapbox-gl.css') }}' rel='stylesheet' />
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
    </style>
    <div class="container-fluid guide-container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="ibox-content guide-container">
                    <h2 class="st_title"><i class="uil uil-apps">
                        </i><span class="font-weight-bold">Địa điểm dào tạo</span>
                    </h2>
                    <br>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <h3 class="name_locals">Địa điểm đào tạo</h3>
                            <div class="all_locals">
                                @if ($boxmaps)
                                    @foreach ($boxmaps as $boxmap)
                                        <div class="local" onclick="jumpTo( {{$boxmap->lat}},{{$boxmap->lng}} )">
                                            <div class="wrapped p-2">
                                                <span class="font-weight-bold my-2 local_title">{{ $boxmap->title }}</span>  
                                                <div class="local_description">{!! $boxmap->description !!}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div id="map"></div>      
                        </div>
                    </div>
                    <div class="paginate_guide">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoic2tpcHBlcmhvYSIsImEiOiJjazE2MjNqMjkxMTljM2luejl0aGRyOTAxIn0.Wyvywisw6bsheh7wJZcq3Q';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [106.660172, 10.762622], //lng,lat 10.818746, 106.629179
            zoom: 12
        });
        var test ='<?php echo $dataArray;?>';  //ta nhận dữ liệu từ Controller
        var dataMap = JSON.parse(test); //chuyển đổi nó về dạng mà Mapbox yêu cầu
        var center = map.getCenter();
        
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
        function jumpTo(lat,lng) {
            map.jumpTo({
                center: [lng, lat],
                zoom: 15,
                // pitch: 45,
                // bearing: 90
            });
        }
        map.addControl(new mapboxgl.FullscreenControl());
    </script>
@stop
