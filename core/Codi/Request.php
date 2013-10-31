<?php namespace Codi;

/**
 * Class Codi_Request of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Error;
use Codi\Modular;

class Request {

  private static $_AFront      = [];
  private static $_AOptions    = [];

  private static $_router      = null;

  public static function getUri()
  {
    return $_SERVER['REQUEST_URI'];
  }

  private static function _validateRequest(Array $REQUEST)
  {
    foreach ($REQUEST as $key => $req) {
      if ($key <= 2) {
        if (!preg_match('/^[\_\-a-zA-Z0-9]+$/', $req)) {
          Error::throwError('Niewlasciwy adres');
        }
      }
      else {
        if (!preg_match('/^[\%\_\-a-zA-Z0-9]+$/', $req)) {
          Error::throwError('Niewlasciwy adres');
        }
      }
    }
    return $REQUEST;
  }

  private static function _loadUrlData()
  {
    if (empty(self::$_AFront)) {
      $REQUEST_URI = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
      $SCRIPT_NAME = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'), -1);

      $ARequest = [];
      $i = 0;
      if (!empty($SCRIPT_NAME)) {
        while (isset($REQUEST_URI[$i]) && isset($SCRIPT_NAME[$i]) && $REQUEST_URI[$i] == $SCRIPT_NAME[$i]) {
          $i++;
        }
      }

      while ($i <= count($REQUEST_URI) - 1) {
        if (!empty($REQUEST_URI[$i])) {
          $ARequest[] = $REQUEST_URI[$i];
        }
        $i++;
      }

      $ARequest = self::_validateRequest($ARequest);

      $AElements = ['module', 'controller', 'action'];

      while ($AElements) {
        $req = array_shift($ARequest);
        self::$_AFront[array_shift($AElements)] = ($req ? ucfirst(strtolower($req)) : null);
      }

      self::$_AFront = self::getRoute(self::_AFront);

      while ($ARequest) {
        self::$_AOptions[] = array_shift($ARequest);
      }
    }
  }

  private static function getRoute($AFront) {
    $route = $AFront['module'] . '#' . $AFront['action'] . '@' . $AFront['controller'];
    $router = Router::get($route);
    return [
        'module'     => $router->getModule(),
        'controller' => $router->getController(),
        'action'     => $router->getAction(),
    ];
  }

  public static function getRequestRouter()
  {
    self::_loadUrlData();

    if (empty(self::$_router)) {
      $route = self::getModule() . '#' . self::getAction() . '@' . self::getController();

      self::$_router = Router::get($route);
    }

    return self::$_router;

    return ['FrontController' => self::$_AFront,
            'Options'         => self::$_AOptions];
  }

  public static function getModule()
  {
    self::_loadUrlData();

    if (!self::$_AFront['module']) {
      self::$_AFront['module'] = ucfirst(Router::get()->getModule());
    }

    return self::$_AFront['module'];
  }

  public static function getController()
  {
    self::_loadUrlData();

    if (!self::$_AFront['controller']) {
      self::$_AFront['controller'] = ucfirst(Modular::get(self::getModule())->getDefaultController());
    }

    return self::$_AFront['controller'];
  }

  public static function getAction()
  {
    self::_loadUrlData();

    if (!self::$_AFront['action']) {
      self::$_AFront['action'] = ucfirst(Modular::get(self::getModule())->getDefaultAction());
    }

    return self::$_AFront['action'];
  }

  public static function getPost()
  {
    $AData = [];
    foreach ($_POST as $var => $value) {
      $KEY = explode('.', $var);
      if (count($KEY) == 2) {
        $AData[$KEY[0]][$KEY[1]] = $value;
      }
      elseif (count($KEY) == 1) {
        $AData[$var] = $value;
      }
      else {
        Error::throwError('Bład przetwarzania danych żadania.');
      }
    }

    return $AData;
  }

  public static function getOptions()
  {
    self::_loadUrlData();

    return self::$_AOptions;
  }

}