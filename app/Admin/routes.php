<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'ApiProjectsController@index');
    $router->resource('/projects','ApiProjectsController');
    /*$router->get('projects', 'ApiProjectsController@index');
    $router->get('projects/create', 'ApiProjectsController@create');*/
});
