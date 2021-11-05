<link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
    <script type="text/javascript">
        var career = {
            'parents_url': '{{ route('module.career_roadmap.frontend.getparents') }}',
            'remove_roadmap_url': '{{ route('module.career_roadmap.frontend.remove_roadmap') }}',
            'remove_title_url': '{{ route('module.career_roadmap.frontend.remove') }}',
            'edit_career_roadmap':'{{ route('module.career_roadmap.frontend.edit') }}',
        };
    </script>
<script src="{{ asset('modules/career_roadmap/js/backend.js') }}"></script>
<div class="tab-pane fade active show" id="nav-courses" role="tabpanel">
    <div class="sa4d25">
        <div class="row">
            <div class="col-md-12">
                <div class="_14d25">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table">
                                    <thead>
                                        <tr class="tbl-heading">
                                            <th data-align="center" data-formatter="index_formatter">STT</th>
                                            <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                                            <th data-field="course_name">Tên khóa học</th>
                                            <th data-field="course_time" data-align="center">Thời lượng khóa học</th>
                                            <th data-field="start_date">Từ ngày</th>
                                            <th data-field="end_date">Đến ngày</th>
                                            <th data-field="time_schedule">Thời gian</th>
                                            <th data-field="attendance" data-align="center">Tổng thời lượng tham gia</th>
                                            <th data-field="schedule_discipline">Buổi học vi phạm</th>
                                            <th data-field="discipline">Vi phạm</th>
                                            <th data-field="absent">Loại nghỉ</th>
                                            <th data-field="absent_reason">Lý do vắng</th>
                                            <th data-field="status_user">Trạng thái</th>
                                            <th data-field="note">Ghi chú</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

    function result_formatter(value, row, index) {
        if (row.result == 1) {
            return '<i class="fa fa-check text-success"></i>';
        }

        return '<i class="fa fa-times text-danger"></i>';
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.frontend.user.violate_rules.get_data') }}',
    });
</script>
