<?php

namespace App\Controllers;

use App;
use App\Core;

use Phalcon\Mvc\Controller;

class AuthController extends Controller
{
    public function signin()
    {
        $AssetsRepository = $this->di->get('assets/repository');

        $this->tag->setTitle('Sign In');
        $this->view->pick('login');

        $this->assets->addCss(
            $AssetsRepository->get('signin.css')
        );
        $this->assets->addJs(
            $AssetsRepository->get('signin.js')
        );

        $this->response->setContent(
            $this->view->getRender('login', 'login')
        );

        return $this->response;
    }
}