<?php

namespace App\Controllers;

use App\Contracts\IRequest;
use Jenssegers\Blade\Blade;
use App\Services\CsrfService;

abstract class Controller
{
    private Blade $view;

    protected IRequest $request;

    protected CsrfService $csrf;

    public function __construct(IRequest $request) {
        $this->request = $request;
        $this->view    = new Blade(views_path(), cache_path());
        $this->csrf    = new CsrfService($request);

        $this->shareViewData();
    }

    /**
     * Returns rendered view
     *
     * @param string $view
     * @param array $data
     * @return string The view
     */
    protected function view(string $view, array $data = []): string
    {
        return $this->view->render($view, $data);
    }

    /**
     * Creates custom view directives
     *
     * @return void
     */
    private function shareViewData(): void
    {
        $this->view->share('csrf', $this->csrf->insertHiddenToken());
    }
}
