<!-- BEGIN Page Header -->
@php($unreadmsg = \App\Models\QaTopic::unreadCount())
@php($msgs = \App\Models\QaTopic::unreadMsg())


<header class="page-header" role="banner">
	<!-- we need this logo when user switches to nav-function-top -->
	<div class="page-logo">
		<a href="#" class="page-logo-link press-scale-down d-flex align-items-center position-relative"
			data-toggle="modal" data-target="#modal-shortcut">
			<img src="{{ asset('/img/logo-1.png') }}" alt="{{ trans('panel.site_title') }} WebApp"
				aria-roledescription="logo">
			<span class="page-logo-text mr-1">{{ trans('panel.site_title') }} WebApp</span>
			<span class="position-absolute text-white opacity-50 small pos-top pos-right mr-2 mt-n2"></span>
			<i class="fal fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i>
		</a>
	</div>
	<!-- DOC: nav menu layout change shortcut -->
	<div class="hidden-md-down dropdown-icon-menu position-relative">
		<a href="#" class="header-btn btn js-waves-off" data-action="toggle" data-class="nav-function-hidden"
			title="Hide Navigation">
			<i class="ni ni-menu"></i>
		</a>

	</div>
	<!-- DOC: mobile button appears during mobile width -->
	<div class="hidden-lg-up">
		<a href="#" class="header-btn btn press-scale-down" data-action="toggle" data-class="mobile-nav-on">
			<i class="ni ni-menu"></i>
		</a>
	</div>
	{{-- <div class="search">
		<select class="searchable-field form-control"></select>
	</div> --}}
	<div class="ml-auto d-flex">
		<!-- activate app search icon (mobile) -->
		<div class="hidden-sm-up">
			<a href="#" class="header-icon" data-action="toggle" data-class="mobile-search-on"
				data-focus="search-field" title="Search">
				<i class="fal fa-search"></i>
			</a>
		</div>
		<!-- app settings -->
		{{-- <div class="hidden-md-down">
			<a href="#" class="header-icon" data-toggle="modal" title="Penyesuaian"
				data-target=".js-modal-settings">
				<i class="fal fa-cog"></i>
			</a>
		</div> --}}

		{{-- <div>
			<a href="#" class="header-icon" data-toggle="dropdown"
				title="{{ $unreadmsg }} pesan @if (Auth::user()->roles[0]->title == 'Admin' || Auth::user()->roles[0]->title == 'Pejabat')
				, {{ $cntpengajuan }} pengajuan baru @endif">
				<i class="fal fa-envelope"></i>
				<span class="badge badge-icon">{{ $unreadmsg  +  $cntpengajuan}} </span>
			</a>
			<div class="dropdown-menu dropdown-menu-animated dropdown-xl">
				<div
					class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center rounded-top mb-2">
					<h4 class="m-0 text-center color-white">
						@if (Auth::user()->roles[0]->title == 'Admin' || Auth::user()->roles[0]->title == 'Pejabat')
							<small class="mb-0 opacity-80">{{ $cntpengajuan }} Pengajuan baru</small>
						@endif
						<small class="mb-0 opacity-80">{{ $unreadmsg }} Pesan baru</small>
					</h4>
				</div>
				<ul class="nav nav-tabs nav-tabs-clean" role="tablist">
					<li class="nav-item">
						<a class="nav-link px-4 fs-md js-waves-on fw-500" data-toggle="tab" href="#tab-messages"
							data-i18n="drpdwn.messages">Pesan</a>
					</li>
					@if (Auth::user()->roles[0]->title == 'Admin' || Auth::user()->roles[0]->title == 'Pejabat')

					<li class="nav-item">
						<a class="nav-link px-4 fs-md js-waves-on fw-500" data-toggle="tab" href="#tab-feeds"
							data-i18n="drpdwn.feeds">Pengajuan</a>
					</li>
					@endif
				</ul>
				<div class="tab-content tab-notification">

					<div class="tab-pane" id="tab-messages" role="tabpanel">
						<div class="custom-scroll h-100">
							<ul class="notification">
								@foreach ($msgs as $item)
									<li class="unread">
										<a href="{{ route('admin.messenger.showMessages', $item['id']) }}"
											class="d-flex align-items-center">
											<span class="mr-2">
												@php($user = \App\Models\User::getUserById($item['sender']))
												@if (!empty($user[0]->data_user->logo))
													<img src="{{ Storage::disk('public')->url($user[0]->data_user->logo) }}"
														class="profile-image rounded-circle" alt="">
												@else
													<img src="{{ asset('/img/favicon.png') }}"
														class="profile-image rounded-circle" alt="">
												@endif
											</span>
											<span class="d-flex flex-column flex-1 ml-1">
												<span class="name">{{ $user[0]->name }}<span
														class="badge badge-primary fw-n position-absolute pos-top pos-right mt-1">INBOX</span></span>
												<span class="msg-a fs-sm">{{ $item['subject'] }}</span>
												<span class="msg-b fs-xs">{{ $item['content'] }}</span>
												<span
													class="fs-nano text-muted mt-1">{{ $item['create_at']->diffForHumans() }}</span>
											</span>
										</a>
									</li>
								@endforeach
							</ul>
						</div>
					</div>
					<div class="tab-pane" id="tab-feeds" role="tabpanel">
						<div class="custom-scroll h-100">
							<ul class="notification">
								@if ($newpengajuan)
									@foreach ($newpengajuan as $pengajuan)
									<li class="unread">
										<a href="{{ route('verification.data.check', [$pengajuan->id]) }}"  class="d-flex align-items-center show-child-on-hover">
											<span class="d-flex flex-column flex-1">
												<span class="name d-flex align-items-center">{{ $pengajuan->datauser->company_name }} <span
														class="badge badge-success fw-n ml-1">New</span></span>
												<span class="msg-a fs-sm">
													{{ $pengajuan->no_pengajuan }}
												</span>
												<span class="fs-nano text-muted mt-1">{{ $pengajuan->created_at->diffForHumans() }}</span>
											</span>

										</a>
									</li>
									@endforeach
								@endif
							</ul>
						</div>
					</div>

				</div>
				<div
					class="py-2 px-3 bg-faded d-block rounded-bottom text-right border-faded border-bottom-0 border-right-0 border-left-0">
					<a href="{{ route('admin.home') }}" class="fs-xs fw-500 ml-auto">Read more..</a>
				</div>
			</div>
		</div> --}}
		<!-- app user menu -->
		<div>
			<a href="#" data-toggle="dropdown" title="{{ Auth::user()->name }}"
				class="header-icon d-flex align-items-center justify-content-center ml-2">
				@if (!empty(Auth::user()::find(Auth::user()->id)->data_user->logo))
					<img src="{{ Storage::disk('public')->url(Auth::user()::find(Auth::user()->id)->data_user->logo) }}"
						class="profile-image rounded-circle">
				@else
					<img src="{{ asset('/img/avatars/avatar-f.png') }}" class="profile-image rounded-circle"
						alt="{{ Auth::user()->name }}">
				@endif
			</a>
			<div class="dropdown-menu dropdown-menu-animated dropdown-lg">
				<div class="dropdown-header bg-trans-gradient d-flex flex-row py-4 rounded-top">
					<div class="d-flex flex-row align-items-center mt-1 mb-1 color-white">
						<span class="mr-2">
							@if (!empty(Auth::user()::find(Auth::user()->id)->data_user->avatar))
								<img src="{{ Storage::disk('public')->url(Auth::user()::find(Auth::user()->id)->data_user->avatar) }}"
									class="profile-image rounded-circle">
							@else
								<img src="{{ asset('/img/avatars/avatar-f.png') }}"
									class="profile-image rounded-circle" alt="{{ Auth::user()->name }}">
							@endif
						</span>
						<div class="info-card-text">
							<div class="fs-lg text-truncate text-truncate-lg">{{ Auth::user()->name }}</div>
							<span class="text-truncate text-truncate-md opacity-80">{{ Auth::user()->email }}</span>
						</div>
					</div>
				</div>
				<div class="dropdown-divider m-0"></div>
				<div class="dropdown-divider m-0"></div>
				<a href="#" class="dropdown-item" data-action="app-fullscreen">
					<span >Fullscreen</span>
					<i class="float-right text-muted fw-n">F11</i>
				</a>
				<a href="{{ route('admin.profile.show') }}" class="dropdown-item">
					<span data-i18n="drpdwn.profile">Profile</span>
				</a>
				{{-- <div class="dropdown-multilevel dropdown-multilevel-left">
					<div class="dropdown-item" data-i18n="drpdwn.lang">
						Bahasa
					</div>
					<div class="dropdown-menu">
						<a href="#?lang=id" class="dropdown-item {{ app()->getLocale() == 'id' ? "active" : "" }}" data-action="lang" data-lang="id">Bahasa (ID)</a>
						<a href="#?lang=en" class="dropdown-item {{ app()->getLocale() == 'en' ? "active" : "" }}" data-action="lang" data-lang="en">English (US)</a>
					</div>
				</div> --}}
				<div class="dropdown-divider m-0"></div>
				<a class="dropdown-item fw-500 pt-3 pb-3"
					onclick="event.preventDefault(); document.getElementById('logoutform').submit();"
					href="{{ trans('global.logout') }}">
					<span data-i18n="drpdwn.page-logout">Logout</span>
					<span class="float-right fw-n">&commat;{{ Auth::user()->name }}</span>
				</a>
			</div>
		</div>
	</div>
</header>
