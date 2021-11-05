@extends('layouts.backend')

@section('page_title', 'Thông số')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Thông số</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <table class="tDefault table table-hover bootstrap-table" id="paramater">
            <thead>
                <tr>
                    <th data-field="name">Tên thông số</th>
                    <th data-field="score" data-formatter="score_formatter" data-width="10%" data-align="center">Điểm đạt</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function score_formatter(value, row, index) {
            return '<input type="text" value="'+ row.score +'" class="form-control changer-score" data-id="'+ row.id +'">'
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.parameter.getdata') }}',
        });

        $('#paramater').on('change', '.changer-score', function () {
            var id = $(this).data('id');
            var score = $(this).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('backend.parameter.changer_score') }}',
                dataType: 'json',
                data: {
                    id: id,
                    score: score
                },
            }).done(function(data) {
                return false;
            }).fail(function(data) {
                Swal.fire(
                    'Lỗi hệ thống',
                    '',
                    'error'
                );
                return false;
            });
        });
    </script>
@endsection
