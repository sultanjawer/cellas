<head>
	<meta charset="utf-8" />
	<title>{{ env('APP_NAME')}} | {{ ($page_title ?? '') }}</title>
	<meta name="description" content="Page Title" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport"
		content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui"/>
	<!-- Call App Mode on ios devices -->
	<meta name="apple-mobile-web-app-capable" content="yes" />

	<!-- Remove Tap Highlight on Windows Phone IE -->
	<meta name="msapplication-tap-highlight" content="no" />

	<!-- base css -->
	<link id="vendorsbundle" rel="stylesheet" media="screen, print" href="{{asset('css/vendors.bundle.css')}}" />
	<link id="appbundle" rel="stylesheet" media="screen, print" href="{{asset('css/app.bundle.css')}}" />
	<link id="mytheme" rel="stylesheet" media="screen, print" href="#" />
	<link id="myskin" rel="stylesheet" media="screen, print" href="{{asset('css/skins/skin-master.css')}}" />

	<!-- Place favicon.ico in the root directory -->
	<link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/logo-1.png')}}" />
	<link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/logo-1.png')}}" />
	<link rel="mask-icon" href="{{asset('img/favicon/safari-pinned-tab.svg')}}" color="#5bbad5" />

	{{-- font awesome --}}
	<link rel="stylesheet" media="screen, print" href="{{asset('css/fa-brands.css')}}">
	<link rel="stylesheet" media="screen, print" href="{{asset('css/fa-duotone.css')}}">
	<link rel="stylesheet" media="screen, print" href="{{asset('css/fa-light.css')}}">
	<link rel="stylesheet" media="screen, print" href="{{asset('css/fa-regular.css')}}">
	<link rel="stylesheet" media="screen, print" href="{{asset('css/fa-solid.css')}}">

	{{-- datatables --}}
	<link rel="stylesheet" media="screen, print" href="{{asset('css/datagrid/datatables/datatables.bundle.css')}}">

	{{-- miscellaneous --}}
	<link rel="stylesheet" media="screen, print" href="{{asset('css/miscellaneous/fullcalendar/fullcalendar.bundle.css')}}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" media="screen, print" href="{{asset('css/miscellaneous/lightgallery/lightgallery.bundle.css')}}">

	{{-- form plugins --}}
	<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css')}}">
	<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css')}}">
	<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">

	<meta name="csrf-token" content="{{ csrf_token() }}">
	@yield('styles')

</head>
<!-- BEGIN Body -->
<!-- Possible Classes

	* 'header-function-fixed'         - header is in a fixed at all times
	* 'nav-function-fixed'            - left panel is fixed
	* 'nav-function-minify'			  - skew nav to maximize space
	* 'nav-function-hidden'           - roll mouse on edge to reveal
	* 'nav-function-top'              - relocate left pane to top
	* 'mod-main-boxed'                - encapsulates to a container
	* 'nav-mobile-push'               - content pushed on menu reveal
	* 'nav-mobile-no-overlay'         - removes mesh on menu reveal
	* 'nav-mobile-slide-out'          - content overlaps menu
	* 'mod-bigger-font'               - content fonts are bigger for readability
	* 'mod-high-contrast'             - 4.5:1 text contrast ratio
	* 'mod-color-blind'               - color vision deficiency
	* 'mod-pace-custom'               - preloader will be inside content
	* 'mod-clean-page-bg'             - adds more whitespace
	* 'mod-hide-nav-icons'            - invisible navigation icons
	* 'mod-disable-animation'         - disables css based animations
	* 'mod-hide-info-card'            - hides info card from left panel
	* 'mod-lean-subheader'            - distinguished page header
	* 'mod-nav-link'                  - clear breakdown of nav links

	>>> more settings are described inside documentation page >>>
-->
