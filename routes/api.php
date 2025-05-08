<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GenericABMController;
use App\Http\Controllers\WebViewRenderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionTypeController;
use App\Models\QuestionCategory;
use App\Models\Image;
use App\Models\Reason;
use App\Models\Config;
use App\Models\Promotion;
use App\Models\Recharge;
use App\Models\WebView;
use App\Models\WebViewType;
use App\Models\WebViewSubType;
use App\Models\Transaction;

Route::post('/users/user/signin', [AuthController::class, 'signin']);

// Route::middleware('jwt.auth')->get('/menu', [MenuController::class, 'index']);

Route::middleware('x-token')->get('/menu', [MenuController::class, 'index']);

// routes/api.php

Route::middleware('x-token')->get('/transacciones', [TransactionController::class, 'summary']);
Route::middleware('x-token')->get('/transacciones/tipos', [TransactionTypeController::class, 'index']); 


// Route::middleware('x-token')->get('/transactions/summary', [TransactionController::class, 'summary']);


// Ruta protegida con JWT
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::middleware(['jwt.auth'])->get('/perfil', function () {
    return response()->json(auth()->user());
});


Route::get('/protegido', [AuthController::class, 'protectedRoute'])->middleware('jwt.auth');


// Route::post('/users/user/profile', [\App\Http\Controllers\AuthController::class, 'profile']);


// Route::post('/users/user/profile', [AuthController::class, 'profile'])->middleware('x-token');



Route::middleware(['x-token'])->prefix('reasons')->group(function () {
    Route::get('/', [GenericABMController::class, 'index'])->defaults('model', Reason::class);

    /*
    Route::get('{id}', [GenericABMController::class, 'show'])->defaults('model', Reason::class);
    Route::get('/{query}', [GenericABMController::class, 'index'])->defaults('model', Reason::class);
*/

Route::get('/{query}', [GenericABMController::class, 'index'])->defaults('model', Reason::class);



    Route::post('/', [GenericABMController::class, 'store'])->defaults('model', Reason::class);
    Route::put('/{query}', [GenericABMController::class, 'update'])->defaults('model', Reason::class);
    Route::delete('/{id}', [GenericABMController::class, 'destroy'])->defaults('model', Reason::class);
});




Route::middleware(['x-token'])->prefix('config')->group(function () {
    Route::get('/', [GenericABMController::class, 'index'])->defaults('model', Config::class);
    Route::get('/{query}', [GenericABMController::class, 'index'])->defaults('model', Config::class);
    Route::post('/', [GenericABMController::class, 'store'])->defaults('model', Config::class);
    Route::put('/{query}', [GenericABMController::class, 'update'])->defaults('model', Config::class);
    Route::delete('/{query}', [GenericABMController::class, 'destroy'])->defaults('model', Config::class);
});


Route::middleware(['x-token'])->prefix('questions')->group(function () {
    Route::get('/', [GenericABMController::class, 'index'])->defaults('model', \App\Models\Question::class);
    Route::get('{id}', [GenericABMController::class, 'show'])->defaults('model', \App\Models\Question::class);
    Route::post('/', [GenericABMController::class, 'store'])->defaults('model', \App\Models\Question::class);
    Route::put('{id}', [GenericABMController::class, 'update'])->defaults('model', \App\Models\Question::class);
    Route::delete('{id}', [GenericABMController::class, 'destroy'])->defaults('model', \App\Models\Question::class);
});

Route::middleware(['x-token'])->prefix('questionscategories')->group(function () {
    Route::get('/', [GenericABMController::class, 'index'])->defaults('model', QuestionCategory::class);
    Route::get('/{id}', [GenericABMController::class, 'show'])->where('id', '[0-9]+')->defaults('model', QuestionCategory::class);
    Route::get('/{query}', [GenericABMController::class, 'index'])->defaults('model', QuestionCategory::class);
    Route::post('/', [GenericABMController::class, 'store'])->defaults('model', QuestionCategory::class);
    Route::put('/{id}', [GenericABMController::class, 'update'])->where('id', '[0-9]+')->defaults('model', QuestionCategory::class);
    Route::delete('/{id}', [GenericABMController::class, 'destroy'])->where('id', '[0-9]+')->defaults('model', QuestionCategory::class);
});



Route::middleware(['x-token'])->prefix('images')->group(function () {
    Route::get('/', [GenericABMController::class, 'index'])->defaults('model', Image::class);
    Route::get('/{id}', [GenericABMController::class, 'show'])->where('id', '[0-9]+')->defaults('model', Image::class);
    Route::get('/{query}', [GenericABMController::class, 'index'])->defaults('model', Image::class);
    Route::post('/', [GenericABMController::class, 'store'])->defaults('model', Image::class);
    Route::put('/{id}', [GenericABMController::class, 'update'])->where('id', '[0-9]+')->defaults('model', Image::class);
    Route::delete('/{id}', [GenericABMController::class, 'destroy'])->where('id', '[0-9]+')->defaults('model', Image::class);
});


