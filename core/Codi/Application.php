<?php namespace Codi;

/**
 * Class Codi_Application of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Request;
use Codi\Error;
use Zend\Session\SessionManager as Session;

class Application {

  private $OFrontController = null;
  private $AOptions = [];

  public function __construct()
  {
    if (!Session::sessionExists()) {
      Session::start();
    }

    $REQUEST = Request::getRequestData();

    $filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . $REQUEST['FrontController']['module']
                . '/controller/' . $REQUEST['FrontController']['controller'] . '.php';

    if (file_exists($filePath)) {
      require_once $filePath;
      $class = ucfirst($REQUEST['FrontController']['module'])
              . '\\'
              . ucfirst($REQUEST['FrontController']['controller'])
              . 'Controller';

      $this->OFrontController = new $class($REQUEST['FrontController']['action']);
    }
    else {
      Error::throwError('Nie odnaleziono klasy kontrollera');
    }

    $this->AOptions = $REQUEST['Options'];
  }

  /**
   * Set and run the application action controller.
   */
  public function start()
  {
    $this->OFrontController->isValidOptions($this->AOptions);

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