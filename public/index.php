<?php
require_once '../booter.php';

/** Codi_Application */
require_once 'Codi/Application.php';

$_ENV['APPLICATION_ENV'] = Conf::getValue('codi.enviroment');

// Create application and run
$application = new Codi_Application();
$application->start();
$application->onFinish();