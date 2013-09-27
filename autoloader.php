<?php
function loadCoreClass($className)
{
  $path = CORE_PATH . DIRECTORY_SEPARATOR . str_replace("\\", "/", $className) . '.php';
  if (file_exists($path)) {
    require_once $path;
  }
}

spl_autoload_register('loadCoreClass');