Route::middleware(['x-token'])->prefix('reportetransacciones')->group(function () {
    Route::get('/', [GenericABMController::class, 'index'])->defaults('model', Transaction::class);
    Route::get('/{id}', [GenericABMController::class, 'show'])->where('id', '[0-9]+')->defaults('model', Transaction::class);
    Route::get('/{query}', [GenericABMController::class, 'index'])->defaults('model', Transaction::class);
    Route::post('/', [GenericABMController::class, 'store'])->defaults('model', Transaction::class);
    Route::put('/{id}', [GenericABMController::class, 'update'])->where('id', '[0-9]+')->defaults('model', Transaction::class);
    Route::delete('/{id}', [GenericABMController::class, 'destroy'])->where('id', '[0-9]+')->defaults('model', Transaction::class);
});



Route::middleware(['x-token'])->prefix('recharge')->group(function () {
    Route::get('/', [GenericABMController::class, 'index'])->defaults('model', Recharge::class);
    Route::get('/{id}', [GenericABMController::class, 'show'])->defaults('model', Recharge::class);
    Route::post('/', [GenericABMController::class, 'store'])->defaults('model', Recharge::class);
    Route::put('/{id}', [GenericABMController::class, 'update'])->defaults('model', Recharge::class);
    Route::delete('/{id}', [GenericABMController::class, 'destroy'])->defaults('model', Recharge::class);
});


Route::middleware(['x-token'])->prefix('promotions')->group(function () {
    Route::get('/all', [GenericABMController::class, 'index'])->defaults('model', Promotion::class);
    Route::get('/', [GenericABMController::class, 'index'])->defaults('model', Promotion::class);
    Route::get('/{id}', [GenericABMController::class, 'show'])->defaults('model', Promotion::class);
    Route::post('/', [GenericABMController::class, 'store'])->defaults('model', Promotion::class);
    Route::put('/{id}', [GenericABMController::class, 'update'])->defaults('model', Promotion::class);
    Route::delete('/{id}', [GenericABMController::class, 'destroy'])->defaults('model', Promotion::class);
});

// Rutas para WebViews
Route::middleware(['x-token'])->prefix('webviews')->group(function () {


    Route::get('/terms/{id?}', [WebViewRenderController::class, 'getWebViews'])->defaults('webviewType', 1);
    // Ruta para obtener la vista de políticas de privacidad
    Route::get('/privacy/{id?}', [WebViewRenderController::class, 'getWebViews'])->defaults('webviewType', 2);
    

    Route::get('/', [GenericABMController::class, 'index'])->defaults('model', WebView::class);
    Route::get('/{query}', [GenericABMController::class, 'index'])->defaults('model', WebView::class);
    Route::post('/', [GenericABMController::class, 'store'])->defaults('model', WebView::class);
    Route::put('/{query}', [GenericABMController::class, 'update'])->defaults('model', WebView::class);
    Route::delete('/{query}', [GenericABMController::class, 'destroy'])->defaults('model', WebView::class);
});

// Rutas para WebViewTypes
Route::middleware(['x-token'])->prefix('webviewsTypes')->group(function () {
    Route::get('/', [GenericABMController::class, 'index'])->defaults('model', WebViewType::class);
    Route::get('/{query}', [GenericABMController::class, 'index'])->defaults('model', WebViewType::class);
    Route::post('/', [GenericABMController::class, 'store'])->defaults('model', WebViewType::class);
    Route::put('/{query}', [GenericABMController::class, 'update'])->defaults('model', WebViewType::class);
    Route::delete('/{query}', [GenericABMController::class, 'destroy'])->defaults('model', WebViewType::class);
});

// Rutas para WebViewSubTypes
Route::middleware(['x-token'])->prefix('webviewsSubtypes')->group(function () {
    Route::get('/', [GenericABMController::class, 'index'])->defaults('model', WebViewSubType::class);
    Route::get('/{query}', [GenericABMController::class, 'index'])->defaults('model', WebViewSubType::class);
    Route::post('/', [GenericABMController::class, 'store'])->defaults('model', WebViewSubType::class);
    Route::put('/{query}', [GenericABMController::class, 'update'])->defaults('model', WebViewSubType::class);
    Route::delete('/{query}', [GenericABMController::class, 'destroy'])->defaults('model', WebViewSubType::class);
});


Route::middleware(['x-token'])->group(function () {
    Route::post('/users/user/profile', [AuthController::class, 'profile']);
});

