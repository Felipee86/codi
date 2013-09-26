<?php

/**
 * Class Abstract of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

abstract class Codi_Controller_Abstract {

  const CONTROLLER_TABLE = 'codi_controller';
  const CONTROLLER_ACTION_TABLE = 'codi_controller_action';

  protected $OUser = null;

  protected $AConfig = [];

  protected $ODataCase = null;

  /**
   * Database adapter handler
   * @var Zend_Db_Adapter_Abstract
   */
  protected $db;

  /**
   * This method is call when the object is creating.
   */
  abstract protected function init();

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

    $db = Dbi::factory();
    $id = $db->fetchOne($q, array(
        Codi_Request::getModule(),
        Codi_Request::getController(),
        Codi_Request::getAction()
    ));

    return $id;
  }
}