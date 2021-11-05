<div class="row pb-2 user-info">
    <div class="col-md-12 text-center">
        <a href="{{route('module.frontend.user.info')}}" class="btn btn-info">
            <div><i class="fa fa-user"></i></div>
            <div>Thông tin học viên</div>
        </a>
        <a href="{{route('module.frontend.user.trainingprocess')}}" class="btn btn-info">
            <div><i class="fa fa-hashtag" aria-hidden="true"></i></div>
            <div>Quá trình đào tạo</div>
        </a>
        <a href="{{route('module.frontend.user.quizresult')}}" class="btn btn-info">
            <div><i class="fa fa-graduation-cap" aria-hidden="true"></i></div>
            <div>Kết quả thi</div>
        </a>
        <a href="{{route('module.frontend.user.roadmap')}}" class="btn btn-info">
            <div><i class="fa fa-sun-o" aria-hidden="true"></i></div>
            <div>{{trans('backend.trainingroadmap')}}</div>
        </a>
    </div>
</div>
