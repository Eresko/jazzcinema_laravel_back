<?php
use App\Http\Controllers\Event\EventController as  Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth as AuthController;
use App\Http\Controllers\Export as ExportController;
use App\Http\Controllers\Halls as HallsController;
use App\Http\Controllers\HandBook as HandBookController;
use App\Http\Controllers\AdminPanel as AdminPanelController;
use App\Http\Controllers\FilmCopy as FilmCopyNameSpace;
use App\Http\Controllers\Banners as BannerController;
use App\Http\Controllers\Import as Import;
use App\Http\Controllers\Users as UsersController;
use App\Http\Middleware\Authenticate;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'auth'], function (): void {
    Route::group(['prefix' => 'user'], function (): void {
        Route::post('login', [AuthController\UserController::class, 'login']);
    });

});
Route::group(['prefix' => 'import'], function (): void {
    Route::post('performance-time-frame', [Import\ImportController::class, 'performanceTimeFrame']);
    Route::post('performance-film-copy', [Import\ImportController::class, 'performanceFilmCopy']);
    Route::post('performance', [Import\ImportController::class, 'performance']);
    Route::get('age', [Import\ImportController::class, 'age']);
    Route::get('film/{film}', [Import\ImportController::class, 'film']);
    Route::post('filmCopy', [Import\ImportController::class, 'filmCopy']);
});

Route::group(['prefix' => 'halls'], function (): void {
    Route::get('/', [HallsController\SchemeHallsController::class, 'getAllHalls']);
    Route::get('/{id}', [HallsController\SchemeHallsController::class, 'get']);
    Route::get('scheme/{performance}', [HallsController\SchemeHallsController::class, 'byPerformance']);

});
Route::group(['prefix' => 'users'], function (): void {
    Route::get('privilege', [UsersController\SessionController::class, 'getPrivilege']);
    Route::get('test', [UsersController\UserController::class, 'test']);
    Route::get('auth/test', [UsersController\AuthController::class, 'test']);
    Route::post('auth/phone', [UsersController\AuthController::class, 'authPhone']);
    Route::post('auth', [UsersController\AuthController::class, 'login']);
    Route::post('auth/check-sms-code', [UsersController\AuthController::class, 'checkSmsCode']);
    Route::post('auth/password', [UsersController\AuthController::class, 'authPassword']);

});

Route::group([
    'prefix' => 'booking',
    'middleware' => [ Authenticate::class]
], function (): void {
    Route::post('reservation-not-select', [FilmCopyNameSpace\BookingController::class, 'reservationNotSelect']);
});


Route::group([
    'prefix' => 'users',
    'middleware' => [ Authenticate::class]
], function (): void {
    Route::get('auth/check-token', [UsersController\AuthController::class, 'checkToken']);
    Route::get('cards', [UsersController\UserController::class, 'getCards']);
    Route::get('profile', [UsersController\UserController::class, 'getProfile']);
    Route::get('reservation', [UsersController\UserController::class, 'getReservation']);
    Route::post('update', [UsersController\UserController::class, 'updateProfile']);
    Route::post('message', [UsersController\UserController::class, 'pushMessage']);
});
Route::group(['prefix' => 'export'], function (): void {
    Route::get('export-filmcopy', [ExportController\ExportFilmCopyController::class, 'exportFilmCopy']);

});
Route::group(['prefix' => 'hand-book'], function (): void {
    Route::get('banner', [HandBookController\getBanner::class, 'getBanner']);

});

Route::group([
    'prefix' => 'admin-panel',
    'middleware' => [ Authenticate::class]
], function (): void {
    Route::get('get-banners', [AdminPanelController\BannerController::class, 'getListBanner']);
    Route::get('users', [AdminPanelController\UsersController::class, 'get']);
    Route::get('users/{id}', [AdminPanelController\UsersController::class, 'getUser']);
    Route::get('cards/{id}', [AdminPanelController\CardController::class, 'getCards']);
    Route::get('privilege/{id}', [AdminPanelController\PrivilegeController::class, 'getPrivilege']);
    Route::post('privilege/{id}', [AdminPanelController\PrivilegeController::class, 'update']);
    Route::post('users/{id}', [AdminPanelController\UsersController::class, 'updateUser']);
    Route::get('halls', [AdminPanelController\HallController::class, 'get']);
    Route::post('halls/{id}', [AdminPanelController\HallController::class, 'update']);
    Route::post('create-banner', [AdminPanelController\BannerController::class, 'create']);
    Route::get('banner/{id}', [AdminPanelController\BannerController::class, 'getBanner']);
    Route::post('update-banner/{id}', [AdminPanelController\BannerController::class, 'update']);
    Route::get('get-film-copies', [AdminPanelController\FilmCopyController::class, 'get']);
    Route::get('get-film-copy/{id}', [AdminPanelController\FilmCopyController::class, 'getFilmCopy']);
    Route::post('update-film-copy/{id}', [AdminPanelController\FilmCopyController::class, 'update']);
    Route::get('booking', [AdminPanelController\BookingController::class, 'get']);
    Route::get('notification', [AdminPanelController\NotificationController::class, 'get']);


});

Route::group(['prefix' => 'film-copy'], function (): void {
    Route::get('current', [FilmCopyNameSpace\FilmCopyController::class, 'getFilmCopyCurrent']);
    Route::get('future', [FilmCopyNameSpace\FilmCopyController::class, 'getFilmCopyFuture']);
    Route::get('ps', [FilmCopyNameSpace\FilmCopyController::class, 'getFilmCopyPs']);
    Route::get('retro', [FilmCopyNameSpace\FilmCopyController::class, 'getFilmCopyRetro']);
    Route::get('not-only-jazz', [FilmCopyNameSpace\FilmCopyController::class, 'getFilmCopyNotOnlyJazz']);
    Route::get('movie-schedule', [FilmCopyNameSpace\ScheduleController::class, 'getScheduleByGroupDate']);
    Route::get('movie-schedule/{performance}', [FilmCopyNameSpace\ScheduleController::class, 'getScheduleByPerformance']);
    Route::get('specific/{id}', [FilmCopyNameSpace\FilmCopyController::class, 'getFilmCopy']);
    Route::get('status/{performance}', [FilmCopyNameSpace\ScheduleController::class, 'getStatusByPerformance']);
});

Route::group([
    'prefix' => 'film-copy',
    'middleware' => [ Authenticate::class]
], function (): void {
    Route::get('status-auth/{performance}', [FilmCopyNameSpace\ScheduleController::class, 'getStatusAuthByPerformance']);
});



Route::group(['prefix' => 'banners'], function (): void {
    Route::get('', [BannerController\BannerController::class, 'getBanners']);
});
