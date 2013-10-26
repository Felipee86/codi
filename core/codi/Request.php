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

  private static $_AFront_ctrl = [];
  private static $_AOptions    = [];
  private static $_AConfig     = [];
  private static $_ADefault    = [];

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

  private static function _loadConfig()
  {
    $ARouts = Conf::getConfig('routing');
    self::$_AConfig  = $ARouts['routs'];
    self::$_ADefault = $ARouts['default'];

  }

  private static function _loadUrlData()
  {
    self::_loadConfig();

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
      self::$_AFront_ctrl['module'] = strtolower($REQUEST[0]);
      if (isset($REQUEST[1])) {
        self::$_AFront_ctrl['controller'] = strtolower($REQUEST[1]);
        if (isset($REQUEST[2])) {
          self::$_AFront_ctrl['action'] = strtolower($REQUEST[2]);
          if (isset($REQUEST[3])) {
            foreach (explode(',', $REQUEST[3]) as $option) {
              $O = explode('=', $option);
              if (isset($O[1])) {
                $ARR = explode(';', $O[1]);
                if (count($ARR) > 1) {
                  self::$_AOptions[$O[0]] = $ARR;
                }
                else {
                  self::$_AOptions[$O[0]] = $O[1];
                }
              }
              else {
                self::$_AOptions[$O[0]] = true;
              }
            }
          }
        }
        else {
          self::$_AFront_ctrl['action'] = self::$_AConfig[self::$_AFront_ctrl['module']]['action'];
        }
      }
      else {
        self::$_AFront_ctrl['controller'] = self::$_AConfig[self::$_AFront_ctrl['module']]['controller'];
        self::$_AFront_ctrl['action']     = self::$_AConfig[self::$_AFront_ctrl['module']]['action'];
      }
    }
    else {
      self::$_AFront_ctrl['module']      = self::$_ADefault['module'];
      self::$_AFront_ctrl['controller']  = self::$_ADefault['controller'];
      self::$_AFront_ctrl['action']      = self::$_ADefault['action'];
    }
  }

  public static function getRequestData()
  {
    if (empty(self::$_AFront_ctrl)) {
      self::_loadUrlData();
    }
    return ['FrontController' => self::$_AFront_ctrl,
            'Options'         => self::$_AOptions];
  }

  public static function getModule()
  {
    if (empty(self::$_AFront_ctrl)) {
      self::_loadUrlData();
    }
    return self::$_AFront_ctrl['module'];
  }

  public static function getController()
  {
    if (empty(self::$_AFront_ctrl)) {
      self::_loadUrlData();
    }
    return self::$_AFront_ctrl['controller'];
  }

  public static function getAction()
  {
    if (empty(self::$_AFront_ctrl)) {
      self::_loadUrlData();
    }
    return self::$_AFront_ctrl['action'];
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
    if (!empty(self::$_AOptions)) {
      return self::$_AOptions;
    }
    return null;
  }

}