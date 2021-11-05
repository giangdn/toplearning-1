<div role="main">
    <div class="row">
        <div class="col-md-12">
            <form action=" " method="post" class="form-ajax">
                <div class="form-group row">
                    <div class="text-center">
                            <div id="qrcode" >
                                {!! QrCode::size(300)->generate($qrcode_quiz); !!}
                                <p>Quét mã để làm bài thi.</p>
                            </div>
                        <a href="javascript:void(0)" id="print_qrcode">In QR Code</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

