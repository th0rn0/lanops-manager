<?php

/**
 * API
 */
Route::group(['middleware' => ['api']], function () {
    Route::get('/api/events/', 'Api\Events\EventsController@index');
    Route::get('/api/events/upcoming', 'Api\Events\EventsController@showUpcoming');
    Route::get('/api/events/next', 'Api\Events\EventsController@showNext');
    Route::get('/api/events/{event}', 'Api\Events\EventsController@show');
});

/**
 * Front End
 */
Route::group(['middleware' => ['web']], function () {
    /**
     * Login & Register
     */
    Route::get('/register/{method}', 'Auth\AuthController@showRegister');
    Route::post('/register/{method}', 'Auth\AuthController@register');

    Route::get('/login', 'Auth\AuthController@prompt');
    Route::get('/login/steam', 'Auth\SteamController@login');

    // LEGACY AUTH ROUTES
    // Route::post('/login/standard', 'Auth\LoginController@login');
    // Route::get('/register/email/verify', 'Auth\VerificationController@show')->name('verification.notice');
    // Route::get('/register/email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
    // Route::get('/register/email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

    // Route::get('/login/forgot', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    // Route::post('/login/forgot', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

    // Route::post('/login/reset/password', 'Auth\ResetPasswordController@reset')->name('password.update');
    // Route::get('/login/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

    Route::group(['middleware' => ['auth', 'banned', 'verified']], function () {
        Route::get('/account', 'AccountController@index')->name('accounts');
        Route::post('/account', 'AccountController@update');
        // Route::post('/account/delete', 'AccountController@destroy');
        Route::get('/account/discord/callback', 'AccountController@linkDiscord');
        Route::post('/account/discord/unlink', 'AccountController@unlinkDiscord');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/logout', 'Auth\AuthController@logout');
    });
    
    /**
     * Index Page
     */
    Route::get('/', 'HomeController@index');

    /**
     * News Pages
     */
    Route::get('/news', 'NewsController@index');
    Route::get('/news/{newsArticle}', 'NewsController@show');
    Route::post('/news/{newsArticle}/comments', 'NewsController@storeComment');
    Route::post('/news/{newsArticle}/comments/{newsComment}', 'NewsController@editComment');
    Route::get('/news/{newsArticle}/comments/{newsComment}/report', 'NewsController@reportComment');
    Route::get('/news/{newsArticle}/comments/{newsComment}/delete', 'NewsController@destroyComment');
    Route::get('/news/tags/{newsTag}', 'NewsController@showTag');

    /**
     * Events
     */
    Route::get('/events', 'Events\EventsController@index');
    Route::get('/events/{event}', 'Events\EventsController@show');

    /**
     * Misc Pages
     */
    Route::get('/about', 'HomeController@about');
    Route::get('/contact', 'HomeController@contact');
    Route::get('/terms', 'HomeController@terms');
    Route::get('/info', 'HomeController@info');

    /**
     * Tickets
     */
    Route::group(['middleware' => ['auth', 'banned', 'verified']], function () {
        Route::get('/tickets/retrieve/{participant}', 'Events\TicketsController@retrieve');
        Route::post('/tickets/purchase/{ticket}', 'Events\TicketsController@purchase');
    });

    /**
     * Gifts
     */
    Route::group(['middleware' => ['auth', 'banned', 'verified']], function () {
        Route::get('/gift/accept', 'Events\ParticipantsController@acceptGift');
        Route::post('/gift/{participant}', 'Events\ParticipantsController@gift');
        Route::post('/gift/{participant}/revoke', 'Events\ParticipantsController@revokeGift');
    });

    /**
     * Galleries
     */
    Route::get('/gallery', 'GalleryController@index');
    Route::get('/gallery/{album}', 'GalleryController@show');

    /**
     * Payments
     */
    Route::group(['middleware' => ['auth', 'banned', 'verified']], function () {    
        Route::get('/payment/checkout', 'PaymentsController@showCheckout');
        Route::post('/payment/checkout/code', 'PaymentsController@applyDiscountCode');
        Route::get('/payment/review/{paymentGateway}', 'PaymentsController@showReview');
        Route::get('/payment/details/{paymentGateway}', 'PaymentsController@showDetails');
        Route::get('/payment/callback', 'PaymentsController@processCallback');
        Route::post('/payment/post', 'PaymentsController@postPayment');
        Route::get('/payment/failed', 'PaymentsController@showFailed');
        Route::get('/payment/cancelled', 'PaymentsController@showCancelled');
        Route::get('/payment/successful/{purchase}', 'PaymentsController@showSuccessful');
    });

    /**
     * Seating
     */
    Route::group(['middleware' => ['auth', 'banned', 'verified']], function () {    
        Route::post('/events/{event}/seating/{seatingPlan}', 'Events\SeatingController@store');
        Route::delete('/events/{event}/seating/{seatingPlan}', 'Events\SeatingController@destroy');
    });

    /**
     * Search
     */
    Route::get('/search/users/autocomplete', 'SearchController@usersAutocomplete')->name('autocomplete');

    /**
     * Polls
     */
    Route::get('/polls', 'PollsController@index');
    Route::get('/polls/{poll}', 'PollsController@show');
    Route::group(['middleware' => ['auth', 'banned', 'verified']], function () {    
        Route::post('/polls/{poll}/options', 'PollsController@storeOption');
        Route::get('/polls/{poll}/options/{option}/vote', 'PollsController@vote');
        Route::get('/polls/{poll}/options/{option}/abstain', 'PollsController@abstain');
    });

    /**
     * Big Screen
     */
    Route::get('/bigscreen/timetable', 'BigScreenController@timetable');
    Route::get('/bigscreen/seating', 'BigScreenController@seating');

    /**
     * Tournaments
     */
    Route::get('/tournaments', 'TournamentsController@index');
    Route::get('/tournaments/{tournament}', 'TournamentsController@show');
    Route::group(['middleware' => ['auth', 'banned', 'verified']], function () {    
        Route::post('/tournaments/{tournament}/register', 'TournamentsController@register');
        Route::post('/tournaments/{tournament}/unregister', 'TournamentsController@unregister');
        Route::post('/tournaments/{tournament}/registerTeam', 'TournamentsController@registerTeam');
    });
});

