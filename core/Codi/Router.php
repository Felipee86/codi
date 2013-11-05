<?php

namespace Codi;

use Codi\Conf;
use Codi\Error;
use Codi\Request;

final class Router {

  private static $ARegister = [];

  private static $_ACustomRoutes = [];

  private $_module = '';

  private $_controller = '';

  private $_action = '';

  private function __construct($route) {
    list($this->_module, $_act_cont) = explode('#', $route . '#');
    if (!empty($_act_cont)) {
      list($this->_action, $this->_controller) = explode('@', $_act_cont . '@');
      if (!empty($this->_controller)) {
        return;
      }
    }

    Error::throwError('Niewłaściwa ścieżka: ' . $route);
  }

  private static function _loadConfig() {
    if (empty($_ACustomRoutes)) {
      // TODO: zbieranie ustawień z wszystkich modułów
      $AConfig = Conf::getConfig('routing');
      self::$_ACustomRoutes = $AConfig['routs'];
    }
  }


  private static function _registry($route) {
    self::_loadConfig();

    if (isset(self::$_ACustomRoutes[$route])) {
      $route = self::$_ACustomRoutes[$route];
    }

    if (!isset(self::$ARegister[$route])) {

      self::$ARegister[$route] = new Router($route);
    }
    return self::$ARegister[$route];
  }

  /**
   * Gets the instance of Codi\Router based on route.
   *
   * @param string $route Route in format module#action(at)controller
   * @return \Codi\Router
   */
  public static function get($route = '') {
    if (empty($route)) {
      $route = 'default';
    }
    return self::_registry($route);
  }

  public static function link($route, $AOptions = []) {
    $route = self::get($route);

    $url = $_SERVER['HTTP_HOST'] . '/' . $route->getModule() . '/' . $route->getController() . '/' . $route->getAction();

    foreach($AOptions as $option) {
      $url .= '/' . $option;
    }

    return $url;
  }

  public function getModule() {
    return $this->_module;
  }

  public function getController() {
    return $this->_controller;
  }

  public function getAction() {
    return $this->_action;
  }

  /**
   * Reciving the controller class after checking class and action existance.
   *
   * @return \Codi\Controller\ControllerAbstract
   */
  public function reciveController() {
    $className = "Application\\Modules\\" . $this->_module . "\\Controllers\\" . $this->_controller;

    if (class_exists($className)) {
      $OController = new $className;
      if (method_exists($OController, $this->_action)) {
        $reflection = new \ReflectionMethod($OController, $this->_action);

        if (count(Request::getOptions()) >= $reflection->getNumberOfRequiredParameters()) {
          $OController->setAction($this->action);

          return $OController;
        }
        Error::throwError('Nie przekazano wszystkich wymaganych parametrów');
      }
      Error::throwError('Akcja: ' . $this->_action . ' nie istnieje.');
    }
    Error::throwError('Klasa kontrollera: ' . $className . ' nie istnieje.');
  }

}

?>
