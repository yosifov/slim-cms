<?php

use App\Utils\Router;
use App\Utils\Request;
use App\Utils\Validator;
use Jenssegers\Blade\Blade;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$router = new Router(new Request());
$blade  = new Blade('resources/views', 'cache');

/**
 * BG Routes
 */

// Home page
$router->get('/', function ($request) use ($blade) {
    $data = [
        'locale' => $request->getLocale(),
        'title'  => trans('home.title', $request->getLocale())
    ];

    return $blade->render('home.index', $data);
});

// Contact page
$router->get('/contact', function ($request) use ($blade) {
    $data = ['locale' => $request->getLocale()];

    return $blade->render('contact.index', $data);
});

// Contact form submit
$router->post('/contact/submit', function ($request) {
    $errors   = [];
    $response = [];
    $body     = $request->getBody();

    $rules = [
        'name'    => 'required',
        'email'   => 'required|email',
        'subject' => 'required'
    ];

    $validator = new Validator($rules, $body);

    $errors = $validator->validate();

    if ($request->httpXRequestedWith !== 'XMLHttpRequest') {
        $errors['global'] = 'Request not allowed!';
    }

    $response = [
        'success' => empty($errors),
        'errors'  => !empty($errors) ? $errors : null,
        'message' => empty($errors) ? 'Success!' : 'Error!'
    ];

    try {
        $mailer = new PHPMailer();
    } catch (Exception $e) {
        $errors['global'] = $e->getMessage();
    }
    
    return json_encode($response);
});


/**
 * EN Routes
 */

// Home page
$router->get('/en', function ($request) use ($blade) {
    $data = [
        'locale' => $request->getLocale(),
        'title'  => trans('home.title', $request->getLocale())
    ];

    return $blade->render('home.index', $data);
});

// Contact page
$router->get('/en/contact', function ($request) use ($blade) {
    $data = ['locale' => $request->getLocale()];

    return $blade->render('contact.index', $data);
});
