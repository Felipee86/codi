<?php namespace Codi;

/**
 * Class Class of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */
class Loader {

  const CONTENT_DIR    = 'content';
  const CONTROLLER_DIR = 'controller';
  const LAYOUT_DIR     = 'layout';
  const LOGIC_DIR      = 'logic';

  public static function loadClass($className, $module = 'core')
  {
    if ($module == 'core') {
      $classPath = CORE_PATH;
    }
    else {
      $classPath = APPLICATION_PATH
                   . DIRECTORY_SEPARATOR . ucfirst($module)
                   . DIRECTORY_SEPARATOR . self::LOGIC_DIR;
    }

    $classPath .= DIRECTORY_SEPARATOR . str_replace('_', '/', $className) . '.php';

    if (file_exists($classPath)) {
      require_once $classPath;
      return true;
    }

    return false;
  }

  public static function loadController($className, $module)
  {
    $classPre = ucfirst($module) . '_Controller_';
    if (strpos($className, $classPre) !== 0) {
      $controllerName = $className;
      $className = $classPre . $className;
    }
    else {
      $AClass = explode('_', $className);
      $controllerName = array_pop($AClass);
    }

    $classPath = APPLICATION_PATH
                . DIRECTORY_SEPARATOR . $module
                . DIRECTORY_SEPARATOR . self::CONTROLLER_DIR
                . DIRECTORY_SEPARATOR . $controllerName . '.php';

    if (file_exists($classPath)) {
      require_once $classPath;
      return true;
    }

    return false;
  }

  public static function loadLayout($className, $module)
  {
    $classPre = 'Rendus_Layout_';

    if (strpos($className, $classPre) !== 0) {
      $layoutName = $className;
      $className = $classPre . $className;
    }
    else {
      $AClass = explode('_', $className);
      $layoutName = array_pop($AClass);

    }


    $classPath = APPLICATION_PATH
                . DIRECTORY_SEPARATOR . $module
                . DIRECTORY_SEPARATOR . self::LAYOUT_DIR
                . DIRECTORY_SEPARATOR . $layoutName . '.php';

    if (file_exists($classPath)) {
      require_once $classPath;
      return true;
    }

    return false;
  }

  public static function loadContent($className, $module)
  {
    $classPre = ucfirst($module) . '_Content_';
    if (strpos($className, $classPre) !== 0) {
      $contentName = $className;
      $className = $classPre . $className;
    }
    else {
      $AClass = explode('_', $className);
      $contentName = array_pop($AClass);
    }

    $classPath = APPLICATION_PATH
                . DIRECTORY_SEPARATOR . $module
                . DIRECTORY_SEPARATOR . self::CONTENT_DIR
                . DIRECTORY_SEPARATOR . $contentName . '.php';

    if (file_exists($classPath)) {
      require_once $classPath;
      return true;
    }

    return false;
  }

}