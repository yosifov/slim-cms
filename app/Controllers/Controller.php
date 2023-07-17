<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

abstract class Controller
{
    private Blade $view;

    public function __construct() {
        $this->view = new Blade(views_path(), cache_path());
    }

    protected function view(string $view, array $data = [])
    {
        return $this->view->render($view, $data);
    }
}
