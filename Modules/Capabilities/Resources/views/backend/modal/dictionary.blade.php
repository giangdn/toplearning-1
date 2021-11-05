<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title">{{ $capabilities->name }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="tDefault table tab-content">
                    <thead class="text-center">
                        <tr>
                            <td width="15%">Tiêu thức đo lường</td>
                            <td width="15%">Cấp 1</td>
                            <td width="15%">Cấp 2</td>
                            <td width="15%">Cấp 3</td>
                            <td width="15%">Cấp 4</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mức độ áp dụng</td>
                            <td>{{ $dictionary ? $dictionary->basic_apply : '' }}</td>
                            <td>{{ $dictionary ? $dictionary->medium_apply : '' }}</td>
                            <td>{{ $dictionary ? $dictionary->advanced_apply : '' }}</td>
                            <td>{{ $dictionary ? $dictionary->profession_apply : '' }}</td>
                        </tr>
                        <tr>
                            <td>Mức độ phức tạp</td>
                            <td>{{ $dictionary ? $dictionary->basic_complex : '' }}</td>
                            <td>{{ $dictionary ? $dictionary->medium_complex : '' }}</td>
                            <td>{{ $dictionary ? $dictionary->advanced_complex : '' }}</td>
                            <td>{{ $dictionary ? $dictionary->profession_complex : '' }}</td>
                        </tr>
                        <tr>
                            <td>Phạm vi ảnh hưởng</td>
                            <td>{{ $dictionary ? $dictionary->basic_affect : '' }}</td>
                            <td>{{ $dictionary ? $dictionary->medium_affect : '' }}</td>
                            <td>{{ $dictionary ? $dictionary->advanced_affect : '' }}</td>
                            <td>{{ $dictionary ? $dictionary->advanced_affect : '' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
