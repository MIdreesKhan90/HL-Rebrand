<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CommercialController;
use App\Http\Controllers\HotelPageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialLoginAccountController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\PriceController;

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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/login', function () {
    if (Auth::guard('web')->check()){
        return redirect(route('home'));
    }else{
        return view('auth.login');
    }
});
Route::post('/login',[LoginController::class,'sendMagicLink'])->name('login');
//Route::get('/generate-magic-link',function (){
//    return view('pages.magicLink');
//})->name('generate.magicLink');
//Route::post('/send-magic-link',[LoginController::class,'sendMagicLink'])->name('send.magicLink');
Route::get('/magic-link/{token}',[LoginController::class,'authenticateMagicLink'])->name('auth.magicLink');
Route::get('/logout',[LoginController::class,'logout'])->name('logout');

// Social Login
Route::get('/login/oauth/{provider}', [SocialLoginAccountController::class, 'oAuthRedirect']);

Route::post('api/register-email-only',[LoginController::class,'registerEmailOnly'])->name('app.register.email.only');

Route::get('/login/oauth/{provider}/callback', [SocialLoginAccountController::class, 'oAuthCallback']);

Route::get('/api/auth-check',[\App\Http\Controllers\API\LoginController::class,'authCheck'])->name('auth.check');

Route::get('/',[HomePageController::class,'index'])->name('home');
Route::get('/commercial',[CommercialController::class,'index'])->name('commercial');
Route::get('/hotels',[HotelPageController::class,'index'])->name('hotels');
Route::get('/blogs',[BlogController::class,'index'])->name('blog.list');
Route::get('/faq',[FaqController::class,'index'])->name('faq');
Route::get('/faq-search', [FaqController::class, 'search'])->name('faq.search');
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index'])->name('privacy-policy');
Route::get('/prices', [PriceController::class, 'index'])->name('pricing');
//Route::get('price/{service}', [PriceController::class, 'show'])->name('pricing.details');
Route::get('price', [PriceController::class, 'showPrice'])->name('pricing.details');
Route::get('/booking', [PriceController::class, 'showBooking'])->name('booking.details');
Route::get('/thankyou',[\App\Http\Controllers\API\OrderController::class,'payment_success']);
Route::get('/thank-you', function (){
    return view('pages.thank-you');
});
Route::get('/payment-failure', function (){
    return view('pages.payment-fail');
});

Route::get('postcode/{postcode}',[\App\Http\Controllers\PostCodeController::class,'zipTimeSlots'])->middleware(['auth'])->name('timeslots');
Route::get('/checkService/{postcode}',[\App\Http\Controllers\API\PostCodeController::class,'checkService']);
Route::post('/online-payment',[\App\Http\Controllers\API\OrderController::class,'stripePayment'])->name('online.payment');




// Add all routes above it
Route::get('/{slug}',[BlogController::class,'show'])->name('blog.detail');
