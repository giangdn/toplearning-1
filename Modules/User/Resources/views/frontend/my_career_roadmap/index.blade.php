<div class="tab-pane fade active show" id="nav-courses" role="tabpanel">
    <div class="crse_content mt-0">
        <h3>@lang('career.career_roadmap')</h3>
        <div class="_14d25 mt-0">
            <div class="row">
                @if($career_roadmaps)
                    @php
                        $title_id = \App\Profile::find(Auth::id())->titles->id;
                        $sub_titles = $career_roadmaps->getTitles();
                    @endphp

                    <div class="text-center">
                        <img src="{{ asset('themes/mobile/img/check.png') }}" class="img-responsive" style="width: 40px; height: 40px;">
                        <p style="width: 75px; word-break: break-word">
                           Bắt đầu
                        </p>
                    </div>
                    @foreach($sub_titles as $index => $sub_title)
                        @php
                            $total_subject = \Modules\CareerRoadmap\Entities\CareerRoadmapTitle::getSubjectRoadmap($sub_title->title->id);
                            $total_result = 0;
                            if ((count($total_subject) > 0)){
                                foreach ($total_subject as $subject){
                                    $subject_course = \App\Models\Categories\Subject::where('code', '=', $subject->subject_code)->first();
                                    if ($subject_course && $subject_course->isCompleted()){
                                        $total_result += 1;
                                    }
                                }
                            }

                            $percent = ($total_result / ((count($total_subject) > 0) ? count($total_subject) : 1)) * 100;
                        @endphp
                        @if($index < count($sub_titles))
                            <span class="progress progress2" style="flex-basis: 10%; margin-top: 10px;">
                                <div class="progress-bar" style="width: {{ $percent }}%" role="progressbar"  aria-valuemin="0" aria-valuemax="100"> {{ number_format($percent, 2) }}% </div>
                            </span>
                        @endif

                        <div class="text-center">
                            @if($percent == 100)
                                <img src="{{ asset('themes/mobile/img/check.png') }}" class="img-responsive" style="width: 40px; height: 40px;">
                            @else
                                <img src="{{ asset('themes/mobile/img/padlock.png') }}" class="img-responsive" style="width: 40px; height: 40px;">
                            @endif
                            <p style="width: 75px; word-break: break-word">
                                {{ $sub_title->title->name }}
                            </p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
