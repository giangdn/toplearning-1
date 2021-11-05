
@php
    $file = @file_get_contents("https://www.vietabank.com.vn/tien-ich/ty-gia/ty-gia-ngoai-te.html");
    if($file === false ) {
        $exchange_rate_descriptions_td = '';
    } else {
        include('../app/Helpers/simple_html_dom.php');
        $url = 'https://www.vietabank.com.vn/tien-ich/ty-gia/ty-gia-ngoai-te.html';
        $html = file_get_html($url);
        $exchange_rate_descriptions_td = $html->find('#dataCurrency',0)->find('tr');
        $infomation_exchange_rate = $html->find('.text-blue',0);
    }
@endphp
@if (!empty($exchange_rate_descriptions_td))
    <div class="row exchange_rate mb-2 pt-2">
        <div class="col-12 mb-1">
            <h5 class="mb-2"><span>Tỷ giá</span></h5>
            
            {!! $infomation_exchange_rate !!}
        </div>
        <div class="col-12 get_table_exchange_rate mt-2">
            <table class="table table-striped" id="table_exchange_rate">
                <tbody>
                    @foreach ($exchange_rate_descriptions_td as $exchange_rate_description_td)
                        {!! $exchange_rate_description_td !!}
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-12 mt-2">
            <p class="mb-1 source_link">Nguồn: <a href="https://vietabank.com.vn/"> vietabank.com.vn</a></p>
        </div>
    </div>
@endif
<div class="advertiment">
    <div class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            @foreach($getAdvertisingPhotos as $key => $getAdvertisingPhoto)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <a href="{{ $getAdvertisingPhoto->url }}">
                        <img src="{{ image_file($getAdvertisingPhoto->image) }}" alt="" class="w-100" />
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
    
