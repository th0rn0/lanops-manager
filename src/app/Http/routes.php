<?php

/**
 * Install
 */
Route::group(['middleware' => ['web', 'notInstalled']], function () {
    Route::get('/install', 'InstallController@installation');
    Route::post('/install', 'InstallController@install');
});

Route::group(['middleware' => ['installed']], function () {

    Route::group(['middleware'=>'language'],function ()
    {
        /**
         * API
         */
        Route::group(['middleware' => ['api']], function () {
            Route::get('/api/events/', 'Api\Events\EventsController@index');
            Route::get('/api/events/upcoming', 'Api\Events\EventsController@showUpcoming');
            Route::get('/api/events/{event}', 'Api\Events\EventsController@show');
            Route::get('/api/events/{event}/participants', 'Api\Events\ParticipantsController@index');
            Route::get('/api/events/{event}/timetables', 'Api\Events\TimetablesController@index');
            Route::get('/api/events/{event}/timetables/{timetable}', 'Api\Events\TimetablesController@show');
            Route::get('/api/events/{event}/tickets', 'Api\Events\TicketsController@index');
            Route::get('/api/events/{event}/tickets/{ticket}', 'Api\Events\TicketsController@show');
        });

        /**
         * Front End
         */
        Route::group(['middleware' => ['web']], function () {

            /**
             * Login & Register
             */
            Route::get('/register/email/verify', 'Auth\VerificationController@show')->name('verification.notice');
            Route::get('/register/email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
            Route::get('/register/email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

            Route::get('/register/{method}', 'Auth\AuthController@showRegister');
            Route::post('/register/{method}', 'Auth\AuthController@register');

            Route::get('/login', 'Auth\AuthController@prompt');

            Route::get('/login/forgot', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
            Route::post('/login/forgot', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

            Route::post('/login/reset/password', 'Auth\ResetPasswordController@reset')->name('password.update');
            Route::get('/login/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

            Route::get('/login/steam', 'Auth\SteamController@login');

            Route::post('/login/standard', 'Auth\LoginController@login');

            Route::group(['middleware' => ['auth', 'banned', 'verified']], function () {
                Route::get('/account', 'AccountController@index');
                Route::post('/account', 'AccountController@update');
                Route::post('/account/delete', 'Auth\SteamController@destroy');
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
            Route::get('/events/{event}/big', 'HomeController@bigScreen');

            /**
             * Misc Pages
             */
            Route::get('/about', 'HomeController@about');
            Route::get('/contact', 'HomeController@contact');
            Route::get('/terms', 'HomeController@terms');
            Route::get('/legalnotice', 'HomeController@legalNotice');


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
             * Tournaments
             */
            Route::get('/events/{event}/tournaments', 'Events\TournamentsController@index');
            Route::get('/events/{event}/tournaments/{tournament}', 'Events\TournamentsController@show');
            Route::get('/events/{event}/tournaments/{tournament}/{match}', 'Events\TournamentsController@matchConfig');
            Route::group(['middleware' => ['auth', 'banned', 'verified']], function () {    
                Route::post('/events/{event}/tournaments/{tournament}/register', 'Events\TournamentsController@registerSingle');
                Route::post('/events/{event}/tournaments/{tournament}/register/team', 'Events\TournamentsController@registerTeam');
                Route::post('/events/{event}/tournaments/{tournament}/register/pug', 'Events\TournamentsController@registerPug');
                Route::post('/events/{event}/tournaments/{tournament}/register/remove', 'Events\TournamentsController@unregister');
            });

            /**
             * Payments
             */
            Route::group(['middleware' => ['auth', 'banned', 'verified']], function () {    
                Route::get('/payment/checkout', 'PaymentsController@showCheckout');
                Route::get('/payment/review/{paymentGateway}', 'PaymentsController@showReview');
                Route::get('/payment/details/{paymentGateway}', 'PaymentsController@showDetails');
                Route::post('/payment/delivery', 'PaymentsController@delivery');
                Route::get('/payment/delivery/{paymentGateway}', 'PaymentsController@showDelivery');
                Route::get('/payment/callback', 'PaymentsController@process');
                Route::post('/payment/post', 'PaymentsController@post');
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
             * Shop
             */
            Route::group(['middleware' => ['auth', 'banned', 'verified']], function () {    
                Route::get('/shop/orders', 'ShopController@showAllOrders');
                Route::get('/shop/orders/{order}', 'ShopController@showOrder');
            });
            Route::get('/shop', 'ShopController@index');
            Route::get('/shop/basket', 'ShopController@showBasket');
            Route::post('/shop/basket', 'ShopController@updateBasket');
            Route::get('/shop/{category}', 'ShopController@showCategory');
            Route::get('/shop/{category}/{item}', 'ShopController@showItem');

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
             * Tournaments
             */
            Route::get('/admin/events/{event}/tournaments', 'Admin\Events\TournamentsController@index');
            Route::post('/admin/events/{event}/tournaments', 'Admin\Events\TournamentsController@store');
            Route::get('/admin/events/{event}/tournaments/{tournament}', 'Admin\Events\TournamentsController@show');
            Route::post('/admin/events/{event}/tournaments/{tournament}', 'Admin\Events\TournamentsController@update');
            Route::delete('/admin/events/{event}/tournaments/{tournament}', 'Admin\Events\TournamentsController@destroy');
            Route::post('/admin/events/{event}/tournaments/{tournament}/start', 'Admin\Events\TournamentsController@start');
            Route::post(
                '/admin/events/{event}/tournaments/{tournament}/finalize',
                'Admin\Events\TournamentsController@finalize'
            );
            Route::post(
                '/admin/events/{event}/tournaments/{tournament}/match',
                'Admin\Events\TournamentsController@updateMatch'
            );
            Route::post(
                '/admin/events/{event}/tournaments/{tournament}/participants/{participant}/team',
                'Admin\Events\TournamentsController@updateParticipantTeam'
            );
            Route::post(
                '/admin/events/{event}/tournaments/{tournament}/participants/{participant}/remove',
                'Admin\Events\TournamentsController@unregisterParticipant'
            );

            // TODO - REMOVE THIS AND ALL LIKE IT
            /**
             * Legacy
             */
            Route::get('/admin/events/tournaments/fix', 'Admin\Events\TournamentsController@fixScores');


            /**
             * Games
             */
            Route::get('/admin/games', 'Admin\GamesController@index');
            Route::post('/admin/games', 'Admin\GamesController@store');
            Route::get('/admin/games/{game}', 'Admin\GamesController@show');
            Route::post('/admin/games/{game}', 'Admin\GamesController@update');
            Route::delete('/admin/games/{game}', 'Admin\GamesController@destroy');
            
            /**
             * GameServers
             */
            Route::get('/admin/games/{game}/gameservers', 'Admin\GameServersController@index');
            Route::post('/admin/games/{game}/gameservers', 'Admin\GameServersController@store');
            Route::get('/admin/games/{game}/gameservers/{gameServer}', 'Admin\GameServersController@show');
            Route::post('/admin/games/{game}/gameservers/{gameServer}', 'Admin\GameServersController@update');
            Route::delete('/admin/games/{game}/gameservers/{gameServer}', 'Admin\GameServersController@destroy');

            /**
             * GameServerCommands
             */
            Route::get('/admin/games/{game}/gameservercommands', 'Admin\GameServerCommandsController@index');
            Route::post('/admin/games/{game}/gameservercommands', 'Admin\GameServerCommandsController@store');
            Route::get('/admin/games/{game}/gameservercommands/{gameServerCommand}', 'Admin\GameServerCommandsController@show');
            Route::post('/admin/games/{game}/gameservercommands/{gameServerCommand}', 'Admin\GameServerCommandsController@update');
            Route::delete('/admin/games/{game}/gameservercommands/{gameServerCommand}', 'Admin\GameServerCommandsController@destroy');
            Route::get('/admin/games/{game}/gameservercommands/{gameServerCommand}/execute/{gameServer}', 'Admin\GameServerCommandsController@execute');
            Route::post('/admin/games/{game}/gameservercommands/execute/{gameServer}', 'Admin\GameServerCommandsController@executeGameServerCommand');


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
             * Announcements
             */
            Route::post('/admin/events/{event}/announcements', 'Admin\Events\AnnouncementsController@store');
            Route::post(
                '/admin/events/{event}/announcements/{announcement}',
                'Admin\Events\AnnouncementsController@update'
            );
            Route::delete(
                '/admin/events/{event}/announcements/{announcement}',
                'Admin\Events\AnnouncementsController@destroy'
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
            Route::get('/admin/users/{user}', 'Admin\UsersController@show');
            Route::post('/admin/users/{user}/admin', 'Admin\UsersController@grantAdmin');
            Route::delete('/admin/users/{user}/admin', 'Admin\UsersController@removeAdmin');
            Route::post('/admin/users/{user}/ban', 'Admin\UsersController@ban');
            Route::post('/admin/users/{user}/unban', 'Admin\UsersController@unban');



            /**
             * Settings
             */
            Route::get('/admin/settings', 'Admin\SettingsController@index');
            Route::post('/admin/settings', 'Admin\SettingsController@update');
            Route::get('/admin/settings/org', 'Admin\SettingsController@showOrg');
            Route::get('/admin/settings/payments', 'Admin\SettingsController@showPayments');
            Route::get('/admin/settings/auth', 'Admin\SettingsController@showAuth');
            Route::get('/admin/settings/api', 'Admin\SettingsController@showApi');
            Route::post('/admin/settings/api', 'Admin\SettingsController@updateApi');
            Route::get('/admin/settings/link/{social}', 'Admin\SettingsController@linkSocial');
            Route::delete('/admin/settings/unlink/{social}', 'Admin\SettingsController@unlinkSocial');
            Route::post('/admin/settings/payments/{gateway}/disable', 'Admin\SettingsController@disablePaymentGateway');
            Route::post('/admin/settings/payments/{gateway}/enable', 'Admin\SettingsController@enablePaymentGateway');
            Route::post('/admin/settings/login/{method}/disable', 'Admin\SettingsController@disableLoginMethod');
            Route::post('/admin/settings/login/{method}/enable', 'Admin\SettingsController@enableLoginMethod');
            Route::post('/admin/settings/credit/enable', 'Admin\SettingsController@enableCreditSystem');
            Route::post('/admin/settings/credit/disable', 'Admin\SettingsController@disableCreditSystem');
            Route::post('/admin/settings/shop/enable', 'Admin\SettingsController@enableShopSystem');
            Route::post('/admin/settings/shop/disable', 'Admin\SettingsController@disableShopSystem');
            Route::post('/admin/settings/generate/qr', 'Admin\SettingsController@regenerateQRCodes');

            /**
             * Appearance
             */
            Route::get('/admin/settings/appearance', 'Admin\AppearanceController@index');
            Route::post('/admin/settings/appearance/slider/images/', 'Admin\AppearanceController@sliderUpload');
            Route::post('/admin/settings/appearance/slider/images/{image}', 'Admin\AppearanceController@sliderUpdate');
            Route::delete('/admin/settings/appearance/slider/images/{image}', 'Admin\AppearanceController@sliderDelete');
            Route::get('/admin/settings/appearance/css/recompile', 'Admin\AppearanceController@cssRecompile');
            Route::post('/admin/settings/appearance/css/override', 'Admin\AppearanceController@cssOverride');
            Route::post('/admin/settings/appearance/css/variables', 'Admin\AppearanceController@cssVariables');

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
            Route::get('/admin/purchases/shop', 'Admin\PurchasesController@showShop');
            Route::get('/admin/purchases/event', 'Admin\PurchasesController@showEvent');
            Route::get('/admin/purchases/{purchase}', 'Admin\PurchasesController@show');

            /**
             * Credit System
             */
            Route::get('/admin/credit', 'Admin\CreditController@index');
            Route::post('/admin/credit/edit', 'Admin\CreditController@edit');
            Route::post('/admin/credit/settings', 'Admin\CreditController@settings');

            /**
             * Shop
             */
            Route::get('/admin/shop', 'Admin\ShopController@index');
            Route::post('/admin/shop/item', 'Admin\ShopController@storeItem');
            Route::post('/admin/shop/category', 'Admin\ShopController@storeCategory');
            Route::get('/admin/shop/{category}', 'Admin\ShopController@showCategory');
            Route::post('/admin/shop/{category}', 'Admin\ShopController@updateCategory');
            Route::delete('/admin/shop/{category}', 'Admin\ShopController@deleteCategory');
            Route::get('/admin/shop/{category}/{item}', 'Admin\ShopController@showItem');
            Route::post('/admin/shop/{category}/{item}', 'Admin\ShopController@updateItem');
            Route::delete('/admin/shop/{category}/{item}', 'Admin\ShopController@deleteItem');
            Route::post('/admin/shop/{category}/{item}/images', 'Admin\ShopController@uploadItemImage');
            Route::post('/admin/shop/{category}/{item}/images/{image}', 'Admin\ShopController@updateItemImage');
            Route::delete('/admin/shop/{category}/{item}/images/{image}', 'Admin\ShopController@deleteItemImage');

            /**
             * Orders
             */
            Route::get('/admin/orders', 'Admin\OrdersController@index');
            Route::get('/admin/orders/{order}', 'Admin\OrdersController@show');
            Route::post('/admin/orders/{order}/processing', 'Admin\OrdersController@setAsProcessing');
            Route::post('/admin/orders/{order}/shipped', 'Admin\OrdersController@setAsShipped');
            Route::post('/admin/orders/{order}/tracking', 'Admin\OrdersController@updateTrackingDetails');
            Route::post('/admin/orders/{order}/complete', 'Admin\OrdersController@setAsComplete');
            Route::post('/admin/orders/{order}/cancel', 'Admin\OrdersController@setAsCancelled');

        });
    
    });

});
