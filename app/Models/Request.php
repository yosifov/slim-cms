<?php

namespace App\Models;

use App\Contracts\IRequest;

class Request implements IRequest
{
    /**
     * The request method
     *
     * @var string
     */
    public $requestMethod;

    /**
     * The request Uri
     *
     * @var string
     */
    public $requestUri;

    /**
     * Array with supported languages
     *
     * @var array
     */
    private $supportedLangs = [
        "en"
    ];

    /**
     * The default language
     *
     * @var string
     */
    private $defaultLang = 'bg';

    public function __construct()
    {
        $this->bootstrapSelf();
    }

    /**
     * Bootstraps $_SERVER props to the model
     *
     * @return void
     */
    private function bootstrapSelf()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{to_camel($key)} = $value;
        }
    }

    /**
     * Returns sanitizes request body
     *
     * @return array
     */
    public function getBody(): array
    {
        $body = array();

        if ($this->requestMethod == "POST") {

            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

        }

        return $body;
    }

    /**
     * Returns locale string
     *
     * @return string
     */
    public function getLocale(): string
    {
        $urlSegments = explode("/", parse_url($this->requestUri, PHP_URL_PATH));

        return in_array($urlSegments[1], $this->supportedLangs) ? $urlSegments[1] : $this->defaultLang;
    }
}
