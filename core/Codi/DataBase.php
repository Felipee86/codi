<?php namespace Codi;

/**
 * Class Db of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Zend/Db/Adapter/Abstract.php';

use Codi\Conf;
use Zend\Db\Adapter as DbAdapter;

define('DB_CONF', 'core.db.');

final class DataBase {

  const DB_CONN_SECTION = 'codi.database';
  const DB_ADAPTER      = 'adapter';

  private static $ADbc = [];

  /**
   * Returns the database handle based on configs
   *
   * @param mixed        the section in config ini file or array of configuration set
   * @return Zend_Db_Adapter_Abstract
   */
  public static function factory($config = '')
  {
     $ADbc = self::_getDbConfig($config);

     $key = md5($ADbc['host'] . '_' . $ADbc['dbname']);

    if (isset(self::$ADbc[$key]) && self::$ADbc[$key] instanceof DbAdapter) {
      return self::$ADbc[$key];
    }

    self::$ADbc[$key] = new DbAdapter($ADbc);

    return self::$ADbc[$key];
  }

  private static function _getDbConfig($config)
  {
    $AConfig = [];

    if (is_array($config)) {
      $AConfig = $config;
    }
    elseif ($config === '') {
      $AConfig = Conf::getSectionOptions(self::DB_CONN_SECTION);
    }
    else {
      $AConfig = Conf::getSectionOptions($config);
    }

    return $AConfig;
  }



}