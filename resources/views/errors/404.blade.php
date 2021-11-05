@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="cmtk_group">
                    <div class="ct-logo">
                        <a href="/"><img src="{{ image_file(\App\Config::getLogo()) }}" alt=""></a>
                    </div>
                    <div class="cmtk_dt">
                        <h1 class="title_404">404</h1>
                        <h4 class="thnk_title1">The page you were looking for could not be found.</h4>
                        <a href="/" class="bk_btn">Go To Homepage</a>
                    </div>
                    <div class="tc_footer_main">
                        <div class="tc_footer_left">
                            <ul>
                                <li><a href="">About</a></li>
                                <li><a href="">Press</a></li>
                                <li><a href="">Contact Us</a></li>
                                <li><a href="">Advertise</a></li>
                                <li><a href="">Developers</a></li>
                                <li><a href="">Copyright</a></li>
                                <li><a href="">Privacy Policy</a></li>
                                <li><a href="">Terms</a></li>
                            </ul>
                        </div>
                        <div class="tc_footer_right">
                            <p>© 2020 <strong>Cursus</strong>. All Rights Reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
