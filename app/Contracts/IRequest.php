<?php

namespace App\Contracts;

interface IRequest
{
    public function getBody();

    public function getNonLocalizedUri();

    public function getLocale();
}
