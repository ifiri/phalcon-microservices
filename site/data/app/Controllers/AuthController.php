<?php

namespace App\Controllers;

use App\Core;

use Phalcon\Mvc\Controller;

/**
 * This conreoller serves `auth`-scoped endpoints.
 */
class AuthController extends Controller
{
    /**
     * Handler for `Sign In` page.
     * 
     * @return Phalcon\Http\Response
     */
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