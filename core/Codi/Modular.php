<?php

namespace Codi;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Modular
 *
 * @author fiko
 */
class Modular {

  const APP_DIR_NAME = '/application/modules';

  private static $_AModules = [];

  private static function _loadModules() {
    $dir = realpath(dirname(__FILE__)) . self::APP_DIR_NAME;
  }

}

?>
