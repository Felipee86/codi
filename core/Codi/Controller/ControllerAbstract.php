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
   * Renders the controller view.
   */
  public abstract function render();

  /**
   * indexAction is the main action of all controllers
   */
  protected abstract function indexAction();

  /**
   * Method to overide in all inherited methods. <br />
   * This function is running while controller is finishing the respone.
   */
  public function onClose() {}

  public function setAction($action) {
    $this->action = $action;
  }

  public function getAction() {
    return $this->action;
  }

  public function getModule() {
    return array_shift(explode('\\', get_class($this)));
  }

  public function getName() {
    return array_pop(explode('\\', get_class($this)));
  }

  public static function getActionId()
  {
    $q = "SELECT
            cca.id
          FROM
            " . self::CONTROLLER_TABLE . " cc
          JOIN
            " . self::CONTROLLER_ACTION_TABLE . " cca ON
              cc.id = cca.id_codi_controller
          WHERE
            cc.module = ?
            AND cc.name = ?
            AND cca.name = ?
          ";

    $db = DDb::factory();
    $id = $db->getQueryOne($q, array(
        Request::getModule(),
        Request::getController(),
        Request::getAction()
    ));

    return $id;
  }
}