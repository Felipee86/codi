<?php

namespace Codi;

use Codi\Conf;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Modular
 *
 * @author fiko
 */
final class Modular {

  const APP_DIR_NAME = '/Modules/';

  const DEFAULT_CONTROLLER = 'home';

  const DEFAULT_ACTION = 'index';

  private static $_AModules = [];

  private $_name = '';

  private $_aConfig = [];

  private function __construct($module) {
    $this->_name = $module;
    $this->aConfig = self::$_ASettings[$module];
  }

  private static function _loadModules() {
    if (empty(self::$_AModules)) {
      self::$_AModules = Conf::getConfig('modules');
    }
  }

  private static function _registry($module) {
    self::_loadModules();

    if (!isset(self::$_AModules[$module])) {
      return false;
    }

    if (!isset(self::$_AModules[$module]['handler'])) {
      self::$_AModules[$module]['handler'] = new Modular($module);
    }

    return self::$_AModules[$module]['handler'];
  }

  /**
   * Gets instance of Modular driver.
   *
   * @param string $module
   * @return Codi\Modular
   */
  public static function get($module) {
    return self::_registry($module);
  }

  /**
   * Gets list of availeble modules.
   *
   * @return array
   */
  public static function getList() {
    self::_loadModules();

    return array_keys(self::$_AModules);
  }

  public function getDefaultAction() {
    if (isset($this->_aConfig['default']['action'])) {
      return $this->_aConfig['default']['action'];
    }
    return self::DEFAULT_ACTION;
  }

  public function getDefaultController() {
    if (isset($this->_aConfig['default']['controller'])) {
      return $this->_aConfig['default']['controller'];
    }
    return self::DEFAULT_CONTROLLER;
  }

}

?>
