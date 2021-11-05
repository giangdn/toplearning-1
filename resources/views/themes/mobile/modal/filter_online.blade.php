<div class="modal fade" id="filterOnline" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header theme-header border-0">
                <h6 class="">@lang('app.search')</h6>
            </div>
            <div class="modal-body p-0" style="border-top: 1px solid #dee2e6;">
                @php
                    $last_review = \Modules\Capabilities\Entities\CapabilitiesResult::getLastReviewUser(\Auth::id());
                @endphp
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" data-type="">@lang('app.all')</li>
                    <li class="list-group-item" data-type="1">@lang('app.course_going_on')</li>
                    <li class="list-group-item" data-type="2">@lang('app.course_about_to_organize')</li>
                    <li class="list-group-item" data-type="3">@lang('app.course_learning')</li>
                    <li class="list-group-item" data-type="4">@lang('app.course_held_during_month')</li>
                    @if($last_review)
                    <li class="list-group-item" data-type="5">@lang('app.competency_framework_course')</li>
                    @endif
                </ul>
            </div>
            <div class="modal-footer">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 text-center align-self-center">
                            <a href="" class="btn btn-primary" id="search-online">OK</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


