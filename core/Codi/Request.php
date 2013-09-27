<?php namespace Codi;

/**
 * Class Codi_Request of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Error;

class Request {

  const MAIN_MODULE = 'center';

  private static $AFront_controller = [];
  private static $AOptions = [];

  public static function getUri()
  {
    return $_SERVER['REQUEST_URI'];
  }

  private static function _validateRequest(Array $REQUEST)
  {
    if (count($REQUEST) > 4) {
      throw new Exception('Nieprawidlowa ilosc parametrow w adresie');
    }

    foreach ($REQUEST as $key => $req) {
      if ($key <= 2) {
        if (!preg_match('/^[\_\-a-zA-Z0-9]+$/', $req)) {
          throw new Exception('Niewlasciwy adres');
        }
      }
      else {
        if (!preg_match('/^([\_\-a-zA-Z0-9]+\=[\;\%\_\-a-zA-Z0-9]+\,*)*$/', $req)) {
          throw new Exception('Niewlasciwy adres');
        }
      }
      $REQUEST[$key] = strtolower($req);
    }
    return $REQUEST;
  }

  private static function _loadUrlData()
  {
    $REQUEST_URI = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $SCRIPT_NAME = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'), -1);

    $REQUEST = [];
    $i = 0;
    if (!empty($SCRIPT_NAME)) {
      while (isset($REQUEST_URI[$i]) && isset($SCRIPT_NAME[$i]) && $REQUEST_URI[$i] == $SCRIPT_NAME[$i]) {
        $i++;
      }
    }

    while ($i <= count($REQUEST_URI) - 1) {
      if (!empty($REQUEST_URI[$i])) {
        $REQUEST[] = $REQUEST_URI[$i];
      }
      $i++;
    }

    $REQUEST = self::_validateRequest($REQUEST);

    if (isset($REQUEST[0])) {
      self::$AFront_controller['module'] = $REQUEST[0];
      if (isset($REQUEST[1])) {
        self::$AFront_controller['controller'] = $REQUEST[1];
        if (isset($REQUEST[2])) {
          self::$AFront_controller['action'] = $REQUEST[2];
          if (isset($REQUEST[3])) {
            foreach (explode(',', $REQUEST[3]) as $option) {
              $O = explode('=', $option);
              if (isset($O[1])) {
                $ARR = explode(';', $O[1]);
                if (count($ARR) > 1) {
                  self::$AOptions[$O[0]] = $ARR;
                }
                else {
                  self::$AOptions[$O[0]] = $O[1];
                }
              }
              else {
                self::$AOptions[$O[0]] = true;
              }
            }
          }
        }
        else {
          self::$AFront_controller['action'] = Conf::getValue(self::$AFront_controller['module'] . '.default.action');
        }
      }
      else {
        self::$AFront_controller['controller'] = Conf::getValue(self::$AFront_controller['module'] . '.default.controller');
        self::$AFront_controller['action']     = Conf::getValue(self::$AFront_controller['module'] . '.default.action');
      }
    }
    else {
      self::$AFront_controller['module']      = self::MAIN_MODULE;
      self::$AFront_controller['controller']  = Conf::getValue('codi.default.controller');
      self::$AFront_controller['action']      = Conf::getValue('codi.default.action');
    }
  }

  public static function getRequestData()
  {
    if (empty(self::$AFront_controller)) {
      self::_loadUrlData();
    }
    return ['FrontController' => self::$AFront_controller,
            'Options'         => self::$AOptions];
  }

  public static function getModule()
  {
    if (empty(self::$AFront_controller)) {
      self::_loadUrlData();
    }
    return self::$AFront_controller['module'];
  }

  public static function getController()
  {
    if (empty(self::$AFront_controller)) {
      self::_loadUrlData();
    }
    return self::$AFront_controller['controller'];
  }

  public static function getAction()
  {
    if (empty(self::$AFront_controller)) {
      self::_loadUrlData();
    }
    return self::$AFront_controller['action'];
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

}