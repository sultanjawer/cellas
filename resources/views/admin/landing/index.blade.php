@extends('layouts.admin')
@section('content')
	@include('partials.subheader')
	@can('landing_access')
		@php($unreadmsg = \App\Models\QaTopic::unreadCount())
		@php($msgs = \App\Models\QaTopic::unreadMsg())
		<div class="row mb-5">
			<div class="col text-center">
				<h1 class="hidden-md-down">Welcome, {{ Auth::user()->data_user->company_name ?? Auth::user()->name }}</h1><br>
				<h2 class="display-4 hidden-sm-up">Hello, <span class="fw-700">{{ Auth::user()->name }}</span></h2>
				<h4 class="hidden-md-down">
					<p class="text-muted">{!! $quote !!}</p>
				</h4>
			</div>
		</div>

		<!-- Page Content -->
		{{-- <div class="row">
			<div class="col-lg-7">
				<div id="panel-1" class="panel">
					<div class="panel-hdr">
						<h2>
							<i class="subheader-icon fal fa-rss mr-1 text-muted"></i>
							<span class="text-primary fw-700" style="text-transform: uppercase">BERITA</span>
						</h2>
						<div class="panel-toolbar">
							<a href="{{ route('admin.posts.index') }}" data-toggle="tooltip" title
								data-original-title="Lihat semua Feeds" class="btn btn-xs btn-primary waves-effect waves-themed"
								type="button" href="/">Lihat semua</a>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content p-0">
							<ul class="notification">
								@foreach ($posts as $post)
									<li>
										<a href="{{ route('admin.posts.show', $post['id']) }}"
											class="d-flex align-items-center">

											<span class="d-flex flex-column flex-1 ml-1">
												<span class="fw-700 fs-md text-primary" style="text-transform: uppercase">
													{{ $post['title'] }}
												</span>
												<span class="name fs-xs text-muted small mb-2">
													create by: {{ $post->user->name }} |
													@if ($post['created_at']->isToday())
														@if ($post['created_at']->diffInHours(date('Y-m-d H:i:s')) > 1)
															<span
																class="fs-nano text-muted mt-1">{{ $post['created_at']->diffInHours(date('Y-m-d H:i:s')) }}
																jam yang lalu</span>
														@else
															@if ($post['created_at']->diffInMinutes(date('Y-m-d H:i:s')) > 1)
																<span
																	class="fs-nano text-muted mt-1">{{ $post['created_at']->diffInMinutes(date('Y-m-d H:i:s')) }}
																	menit yang lalu</span>
															@else
																<span
																	class="fs-nano text-muted mt-1">{{ $post['created_at']->diffInSeconds(date('Y-m-d H:i:s')) }}
																	detik yang lalu</span>
															@endif
														@endif
													@else
														@if ($post['created_at']->isYesterday())
															<span class="fs-nano text-muted mt-1">Kemarin</span>
														@else
															<span
																class="fs-nano text-muted mt-1">{{ $post['created_at'] }}</span>
														@endif
													@endif
													| {{ ($post->category->name ?? '') }}
												</span>
												<span class="text-muted">{{ $post['exerpt'] }}</span>
											</span>
										</a>
									</li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-5">
				<div id="panel-2" class="panel">
					<div class="panel-hdr">
						<h2>
							<i class="subheader-icon fal fa-envelope mr-1"></i><span class="color-warning-700 fw-700"
								style="text-transform:uppercase">Pesan baru</span>
						</h2>
						<div class="panel-toolbar">
							<a href="{{ route('admin.messenger.index') }}" data-toggle="tooltip" title
								data-original-title="Lihat semua pesan" class="btn btn-xs btn-warning waves-effect waves-themed"
								type="button" href="/">Lihat</a>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content p-0">
							<ul class="notification">
								@foreach ($msgs as $item)
									<li>
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
														class="badge badge-danger fw-n position-absolute pos-top pos-right mt-1">NEW</span></span>
												<span class="msg-a fs-sm">{{ $item['subject'] }}</span>
												<span class="msg-b fs-xs">{{ $item['content'] }}</span>
												@if ($item['create_at']->isToday())
													@if ($item['create_at']->diffInHours(date('Y-m-d H:i:s')) > 1)
														<span
															class="fs-nano text-muted mt-1">{{ $item['create_at']->diffInHours(date('Y-m-d H:i:s')) }}
															jam yang lalu</span>
													@else
														@if ($item['create_at']->diffInMinutes(date('Y-m-d H:i:s')) > 1)
															<span
																class="fs-nano text-muted mt-1">{{ $item['create_at']->diffInMinutes(date('Y-m-d H:i:s')) }}
																menit yang lalu</span>
														@else
															<span
																class="fs-nano text-muted mt-1">{{ $item['create_at']->diffInSeconds(date('Y-m-d H:i:s')) }}
																detik yang lalu</span>
														@endif
													@endif
												@else
													@if ($item['create_at']->isYesterday())
														<span class="fs-nano text-muted mt-1">Kemarin</span>
													@else
														<span class="fs-nano text-muted mt-1">{{ $item['create_at'] }}</span>
													@endif
												@endif
											</span>
										</a>
									</li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>
				@if (Auth::user()->roles[0]->title == 'Admin' || Auth::user()->roles[0]->title == 'Pejabat' || Auth::user()->roles[0]->title == 'Verifikator')
				<div id="panel-2" class="panel">
					<div class="panel-hdr">
						<h2>
							<i class="subheader-icon fal fa-ballot-check mr-1"></i><span class="color-info-700 fw-700"
								style="text-transform:uppercase">Pengajuan Baru</span>
						</h2>
						<div class="panel-toolbar">
							<a href="{{ route('verification.data') }}" data-toggle="tooltip" title
								data-original-title="Lihat semua pengajuan" class="btn btn-xs btn-warning waves-effect waves-themed"
								type="button" href="/">Lihat</a>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content p-0">
							<ul class="notification">
								@foreach ($newpengajuan as $item)
									<li>
										<a href="{{ route('verification.data.check', [$item->id]) }}"  class="d-flex align-items-center show-child-on-hover">
											<span class="mr-2">
												@if (!empty($item->data_user->logo))
													<img src="{{ Storage::disk('public')->url($item->data_user->logo) }}"
														class="profile-image rounded-circle" alt="">
												@else
													<img src="{{ asset('/img/avatars/farmer.png') }}"
														class="profile-image rounded-circle" alt="">
												@endif
											</span>
											<span class="d-flex flex-column flex-1">
												<span class="name">{{ $item->datauser->company_name }} <span
													class="badge badge-success fw-n position-absolute pos-top pos-right mt-1">NEW</span></span>
												<span class="msg-a fs-sm">
													{{ $item->no_pengajuan }}
												</span>
												<span class="fs-nano text-muted mt-1">{{ $item->created_at->diffForHumans() }}</span>
											</span>
										</a>
									</li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>
				@endif
			</div>
		</div> --}}
		<!-- Page Content -->
	@endcan
@endsection
@section('scripts')
	@parent

@endsection
