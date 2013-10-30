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

    $router = Request::getRequestRouter();

    $this->OFrontController = $router->reciveController();
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