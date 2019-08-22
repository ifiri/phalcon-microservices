<?php

namespace App\Core\Application\Builder;

use App\Contracts;
use App\Core;
use App;
use App\Controllers;
use App\Data;

use Phalcon\Mvc;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\Di;
use Phalcon\Config;

/**
 * Builder for Micro Phalcon applications.
 * Encapsulates itself logic of all steps which required
 * for Micro application.
 */
class MicroBuilder implements Contracts\Builder\ApplicationBuilder
{
    private $Application;

    /**
     * In constructor, create an empty application
     * which we will build.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->Application = new Mvc\Micro;
    }

    /**
     * Returns current product.
     * 
     * @return object
     */
    public function getProduct(): object
    {
        return $this->Application;
    }

    /**
     * Builds Phalcon DI and binds it with app.
     * Also creates aliases of Config, repositories, etc.
     * 
     * @return void
     */
    public function buildDependencies()
    {
        $di = new Di\FactoryDefault;

        $di->set(
            'config', function() {
                return Config\Factory::load([
                    'filePath' => APPLICATION_PATH . '/config/config.php',
                    'adapter' => 'php',
                ]);
            }
        );
        $di->set(
            'assets/repository', function() {
                return new Core\AssetsRepository($this->get('config'));
            }
        );
        
        $this->Application->setDI($di);
    }

    /**
     * Sets up routing. Gets all routes which should be served
     * from the config and register controllers in application
     * via MicroCollection.
     * 
     * @return void
     */
    public function buildRouting()
    {
        $config = $this->Application->di->get('config')->routing;

        foreach ($config as $collectionParams) {
            try {
                $Collection = $this->buildRoutesCollection($collectionParams);

                if($Collection->getHandler() instanceof Controllers\RootController) {
                    $this->Application->notFound([
                        $Collection->getHandler(), 'notFound'
                    ]);

                    $this->Application->error([
                        $Collection->getHandler(), 'error'
                    ]);
                }

                $this->Application->mount($Collection);
            } catch (\Exception $Exception) {
                throw $Exception;
            }
        }
    }

    /**
     * Adds support for views. Register in Application instance
     * callback which will be called every time when Micro App
     * requests a view.
     * 
     * @return void
     */
    public function buildViews()
    {
        $config = $this->Application->di->get('config');

        $this->Application['view'] = function() use ($config) {
            $viewsPath = APPLICATION_PATH . '/' . $config->application->directories->views;

            $view = new Mvc\View;
            $view->setViewsDir($viewsPath . '/');
            $view->setLayoutsDir($viewsPath . '/layouts/');
            $view->registerEngines([
                '.phtml' => 'Phalcon\Mvc\View\Engine\Volt',
            ]);
            $view->setMainView('layouts/main');

            return $view;
        };
    }

    /**
     * Helper method. Accept parameters of creating collection 
     * from the config, then creates new MicroCollection instance,
     * then sets it up and returns.
     * 
     * @param Config $collectionParams 
     * @return MicroCollection
     */
    private function buildRoutesCollection(Config $collectionParams): MicroCollection
    {
        $handlerCallable = $collectionParams->get('handler');
        $collectionHandler = new $handlerCallable;

        $Collection = new MicroCollection;

        $Collection->setHandler(
            new $handlerCallable
        );
        $Collection->setPrefix($collectionParams->get('prefix'));

        foreach ($collectionParams->get('endpoints') as $endpoint => $endpointData) {
            $method = $endpointData['method'];
            $handler = $endpointData['handler'];

            $Collection->{$method}($endpoint, $handler);
        }

        return $Collection;
    }
}