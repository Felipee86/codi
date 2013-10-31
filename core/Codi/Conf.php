<?php

namespace Codi;

/**
 * Class Conf of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Error;
use Codi\Modular;

final class Conf {

  /**
   * Array of configs from .ini files
   * @var array
   */
  private static $_AConf = [];

  private static $_AConfFlattern = [];

  public static function getConfig($config)
  {
    self::_loadConfig();

    if (isset(self::$_AConf[$config])) {
      return self::$_AConf[$config];
    }
    else {
      Error::throwError('Nie istnieje konfiguracja: ' . $config);
    }
  }

  private static function _loadConfig()
  {
    if (empty(self::$_AConf)) {
      $handle = opendir(realpath(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config'));
      self::_addConfigDir($handle);

      foreach (array_keys(self::$_AConf['modules']) as $module) {
        $module_path = opendir(realpath(APPLICATION_PATH . '/Modules/' . $module . DIRECTORY_SEPARATOR . 'config'));
        if (is_dir($module_path)) {
          self::_addConfigDir($module_path, $module);
        }
      }

      self::$_AConfFlattern = self::_flatternConfig();
    }
  }

  private static function _addConfigDir($dir, $module = '') {
    while (false !== ($configFile = readdir($dir))) {
      $AFile = explode('.', $configFile);
      $ext = array_pop($AFile);
      $configName = implode('.', $AFile);

      if ($ext == 'php' && !is_dir($configFile)) {
        $configPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $configFile;

        if (!empty($module)) {
          if (!isset(self::$_AConf[$module])) {
            self::$_AConf[$module] = [];
          }
          self::$_AConf[$module][$configName] = require $configPath;
        }
        else {
          self::$_AConf[$configName] = require $configPath;
        }
      }
    }
  }


  public static function getOption($option)
  {
    self::_loadConfig();

    if (isset(self::$_AConfFlattern[$option])) {
      return self::$_AConfFlattern[$option];
    }
    elseif (isset(self::$_AConfFlattern['general.' . $option])) {
      return self::$_AConfFlattern['general.' . $option];
    }
    return false;
  }

  private static function _flatternConfig($AConfig = null, $_key = '')
  {
    // TODO: Opcja z cachowaniem

    $_AFlat = [];
    if (!empty($_key)) {
      $_key .= '.';
    }

    if (is_null($AConfig)) {
      $AConfig = self::$_AConf;
    }

    foreach ($AConfig as $key => $AOptions) {
      $key = $_key . $key;
      $_AFlat[$key] = $AOptions;
      if (is_array($AOptions)) {
        $ATmp = self::_flatternConfig($AOptions, $key);
        $_AFlat = array_merge($_AFlat, $ATmp);
      }
    }

    return $_AFlat;
  }

  public static function isOption($option)
  {
    self::_loadConfig();

    if (isset(self::$_AConfFlattern[$option])) {
      return true;
    }
    return false;
  }

}