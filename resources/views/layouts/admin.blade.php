<!DOCTYPE html>
<html lang="en" class="root-text-sm">
	@include('partials.head')

	<body class="mod-bg-2 mod-nav-link blur">  {{-- mod-skin-dark --}}
		<script src="{{ asset('js/smartadmin/pagesetting.js') }}"></script>
		<!-- begin page wrapper -->
		<div class="page-wrapper">
            <div class="page-inner">
				<!-- begin sidebar -->
				@include('partials.menu')
				<!-- end sidebar -->
				<div class="page-content-wrapper">
					<!-- begin page header -->
					@include('partials.header')
					<!-- end page header -->
					<!-- begin page content -->
					<main id="js-page-content" role="main" class="page-content">
						<!-- start alert pesan -->
						@if(session('message'))
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true"><i class="fal fa-times-circle"></i></span>
								</button>
								<strong>{{ session('message') }}.</strong>
							</div>
						@endif
						<!-- end alert pesan -->
						<!-- start alert error -->
						@if($errors->count() > 0)
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true"><i class="fal fa-times-circle"></i></span>
								</button>
								<strong>PERHATIAN!</strong>
								<ul class="list-unstyled">
									@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif
						<!-- end alert error -->
						@yield('content')
					</main>
					<div class="page-content-overlay" data-action="toggle" data-class="mobile-nav-on"></div>
					<!-- end page content -->

					<!-- begin page footer -->
					{{-- @include('partials.footer') --}}
					<!-- end page footer -->
					<!-- begin shortcut -->
					{{-- @include('partials.shortcut') --}}
					<!-- end shortcut -->
				</div>
			</div>
		</div>
		<form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
			{{ csrf_field() }}
		</form>
		<script src="{{ asset('js/app.js') }}"></script>
		<!-- end page wrapper -->
		<!-- begin quick menu -->
		@include('partials.quickmenu')
		<!-- END Quick Menu -->
		<!-- BEGIN Page Settings -->
		{{-- @include('partials.pagesettings') --}}

		<script src="{{asset('js/vendors.bundle.js')}}"></script>
		<script src="{{asset('js/app.bundle.js')}}"></script>

		<script src="{{ asset('js/datagrid/datatables/datatables.bundle.js') }}"></script>
		<script src="{{ asset('js/datagrid/datatables/datatables.export.js') }}"></script>

		<script src="{{asset('js/miscellaneous/fullcalendar/fullcalendar.bundle.js')}}"></script>
		<script src="{{asset('js/miscellaneous/lightgallery/lightgallery.bundle.js')}}"></script>

		<script src="{{asset('js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
		<script src="{{asset('js/dependency/moment/moment.js')}}"></script>
		<script src="{{asset('js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js')}}"></script>
		<script src="{{asset('js/formplugins/select2/select2.bundle.js')}}"></script>

		<!-- search bar -->
		<script type="text/javascript">
			console.log("Init Language");
			if (!$.i18n) {
				initApp.loadScript("/js/i18n/i18n.js",
					function activateLang () {
						$.i18n.init({
							resGetPath: '/media/data/__lng__.json',
							load: 'unspecific',
							fallbackLng: false,
							lng: '{{ app()->getLocale() }}'
						}, function (t){
							$('[data-i18n]').i18n();
							$('[data-lang]').removeClass('active');
							$('[data-lang="{{ app()->getLocale() }}"]').addClass('active');
							console.log("Init language to: " + "{{ app()->getLocale() }}");
						});

					}

				);

			} else {
				i18n.setLng('{{ app()->getLocale() }}', function(){
					$('[data-i18n]').i18n();
					$('[data-lang]').removeClass('active');
					$('[data-lang="{{ app()->getLocale() }}"]').addClass('active');
					console.log("setting language to: " + "{{ app()->getLocale() }}");
				});

			}


		$(document).ready(function() {
		  $('.searchable-field').select2({
			minimumInputLength: 3,
			ajax: {
			  url: '{{ route("admin.globalSearch") }}',
			  dataType: 'json',
			  type: 'GET',
			  delay: 200,
			  data: function(term) {
				return {
				  search: term
				};
			  },
			  results: function(data) {
				return {
				  data
				};
			  }
			},
			escapeMarkup: function(markup) {
			  return markup;
			},
			templateResult: formatItem,
			templateSelection: formatItemSelection,
			placeholder: "{{ trans('global.search') }} ...",
			language: {
			  inputTooShort: function(args) {
				var remainingChars = args.minimum - args.input.length;
				var translation = "{{ trans('global.search_input_too_short') }}";

				return translation.replace(':count', remainingChars);
			  },
			  errorLoading: function() {
				return "{{ trans('global.results_could_not_be_loaded') }}";
			  },
			  searching: function() {
				return "{{ trans('global.searching') }}";
			  },
			  noResults: function() {
				return "{{ trans('global.no_results') }}";
			  },
			}

		  });

		  function formatItem(item) {
			if (item.loading) {
			  return "{{ trans('global.searching') }}...";
			}
			var markup = "<div class='searchable-link' href='" + item.url + "'>";
			markup += "<div class='searchable-title'>" + item.model + "</div>";
			$.each(item.fields, function(key, field) {
			  markup += "<div class='searchable-fields'>" + item.fields_formated[field] + " : " + item[field] + "</div>";
			});
			markup += "</div>";

			return markup;
		  }

		  function formatItemSelection(item) {
			if (!item.model) {
			  return "{{ trans('global.search') }}...";
			}
			return item.model;
		  }
		  $(document).delegate('.searchable-link', 'click', function() {
			var url = $(this).attr('href');
			window.location = url;
		  });


		});
		</script>
		@yield('scripts')
	</body>
</html>
