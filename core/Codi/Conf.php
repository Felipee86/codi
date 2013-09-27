<?php namespace Codi;

/**
 * Class Conf of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Error;

final class Conf {

  /**
   * Array of configs from .ini files
   * @var array
   */
  private static $_AConf = [];

  private static function _loadConfig()
  {
    if (empty(self::$_AConf)) {
      $handle = opendir(realpath(CONFIG_PATH));
      while (false !== ($dir = readdir($handle))) {
        if ($dir != "." && $dir != ".." && is_dir(CONFIG_PATH . DIRECTORY_SEPARATOR . $dir)) {
          $option = new Codi\Ini(CONFIG_PATH . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . "module.ini");
          self::$_AConf = array_merge(self::$_AConf, self::_getAllOptions($option->toArray(), $dir));
        }
      }
      closedir($handle);

      if (file_exists(CONFIG_PATH . DIRECTORY_SEPARATOR . 'system.ini')) {
        $option_system = new Codi\Ini(CONFIG_PATH . DIRECTORY_SEPARATOR . 'system.ini');
        self::$_AConf = array_merge(self::$_AConf, self::_getAllOptions($option_system->toArray()));
      }

      if (file_exists(CONFIG_PATH . DIRECTORY_SEPARATOR . 'user.ini')) {
        $option_user = new Codi\Ini(CONFIG_PATH . DIRECTORY_SEPARATOR . 'user.ini');
        self::$_AConf = array_merge(self::$_AConf, self::_getAllOptions($option_user->toArray()));
      }
    }
  }

  private static function _getAllOptions(Array $AConfig, $ckey = '')
  {
    $AConf = [];
    foreach ($AConfig as $key => $value) {
      $temp = ($ckey !== '' ? $ckey . '.' : '') . $key;
      if (is_array($value)) {
        $AConf = array_merge($AConf, self::_getAllOptions($value, $temp));
      }
      else {
        $AConf[$temp] = $value;
      }
    }
    return $AConf;
  }

  public static function getSectionOptions($section)
  {
    self::_loadConfig();

    $AOption = [];
    if ($section != '') {
      foreach (self::$_AConf as $key => $value) {
        $AFields = [];
        if (preg_match("/^($section\.)(.+)$/", $key, $AFields)) {
          $AOption[$AFields[2]] = $value;
        }
      }
    }

    if (empty($AOption)) {
      return false;
    }
    return $AOption;
  }

  public static function getValue($config)
  {
    self::_loadConfig();

    if (isset(self::$_AConf[$config])) {
      return self::$_AConf[$config];
    }
    Error::addMsg('Brak opcji: ' . $config);
    return false;
  }

  public function getAllConfigs()
  {
    self::_loadConfig();

    return self::$_AConf;
  }

  public static function isOption($option)
  {
    self::_loadConfig();

    if (array_key_exists($option, self::$_AConf)) {
      $config = self::getValue(self::$_AConf[$option]);
      if (empty($config)) {
        return false;
      }
      return true;
    }
    return false;
  }

}