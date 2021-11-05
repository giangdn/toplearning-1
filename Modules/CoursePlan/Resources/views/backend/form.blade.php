@if($course_type == 1)
    @include('courseplan::backend.online.form')
@else
    @include('courseplan::backend.offline.form')
@endif
