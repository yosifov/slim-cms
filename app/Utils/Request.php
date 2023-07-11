<?php

namespace App\Utils;

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
     * The current locale
     *
     * @var string
     */
    private $currentLocale;

    /**
     * Array with supported languages
     *
     * @var array
     */
    private $supportedLangs = [
        "en"
    ];

    public function __construct()
    {
        $this->bootstrapSelf();
        $this->setLocale();
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
     * Return the request URI without lang segment if supported
     *
     * @return string
     */
    public function getNonLocalizedUri(): string
    {
        $uriSegments = explode('/', parse_url($this->requestUri, PHP_URL_PATH));

        if (count($uriSegments) > 1) {
            $lang = $uriSegments[1];
            if (in_array($lang, $this->supportedLangs)) {
                array_forget($uriSegments, '1');

                return implode('/', $uriSegments);
            }
        }

        return $this->requestUri;
    }

    /**
     * Sets current locale string
     *
     * @return string
     */
    private function setLocale(): void
    {
        $urlSegments = explode("/", parse_url($this->requestUri, PHP_URL_PATH));

        $this->currentLocale = in_array($urlSegments[1], $this->supportedLangs) ? $urlSegments[1] : array_get($_ENV, 'DEFAULT_LOCALE', 'bg');

        if (!isset($_COOKIE['lang']) || $_COOKIE['lang'] !== $this->currentLocale) {
            setcookie('lang', $this->currentLocale);
            header('Location: ' . $this->requestUri);
        }
    }
}
