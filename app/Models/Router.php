<?php

namespace App\Models;

use Jenssegers\Blade\Blade;

class Router
{
    /** @var Request $request */
    private $request;

    private $supportedHttpMethods = array(
        "GET",
        "POST"
    );

    public function __construct(IRequest $request)
    {
        $this->request = $request;
    }

    public function __call($name, $args)
    {
        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes trailing forward slashes from the right of the route.
     * @param route (string)
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler()
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");

        $blade = new Blade('resources/views', 'cache');

        echo $blade->render('error.404', [
            'locale' => $this->request->getLocale(),
            'lang' => ['title' => 'Page Not Found'],
        ]);
    }

    /**
     * Resolves a route
     */
    private function resolve()
    {
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        $formattedRoute = $this->formatRoute($this->request->requestUri);
        $method = $methodDictionary[$formattedRoute];

        if (is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }

        echo call_user_func_array($method, array($this->request));
    }

    public function getLocale()
    {
        return $this->request->getLocale();
    }

    public function __destruct()
    {
        $this->resolve();
    }
}
