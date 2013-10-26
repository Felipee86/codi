<?php
// Define path to application directory
defined('APPLICATION_PATH')
  || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

define('CORE_PATH', realpath(dirname(__FILE__)) . '/core');

define('EXTEND_PATH', realpath(dirname(__FILE__)) . '/extend');

define('HTML_PATH', realpath(dirname(__FILE__)) . '/public');

defined('APPLICATION_ENV')
  || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

ini_set('display_errors', 1);
error_reporting(E_ALL);