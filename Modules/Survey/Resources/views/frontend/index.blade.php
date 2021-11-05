@extends('layouts.app')

@section('page_title', __('app.survey'))

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title"><i class="uil uil-apps"></i><span class="font-weight-bold">@lang('app.survey')</span></h2>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="_14d25">
                                    <div class="row m-0" id="results"></div>
                                    <div class="ajax-loading text-center mb-5">
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var page = 1; 
        load_more(page); 
        $(window).scroll(function() { 
            if($(window).scrollTop() + $(window).height() >= $(document).height()) { 
                page++; 
                load_more(page);   
            }
        });     
        function load_more(page){
            $.ajax({
                url: '{{ route('module.survey') }}' + "?page=" + page,
                type: "get",
                datatype: "html",
                beforeSend: function() 
                {
                    $('.ajax-loading').show();
                }
                })
                .done(function(data)
                {
                    if(data.length == 0){
                    console.log(data.length);
                    $('.ajax-loading').html("No more records!");
                    return;
                }
                $('.ajax-loading').hide(); 
                $("#results").append(data);        
                console.log('data.length');
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                alert('No response from server');
            });
        }
    </script>
@stop
