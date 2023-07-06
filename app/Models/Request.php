<?php

namespace App\Models;

use App\Models\IRequest;

class Request implements IRequest
{
    private $supportedLangs = array(
        "en"
    );

    private $defaultLang = 'bg';

    public function __construct()
    {
        $this->bootstrapSelf();
    }

    private function bootstrapSelf()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach ($matches[0] as $match) {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        return $result;
    }

    public function getBody()
    {
        $body = array();

        if ($this->requestMethod == "POST") {

            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

        }

        return $body;
    }

    public function getLocale()
    {
        $urlSegments = explode("/", parse_url($this->requestUri, PHP_URL_PATH));

        return in_array($urlSegments[1], $this->supportedLangs) ? $urlSegments[1] : $this->defaultLang;
    }
}
