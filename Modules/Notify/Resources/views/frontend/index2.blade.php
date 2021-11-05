@extends('layouts.app')

@section('page_title', 'Thông báo')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/prism.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/chosen.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/jquery.dataTables.min.css') }}">

@endsection

@section('content')

<div class="container-fluid" id="trainingroadmap" style="background: white;">
	<div class="row">
		<div class="col-md-9">
			<ol class="breadcrumb" style="background: white;margin-bottom: 0;" >
				<li>
					<a href="/"><i class="glyphicon glyphicon-home"></i> &nbsp;{{ trans('app.home') }}</a>
				</li>
				<li style="padding-left: 0px;color: #717171;padding-right: 0;font-weight: 700;">&raquo;</li>
				<li>
					<span>{{ trans('app.notify') }}</span>
				</li>
			</ol>
		</div>
		<div class="col-md-3 text-right pt-3">
			<button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('app.delete') }}</button>
		</div>
	</div>
	<div  class="courses_news" style="background: white;">
		<table class="tDefault table table-hover bootstrap-table" id="notify">
			<thead>
			<tr>
				<th data-field="state" data-checkbox="true"></th>
				<th data-sortable="true" data-field="subject" data-formatter="subject_formatter">{{ trans('app.notify') }}</th>
				<th data-field="created_at2" data-align="center">{{ trans('app.time') }}</th>
			</tr>
			</thead>
		</table>
	</div>
</div>
<script type="text/javascript">

	var table = new LoadBootstrapTable({
		url: '{{ route('module.notify.getdata') }}',
		remove_url: '{{ route('module.notify.remove') }}',
        locale: '{{ data_locale('vi-VN', 'en-US') }}',
	});

	function subject_formatter(value, row, index) {
		return '<a href="'+ row.link +'" target="_blank" class="view" data-id="'+row.id+'" style="'+row.check+'"> '+ value +' </a>';
	}

	$('#notify').on('click', '.view', function () {
		var id = $(this).data('id');

		$.ajax({
			url: "{{ route('module.notify.view') }}",
			type: 'post',
			data: {
				id: id,
			},
		}).done(function(data) {
			window.location = '';
			return false;
		}).fail(function(data) {
			show_message('Lỗi hệ thống', 'error');
			return false;
		});
	});
</script>
@stop
