<div class="mt-10 mb-40" id="training-by-title">
    {{-- <ul>
        <li class="mb-2">
            <img src="{{ asset('images/user_tt.png') }}" alt="" class="img-responsive img-info">
            Thông tin của bạn: <span class="font-weight-bold text-uppercase"> {{ $user->lastname." ".$user->firstname }} </span>
        </li>
        <li class="mb-2">
            <img src="{{ asset('images/user_tt.png') }}" alt="" class="img-responsive img-info">
            Chức danh: <span class="font-weight-bold"> {{ @$title->name }} </span>
        </li>
        <li class="mb-2">
            <img src="{{ asset('images/user_tt.png') }}" alt="" class="img-responsive img-info">
            MSNV: <span class="font-weight-bold"> {{ $user->code }} </span>
        </li>
        <li class="mb-2">
            <img src="{{ asset('images/user_tt.png') }}" alt="" class="img-responsive img-info">
            Ngày vào làm: <span class="font-weight-bold"> {{ get_date($user->join_company) }} </span>
        </li>
    </ul> --}}

    {{-- <div class="pt-2 pb-2">
        <h5 class="font-weight-bold">Thống kê quá trình hoàn thành của Bạn</h5>
    </div>
    @php
        $percent = ($count_subject_completed / ($count_training_by_title_detail > 0 ? $count_training_by_title_detail : 1))*100;
    @endphp
    <div class="mt-5 mb-3" style="position: relative;">
        <div class="progress progress2">
            <div class="progress-bar" style="width: {{ $percent }}%" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                @if($percent > 0) {{ number_format($percent, 2) }}% @endif
            </div>
        </div>
        <img src="{{ asset('images/user_woman.png') }}" alt="" class="img-responsive img-percent" style="position: absolute; top: -43px; left: {{ $percent - ($percent == 0 ? 1 : 2) }}%">
    </div> --}}

    <div id="tree-unit" class="tree mt-5">
        @php
            $old_date = '';
        @endphp
        @foreach($training_by_title_category as $key => $item)
            <div class="item mb-2">
                <i class="uil uil-plus"></i>
                @if ($key == 0)
                    @php
                        $old_date =\Carbon\Carbon::parse($start_date)->addDays($item->num_date_category + 1);
                    @endphp
                    <a href="javascript:void(0)" data-id="{{ $item->id }}" data-route="{{ route('module.frontend.user.training_by_title.tree_folder.get_child', ['id' => $item->id,'start_date' => $start_date]) }}" class="tree-item">
                        <strong>{{ mb_strtoupper($item->name, 'UTF-8') }}</strong> ({{ $count_subject_completed . '/'. $item->trainingtitledetail->count() }})
                    </a> 
                    <span style="color: #4183c4">
                        <strong>( {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($start_date)->addDays($item->num_date_category)->format('d/m/Y')  }} )</strong>
                    </span>
                @else
                    @php
                        $start_date = \Carbon\Carbon::parse($old_date)->format('Y-m-d');
                        $old_value_format = \Carbon\Carbon::parse($old_date)->format('d/m/Y');
                        $end_date = \Carbon\Carbon::parse($old_date)->addDays($item->num_date_category);
                        $old_date = \Carbon\Carbon::parse($old_date)->addDays($item->num_date_category + 1);
                    @endphp
                    <a href="javascript:void(0)" data-id="{{ $item->id }}" data-route="{{ route('module.frontend.user.training_by_title.tree_folder.get_child', ['id' => $item->id,'start_date' => $start_date]) }}" class="tree-item">
                        <strong>{{ mb_strtoupper($item->name, 'UTF-8') }}</strong> ({{$count_subject_completed . '/'. $item->trainingtitledetail->count()}})
                    </a> 
                    <span style="color: #4183c4">
                        <strong>( {{ $old_value_format }} - {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y')  }} )</strong>
                    </span> 
                @endif
            </div>
            <div id="list{{ $item->id }}"></div>
        @endforeach
    </div>

</div>
<script type="text/javascript">
    $(function () {
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
                cancelButtonText: 'Không đồng ý!',
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
    });

    var openedClass = 'uil-minus uil';
    var closedClass = 'uil uil-plus';

    $('#tree-unit').on('click', '.tree-item', function (e) {
        var id = $(this).data('id');
        var child_url = $(this).data('route');

        if ($(this).closest('.item').find('i:first').hasClass(openedClass)){
            $('#list'+id).find('ul').remove();
        }else{
            $('#list'+id).load(child_url);
        }

        if (this == e.target) {
            var icon = $(this).closest('.item').children('i:first');
            icon.toggleClass(openedClass + " " + closedClass);
            $(this).children().children().toggle();
        }
    });
</script>
