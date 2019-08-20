<?php

return [
    'adapter' => 'Sqlite',

    // If in cli, APPLICATION_PATH not defined, so use relative path
    // If in php, APPLICATION_PATH defined, so use absolute path
    'dbname' => defined('APPLICATION_PATH') ? (
        APPLICATION_PATH . '/users.db'
    ) : 'users.db',
];