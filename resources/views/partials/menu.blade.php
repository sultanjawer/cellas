<aside class="page-sidebar">
	<div class="page-logo">
		<a href="/admin" class="page-logo-link press-scale-down d-flex align-items-center position-relative">
			<img src="{{ asset('img/logo-1.png') }}" alt="Cella's" aria-roledescription="logo">
			<span class="page-logo-text mr-1">{{ env('APP_NAME')}}</span>
		</a>
	</div>

	<!-- BEGIN PRIMARY NAVIGATION -->
	<nav id="js-primary-nav" class="primary-nav" role="navigation">
		{{-- search menu --}}
		<div class="nav-filter">
			<div class="position-relative">
				<input type="text" id="nav_filter_input" placeholder="Cari menu" class="form-control" tabindex="0">
				<a href="#" onclick="return false;" class="btn-primary btn-search-close js-waves-off"
					data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar">
					<i class="fal fa-chevron-up"></i>
				</a>
			</div>
		</div>

		{{-- picture --}}
		<div class="info-card">
			@if (!empty(Auth::user()::find(Auth::user()->id)->data_user->avatar))
				<img src="{{ Storage::disk('public')->url(Auth::user()::find(Auth::user()->id)->data_user->avatar) }}"
					class="profile-image rounded-circle" alt="">
			@else
				<img src="{{ asset('/img/avatars/avatar-f.png') }}" class="profile-image rounded-circle" alt="">
			@endif

			<div class="info-card-text">
				<a href="#" class="d-flex align-items-center text-white">
					<span class="text-truncate text-truncate-sm d-inline-block">
						{{ Auth::user()->username }}
					</span>
				</a>
				<span class="d-inline-block text-truncate text-truncate-sm">
					{{ Auth::user()::find(Auth::user()->id)->data_user->company_name ?? 'user' }}
				</span>
			</div>
			<img src="{{ asset('/img/menu_img.jpg') }}" class="cover" alt="cover">
			<a href="#" onclick="return false;" class="pull-trigger-btn" data-action="toggle"
				data-class="list-filter-active" data-target=".page-sidebar" data-focus="nav_filter_input">
				<i class="fal fa-angle-down"></i>
			</a>
		</div>
		<div class="container" style="background-color: rgba(0, 0, 0, 0.2)">
			<ul id="date" class="list-table m-auto pt-3 pb-3">
				<li>
					<span class="d-inline-block" style="color:white"
						data-filter-tags="date day today todate">
						<span class="nav-link-text js-get-date">Today</span>
					</span>
				</li>
			</ul>
		</div>
		<ul id="js-nav-menu" class="nav-menu">
			{{-- landing / beranda --}}
			@can('dashboard_access')
				<li class="c-sidebar-nav-item {{ request()->is('admin') ? 'active' : '' }}">
					<a href="{{ route('admin.home') }}" class="c-sidebar-nav-link"
						data-filter-tags="home beranda landing informasi berita pesan">
						<i class="c-sidebar-nav-icon fal fa-analytics">
						</i>
						<span class="nav-link-text">Dashboard</span>
					</a>
				</li>
			@endcan

			{{-- transaction --}}
			@can('transaction_access')
				<li class="nav-title">Transaction</li>
				@can('order_access')
					<li class="c-sidebar-nav-item {{ request()->is('admin/order*') ? 'active' : '' }}">
						<a href=""
							data-filter-tags="transaction orders">
							<i class="fa-fw fal fa-receipt c-sidebar-nav-icon">
							</i>
							Customer Orders
						</a>
					</li>
				@endcan
				@can('payment_access')
					<li class="c-sidebar-nav-item {{ request()->is('admin/payment*') ? 'active' : '' }}">
						<a href="{{route('admin.payments.index')}}" data-filter-tags="transaction payments deposits cash in out">
							<i class="fa-fw fal fa-cash-register c-sidebar-nav-icon">
							</i>
							Cash In/Out
						</a>
					</li>
				@endcan
			@endcan
			@can('master_access')
				<li class="nav-title">Master Data</li>
				@can('master_customer_access')
					<li class="c-sidebar-nav-item {{ request()->is('admin/master/customer*') ? 'active' : '' }}">
						<a href="{{route('admin.master.customers.index')}}"
							data-filter-tags="transaction orders">
							<i class="fa-fw fal fa-user-tie c-sidebar-nav-icon">
							</i>
							Customers
						</a>
					</li>
				@endcan
				@can('master_company_access')
					<li class="c-sidebar-nav-item {{ request()->is('admin/master/companies*') ? 'active' : '' }}">
						<a href="{{route('admin.master.companies.index')}}"
							data-filter-tags="transaction orders">
							<i class="fa-fw fal fa-landmark c-sidebar-nav-icon">
							</i>
							Companies
						</a>
					</li>
				@endcan
				@can('master_bank_access')
					<li class="c-sidebar-nav-item {{ request()->is('admin/master/bank*') ? 'active' : '' }}">
						<a href="{{route('admin.master.banks.index')}}"
							data-filter-tags="transaction orders">
							<i class="fa-fw fal fa-university c-sidebar-nav-icon">
							</i>
							Banks
						</a>
					</li>
				@endcan
				@can('master_product_access')
					<li class="c-sidebar-nav-item {{ request()->is('admin/master/product*') ? 'active' : '' }}">
						<a href="{{route('admin.master.products.index')}}"
							data-filter-tags="transaction orders">
							<i class="fa-fw fal fa-bags-shopping c-sidebar-nav-icon">
							</i>
							Products
						</a>
					</li>
				@endcan
			@endcan
			{{-- master data --}}

			{{-- pengelolaan berkas --}}
			{{-- @can('folder_access')
				<li class="nav-title">Pengelolaan Berkas</li>
				<li class="{{ request()->is('admin/task/berkas*')
					|| request()->is('admin/task/galeri*')
					|| request()->is('admin/task/template*') ? 'active open' : '' }} ">
					<a href="#" title="Pengelolaan Berkas"
						data-filter-tags="pengelolaan manajemen manajer berkas file unggahan unduhan foto">
						<i class="fa-fw fal fa-folders"></i>
						<span class="nav-link-text">{{ trans('cruds.folder.title_lang') }}</span>
					</a>
					<ul>

						@can('berkas_access')
							<li class="c-sidebar-nav-item {{ request()->is('admin/task/berkas')
								|| request()->is('admin/task/berkas/*') ? 'active' : '' }}">
								<a href="{{ route('admin.task.berkas') }} javascript:void()" title="Berkas"
									data-filter-tags="berkas file unggahan unduhan" class="disabled">
									<i class="fa-fw fal fa-file c-sidebar-nav-icon"></i>
									<span class="nav-link-text">{{ trans('cruds.berkas.title_lang') }}</span>
								</a>
							</li>
						@endcan
						@can('galeri_access')

							<li class="c-sidebar-nav-item {{ request()->is('admin/task/galeri')
								|| request()->is('admin/task/skl/*') ? 'active' : '' }}">

								<a href="{{ route('admin.task.galeri') }} javascript:void()" title="Galeri"
									data-filter-tags="galeri gallery daftar foto">
									<i class="fa-fw fal fa-images c-sidebar-nav-icon"></i>
									<span class="nav-link-text">{{ trans('cruds.galeri.title_lang') }}</span>
								</a>
							</li>
						@endcan
						@can('template_access')
							<li class="c-sidebar-nav-item {{ request()->is('admin/task/template')
								|| request()->is('admin/task/template/*') ? 'active' : '' }}">
								<a href="{{ route('admin.task.template') }}" title="Skl"
									data-filter-tags="daftar berkas file template">
									<i class="fa-fw fal fa-folder c-sidebar-nav-icon"></i>
									<span class="nav-link-text">{{ trans('cruds.template.title_lang') }}</span>
								</a>
								<a href="{{ route('admin.task.template') }}" title="Skl"
									data-filter-tags="daftar berkas file template">
									<i class="fa-fw fal fa-folder c-sidebar-nav-icon"></i>
									<span class="nav-link-text">{{ trans('cruds.template.title_lang') }}</span>
								</a>
							</li>
						@endcan
					</ul>
				</li>
			@endcan --}}

			{{-- Feed & Messages --}}
			@can('feedmsg_access')
				<li class="nav-title">Messenger</li>
				@can('feeds_access')
					{{-- <li class="{{ request()->is('admin/posts*')
						|| request()->is('admin/categories*') ? 'active open' : '' }}">
						<a href="#" title="Artikel/Berita"
							data-filter-tags="artikel berita informasi">
							<i class="fa-fw fal fa-rss c-sidebar-nav-icon"></i>
							<span class="nav-link-text">Artikel/Berita</span>
						</a>
						<ul>
							@can('feeds_access')
							<li class="c-sidebar-nav-item {{ request()->is('admin/categories')
								|| request()->is('admin/categories/*') ? 'active' : '' }}">
								<a href="{{ route('admin.categories.index') }}" title="Categories"
									data-filter-tags="categories kategori">
									<i class="fa-fw fal fa-rss c-sidebar-nav-icon"></i>
									Categories
								</a>
							</li>
							<li class="c-sidebar-nav-item {{ request()->is('admin/posts')
								|| request()->is('admin/posts/*') ? 'active' : '' }}">
								<a href="{{ route('admin.posts.index') }}" title="Posts"
									data-filter-tags="post artikel berita">
									<i class="fa-fw fal fa-rss c-sidebar-nav-icon"></i>
									Articles
								</a>
							</li>
							@endcan
						</ul>
					</li> --}}
				@endcan
				@can('messenger_access')
					@php($unread = \App\Models\QaTopic::unreadCount())
					<li class="c-sidebar-nav-item {{ request()->is('admin/messenger')
						|| request()->is('admin/messenger/*') ? 'active' : '' }}">
						<a href="{{ route('admin.messenger.index') }}"
							data-filter-tags="kirim pesan perpesanan send message messenger">
							<i class="c-sidebar-nav-icon fal fa-envelope"></i>
							<span class="nav-link-text">Messenger</span>
							@if ($unread > 0)
								<span
									class="dl-ref bg-primary-500 hidden-nav-function-minify hidden-nav-function-top">{{ $unread }}
									pesan</span>
							@endif
						</a>
					</li>
				@endcan
			@endcan
			{{-- end feed --}}

			{{-- administrator access --}}
			@can('administrator_access')
				<li class="nav-title" data-i18n="nav.administation">ADMINISTRATOR</li>
				{{-- user Management --}}
				@can('user_management_access')
					<li class="{{ request()->is('admin/permissions*')
						|| request()->is('admin/roles*') || request()->is('admin/users*')
						|| request()->is('admin/audit-logs*') ? 'active open' : '' }} ">
						<a href="#" title="User Management"
							data-filter-tags="setting permission user">
							<i class="fal fal fa-users"></i>
							<span class="nav-link-text">User Management</span>
						</a>
						<ul>
							@can('permission_access')
								<li class="c-sidebar-nav-item {{ request()->is('admin/permissions')
									|| request()->is('admin/permissions/*') ? 'active' : '' }}">
									<a href="{{ route('admin.permissions.index') }}" title="Permission"
										data-filter-tags="setting daftar permission user">
										<i class="fa-fw fal fa-unlock-alt c-sidebar-nav-icon"></i>
										<span class="nav-link-text">{{ trans('cruds.permission.title_lang') }}</span>
									</a>
								</li>
							@endcan
							@can('role_access')
								<li class="c-sidebar-nav-item {{ request()->is('admin/roles')
									|| request()->is('admin/roles/*') ? 'active' : '' }}">
									<a href="{{ route('admin.roles.index') }}" title="Roles"
										data-filter-tags="setting role user">
										<i class="fa-fw fal fa-briefcase c-sidebar-nav-icon"></i>
										<span class="nav-link-text">{{ trans('cruds.role.title_lang') }}</span>
									</a>
								</li>
							@endcan
							@can('user_access')
								<li class="c-sidebar-nav-item {{ request()->is('admin/users')
									|| request()->is('admin/users/*') ? 'active' : '' }}">
									<a href="{{ route('admin.users.index') }}" title="User"
										data-filter-tags="setting user pengguna">
										<i class="fa-fw fal fa-user c-sidebar-nav-icon"></i>
										<span class="nav-link-text">Users</span>
									</a>
								</li>
							@endcan
							@can('audit_log_access')
								<li class="c-sidebar-nav-item {{ request()->is('admin/audit-logs')
									|| request()->is('admin/audit-logs/*') ? 'active' : '' }}">
									<a href="{{ route('admin.audit-logs.index') }}" title="Audit Log"
										data-filter-tags="setting log_access audit">
										<i class="fa-fw fal fa-file-alt c-sidebar-nav-icon"></i>
										<span class="nav-link-text">Activity Logs</span>
									</a>
								</li>
							@endcan
						</ul>
					</li>
				@endcan

				{{-- data report --}}
				@can('data_report_access')
					<li
						class="{{ request()->is('admin/datareport') || request()->is('admin/datareport/*') ? 'active open' : '' }}">
						<a href="#" title="Data Report"
							data-filter-tags="lapoan wajib tanam produksi report realisasi">
							<i class="fal fa-print c-sidebar-nav-icon"></i>
							<span class="nav-link-text">{{ trans('cruds.datareport.title_lang') }}</span>
						</a>
						<ul>
							@can('commitment_list_access')
								<li class="c-sidebar-nav-item {{ request()->is('admin/datareport/comlist') ? 'active' : '' }}">
									<a href="{{ route('admin.audit-logs.index') }}" title="Commitment List"
										data-filter-tags="laporan realisasi komitmen">
										<i class="fa-fw fal fa-file-alt c-sidebar-nav-icon"></i>
										<span class="nav-link-text">{{ trans('cruds.commitmentlist.title_lang') }}</span>
									</a>
								</li>
							@endcan
						</ul>
					</li>
				@endcan
			@endcan

			{{-- personalisasi --}}
			<li class="nav-title" data-i18n="nav.administation">Personalization</li>
			{{-- Change Password --}}
			@if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
				@can('profile_password_edit')
					<li
						class="c-sidebar-nav-item {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}">
						<a href="{{ route('profile.password.edit') }}"
							data-filter-tags="personalisasi ganti ubah change password ">
							<i class="fa-fw fas fa-key c-sidebar-nav-icon">
							</i>
							Change Password
						</a>
					</li>
				@endcan
			@endif

			{{-- logout --}}
			<li class="c-sidebar-nav-item">
				<a href="#" class="c-sidebar-nav-link"
					data-filter-tags="keluar log out tutup"
					onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
					<i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

					</i>
					Log Me Out!
				</a>
			</li>
		</ul>
	</nav>
	<!-- END PRIMARY NAVIGATION -->

</aside>
