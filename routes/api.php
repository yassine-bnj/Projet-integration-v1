<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\categories;
use App\Http\Controllers\Film;  
use App\Http\Controllers\Series; 
use App\Http\Controllers\Episodes; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\Serie;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware'=>'api'],function($router){
Route::post('/cat',[categories::class,'addcategorie']);
Route::get('/cat/{id}',[categories::class,'getcatbyid']);
Route::put('/cat',[categories::class,'updatecat']);
Route::delete('/cat',[categories::class,'deletecat']);
Route::get('/cat',[categories::class,'getcategorie']);
Route::post('/film',[Film::class,'addfilm']);
Route::get('/film',[Film::class,'getfilm']);
Route::get('/film/{id}',[Film::class,'getfilmbyid']);
Route::put('/film/{id}',[Film::class,'updatefilm']);
Route::delete('/film/{id}',[Film::class,'deletefilm']);
Route::get('film/categorie/{id}',[Film::class,'getfilmsbycat']);

Route::get('film/getByCategorie/{id}',[Film::class,'getByCategorie']);


Route::post('/serie',[Series::class,'addserie']);
Route::get('/serie',[Series::class,'getseries']);
Route::get('/serie/{id}',[Series::class,'getseriebyid']);
Route::put('/serie',[Series::class,'updateserie']);
Route::delete('/serie/{id}',[Series::class,'deleteserie']);
Route::get('serie/categorie/{id}',[Series::class,'getseriesbycat']);
Route::get('getSaisonsFromEpisodes/{id}',[Series::class,'getSaisonsFromEpisodes']);
Route::get('serie/getByCategorie/{id}',[Series::class,'getByCategorie']);
Route::get('searchSeriesOrFilms',[Series::class,'searchSeriesOrFilms']);

Route::post('/episode',[Episodes::class,'addepisode']);
Route::get('/episode',[Episodes::class,'getepisodes']);
Route::get('/episode/{id}',[Episodes::class,'getepisode']);
Route::put('/episode',[Episodes::class,'updateepisode']);
Route::delete('/episode/{id}',[Episodes::class,'deleteepisode']);
Route::get('episode/serie/{id}',[Episodes::class,'getepisodesbyserie']);
Route::get('episode/{idSerie}/{numSaison}',[Episodes::class,'getEpisodeBySaisonAndSerie']);
Route::post('/login',[AuthController::class,'login']);
Route::get('/logout',[AuthController::class,'logout']);
Route::post('/register',[AuthController::class,'register']);


// crud users
Route::get('/admin',[UserController::class,'index']);
Route::post('/admin',[UserController::class,'store']);
Route::get('/admin/{id}',[UserController::class,'show']);
Route::put('/admin/{id}',[UserController::class,'update']);
Route::delete('/admin/{id}',[UserController::class,'destroy']);



});
