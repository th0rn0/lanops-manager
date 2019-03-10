<?php

/**
 * Login & Register
 */
Route::group(['middleware' => ['web']], function () {
	
	Route::get('/login', 'Auth\SteamAuthController@login');
	Route::get('/register', 'Auth\SteamAuthController@register');
	Route::post('/account/register', 'Auth\SteamAuthController@store');
	
	Route::group(['middleware' => ['auth']], function () {
		Route::get('/account', 'AccountController@index');
		Route::post('/account/delete', 'Auth\SteamAuthController@destroy');
		Route::get('/logout', 'Auth\SteamAuthController@doLogout');
	});
});

/**
 * API
 */
// TODO - Move these endpoints to their own controllers EG src/app/Http/Controllers/Api
Route::get('/api/events/{event}/timetables', 'Events\TimetablesController@index');
Route::get('/api/events/{event}/timetables/{timetable}', 'Events\TimetablesController@show');

Route::get('/api/events/{event}/participants', 'Events\ParticipantsController@show');

Route::get('/api/events/{event}/tickets', 'Events\TicketsController@index');
Route::get('/api/events/{event}/tickets/{ticket}', 'Events\TicketsController@show');

Route::group(['middleware' => ['web']], function () {


	/**
	 * Index Page
	 */
	Route::get('/', 'HomeController@index');

	/**
	 * News Pages
	 */
	Route::get('/news', 'NewsController@index');
	Route::get('/news/{news_article}', 'NewsController@show');
	Route::post('/news/{news_article}/comments', 'NewsController@storeComment');
	Route::post('/news/{news_article}/comments/{news_comment}', 'NewsController@editComment');
	Route::get('/news/{news_article}/comments/{news_comment}/report', 'NewsController@reportComment');
	Route::get('/news/{news_article}/comments/{news_comment}/delete', 'NewsController@destroyComment');
	Route::get('/news/tags/{news_tag}', 'NewsController@showTag');

	/**
	 * Events
	 */
	Route::get('/events', 'Events\EventsController@index');
	Route::get('/events/{event}', 'Events\EventsController@show');
	Route::get('/events/{event}/big', 'HomeController@bigScreen');

	/**
	 * Misc Pages
	 */
	Route::get('/about', 'HomeController@about');
	Route::get('/contact', 'HomeController@contact');

	/**
	 * Tickets
	 */
	Route::get('/tickets/retrieve/{participant}', 'Events\TicketsController@retrieve');
	Route::post('/tickets/purchase/{ticket}', 'Events\TicketsController@purchase');

	/**
	 * Gifts
	 */
	Route::get('/gift/accept', 'Events\ParticipantsController@acceptGift');
	Route::post('/gift/{participant}', 'Events\ParticipantsController@gift');
	Route::post('/gift/{participant}/revoke', 'Events\ParticipantsController@revokeGift');

	/**
	 * Galleries
	 */
	Route::get('/gallery', 'GalleryController@index');
	Route::get('/gallery/{album}', 'GalleryController@show');

	/**
	 * Tournaments
	 */
	Route::get('/events/{event}/tournaments', 'Events\TournamentsController@index');
	Route::get('/events/{event}/tournaments/{tournament}', 'Events\TournamentsController@show');
	Route::post('/events/{event}/tournaments/{tournament}/register', 'Events\TournamentsController@registerSingle');
	Route::post('/events/{event}/tournaments/{tournament}/register/team', 'Events\TournamentsController@registerTeam');
	Route::post('/events/{event}/tournaments/{tournament}/register/pug', 'Events\TournamentsController@registerPug');
	Route::post('/events/{event}/tournaments/{tournament}/register/remove', 'Events\TournamentsController@unregister');

	/**
	 * Payments
	 */
	Route::get('/payment/review', 'PaymentsController@review');
	Route::get('/payment/callback', 'PaymentsController@process');
	Route::post('/payment/post', 'PaymentsController@post');
	Route::get('/payment/failed', 'PaymentsController@failed');
	Route::get('/payment/cancelled', 'PaymentsController@cancelled');
	Route::get('/payment/successful/{purchase}', 'PaymentsController@successful');

	/**
	 * Seating
	 */
	Route::post('/events/{event}/seating/{seating_plan}', 'Events\SeatingController@store');
	Route::delete('/events/{event}/seating/{seating_plan}', 'Events\SeatingController@destroy');

	/**
	 * Admin
	 */
	Route::group(['middleware' => ['admin']], function () {

		/**
		 * Index Page
		 */
		Route::get('/admin', 'Admin\AdminController@index');

		/**
		 * Events
		 */
		Route::get('/admin/events', 'Admin\Events\EventsController@index');
		Route::post('/admin/events', 'Admin\Events\EventsController@store');
		Route::get('/admin/events/{event}', 'Admin\Events\EventsController@show');
		Route::post('/admin/events/{event}', 'Admin\Events\EventsController@update');
		Route::delete('/admin/events/{event}', 'Admin\Events\EventsController@destroy');
		Route::post('/admin/events/{event}/information', 'Admin\Events\InformationController@store');
		Route::post('/admin/information/{information}', 'Admin\Events\InformationController@update');
		Route::delete('/admin/information/{information}', 'Admin\Events\InformationController@destroy');
		/**
		 * Seating
		 */
		Route::get('/admin/events/{event}/seating', 'Admin\Events\SeatingController@index');
		Route::post('/admin/events/{event}/seating', 'Admin\Events\SeatingController@store');
		Route::get('/admin/events/{event}/seating/{seating_plan}', 'Admin\Events\SeatingController@show');
		Route::post('/admin/events/{event}/seating/{seating_plan}', 'Admin\Events\SeatingController@update');
		Route::delete('/admin/events/{event}/seating/{seating_plan}', 'Admin\Events\SeatingController@destroy');
		Route::post('/admin/events/{event}/seating/{seating_plan}/seat', 'Admin\Events\SeatingController@storeSeat');
		Route::delete('/admin/events/{event}/seating/{seating_plan}/seat', 'Admin\Events\SeatingController@destroySeat');

		/**
		 * Timetables
		 */
		Route::get('/admin/events/{event}/timetables', 'Admin\Events\TimetablesController@index');
		Route::post('/admin/events/{event}/timetables', 'Admin\Events\TimetablesController@store');
		Route::get('/admin/events/{event}/timetables/{timetable}', 'Admin\Events\TimetablesController@show');
		Route::post('/admin/events/{event}/timetables/{timetable}', 'Admin\Events\TimetablesController@update');
		Route::delete('/admin/events/{event}/timetables/{timetable}', 'Admin\Events\TimetablesController@destroy');
		Route::post('/admin/events/{event}/timetables/{timetable}/data', 'Admin\Events\TimetableDataController@store');
		Route::post('/admin/events/{event}/timetables/{timetable}/data/{data}', 'Admin\Events\TimetableDataController@update');

		/**
		 * Tournaments
		 */
		Route::get('/admin/events/{event}/tournaments', 'Admin\Events\TournamentsController@index');
		Route::post('/admin/events/{event}/tournaments', 'Admin\Events\TournamentsController@store');
		Route::get('/admin/events/{event}/tournaments/{tournament}', 'Admin\Events\TournamentsController@show');
		Route::post('/admin/events/{event}/tournaments/{tournament}', 'Admin\Events\TournamentsController@update');
		Route::delete('/admin/events/{event}/tournaments/{tournament}', 'Admin\Events\TournamentsController@destroy');
		Route::post('/admin/events/{event}/tournaments/{tournament}/start', 'Admin\Events\TournamentsController@start');
		Route::post('/admin/events/{event}/tournaments/{tournament}/finalize', 'Admin\Events\TournamentsController@finalize');
		Route::post('/admin/events/{event}/tournaments/{tournament}/match', 'Admin\Events\TournamentsController@updateMatch');
		Route::post('/admin/events/{event}/tournaments/{tournament}/participants/{participant}/team', 'Admin\Events\TournamentsController@updateParticipantTeam');

		// TODO - REMOVE THIS AND ALL LIKE IT
		/**
		 * Legacy
		 */
		Route::get('/admin/events/tournaments/fix', 'Admin\Events\TournamentsController@fixScores');


		/**
		 * Games
		 */
		Route::get('/admin/games',  'Admin\GamesController@index');
		Route::post('/admin/games',  'Admin\GamesController@store');
		Route::get('/admin/games/{game}',  'Admin\GamesController@show');
		Route::post('/admin/games/{game}',  'Admin\GamesController@update');
		Route::delete('/admin/games/{game}',  'Admin\GamesController@destroy');

		/**
		 * Participants
		 */
		Route::get('/admin/events/{event}/participants', 'Admin\Events\ParticipantsController@index');
		Route::get('/admin/events/{event}/participants/{participant}', 'Admin\Events\ParticipantsController@show');
		Route::post('/admin/events/{event}/participants/{participant}', 'Admin\Events\ParticipantsController@update');
		Route::post('/admin/events/{event}/participants/{participant}/signin', 'Admin\Events\ParticipantsController@signIn');

		/**
		 * Annoucements
		 */
		Route::post('/admin/events/{event}/annoucements', 'Admin\Events\AnnoucementsController@store');
		Route::post('/admin/events/{event}/annoucements/{annoucement}', 'Admin\Events\AnnoucementsController@update');
		Route::delete('/admin/events/{event}/annoucements/{annoucement}', 'Admin\Events\AnnoucementsController@destroy');

		/**
		 * Tickets
		 */
		Route::get('/admin/events/{event}/tickets', 'Admin\Events\TicketsController@index');
		Route::post('/admin/events/{event}/tickets', 'Admin\Events\TicketsController@store');
		Route::get('/admin/events/{event}/tickets/{ticket}', 'Admin\Events\TicketsController@show');
		Route::post('/admin/events/{event}/tickets/{ticket}', 'Admin\Events\TicketsController@update');
		Route::delete('/admin/events/{event}/tickets/{ticket}', 'Admin\Events\TicketsController@destroy');

		/**
		 * Gifts
		 */
		Route::post('/admin/events/{event}/freebies/admin', 'Admin\Events\EventsController@freeStaff');
		Route::post('/admin/events/{event}/freebies/gift', 'Admin\Events\EventsController@freeGift');

		/**
		 * Sponsors
		 */
		Route::post('/admin/events/{event}/sponsors', 'Admin\Events\SponsorsController@store');

		/**
		 * Venues
		 */
		Route::get('/admin/venues', 'Admin\Events\VenuesController@index');
		Route::post('/admin/venues', 'Admin\Events\VenuesController@store');
		Route::get('/admin/venues/{venue}', 'Admin\Events\VenuesController@show');
		Route::post('/admin/venues/{venue}', 'Admin\Events\VenuesController@update');
		Route::delete('/admin/venues/{venue}', 'Admin\Events\VenuesController@destroy');
		Route::post('/admin/venues/{venue}/{image}', 'Admin\Events\VenuesController@updateImage');
		Route::delete('/admin/venues/{venue}/{image}', 'Admin\Events\VenuesController@destroyImage');
		
		/**
		 * Galleries
		 */
		Route::get('/admin/gallery', 'Admin\GalleryController@index');
		Route::post('/admin/gallery', 'Admin\GalleryController@store');
		Route::get('/admin/gallery/{album}', 'Admin\GalleryController@show');
		Route::post('/admin/gallery/{album}', 'Admin\GalleryController@update');
		Route::delete('/admin/gallery/{album}', 'Admin\GalleryController@destroy');
		Route::post('/admin/gallery/{album}/upload', 'Admin\GalleryController@uploadImage');
		Route::post('/admin/gallery/{album}/{image}', 'Admin\GalleryController@updateImage');
		Route::delete('/admin/gallery/{album}/{image}', 'Admin\GalleryController@destroyImage');

		/**
		 * Users
		 */
		Route::get('/admin/users', 'Admin\UsersController@index');

		/**
		 * Settings
		 */
		Route::get('/admin/settings', 'Admin\SettingsController@index');
		Route::post('/admin/settings', 'Admin\SettingsController@update');
		Route::get('/admin/settings/link/{social}', 'Admin\SettingsController@linkSocial');
		Route::delete('/admin/settings/unlink/{social}', 'Admin\SettingsController@unlinkSocial');
		Route::post('/admin/settings/generate/qr', 'Admin\SettingsController@regenerateQRCodes');

		/**
		 * News
		 */
		Route::get('/admin/news', 'Admin\NewsController@index');
		Route::post('/admin/news', 'Admin\NewsController@store');
		Route::get('/admin/news/{news_article}', 'Admin\NewsController@show');
		Route::post('/admin/news/{news_article}', 'Admin\NewsController@update');
		Route::delete('/admin/news/{news_article}', 'Admin\NewsController@destroy');
		Route::get('/admin/news/{news_article}/comments/{news_comment}/delete', 'Admin\NewsController@destroyComment');
		Route::get('/admin/news/{news_article}/comments/{news_comment}/approve', 'Admin\NewsController@approveComment');
		Route::get('/admin/news/{news_article}/comments/{news_comment}/reject', 'Admin\NewsController@rejectComment');
		Route::get('/admin/news/{news_article}/comments/{news_comment}/reports/{news_comment_report}/delete', 'Admin\NewsController@destroyReport');
	});
});