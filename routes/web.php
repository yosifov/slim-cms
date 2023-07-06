<?php

use App\Models\ContactRequest;
use App\Models\Router;
use App\Models\Request;
use App\Models\Validator;
use Jenssegers\Blade\Blade;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$router = new Router(new Request());
$blade  = new Blade('resources/views', 'cache');

$router->get('/', function ($request) use ($blade) {
    $data = [
        'locale' => $request->getLocale(),
        'title'  => 'Добре дошли'
    ];

    return $blade->render('home.index', $data);
});

$router->get('/contact', function ($request) use ($blade) {
    $data = ['locale' => $request->getLocale()];

    return $blade->render('contact.index', $data);
});

$router->post('/contact/submit', function ($request) {
    $errors = [];
    $response   = [];
    $body   = $request->getBody();

    $rules = [
        'name'      => 'required',
        'email'     => 'required|email',
        'superhero' => 'required'
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

$router->get('/en', function ($request) use ($blade) {
    $data = [
        'locale' => $request->getLocale(),
        'title'  => 'Welcome'
    ];

    return $blade->render('home.index', $data);
});

