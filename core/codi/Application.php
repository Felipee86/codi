<?php namespace Codi;

/**
 * Class Codi_Application of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Request;
use Codi\Error;
use Codi\Session;

class Application {

  private $OFrontController = null;

  public function __construct()
  {
    Session::start();

    $REQUEST = Request::getRequestData();

    $class = ucfirst($REQUEST['FrontController']['module'])
            . '\\'
            . ucfirst($REQUEST['FrontController']['controller'])
            . 'Controller';

    if (class_exists($class)) {
      $this->OFrontController = new $class($REQUEST['FrontController']['action']);
    }
    else {
      //TODO: zmienic na Redirect
      Error::throwError('nie ma klasy kontrolera');
    }
  }

  /**
   * Set and run the application action controller.
   */
  public function start()
  {
    $this->OFrontController->run();

    $this->OFrontController->render();
  }

  /**
   * Method to run on finishing the application screen
   */
  public function onFinish()
  {
    $this->OFrontController->onClose();
//    Error::throwError('END');
  }
}