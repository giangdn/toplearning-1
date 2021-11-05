@extends('quiz::layout.app')

@section('page_title', $quiz->name)

@section('content')
    <link rel="stylesheet" href="{{ asset('styles/module/quiz/css/doquiz.css') }}">

    @livewire('quiz::livewire.attempt', ['attempt' => $attempt])


    <template id="qqcategory-template">
        <h3 class="question-title">
            <div class="row">
                <div class="col-md-10">
                    {name}
                </div>
                <div class="col-md-2 text-right">
                    {percent} %
                </div>
            </div>
        </h3>
    </template>

@stop
