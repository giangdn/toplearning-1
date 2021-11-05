<ul>
    @foreach($childs as $key => $item)
        <div class="ml-2 mb-2">
            @if($key==0)
                {{ $level_subject_arr[$item->level_subject] }}
            @endif
            @if($key > 0 && $childs[$key]->level_subject != $childs[$key-1]->level_subject)
                {{ $level_subject_arr[$item->level_subject] }}
            @endif
        </div>
        <li>
            <div class="item">
                <div class="subject-item">
                    @if($item->has_course)
                        <a href="javascript:void(0)" class="load-modal" data-url="{{ route('module.frontend.user.show_modal_roadmap', [$item->subject_id] ) }}">
                            {{ '('. $item->subject_code. ') '. $item->subject_name }} - Thời lượng: {{ $item->num_time }}
                        </a>
                    @else
                        <a href="javascript:void(0)" data-subject_id="{{ $item->subject_id }}" class="btnRegisterSubject">
                            {{ '('. $item->subject_code. ') '. $item->subject_name }} - Thời lượng: {{ $item->num_time }}
                        </a>
                    @endif
                    {{ ' ('. $item->start_date .' - '. $item->end_date .')' }}
                    <span class="ml-1">
                        @if($item->percent_subject > 0)
                            @if($item->percent_subject == 100)
                                <i class="fa fa-check text-success"></i>
                            @else
                                <i class="fa fa-times text-danger"></i>
                            @endif
                        @endif
                    </span>
                </div>
                <div class="progress progress2">
                    <div class="progress-bar" style="width: {{ $item->percent_subject }}%" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        @if($item->percent_subject > 0) {{ number_format($item->percent_subject, 2) }}% @endif
                    </div>
                </div>
            </div>
        </li>
    @endforeach
</ul>

<script>
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
