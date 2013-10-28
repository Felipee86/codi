<?php
require_once '../booter.php';
require_once '../vendor/autoload.php';

/** Codi_Application */

$_ENV['APPLICATION_ENV'] = \Codi\Conf::getOption('enviroment');

// Create application and run
$application = new Codi\Application();
$application->start();
$application->onFinish();