/**
 * Admin
 */
Route::group(['middleware' => ['web', 'admin']], function () {

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
    Route::post('/admin/events/{event}/discord/link', 'Admin\Events\EventsController@linkDiscord');
    
    /**
     * Seating
     */
    Route::get('/admin/events/{event}/seating', 'Admin\Events\SeatingController@index');
    Route::post('/admin/events/{event}/seating', 'Admin\Events\SeatingController@store');
    Route::get('/admin/events/{event}/seating/{seatingPlan}', 'Admin\Events\SeatingController@show');
    Route::post('/admin/events/{event}/seating/{seatingPlan}', 'Admin\Events\SeatingController@update');
    Route::delete('/admin/events/{event}/seating/{seatingPlan}', 'Admin\Events\SeatingController@destroy');
    Route::post('/admin/events/{event}/seating/{seatingPlan}/seat', 'Admin\Events\SeatingController@storeSeat');
    Route::delete('/admin/events/{event}/seating/{seatingPlan}/seat', 'Admin\Events\SeatingController@destroySeat');

    /**
     * Timetables
     */
    Route::get('/admin/events/{event}/timetables', 'Admin\Events\TimetablesController@index');
    Route::post('/admin/events/{event}/timetables', 'Admin\Events\TimetablesController@store');
    Route::get('/admin/events/{event}/timetables/{timetable}', 'Admin\Events\TimetablesController@show');
    Route::post('/admin/events/{event}/timetables/{timetable}', 'Admin\Events\TimetablesController@update');
    Route::delete('/admin/events/{event}/timetables/{timetable}', 'Admin\Events\TimetablesController@destroy');
    Route::post('/admin/events/{event}/timetables/{timetable}/data', 'Admin\Events\TimetableDataController@store');
    Route::post(
        '/admin/events/{event}/timetables/{timetable}/data/{data}',
        'Admin\Events\TimetableDataController@update'
    );

    /**
     * Participants
     */
    Route::get('/admin/events/{event}/participants', 'Admin\Events\ParticipantsController@index');
    Route::get('/admin/events/{event}/participants/{participant}', 'Admin\Events\ParticipantsController@show');
    Route::post('/admin/events/{event}/participants/{participant}', 'Admin\Events\ParticipantsController@update');
    Route::post(
        '/admin/events/{event}/participants/{participant}/signin',
        'Admin\Events\ParticipantsController@signIn'
    );
    Route::post(
        '/admin/events/{event}/participants/{participant}/transfer',
        'Admin\Events\ParticipantsController@transfer'
    );

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
    Route::get('/admin/gallery/{album}/ingest', 'Admin\GalleryController@ingestImages');
    Route::post('/admin/gallery/{album}/upload', 'Admin\GalleryController@uploadImage');
    Route::delete('/admin/gallery/{album}/{image}', 'Admin\GalleryController@destroyImage');

    /**
     * Users
     */
    Route::get('/admin/users', 'Admin\UsersController@index');
    Route::get('/admin/users/referralcodes', 'Admin\UsersController@generalReferralCodes');
    Route::get('/admin/users/{user}', 'Admin\UsersController@show');
    Route::post('/admin/users/{user}/admin', 'Admin\UsersController@grantAdmin');
    Route::delete('/admin/users/{user}/admin', 'Admin\UsersController@removeAdmin');
    Route::post('/admin/users/{user}/ban', 'Admin\UsersController@ban');
    Route::post('/admin/users/{user}/unban', 'Admin\UsersController@unban');

    /**
     * News
     */
    Route::get('/admin/news', 'Admin\NewsController@index');
    Route::post('/admin/news', 'Admin\NewsController@store');
    Route::get('/admin/news/{newsArticle}', 'Admin\NewsController@show');
    Route::post('/admin/news/{newsArticle}', 'Admin\NewsController@update');
    Route::delete('/admin/news/{newsArticle}', 'Admin\NewsController@destroy');
    Route::get('/admin/news/{newsArticle}/comments/{newsComment}/delete', 'Admin\NewsController@destroyComment');
    Route::get('/admin/news/{newsArticle}/comments/{newsComment}/approve', 'Admin\NewsController@approveComment');
    Route::get('/admin/news/{newsArticle}/comments/{newsComment}/reject', 'Admin\NewsController@rejectComment');
    Route::get(
        '/admin/news/{newsArticle}/comments/{newsComment}/reports/{newsCommentReport}/delete',
        'Admin\NewsController@destroyReport'
    );

    /**
     * Polls
     */
    Route::get('/admin/polls', 'Admin\PollsController@index');
    Route::post('/admin/polls', 'Admin\PollsController@store');
    Route::get('/admin/polls/{poll}', 'Admin\PollsController@show');
    Route::post('/admin/polls/{poll}', 'Admin\PollsController@update');
    Route::post('/admin/polls/{poll}/end', 'Admin\PollsController@endPoll');
    Route::delete('/admin/polls/{poll}', 'Admin\PollsController@destroy');
    Route::post('/admin/polls/{poll}/options', 'Admin\PollsController@storeOption');
    Route::delete('/admin/polls/{poll}/options/{option}', 'Admin\PollsController@destroyOption');

    /**
     * Purchases
     */
    Route::get('/admin/purchases', 'Admin\PurchasesController@index');
    Route::get('/admin/purchases/{purchase}', 'Admin\PurchasesController@show');

    /**
     * Tournaments
     */
    Route::get('/admin/tournaments', 'Admin\TournamentsController@index');
    Route::post('/admin/tournaments', 'Admin\TournamentsController@store');
    Route::get('/admin/tournaments/{tournament}', 'Admin\TournamentsController@show');
    Route::post('/admin/tournaments/{tournament}', 'Admin\TournamentsController@update');
    Route::delete('/admin/tournaments/{tournament}', 'Admin\TournamentsController@destroy');
});
