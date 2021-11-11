<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// locations
Route::prefix('locations')->group(function () {
    Route::get('/', [\App\Http\Controllers\LocationController::class, 'index']);
    Route::get('/{location}', [\App\Http\Controllers\LocationController::class, 'show']);
});

Route::middleware('auth:sanctum')->group(function () {
    // locations
    Route::prefix('locations')->group(function () {
        Route::post('/', [\App\Http\Controllers\LocationController::class, 'store']);
        Route::match(['put', 'patch'], '/{location}', [\App\Http\Controllers\LocationController::class, 'update']);
        Route::delete('/{location}', [\App\Http\Controllers\LocationController::class, 'destroy']);
        Route::patch('/{location}/restore', [\App\Http\Controllers\LocationController::class, 'restore'])
            ->middleware('can:restore,location');
        Route::delete('/{location}/force-delete', [\App\Http\Controllers\LocationController::class, 'forceDelete'])
            ->middleware('can:forceDelete,location');
    });
});

//Route::post('/login', function (Request $request) {
//    $request->validate([
//        'email' => 'required|string|email|max:255',
//        'password' => 'required|string|min:8',
//    ]);
//
//    $credentials = $request->only('email', 'password');
//
//    if (auth()->attempt($credentials)) {
//        $user = \App\Models\User::query()
//            ->where('email', $request->get('email'))
//            ->firstOrFail();
//
//        $token = $user->createToken(
//            config('app.name')
//        )->plainTextToken;
//
//        return response()
//            ->json([
//                'user' => $user,
//                'token' => $token,
//            ], \Illuminate\Http\JsonResponse::HTTP_OK);
//    }
//
//    return response()
//        ->json([],\Illuminate\Http\JsonResponse::HTTP_UNAUTHORIZED);
//});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:8',
    ]);

    $user = \App\Models\User::query()
        ->where('email', $request->email)
        ->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()
            ->json([],\Illuminate\Http\JsonResponse::HTTP_UNAUTHORIZED);
    }

    $token = $user->createToken(
        config('app.name')
    )->plainTextToken;

    return response()
        ->json([
            'user' => $user,
            'token' => $token,
        ], \Illuminate\Http\JsonResponse::HTTP_OK);
});
