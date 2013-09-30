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
  private $_OEm;

  public $OQb;

  private static $_ADbc = [];

  private static $_ODoctrineConfig;

  private function __construct($connection)
  {
    $this->_connection = $connection;

    $AConfig = self::$_ADbc[$connection]['config'];
    $this->_OEm = $this->_getEntityManager($AConfig);
    $this->OQb = $this->_OEm->createQueryBuilder();
  }

  /**
   * Executing the query and reciving all resaults.
   *
   * @return array
   */
  public final function getQueryAll()
  {
    $query = $this->OQb->getQuery();

    return $query->getResult();
  }

  /**
   * Executing the query and reciving single value.
   *
   * @return array
   */
  public final function getQueryOne()
  {
    $query = $this->OQb->getQuery();

    return $query->getSingleResult();
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

  private function _getEntityManager($AConfig)
  {
    return EntityManager::create($AConfig, self::$_ODoctrineConfig);
  }

  private static function _loadConfig()
  {
    if (empty(self::$_ADbc)) {
      $ADb = Conf::getConfig('database');

      $ADoctrinConfigPath = [];
      $AModulesDirs = File::getModulesDirs();
      foreach ($AModulesDirs as $module => $moduleDir) {
        $ADoctrinConfigPath[] = $moduleDir . DIRECTORY_SEPARATOR . 'entity';
      }
      self::$_ODoctrineConfig = Setup::createAnnotationMetadataConfiguration($ADoctrinConfigPath, $ADb['connections']['isDevMode']);

      foreach ($ADb['connections'] as $connName => $ADbConfig) {
        self::$_ADbc[$connName] = [];
        self::$_ADbc[$connName]['config']  = $ADbConfig;
      }
    }
  }
}