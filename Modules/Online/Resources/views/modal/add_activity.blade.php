<div class="modal fade modal-add-activity" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{trans('backend.activiti')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 block-left">
                        @foreach($activities as $index => $activity)
                        <div class="option">
                            <input type="radio" name="activity" id="item-{{ $activity->code }}" class="select-activity" value="{{ $activity->code }}" {{ $index == 0 ? 'checked' : '' }}>
                            <label for="item-{{ $activity->code }}">
                                <span class="modicon">
                                    <img class="icon icon" src="{{ $activity->icon }}">
                                </span>
                                <span class="typename">{{ $activity->name }}</span>
                                <span class="typesummary d-none">{!! $activity->description !!}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="col-md-7">
                        <div id="activity-description"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="add-activity"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                <button type="button" id="closed" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#activity-description").html($(".option:eq(0) .typesummary").html());

    $(".select-activity").on('change', function () {
        let desc = $(this).closest('.option').find('.typesummary').html();
        $("#activity-description").html(desc);
    });

    $("#add-activity").on('click', function () {
        let item = $(this);
        let icon = item.find('i').attr('class');

        item.find('i').attr('class', 'fa fa-spinner fa-spin');
        item.prop("disabled", true);
        item.addClass('disabled');

        let activity = $(".select-activity:checked").val();
        if (!activity) {
            show_message('Vui lòng chọn hoạt động', 'error');
            item.find('i').attr('class', icon);
            item.prop("disabled", false);
            item.removeClass('disabled');
            return false;
        }

        $("#app-modal #myModal").modal('hide');

        $.ajax({
            type: 'POST',
            url: '{{ route('module.online.modal_activity', ['id' => $course->id]) }}',
            dataType: 'html',
            data: {
                'activity': activity
            }
        }).done(function(data) {
            $("#app-modal").html(data);
            $("#app-modal #myModal").modal();

            return false;
        }).fail(function(data) {
            show_message('{{ trans('lageneral.data_error ') }}', 'error');
            return false;
        });

    });

    $('#closed').on('click', function () {
        window.location = '';
    })
</script>