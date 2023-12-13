<?php

use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ParticipateController;
use App\Http\Controllers\judgeController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\OpenAIController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth', 'user-access:admin', 'checkSelectedEvent'])->group(function () {
  
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('home');
    Route::get('/admin/admindashboard', [HomeController::class, 'admindashboard'])->name('admindashboard');
    Route::get('participateindex', [ParticipateController::class,'participateindex'])->name('participateindex');

    // Route::get('admin/events', [AdminEventController::class,'index'])->name('admin.eventindex');
    // Route::post('admin/events', [AdminEventController::class,'store'])->name('admin.events.store');
    // Route::get('admin/events/{event}/edit',[AdminEventController::class,'edit'])->name('admin.events.edit');
    // Route::put('admin/events/{event}', [AdminEventController::class,'update'])->name('admin.events.update');
    // Route::delete('admin/events/{event}',[AdminEventController::class,'destroy'])->name('admin.events.destroy');


    Route::prefix('admin')->group(function () {
        
        Route::resource('participate', ParticipateController::class);
        Route::any('participate/edit/{resource}', [ParticipateController::class, 'edit'])->name('participate.edit');

        Route::get('showDailyRanking', [ParticipateController::class,'showDailyRanking'])->name('showDailyRanking');
        Route::get('AllDaysRank', [ParticipateController::class,'AllDaysRank'])->name('AllDaysRank');
        Route::get('finalRanking', [ParticipateController::class,'finalRanking'])->name('finalRanking');
     
    });
    
});

 Route::middleware(['auth', 'user-access:manager', 'checkSelectedEvent'])->group(function () {
  
    Route::get('/manager/home', [HomeController::class, 'managerHome'])->name('manager.home');
    Route::get('/managerdashboard', [HomeController::class, 'managerdashboard'])->name('managerdashboard');
    Route::get('showDailyRanking', [ParticipateController::class,'showDailyRanking'])->name('showDailyRanking');
    Route::get('AllDaysRank', [ParticipateController::class,'AllDaysRank'])->name('AllDaysRank');
    Route::get('finalRanking', [ParticipateController::class,'finalRanking'])->name('finalRanking');

    Route::resource('judge', judgeController::class);
Route::any('judge/show/{resource}', [judgeController::class, 'show'])->name('judge.show');

});
Route::post('/store-selected-event', [HomeController::class,'storeSelectedEvent'])->name('storeSelectedEvent');

//Route::get('/check-event-status', [AdminEventController::class,'checkEventStatus'])->name('check-event-status');
Route::get('/layoutapp', [HomeController::class,'layoutapp'])->name('layoutapp');

Route::get('admin/events', [AdminEventController::class,'index'])->name('admin.eventindex');
Route::post('admin/events', [AdminEventController::class,'store'])->name('admin.events.store');
Route::get('admin/events/{event}/edit',[AdminEventController::class,'edit'])->name('admin.events.edit');
Route::put('admin/events/{event}', [AdminEventController::class,'update'])->name('admin.events.update');
Route::delete('admin/events/{event}',[AdminEventController::class,'destroy'])->name('admin.events.destroy');

// for stripe payment gateway
Route::get('stripe', [StripePaymentController::class, 'stripe']);
Route::post('stripe', [StripePaymentController::class, 'stripePost'])->name('stripe.post');

//chatgpt response

Route::get('/chat', [OpenAIController::class, 'chatGPT'])->name('chat');
Route::post('/chat', [OpenAIController::class, 'chatGPT'])->name('chat.post');


Route::get('/chat', function () {
    return view('chat');
});

Route::post('/generate-text', [OpenAIController::class, 'generateText']);