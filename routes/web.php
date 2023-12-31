<?php

use App\Utils\Router;
use App\Utils\Request;
use App\Controllers\HomeController;

$router = new Router(new Request());

/**
 * Home page
 */
$router->get('/', function ($request) {
    return (new HomeController($request))->index();
});

/**
 * Contact form submit
 */
$router->post('/contact/submit', function ($request) {
    return (new HomeController($request))->contact();
});
