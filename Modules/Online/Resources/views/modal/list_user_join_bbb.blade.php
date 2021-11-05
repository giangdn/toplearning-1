<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('backend.student_access_history') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="tDefault table table-hover table-bordered">
                    <thead>
                        <th>{{ trans('backend.student_name') }}</th>
                        <th>{{ trans('backend.virtual_classroom_name') }}</th>
                        <th>{{ trans('backend.hold_time') }}</th>
                        <th>{{ trans('backend.access_number') }}</th>
                    </thead>
                    <tbody>
                    @if ($get_user_joined)
                        @foreach ($get_user_joined as $item)
                            <tr>
                                <td>{{ $item->lastname . ' ' . $item->firstname . ' - ' . $item->user_code }}</td>
                                <td>
                                    {{ $item->course_name .' ('. $item->course_code .')' }} <br>
                                    {{ '+ '. $item->bbb_name .' - '. $item->bbb_code }}
                                </td>
                                <td>
                                    {{ get_date($item->start_date, 'H:i d/m/Y') }} <br>
                                    {{ get_date($item->end_date, 'H:i d/m/Y') }}
                                </td>
                                <td>{{ get_date($item->time_join, 'H:i d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

