<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;

class RootController extends Controller
{
    public function index()
    {
        $this->tag->setTitle('Forbidden');

        $this->response->setStatusCode(403, 'Forbidden');

        return $this->response;
    }

    public function notFound()
    {
        $this->tag->setTitle('Nothing Found');

        $this->view->pick('not-found');

        $this->response->setStatusCode(404, 'Not Found');
        $this->response->setContent(
            $this->view->getRender('not-found', 'not-found')
        );

        return $this->response;
    }

    public function error($exception)
    {
        $this->tag->setTitle('Unexpected Error');

        $this->view->pick('error');

        $this->response->setStatusCode(500, 'Internal Server Error');
        $this->response->setContent(
            $this->view->getRender('error', 'error')
        );

        return $this->response;
    }
}