<?php

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

$router->post('/register', 'UserController@register');
$router->get('/users', 'UserController@get_user');
$router->get('/user/delete/{id}', 'UserController@delete_user');
$router->post('/user/update/{id}', 'UserController@update_user');


$router->post(
    'auth/login', 
    [
       'uses' => 'AuthController@authenticate'
    ]
);


$router->post('/edit/{id}', 'UserController@edit');

$router->post('/auth/login', 'AuthController@postLogin');

$router->group(['middleware' => 'auth:api|role:admin'], function() use ($router) {
       $router->get('/users', 'UserController@get_user');
       $router->get('/roles', 'RolesController@getRole');
    }
);