<?php

Route::group(['namespace' => 'Mgcoder\Knet\Http\Controllers'], function () {
  Route::post('/HandlerResponse', 'KnetController@GetHandlerResponse');
  Route::get('/Error', 'KnetController@Error');
});

?>
