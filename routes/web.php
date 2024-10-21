<?php

use App\Utils\Router;
use App\Utils\Request;
use App\Controllers\HomeController;
use App\Services\RateLimiter;
use Jenssegers\Blade\Blade;

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
    $limitKey = $request->remoteAddr . $request->requestMethod . $request->requestUri;

    if (!(new RateLimiter(3, 10))->check($limitKey)) {
        // Send a 429 Too Many Requests response
        http_response_code(429);

        $blade = new Blade(views_path(), cache_path());

        echo $blade->render('error.429', [
            'meta' => [
                'title' => trans('errors.429.title')
            ],
        ]);

        exit;
    }

    return (new HomeController($request))->contact();
});
