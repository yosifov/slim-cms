<?php

namespace App\Controllers;

use App\Contracts\IRequest;
use App\Controllers\Traits\SendMessage;

class HomeController extends Controller
{
    use SendMessage;

    /**
     * Returns the index page
     *
     * @param IRequest $request
     * @return string
     */
    public function index(IRequest $request): string
    {
        $data = [
            'title'  => trans('home.title')
        ];
    
        return $this->view('home.index', $data);
    }

    /**
     * Submits contact form and send message via Email
     *
     * @param IRequest $request
     * @return void
     */
    public function contact(IRequest $request)
    {
        $rules = [
            'name'    => 'required',
            'email'   => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ];

        return $this->sendMessage($request, $rules, true);
    }
}
