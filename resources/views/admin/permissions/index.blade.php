@extends('layouts.admin')
@section('content')
@include('partials.subheader')
@can('permission_access')
	<div class="row">
		<div class="col-12">
			<div id="panel-1" class="panel show" data-panel-sortable data-panel-close data-panel-collapsed>
				<div class="panel-hdr">
					<h2>
						Data | <span class="fw-300"><i>{{ $breadcrumb }}</i></span>
					</h2>
					<div class="panel-toolbar">
						@can('permission_create')
						<a class="btn btn-success  waves-effect waves-themed btn-sm mr-2" href="{{ route('admin.permissions.create') }}" data-toggle="tooltip" title="tambah data" data-original-title="tambah data">
							{{ trans('global.add') }} {{ trans('cruds.permission.title') }}
						</a>
						@endcan
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
						<div class="row">
							<div class="col-12">
								<div class="table dataTables_wrapper dt-bootstrap4">
									<table class="dtr-inline table table-bordered table-striped table-hover ajaxTable datatable datatable-Permission w-100">
										<thead  class="bg-primary-50">
											<tr>
											<th width="10">
											</th>
											<th>
												{{ __('Nama') }}
											</th>
											<th>
												{{ __('Type') }}
											</th>
											<th>
												{{ __('Group') }}
											</th>
											<th style="width:15%">
												{{ trans('global.actions') }}
											</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endcan

@endsection
@section('scripts')
@parent
<script>
	$(function () {
		let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
		@can('permission_delete')
			let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
			let deleteButton = {
				text: deleteButtonTrans,
				url: "{{ route('admin.permissions.massDestroy') }}",
				className: 'btn-danger  waves-effect waves-themed  mr-1',
				action: function (e, dt, node, config) {
				var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
					return entry.id
				});

				if (ids.length === 0) {
					alert('{{ trans('global.datatables.zero_selected') }}')

					return
				}

				if (confirm('{{ trans('global.areYouSure') }}')) {
					$.ajax({
					headers: {'x-csrf-token': _token},
					method: 'POST',
					url: config.url,
					data: { ids: ids, _method: 'DELETE' }})
					.done(function () { location.reload() })
				}
				}
			}
			dtButtons.push(deleteButton)
		@endcan

		let dtOverrideGlobals = {
			buttons: dtButtons,
			processing: true,
			serverSide: true,
			retrieve: true,
			aaSorting: [],
			columnDefs: [
				{
					orderable: false,
					className: 'select-checkbox',
					targets: 0
				},
				{
					orderable: false,
					searchable: false,
					targets: -1
				}
			],
			select: {
				style:		'multi+shift',
				selector:	'td:first-child'
			},
			dom:
				"<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-8 d-flex'B><'col-sm-12 col-md-2 d-flex justify-content-end'f>>" +
				"<'row'<'col-sm-12 col-md-12'tr>>" +
				"<'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'p>>",
			ajax: "{{ route('admin.permissions.index') }}",
			columns: [
				{ data: 'id', name: 'id' },
				{ data: 'title', name: 'title' },
				{ data: 'perm_type', name: 'perm_type' },
				{ data: 'grp_title', name: 'grp_title' },
				{ data: 'actions', name: '{{ trans('global.actions') }}' }
			],
			orderCellsTop: true,
			// order: [[ 1, 'desc' ]],
			pageLength: 5,
		};
		let table = $('.datatable-Permission').DataTable(dtOverrideGlobals);
		$('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
			$($.fn.dataTable.tables(true)).DataTable()
				.columns.adjust();
		});
	});
</script>
@endsection
