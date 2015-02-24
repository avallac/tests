<?php

function __autoload($name)
{
    $name = str_replace("\\", DIRECTORY_SEPARATOR, $name);
    $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'protected' .
            DIRECTORY_SEPARATOR . $name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

$config=dirname(__FILE__).'/protected/config/config.php';
$app = new \Anonymizer\WebApplication($config);
$app->run();
