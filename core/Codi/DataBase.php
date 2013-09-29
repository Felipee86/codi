<?php namespace Codi;

/**
 * Class Db of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Conf;
use Zend\Db\Adapter\Adapter as DbAdapter;

define('DB_CONF', 'core.db.');

final class DataBase {

  const MAIN_DB_CONFIG = 'main';

  private static $_ADbc = [];

  /**
   * Returns the database handle based on config.
   *
   * @param  mixed           (optional)The section in config file or array of configuration set.
   * @return Zend\Db\Adapter\Adapter
   */
  public static function factory($config = '')
  {
    self::_loadConfig();

    if (empty($config)) {
      $config = self::MAIN_DB_CONFIG;
    }

    if (is_array($config)) {
      return new DbAdapter($config);
    }
    elseif (isset(self::$_ADbc[$config])) {
      return self::$_ADbc[$config];
    }
    else {
      Error::throwError('Nie istnieje konfiguracja bazy danych o nazwie: ' . $config);
    }
  }

  private static function _loadConfig()
  {
    if (empty(self::$_ADbc)) {
      $ADb = Conf::getConfig('database');

      foreach ($ADb['connections'] as $connName => $ADbConfig) {
        self::$_ADbc[$connName] = new DbAdapter($ADbConfig);
      }
    }
  }
}