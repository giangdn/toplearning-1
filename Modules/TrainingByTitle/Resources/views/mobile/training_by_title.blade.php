@extends('themes.mobile.layouts.app')

@section('page_title', 'Lộ trình đào tạo')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 px-0">
                <div class="swiper-container career-slide">
                    <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                        @php
                            $arr_start_date = [];
                        @endphp
                        @foreach($training_by_title_category as $key => $item)
                            @php
                                if ($key == 0){
                                    $old_date =\Carbon\Carbon::parse($start_date)->addDays($item->num_date_category + 1);

                                    $arr_start_date[$key] = $start_date;
                                }
                                else{
                                    $start_date = \Carbon\Carbon::parse($old_date)->format('Y-m-d');
                                    $old_value_format = \Carbon\Carbon::parse($old_date)->format('d/m/Y');
                                    $end_date = \Carbon\Carbon::parse($old_date)->addDays($item->num_date_category);
                                    $old_date = \Carbon\Carbon::parse($old_date)->addDays($item->num_date_category + 1);

                                    $arr_start_date[$key] = $start_date;
                                }
                            @endphp
                            <a class="swiper-slide nav-item nav-link {{ $key == 0 ? 'active' : '' }} pl-0 pr-0" id="nav-{{$item->id}}-tab" data-toggle="tab" href="#nav-{{$item->id}}" role="tab" aria-selected="true">
                                <img src="{{ asset('themes/mobile/img/crown.png') }}" alt="" class="avatar-20 shadow"> <br>
                                {{ $item->name }} <br>

                                <span>
                                    <strong>
                                         @if ($key == 0)
                                            {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') .' - '. \Carbon\Carbon::parse($start_date)->addDays($item->num_date_category)->format('d/m/Y')  }}
                                        @else
                                            {{ $old_value_format }} - {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                                        @endif
                                    </strong>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-12 px-0">
                <div class="career_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        @foreach($training_by_title_category as $key => $item)
                            @php
                                $childs = $item->getChildTrainingByTitleCategory($arr_start_date[$key]);
                            @endphp

                        <div class="tab-pane fade {{ $key == 0 ? 'active show' : '' }}" id="nav-{{$item->id}}" role="tabpanel">
                            <div class="list-group list-group-flush">
                                @foreach($childs as $key => $child)
                                    @if($child->has_course)
                                        <a href="javascript:void(0)" class="load-modal text-primary" data-url="{{ route('module.frontend.user.show_modal_roadmap', [$child->subject_id] ) }}">
                                            {{ '('. $child->subject_code. ') '. $child->subject_name }}
                                        </a>
                                    @else
                                        <a href="javascript:void(0)" data-subject_id="{{ $child->subject_id }}" class="btnRegisterSubject text-primary">
                                            {{ '('. $child->subject_code. ') '. $child->subject_name }}
                                        </a>
                                    @endif
                                    Thời lượng: {{ $child->num_time }}
                                    <br>
                                    {{ ' ('. $child->start_date .' - '. $child->end_date .')' }}

                                    <div class="progress progress2">
                                        <div class="progress-bar" style="width: {{ $child->percent_subject }}%" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                            @if($child->percent_subject > 0) {{ number_format($child->percent_subject, 2) }}% @endif
                                        </div>
                                    </div>
                                    <p></p>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#nav-tab').on('click', '.nav-item', function () {
            $('a[data-toggle="tab"]').removeClass('active');
        });

        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab-training-by-title', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab-training-by-title');
            if(activeTab){
                $('a[data-toggle="tab"]').removeClass('active');
                $('#nav-tab a[href="' + activeTab + '"]').tab('show');
                $('#nav-tab a[href="' + activeTab + '"]').addClass('active');
            }
        });

        var swiper = new Swiper('.career-slide', {
            slidesPerView: 3,
            spaceBetween: 0,
            breakpoints: {
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 0,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 0,
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                },
                320: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                }
            }
        });

        $(document).on('click','.btnRegisterSubject',function (e) {
            e.preventDefault();
            Swal.fire({
                title: '',
                text: 'Chuyên đề này chưa có khóa học, bạn có muốn đăng ký tham gia chuyên đề này không ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý!',
                cancelButtonText: 'Hủy!',
            }).then((result) => {
                if (result.value) {
                    let data = {};
                    data.subject_id = $(this).data('subject_id');
                    let item = $(this);
                    let oldtext = item.html();
                    item.attr('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> Đang chờ');
                    $.ajax({
                        type: 'PUT',
                        url: '{{ route('module.frontend.user.roadmap.register') }}',
                        dataType: 'json',
                        data
                    }).done(function(data) {
                        item.attr('disabled',false).html(oldtext);
                        show_message(data.message,data.status);
                    }).fail(function(data) {
                        item.attr('disabled',false).html(oldtext);
                        show_message('{{ trans('lageneral.data_error ') }}','error');
                        return false;
                    });
                }
            });

        });
    </script>
@endsection
