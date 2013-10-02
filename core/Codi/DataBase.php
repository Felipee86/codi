<?php namespace Codi;

/**
 * Class Db of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Conf;
use Codi\File;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

final class DataBase {

  const MAIN_DB_CONFIG = 'main';

  /**
   * Name of the connection.
   * @var string
   */
  private $_connection;

  /**
   * Object of Doctrine entity manager.
   * @var Doctrine\ORM\EntityManager
   */
  private $_OPdo;

  private static $_ADbc = [];

  private function __construct($connection)
  {
    $this->_connection = $connection;

    $AConfig = self::$_ADbc[$connection]['config'];

    $conn = $AConfig['adapter'] . ':host=' . $AConfig['host'] . ';dbname=' . $AConfig['dbname'];

    $this->_OPdo = new \PDO($conn, $AConfig['user'], $AConfig['password']);
  }

  /**
   * Executing the query and reciving all resaults.
   *
   * @return array
   */
  public final function getQueryAll($query, array $ABind = [])
  {
    $OStmt = $this->_getQuery($query, $ABind);

    return $OStmt->fetchAll();
  }

  /**
   * Executing the query and reciving first column of the resaults.
   *
   * @return array
   */
  public final function getQueryCol($query, array $ABind = [])
  {
    $OStmt = $this->_getQuery($query, $ABind);

    // TODO:
    return $OStmt->fetchAll(\PDO::FETCH_COLUMN);
  }

  /**
   * Executing the query and reciving single value.
   *
   * @return array
   */
  public final function getQueryOne($query, array $ABind = [])
  {
    $OStmt = $this->_getQuery($query, $ABind);

    if ($OStmt->rowCount() == 1 && $OStmt->columnCount() == 1) {
      $AResults = $OStmt->fetchAll();
      return $AResults[0][0];
    }
    else {
      Error::throwError('Zapytanie powinno zwrocic tylko pojednyncza wartosc.');
    }


  }

  private function _getQuery($query, array $ABind = [])
  {
    $type = \PDO::PARAM_STR;

    $OStmt = $this->_OPdo->prepare($query);
    if (!empty($ABind)) {
      foreach ($ABind as $param => $value) {
        if (is_numeric($param)) {
          ++$param;
          $type = \PDO::PARAM_INT;
        }
        $OStmt->bindParam($param, $value, $type);
      }
    }

    $OStmt->execute();
    return $OStmt;
  }

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
      $configNameMd5 = md5(implode('', $config));
      self::$_ADbc[$configNameMd5] = [];
      self::$_ADbc[$configNameMd5]['config']  = $config;
      self::$_ADbc[$configNameMd5]['handler'] = new DataBase($configNameMd5);
      return self::$_ADbc[$configNameMd5]['handler'];
    }
    elseif (isset(self::$_ADbc[$config])) {
      if (isset(self::$_ADbc[$config]['handler'])) {
        return self::$_ADbc[$config]['handler'];
      }
      else {
        self::$_ADbc[$config]['handler'] = new DataBase($config);
        return self::$_ADbc[$config]['handler'];
      }
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
        self::$_ADbc[$connName] = [];
        self::$_ADbc[$connName]['config']  = $ADbConfig;
      }
    }
  }
}