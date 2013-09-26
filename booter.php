<?php

// Define path to application directory
defined('APPLICATION_PATH')
  || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

define('CONFIG_PATH', realpath(dirname(__FILE__)) . '/config');

define('HTML_PATH', realpath(dirname(__FILE__)) . '/public');

defined('APPLICATION_ENV')
  || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

$handle = opendir(realpath(APPLICATION_PATH));
$MODULES = array();
$MODULES[] = get_include_path();
while (false !== ($dir = readdir($handle))) {
  $sdir = APPLICATION_PATH . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR;
  if ($dir != "." && $dir != ".." && is_dir($sdir)) {
      $MODULES[] = $sdir . "logic";
  }
}
closedir($handle);

// Ensure all logic is on include_path
set_include_path(implode(PATH_SEPARATOR, $MODULES));

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'Conf.php';
require_once 'Dbi.php';
require_once 'Error.php';
require_once 'Lang.php';