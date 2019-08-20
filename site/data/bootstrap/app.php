<?php

require_once APPLICATION_PATH . '/vendor/autoload.php';

use App\Core\Application\Builder as ApplicationBuilder;

// Create the application
$BuilderInstance = new ApplicationBuilder\MicroBuilder;
$BuildDirector = new ApplicationBuilder\Director($BuilderInstance);

return $BuildDirector->build();