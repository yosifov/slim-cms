<?php

namespace App\Controllers\Traits;

use App\Utils\Request;
use App\Utils\Validator;
use App\Services\MailService;

trait SendMessage
{
    public function sendMessage(Request $request, array $rules, bool $isAjax = false)
    {
        $errors   = [];
        $response = [];
        $body     = $request->getBody();
        $errors   = (new Validator($rules, $body))->validate();

        if ($isAjax && $request->httpXRequestedWith !== 'XMLHttpRequest') {
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
    }
}
