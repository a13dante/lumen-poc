<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// $router->get('users', 'UserController@index');
// $router->get('user/{id}', 'UserController@show');
// $router->post('user', 'UserController@store');
// $router->put('user/{id}', 'UserController@update');
// $router->delete('user/{id}', 'UserController@delete');
// $router->get('user/db/columns', 'UserController@columns');

$router->get('user/show', 'UserController@commonShow');
$router->get('user/index', 'UserController@commonIndex');
$router->delete('user/delete', 'UserController@commonDelete');
$router->post('user/store', 'UserController@commonStore');