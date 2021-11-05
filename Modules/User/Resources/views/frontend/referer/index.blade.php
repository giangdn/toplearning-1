<div class="sa4d25">
    <div class="container-fluid">
        <form method="post" action="{{route('frontend.user.referer.save')}}" class="form-horizontal form-ajax">
            <div class="row form-group">
                <div class="col-md-3 text-right">
                    <label>@lang('app.presenter_code')</label>
                </div>
                <div class="col-md-4">
                    <input type="text" name="referer" {{$referer?'disabled':''}} value="{{$referer}}" class="form-control">
                    @if(!$referer)
                    <a href="javascript:void(0)" id="referer_modal" class="load-modal" data-url="{{ route('frontend.user.referer.show_modal' ) }}"><img src="{{asset('images/qr-code.svg')}}" width="30px" /> Quét mã người giới thiệu</a>
                    @endif
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3 text-right">
                    <label> </label>
                </div>
                <div class="col-md-4">
                    <button type="submit" {{$referer?'disabled':''}} class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> @lang('app.save') </button>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class ="col-md-12">
            <h4>@lang('app.presenter')</h4>
            <table class="tDefault table table-bordered bootstrap-table">
                <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-width="30px;">STT</th>
                    <th data-field="name_referer" >@lang('app.presenter')</th>
                    <th data-field="created_at" >@lang('app.date_created')</th>
                    <th data-field="point" data-formatter="point_formatter">@lang('app.score')</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    function point_formatter(value, row, index) {
        return '+'+value;
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('frontend.user.referer.getRefererHist') }}',
        locale: '{{ App::getLocale() }}',
    });

</script>
