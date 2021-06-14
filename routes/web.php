<?php

use App\Models\Airport;
use App\Models\Flight;
use App\Services\FlightsService;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ajax/airports', function(){
    return response()->json(['airports' => Airport::all()]);
});

Route::get('/ajax/flights/{dep}/{arr}', function($dep_id, $arr_id){

    $dep = Airport::find($dep_id);
    $arr = Airport::find($arr_id);

    $data = Flight::all();

    $flightsService = new FlightsService($data->toArray(), $dep->code, $arr->code);

    $flights = $flightsService->getFlights();
    
    $price = $flights['total_price'];

    unset($flights['total_price']);

    return response()->json(['flights' => collect($flights)->toArray(), 'total_price' => $price]);
});



Route::get('/flights/{dep}/{arr}', function($dep, $arr){

    $data = Flight::all();

    $flightsService = new FlightsService($data->toArray(), $dep, $arr);

    $flights = $flightsService->getFlights();

    dd( $flights );
});