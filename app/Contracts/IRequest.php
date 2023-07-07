<?php

namespace App\Contracts;

interface IRequest
{
    public function getBody();

    public function getLocale();
}
