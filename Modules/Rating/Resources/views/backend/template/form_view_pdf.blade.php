<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page_title }}</title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ image_file(\App\Config::getFavicon()) }}">

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
        }
        table {
            border-collapse: collapse;
            border: 1px solid #0c0c0c;
        }
        table tr th{
            border: 1px solid #0c0c0c;
            padding: 5px;
        }
    </style>
</head>
<body>
<div role="main">
    <div class="row">
        <div class="col-md-12" id="input-category">
            @if(isset($categories))
                @foreach($categories as $cate_key => $category)
                    <div class="ques_item mb-3">
                        <h3 class="mb-0">{{ Str::ucfirst($category->name) }}</h3>
                        <hr class="mt-1">
                    </div>
                    @foreach ($category->questions as $ques_key => $question)
                        <div class="ques_item mb-2">
                            <div class="ques_title survey mb-1">
                                <span>{{ ($ques_key + 1) .'. '. Str::ucfirst($question->name) }}</span>
                            </div>
                            @if ($question->type == "essay")
                                <div class="ui search focus">
                                    <div class="ui form swdh30 survey">
                                        <div class="field">
                                            <textarea rows="3" placeholder="{{ trans('backend.content') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            @elseif($question->type == 'dropdown')
                                <div class="ui form survey ml-5">
                                    @foreach($question->answers as $ans_key => $answer)
                                        <div class="ui mb-2">
                                        {{ $answer->name }}
                                        </div>
                                    @endforeach
                                </div>
                            @elseif ($question->type == "time")
                                <div class="ui form survey ml-5">
                                    <div class="grouped fields item-answer">
                                        <input type="text" class="form-control mt-2">
                                    </div>
                                </div>
                            @elseif (in_array($question->type, ['matrix','matrix_text']))
                                <div class="ui form survey ml-5">
                                    <div class="grouped fields item-answer">
                                        @php
                                            $rows = $question->answers->where('is_row', '=', 1);
                                            $cols = $question->answers->where('is_row', '=', 0);
                                        @endphp
                                        <table>
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                @foreach($cols as $ans_key => $answer_col)
                                                    <th>{{ $answer_col->name }}</th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($rows as $ans_row_key => $answer_row)
                                                <tr>
                                                    <th>{{ $answer_row->name }}</th>
                                                    @foreach($cols as $ans_key => $answer_col)
                                                        <th style="text-align: center;">
                                                            @if($question->type == 'matrix')
                                                                <input type="{{ $question->multiple != 1 ? 'radio' : 'checkbox' }}">
                                                            @else
                                                                <textarea rows="1" class="form-control w-100"></textarea>
                                                            @endif
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            @else
                                <div class="ui form survey ml-5">
                                    <div class="grouped fields item-answer">
                                        @foreach($question->answers as $ans_key => $answer)
                                            @if($question->type == 'sort')
                                                <div class="field fltr-radio m-0">
                                                    <div class="ui">
                                                        <div class="form-inline mb-1">
                                                            <input type="text" class="form-control w-5">
                                                            <span class="ml-1">{{ $answer->name }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($question->type == 'text')
                                                <div class="field fltr-radio m-0">
                                                    <div class="ui">
                                                        <div class="input-group d-flex align-items-center mb-1">
                                                            <span class="mr-1">{{ $answer->name }}</span>
                                                            <textarea rows="1" class="form-control w-auto"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if(in_array($question->type, ['number', 'percent']))
                                                <div class="field fltr-radio m-0">
                                                    <div class="ui">
                                                        <div class="form-inline mb-1">
                                                            <span class="mr-1">{{ $answer->name }}</span>
                                                            <input type="text" name="" class="form-control w-5">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($question->type == 'choice')
                                                <div class="field fltr-radio m-0">
                                                    <div class="ui mb-2">
                                                        <input type="{{ $question->multiple != 1 ? 'radio' : 'checkbox' }}"> {{ $answer->name }}
                                                        @if($answer->is_text == 1)
                                                            <input type="text" class="form-control">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endforeach
            @endif
        </div>
    </div>
</div>
</body>
</html>
