<input type="hidden" id="json-data" value="{{ json_encode(['title' => @$title, 'description' => @$description, 'keywords' => @$keywords]) }}">

@yield('content')