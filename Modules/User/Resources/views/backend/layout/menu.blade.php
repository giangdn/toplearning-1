<div class="row pb-2">
    <div class="col-md-12 text-center">
        {{--<a href="{{route('module.user.info')}}" class="btn btn-info">--}}
            {{--<div><i class="fa fa-user"></i></div>--}}
            {{--<div>Thông tin tài khoản</div>--}}
        {{--</a>--}}
        @if(isset($model))
        <a href="" id="change-avatar">
            <img class="avatar_user_edit" src="{{ \App\Profile::avatar($model->id) }}" alt="">
        </a>
        @endif
        @if(userCan('user-view-roadmap') || \App\Permission::isUnitManager())
        <a href="{{route('module.backend.user.roadmap',['user_id'=>$user_id])}}" class="btn btn-info">
            <div><i class="fa fa-tree" aria-hidden="true"></i></div>
            <div>{{ trans('backend.roadmap') }}</div>
        </a>
        @endif
        @if(userCan('user-view-training-process') || \App\Permission::isUnitManager())
        <a href="{{route('module.backend.user.trainingprocess',['user_id'=>$user_id])}}" class="btn btn-info">
            <div><i class="fa fa-hashtag" aria-hidden="true"></i></div>
            <div>{{ trans('backend.training_process') }}</div>
        </a>
        @endif
        @if(userCan('user-view-quiz-result') || \App\Permission::isUnitManager())
        <a href="{{route('module.backend.user.quizresult',['user_id'=>$user_id])}}" class="btn btn-info">
            <div><i class="fa fa-graduation-cap" aria-hidden="true"></i></div>
            <div>{{ trans('backend.quiz_result') }}</div>
        </a>
        @endif
        @if(userCan('user-view-training-program-learned') || \App\Permission::isUnitManager())
        <a href="{{route('module.backend.training_program_learned',['user_id'=>$user_id])}}" class="btn btn-info">
            <div><i class="fas fa-book-open" aria-hidden="true"></i></div>
            <div>{{ trans('backend.training_program_learned') }}</div>
        </a>
        @endif
        @if(userCan('user-view-working-process') || \App\Permission::isUnitManager())
        <a href="{{route('module.backend.working_process',['user_id'=>$user_id])}}" class="btn btn-info">
            <div><i class="fas fa-chart-line" aria-hidden="true"></i></div>
            <div>{{ trans('backend.working_process') }}</div>
        </a>
        @endif
        @if(userCan('user-view-career-roadmap') || \App\Permission::isUnitManager())
        <a href="{{route('module.career_roadmap.user',[$user_id])}}" class="btn btn-info">
            <div><i class="fa fa-list-alt" aria-hidden="true"></i></div>
            <div>{{ trans('career.career_roadmap') }}</div>
        </a>
        @endif


        {{--@can('user-view-training-by-title')
        <a href="{{route('module.backend.user.training_by_title',['user_id'=>$user_id])}}" class="btn btn-info">
            <div><i class="fas fa-chart-line" aria-hidden="true"></i></div>
            <div>Lộ trình đào tạo</div>
        </a>
        @endcan--}}
    </div>
</div>
