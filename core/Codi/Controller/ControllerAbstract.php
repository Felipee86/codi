<?php

namespace Codi\Controller;

/**
 * Class Abstract of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\DataBase as DDb;
use Codi\Request;

abstract class ControllerAbstract {

  const CONTROLLER_TABLE = 'codi_controller';
  const CONTROLLER_ACTION_TABLE = 'codi_controller_action';

  protected $AConfig = [];

  protected $ODataCase = null;

  protected $action = '';


  /**
   * Database adapter handler
   * @var Zend\Db\Adapter\Adapter
   */
  protected $db;

  /**
   * Runs the controller.
   */
  abstract public function run();

  /**
   * indexAction is the main action of all controllers
   */
  protected abstract function index();

  

  public final function setAction($action) {
    $this->action = $action;
  }

  public final function getAction() {
    return $this->action;
  }

  public final function getModule() {
    return array_shift(explode('\\', get_class($this)));
  }

  public final function getName() {
    return array_pop(explode('\\', get_class($this)));
  }

}