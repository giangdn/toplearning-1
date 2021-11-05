@extends('themes.mobile.layouts.app')

@section('page_title', 'Góp ý')

@section('content')
    <div class="container-fluid suggest-container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="ibox-content suggest-container">
                    <div class="row mt-2">
                        <div class="col-12 col-xl-12 col-lg-12 col-md-12 text-right act-btns">
                            <button class="btn btn-info" id="create">
                                <i class="fa fa-edit"></i> {{ trans('app.create_suggest') }}
                            </button>
                        </div>
                    </div>
                    <p></p>
                    <table class="tDefault table table-hover table-bordered bootstrap-table bg-white">
                        <thead>
                        <tr>
                            <th class="text-center">{{ trans('app.suggest') }}</th>
                            <th class="text-center">{{ trans('app.comment') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($suggest)
                            @foreach($suggest as $item)
                                <tr>
                                    <td>
                                        {{ $item->name }} <br>
                                        {{ get_date($item->created_at) }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('module.suggest.get_comment', ['id' => $item->id]) }}"><i class="material-icons">comment</i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form action="{{ route('module.suggest.save') }}" method="post" class="form-ajax">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('app.add_suggest') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-3 label">
                                <label> {{ trans('app.name_suggest') }}</label>
                            </div>
                            <div class="col-md-9">
                                <input class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 label">
                                <label> {{ trans('app.content') }}</label>
                            </div>
                            <div class="col-md-9">
                                <textarea class="form-control" name="content" required rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('app.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('app.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#create').on('click', function() {
            $('#modal-create').modal();
        });
    </script>
@endsection
