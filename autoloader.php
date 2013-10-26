<?php

function loadCoreClass($className)
{
  $path = CORE_PATH . DIRECTORY_SEPARATOR . str_replace("\\", "/", $className) . '.php';
  if (file_exists($path)) {
    require_once $path;
  }
}

function loadExtendClass($className)
{
  $path = EXTEND_PATH . DIRECTORY_SEPARATOR . 'backend' . DIRECTORY_SEPARATOR . str_replace("\\", "/", $className) . '.php';
  if (file_exists($path)) {
    require_once $path;
  }
}

function loadControllerClass($className)
{
  $AClass = explode("\\", $className);
  $path = APPLICATION_PATH . DIRECTORY_SEPARATOR .
          strtolower($AClass[0]) . DIRECTORY_SEPARATOR .
          'controller' . DIRECTORY_SEPARATOR .
          $AClass[1] . '.php';

  if (file_exists($path)) {
    require_once $path;
  }
}

function loadContentClass($className)
{
  $AClass = explode("\\", $className);
  $path = APPLICATION_PATH . DIRECTORY_SEPARATOR .
          strtolower($AClass[0]) . DIRECTORY_SEPARATOR .
          'content' . DIRECTORY_SEPARATOR .
          $AClass[1] . '.php';

  if (file_exists($path)) {
    require_once $path;
  }
}

function loadLayoutClass($className)
{
  $AClass = explode("\\", $className);
  $path = APPLICATION_PATH . DIRECTORY_SEPARATOR .
          strtolower($AClass[0]) . DIRECTORY_SEPARATOR .
          'layout' . DIRECTORY_SEPARATOR .
          $AClass[1] . '.php';

  if (file_exists($path)) {
    require_once $path;
  }
}

spl_autoload_register('loadCoreClass');
spl_autoload_register('loadExtendClass');
spl_autoload_register('loadControllerClass');
spl_autoload_register('loadContentClass');
spl_autoload_register('loadLayoutClass');