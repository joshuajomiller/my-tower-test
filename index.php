<?php

//autoload classes
require 'vendor/autoload.php';
spl_autoload_register(function ($classname) {
    require ("app/classes/" . $classname . ".php");
});

//Config file
require "app/config.php";

//create new app with config
$app = new \Slim\App(["settings" => $config]);
$container = $app->getContainer();

// Register view on container
$container['view'] = function ($container) {
    return new \Slim\Views\PhpRenderer('app/views/');
};

//require db connection and routes
require "app/database.php";
require "app/routes.php";

//start app
$app->run();