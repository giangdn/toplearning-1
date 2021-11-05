@extends('layouts.backend')

@section('page_title', trans('backend.category'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    
    <div class="row mb-4">
        @canany(['category-unit','category-unit-type','category-titles','category-cert'])
            {{-- TỔ CHỨC --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('backend.organize')) }}</h4>
                    </div>
                    @can('category-unit')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.unit', ['level' => 0]) }}">{{ trans('lageneral.company_categories') }}</a>
                        </div>
                        @for($i = 1; $i <= 6; $i++)
                            <div class="item_name mb-2">
                                <a href="{{ route('backend.category.unit', ['level' => $i]) }}">{{ trans('lageneral.unit_level') }} {{$i}}</a>
                            </div>
                        @endfor
                    @endcan
                </div>
            </div>
            {{-- VỊ TRÍ ĐỊA LÝ --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('backend.geographical_location')) }}</h4>
                    </div>
                    @can('category-area')
                        @for($i = 1; $i <= $max_level_area; $i++)
                            <div class="item_name mb-2">
                                <a href="{{ route('backend.category.area', ['level' => $i]) }}">{{ data_locale($level_name_area($i)->name, $level_name_area($i)->name_en) }}</a>
                            </div>
                        @endfor
                    @endcan
                </div>
            </div>
            {{-- THÔNG TIN --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('backend.info')) }}</h4>
                    </div>
                    @can('category-unit-type')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.unit_type') }}">{{ trans('backend.unit_type') }}</a>
                        </div>
                    @endcan
                    @can('category-titles')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.title_rank') }}">{{ trans('lageneral.title_level') }}</a>
                        </div>
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.titles') }}">{{ trans('backend.title') }}</a>
                        </div>
                    @endcan
                    @can('category-cert')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.cert') }}">{{ trans('backend.level') }}</a>
                        </div>
                    @endcan
                    @can('category-titles')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.position') }}">{{ trans('lageneral.potision') }}</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endcanany
        @canany(['category-training-program', 'category-subject','category-training-location','category-training-form','category-quiz-type'])
            {{-- ĐÀO TẠO --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('backend.training')) }}</h4>
                    </div>
                    @can('category-training-program')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training_program') }}">{{ trans('lageneral.training_program') }}</a>
                        </div>
                    @endcan
                    @can('category-level-subject')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.level_subject') }}">{{ trans('backend.type_subject') }}</a>
                        </div>
                    @endcan
                    @can('category-subject')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.subject') }}">{{ trans('lageneral.course') }}</a>
                        </div>
                    @endcan
                    @can('category-training-form')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training_form') }}">{{ trans('backend.training_form') }}</a>
                        </div>
                    @endcan
                    @can('category-training-type')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training-type') }}">{{ trans('lageneral.training_form') }}</a>
                        </div>
                    @endcan
                    @can('category-training-form')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training-object') }}">{{ trans('lageneral.training_object_group') }}</a>
                        </div>
                    @endcan
                    @can('category-quiz-type')
                        <div class="item_name mb-2">
                            <a href="{{ route('module.quiz.type.manager') }}">{{ trans('backend.quiz_type') }}</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endcanany
        @canany(['category-absent', 'category-discipline', 'category-absent-reason'])
            {{-- KỶ LUẬT --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('backend.discipline')) }}</h4>
                    </div>
                    @can('category-absent')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.absent') }}">{{ trans('lageneral.absent_type') }}</a>
                        </div>
                    @endcan
                    @can('category-discipline')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.discipline') }}">{{ trans('lageneral.violator_list') }}</a>
                        </div>
                    @endcan
                    @can('category-absent-reason')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.absent-reason') }}">{{ trans('lageneral.absent_reason') }}</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endcanany
        @canany(['category-training-cost','category-student-cost','commit-month'])
            {{-- CHI PHÍ --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('backend.cost')) }}</h4>
                    </div>
                    @can('category-training-cost')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.type_cost') }}">{{ trans('lageneral.fee_type') }}</a>
                        </div>
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training_cost') }}">{{ trans('backend.training_cost') }}</a>
                        </div>
                    @endcan
                    @can('category-student-cost')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.student_cost') }}">{{ trans('backend.student_cost') }}</a>
                        </div>
                    @endcan
                    @can('commit-month')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.commit_month') }}">{{ trans('backend.commit') }}</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endcanany
        @canany(['category-teacher','category-teacher-type','category-partner'])
            {{-- GIẢNG VIÊN --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('backend.teacher')) }}</h4>
                    </div>
                    @can('category-teacher')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training_teacher') }}">{{ trans('backend.list_teacher') }}</a>
                        </div>
                    @endcan
                    @can('category-teacher-type')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.teacher_type') }}">{{ trans('backend.teacher_type') }}</a>
                        </div>
                    @endcan
                    @can('category-partner')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training_partner') }}">{{ trans('backend.partner') }}</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endcanany
        @canany(['category-province','category-district','category-training-location'])
            {{-- ĐỊA ĐIỂM ĐÀO TẠO --}}
            <div class="col-3 mb-4">
                <div class="wrapped_category">
                    <div class="title mb-3">
                        <h4 class="font-weight-bold">{{ mb_strtoupper(trans('backend.training_location')) }}</h4>
                    </div>
                    @can('category-province')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.province') }}">{{ trans('backend.province') }}</a>
                        </div>
                    @endcan
                    @can('category-district')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.district') }}">{{ trans('backend.district') }}</a>
                        </div>
                    @endcan
                    @can('category-training-location')
                        <div class="item_name mb-2">
                            <a href="{{ route('backend.category.training_location') }}">{{ trans('backend.training_location') }}</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endcanany
    </div>
@endsection
