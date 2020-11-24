<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => App\Http\Middleware\LocaleMiddleware::getLocale()], function(){
	
	
	Route::get('/', 'IndexController@index')->name('home');

    Route::post('pay/request', 'PayController@request')->name('pay');
    Route::any('pay/response', 'PayController@response')->name('payResponse');

    Route::get('pay/client-request/{idHash}', 'PayController@payClientRequest')->name('payClientRequest');
    Route::post('pay/client-request-process', 'PayController@payClientRequestProcess')->name('payClientRequestProcess');
		
		
	Route::get('photo-video', 'IndexController@photo_video_page')->name('photo-video');

	Route::get('successful', 'IndexController@successful_page')->name('successful_payment');
	
	Route::get('terms-and-conditions', 'IndexController@terms_conditions_page')->name('terms-and-conditions');
	
	Route::get('privacy-policy', 'IndexController@privacy_policy_page')->name('privacy');
	
	Route::get('testimonials', 'IndexController@testimonials')->name('testimonials');
	
	Route::get('services', 'ServicesController@services')->name('services');

    Route::get('procedure', 'IndexController@procedure_page')->name('procedure');
	
	Route::get('reservation', 'IndexController@reservation')->name('reservation');
	
	Route::get('service/{url}', 'ServicesController@getService')->name('service');
	
	Route::get('articles', 'ArticlesController@articleList')->name('articles');
	
	Route::get('article/{url}', 'ArticlesController@getArticle')->name('article');
	
	Route::get('groups', 'IndexController@groups_page')->name('groups');
	
	Route::get('contacts', 'IndexController@contacts_page')->name('contacts');
	
	Route::any('contactsAJAX', 'IndexController@contacts_sendemail')->name('contactsAJAX');
	
	
	
	
	
	Route::get('login', 'IndexController@login')->name('login');
	Route::post('login', 'IndexController@postLogin');
	
	//Route::get('register', 'IndexController@register')->name('register');
	//Route::post('register', 'IndexController@postRegister');
	
	Route::get('logout', 'IndexController@logout')->name('logout');
	
	// Password reset link request routes...
	Route::get('password/email', 'Auth\PasswordController@getEmail')->name('paswordEmail');
	Route::post('password/email', 'Auth\PasswordController@postEmail');
	
	// Password reset routes...
	Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
	Route::post('password/reset', 'Auth\PasswordController@postReset')->name('password.reset');
	
	Route::get('auth/confirm/{code}', 'IndexController@confirm');
	
	//Route::post('users/login', 'Auth\AuthController@postLogin');

    Route::post('get/hourlist', 'ReservationController@getHourList')->name('getHourList');
    Route::get('get/reservations-count', 'ReservationController@getReservationsCount')->name('getreservationsCount');
    Route::post('group-order-place', 'ReservationController@placeGroupOrder')->name('placeGroupOrder');




    Route::group(['middleware' => ['auth']], function () {
	
		Route::get('wallets', 'WalletsController@index')->name('wallets');
		Route::get('cards', 'CardsController@index')->name('cards');
		Route::get('settings', 'SettingsController@index')->name('userSettings');
		
	
	});
	
	Route::group(['middleware' => ['admin'], 'namespace' => 'Admin', 'prefix' => 'admin'], function () {
		
		Route::get('/', 'IndexController@index');
		Route::post('login', 'IndexController@postLogin');
		Route::get('logout', 'IndexController@logout');
		Route::post('reservation', 'IndexController@reservation');
		Route::post('groups', 'IndexController@groups');
		Route::post('about', 'IndexController@about_page');
		Route::post('about', 'IndexController@groups_page');
		
		Route::get('profile', 'AdminController@profile');
		Route::post('profile', 'AdminController@updateProfile');
		Route::post('profile_pass', 'AdminController@updatePassword');
		
		Route::get('settings', 'SettingsController@settings')->name('settings');
		Route::post('settings', 'SettingsController@settingsUpdates');
		Route::post('social_links', 'SettingsController@social_links_update');
		Route::post('addthisdisqus', 'SettingsController@addthisdisqus');
		Route::post('about', 'SettingsController@about_page');
		Route::post('careers_with_us', 'SettingsController@careers_with_us_page');
		Route::post('terms_conditions', 'SettingsController@terms_conditions_page');
		Route::post('privacy_policy', 'SettingsController@privacy_policy_page');
		Route::post('prices', 'SettingsController@prices_page');
		Route::post('headfootupdate', 'SettingsController@headfootupdate');
		
		Route::get('dashboard', 'IndexController@dashboard')->name('dashboard');
		
		Route::get('users', 'UsersController@userslist')->name('users');
		Route::get('users/add', 'UsersController@addeditUser')->name('userAdd');
		Route::post('users/add', 'UsersController@addnew')->name('POSTUserAdd');
		Route::get('users/edit/{id}', 'UsersController@editUser')->name('userEdit');
		Route::get('users/delete/{id}', 'UsersController@delete')->name('userDelete');
		
		Route::get('articles', 'ArticlesController@list')->name('adminArticles');
		Route::get('article/add', 'ArticlesController@addedit')->name('articleAdd');
		Route::post('article/add', 'ArticlesController@addnew')->name('POSTArticleAdd');
		Route::get('article/edit/{id}', 'ArticlesController@edit')->name('articleEdit');
		Route::get('article/delete/{id}', 'ArticlesController@delete')->name('articleDelete');

        Route::get('articles', 'ReservationsController@index')->name('adminReservations');
        Route::get('reservations', 'ReservationsController@index')->name('adminReservations');
        Route::post('reservations/get', 'ReservationsController@getReservations')->name('adminGetReservations');
        Route::post('reservations/reservationsList', 'ReservationsController@getReservationsList')->name('adminGetReservationsList');
        Route::post('reservations/groupList', 'ReservationsController@getGroupList')->name('adminGetGroupList');
        Route::post('reservations/groupOrder', 'ReservationsController@getGroupOrder')->name('adminGetGroupOrder');
        Route::post('reservations/set', 'ReservationsController@setReservation')->name('adminSetReservation');
        Route::post('reservations/getReservationToEdit', 'ReservationsController@getReservationToEdit')->name('adminGetReservationToEdit');
        Route::post('reservations/saveCreatedOrEditedReservation', 'ReservationsController@saveCreatedOrEditedReservation')->name('adminSaveCreatedOrEditedReservation');
        Route::post('reservations/deleteReservation', 'ReservationsController@deleteReservation')->name('adminDeleteReservation');



        Route::post('reservations/hourlist', 'ReservationsController@getHourListAdmin')->name('adminGetHourListAdmin');

        Route::get('baths/getlist', 'BathsController@getList')->name('adminGetList');
        Route::post('baths/getBusyRooms', 'BathsController@getBusyRooms')->name('adminGetBusyRooms');

        Route::get('room/getlist', 'RoomController@getList')->name('adminGetRooms');

        Route::get('reservationslist', 'ReservationsController@byList')->name('adminReservationsList');

        Route::get('services', 'ServicesController@list')->name('adminServices');
		Route::get('service/add', 'ServicesController@addedit')->name('serviceAdd');
		Route::post('service/add', 'ServicesController@addnew')->name('POSTServiceAdd');
		Route::get('service/edit/{id}', 'ServicesController@edit')->name('serviceEdit');
		Route::get('service/delete/{id}', 'ServicesController@delete')->name('serviceDelete');
		
	});
	

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
