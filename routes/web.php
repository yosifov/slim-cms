<?php

use App\Services\MailService;
use App\Utils\Router;
use App\Utils\Request;
use App\Utils\Validator;
use Jenssegers\Blade\Blade;

$router = new Router(new Request());
$blade  = new Blade(views_path(), cache_path());

/**
 * Home page
 */
$router->get('/', function ($request) use ($blade) {
    $data = [
        'locale' => $request->getLocale(),
        'title'  => trans('home.title', $request->getLocale())
    ];

    return $blade->render('home.index', $data);
});

/**
 * Contact form submit
 */
$router->post('/contact/submit', function ($request) {
    $errors   = [];
    $response = [];
    $body     = $request->getBody();

    $rules = [
        'name'    => 'required',
        'email'   => 'required|email',
        'subject' => 'required',
        'message' => 'required',
    ];

    $validator = new Validator($rules, $body);

    $errors = $validator->validate();

    if ($request->httpXRequestedWith !== 'XMLHttpRequest') {
        $errors['general'] = 'Request not allowed!';
    }

    if (empty($errors)) {
        try {
            $mail = new MailService($body);
            $mail->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $errors['general'] = "Message cannot be send. Error: {$e->errorMessage()}";
        }
    }

    $response = [
        'success' => empty($errors),
        'errors'  => !empty($errors) ? $errors : null,
        'message' => empty($errors) ? 'Message send successfully!' : 'General error! Please try again later.'
    ];

    return json_encode($response);
});
