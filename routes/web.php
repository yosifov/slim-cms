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
        'title'  => trans('home.title')
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
        $errors['general'] = trans('forms.errors.request_not_allowed');
    }

    if (empty($errors)) {
        try {
            $mail = new MailService($body);
            $mail->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $errors['general'] = trans('forms.errors.message_cannot_be_sent') . $e->errorMessage();
        }
    }

    $response = [
        'success' => empty($errors),
        'errors'  => !empty($errors) ? $errors : null,
        'message' => empty($errors) ? trans('forms.success.sent') : trans('forms.errors.general')
    ];

    return json_encode($response);
});
