<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Verifikator\SklOldController;

Route::get('/', function () {
	return redirect()->route('login');
});

// Route::get('/v2/register', function () {
// 	return view('v2register');
// });

Route::get('/home', function () {
	if (session('status')) {
		return redirect()->route('admin.home')->with('status', session('status'));
	}

	return redirect()->route('admin.home');
});


Auth::routes(['register' => true]); // menghidupkan registration

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
	// landing
	Route::get('/', 'HomeController@index')->name('home');
	// Dashboard
	Route::get('/dashboard/main', 'DashboardController@index')->name('dashboard.main');
	Route::get('/dashboard/client', 'ClientDashboardController@index')->name('dashboard.client');


	// Permissions
	Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
	Route::resource('permissions', 'PermissionsController');

	// Roles
	Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
	Route::resource('roles', 'RolesController');

	// Users
	Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
	Route::resource('users', 'UsersController');

	// Audit Logs
	Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

	Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');

	Route::get('profile', 'ProfileController@index')->name('profile.show');
	Route::post('profile', 'ProfileController@store')->name('profile.store');
	Route::post('profile/{id}', 'ProfileController@update')->name('profile.update');
	Route::get('profile/pejabat', 'AdminProfileController@index')->name('profile.pejabat');
	Route::post('profile/pejabat/store', 'AdminProfileController@store')->name('profile.pejabat.store');

	//posts
	Route::put('posts/{post}/restore', 'PostsController@restore')->name('posts.restore');
	Route::resource('posts', 'PostsController');
	Route::get('allblogs', 'PostsController@allblogs')->name('allblogs');
	Route::post('posts/{post}/star', 'StarredPostController@star')->name('posts.star');
	Route::delete('posts/{post}/unstar', 'StarredPostController@unstar')->name('posts.unstar');

	//posts categories
	Route::resource('categories', 'CategoryController');

	//messenger
	Route::get('messenger', 'MessengerController@index')->name('messenger.index');
	Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
	Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
	Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
	Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
	Route::post('messenger/{topic}/update', 'MessengerController@updateTopic')->name('messenger.updateTopic');
	Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
	Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
	Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
	Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');

	//verifikasi
	// Route::get('dir_check_b', 'MessengerController@showReply')->name('verifikasi.dir_check_b');
	// Route::get('dir_check_c', 'MessengerController@showReply')->name('verifikasi.dir_check_c');

	//master products
	Route::get('master/products', 'ProductController@index')->name('master.products.index');
	Route::get('master/product/create', 'ProductController@create')->name('master.product.create');
	Route::post('master/product/store', 'ProductController@store')->name('master.product.store');
	Route::get('master/product/{product_id}/show', 'ProductController@show')->name('master.product.show');
	Route::get('master/product/{product_id}/edit', 'ProductController@edit')->name('master.product.edit');
	Route::put('master/product/{id}/update', 'ProductController@update')->name('master.product.update');
	Route::delete('master/product/{id}/delete', 'ProductController@destroy')->name('master.product.delete');

	//master company
	Route::get('master/companies', 'CompanyController@index')->name('master.companies.index');
	Route::post('master/companies/store', 'CompanyController@store')->name('master.company.store');
	Route::put('master/companies/{id}/update', 'CompanyController@update')->name('master.company.update');
	Route::delete('master/companies/{id}/delete', 'CompanyController@destroy')->name('master.company.delete');

	//master rek bank penerima
	Route::get('master/banks', 'BankController@index')->name('master.banks.index');
	Route::get('master/bank/create', 'BankController@create')->name('master.bank.create');
	Route::post('master/bank/store', 'BankController@store')->name('master.bank.store');
	Route::get('master/bank/{id}/show', 'BankController@show')->name('master.bank.show');
	Route::get('master/bank/{id}/edit', 'BankController@edit')->name('master.bank.edit');
	Route::put('master/bank/{id}/update', 'BankController@update')->name('master.bank.update');
	Route::delete('master/bank/{id}/delete', 'BankController@destroy')->name('master.bank.delete');

	//master customers
	Route::get('master/customers', 'CustomerController@index')->name('master.customers.index');
	Route::get('master/customer/create', 'CustomerController@create')->name('master.customer.create');
	Route::post('master/customer/store', 'CustomerController@store')->name('master.customer.store');
	Route::get('master/customer/{id}/show', 'CustomerController@show')->name('master.customer.show');
	Route::get('master/customer/{id}/edit', 'CustomerController@edit')->name('master.customer.edit');
	Route::put('master/customer/{id}/update', 'CustomerController@update')->name('master.customer.update');
	Route::delete('master/customer/{id}', 'CustomerController@destroy')->name('master.customer.delete');
	Route::get('customer/{id}/transactions', 'CustomerController@transactions')->name('customer.transactions');

	// transaction orders
	Route::get('orders', 'OrderController@index')->name('orders.index');
	Route::get('order/create', 'OrderController@create')->name('order.create');
	Route::post('order/store', 'OrderController@store')->name('order.store');
	Route::get('order/{id}/show', 'OrderController@show')->name('order.show');
	Route::get('order/{id}/edit', 'OrderController@edit')->name('order.edit');
	Route::put('order/{id}/update', 'OrderController@update')->name('order.update');
	Route::delete('order/{id}/delete', 'OrderController@destroy')->name('order.delete');

	// transaction payment
	Route::get('payments', 'PaymentController@index')->name('payments.index');
	Route::get('payment/create', 'PaymentController@create')->name('payment.create');
	Route::post('payment/store', 'PaymentController@store')->name('payment.store');
	Route::get('payment/{id}/show', 'PaymentController@show')->name('payment.show');
	Route::get('payment/{id}/edit', 'PaymentController@edit')->name('payment.edit');
	Route::put('payment/{id}/update', 'PaymentController@update')->name('payment.update');
	Route::delete('payment/{id}/delete', 'PaymentController@destroy')->name('payment.delete');
	Route::put('payment/{id}/setstatus', 'PaymentController@setStatus')->name('payment.setStatus');
	Route::put('payment/{id}/setValidation', 'PaymentController@setValidation')->name('payment.setValidation');

	//new method
	Route::post('payment/bulkstore', 'PaymentController@bulkstore')->name('payment.bulkstore');

	// report
	//new in cellas
	Route::get('report/orders/customerOrderByDate', 'ReportDataController@customerOrderByDate')->name('report.orders.customerOrderByDate');
	Route::get('report/insightByDateRange/client', 'ClientDashboardController@InsightByDateRange')->name('report.customerDashboard');

	//old
	Route::get('report/orders/data', 'ReportDataController@transactsData')->name('report.orders.data');
	Route::get('report/orders', 'ReportController@index')->name('report.orders');
	Route::get('report/orders/byDateRangeByBank', 'ReportDataController@byDateRangeByBank')->name('report.orders.byDateRangeByBank');
	Route::get('report/deposits/data', 'ReportDataController@depositsData')->name('report.deposits.data');
	Route::get('report/deposits', 'ReportController@deposit')->name('report.deposits');
	Route::get('report/insightByDateRange', 'ReportDataController@InsightByDateRange')->name('report.insightByDateRange'); //will be removed


	//user task
	Route::group(['prefix' => 'task', 'as' => 'task.'], function () {

		//berkas
		Route::get('berkas', 'BerkasController@indexberkas')->name('berkas');

		//galeri
		Route::get('galeri', 'BerkasController@indexgaleri')->name('galeri');

		//template
		Route::delete('template/destroy', 'BerkasController@massDestroy')->name('template.massDestroy');
		Route::delete('template/{id}', 'BerkasController@destroytemplate')->name('template.destroy');
		Route::get('template/{berkas}/edit', 'BerkasController@edittemplate')->name('template.edit');
		Route::put('template/{berkas}', 'BerkasController@updatetemplate')->name('template.update');
		// Route::get('template', 'BerkasController@indextemplate')->name('template');
		//Route::get('template/{berkas}', 'BerkasController@showtemplate')->name('template.show');
		// Route::get('template/create', 'BerkasController@createtemplate')->name('template.create');
		// Route::post('template', 'BerkasController@storetemplate')->name('template.store');

		Route::get('template', 'FileManagementController@templateindex')->name('template');
		Route::get('template/create', 'FileManagementController@templatecreate')->name('template.create');
		Route::post('template', 'FileManagementController@templatestore')->name('template.store');
	});
});

Route::group(['prefix' => 'verification', 'as' => 'verification.', 'namespace' => 'Verifikator', 'middleware' => ['auth']], function () {
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
	// Change password
	if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
		Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
		Route::post('password', 'ChangePasswordController@update')->name('password.update');
		Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
		Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
	}
});
