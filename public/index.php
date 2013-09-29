<?php
require_once '../booter.php';
require_once '../autoloader.php';

/** Codi_Application */
//require_once 'Codi/Application.php';

$_ENV['APPLICATION_ENV'] = Codi\Conf::getOption('enviroment');

// Create application and run
$application = new Codi\Application();
$application->start();
$application->onFinish();