<?php

define('APPLICATION_PATH', __DIR__ . '/..');

$app = require APPLICATION_PATH . '/bootstrap/app.php';
$app->handle();