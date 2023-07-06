<?php

use App\Models\Router;
use App\Models\Request;
use Jenssegers\Blade\Blade;

$router = new Router(new Request());
$blade  = new Blade('resources/views', 'cache');

$router->get('/', function ($request) use ($blade) {
    $data = [
        'locale' => $request->getLocale(),
        'title'  => 'Добре дошли'
    ];

    return $blade->render('home.index', $data);
});

$router->get('/en', function ($request) use ($blade) {
    $data = [
        'locale' => $request->getLocale(),
        'title'  => 'Welcome'
    ];

    return $blade->render('home.index', $data);
});

