<?php

/**
 * Class Db of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Conf.php';
require_once 'Zend/Db/Adapter/Abstract.php';

define('DB_CONF', 'core.db.');

final class Dbi {

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

     $key = $ADbc['host'] . '_' . $ADbc['dbname'];

    if (isset(self::$ADbc[$key]) && self::$ADbc[$key] instanceof Zend_Db_Adapter_Abstract) {
      return self::$ADbc[$key];
    }

    switch ($ADbc['adapter']) {
      case 'mysql' :
        require_once 'Zend/Db/Adapter/Pdo/Mysql.php';
        self::$ADbc[$key] = new Zend_Db_Adapter_Pdo_Mysql($ADbc);
        break;
      case 'mssql' :
        require_once 'Zend/Db/Adapter/Pdo/Mssql.php';
        self::$ADbc[$key] = new Zend_Db_Adapter_Pdo_Mssql($ADbc);
        break;
      default :
        Error::throwError('Nie odnaleziono adaptera');
    }

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