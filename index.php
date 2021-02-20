<?php

require_once './vendor/autoload.php';

use Jackin\Starter\Environment;
use Jackin\Starter\Router;
use Jackin\Starter\Starter;
use Jackin\Config\DirectoryConfig;

DirectoryConfig::setBaseDirectory(__DIR__);

$Starter = new Starter(new Router, new Environment);
$Starter->run();