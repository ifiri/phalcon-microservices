<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;

/**
 * Root controller serves index page and cases when something
 * not found or resulted with error.
 */
class RootController extends Controller
{
    /**
     * Handler for index page of this service.
     * 
     * @return Phalcon\Http\Response
     */
    public function index()
    {
        $this->tag->setTitle('Forbidden');

        $this->response->setStatusCode(403, 'Forbidden');

        return $this->response;
    }

    /**
     * Handler for cases when some page was not found.
     * 
     * @return Phalcon\Http\Response
     */
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

    /**
     * Handler for cases when some exception was thrown.
     * 
     * @return Phalcon\Http\Response
     */
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