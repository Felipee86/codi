<?php

/**
 * Class Codi_Application of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Codi/Request.php';
require_once 'Zend/Session.php';

class Codi_Application {

  private $OFrontController = null;
  private $AOptions = [];

  public function __construct()
  {
    if (!Zend_Session::sessionExists()) {
      Zend_Session::start();
    }

    $REQUEST = Codi_Request::getRequestData();

    $filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . $REQUEST['FrontController']['module']
                . '/controller/' . $REQUEST['FrontController']['controller'] . '.php';

    if (file_exists($filePath)) {
      require_once $filePath;
      $class = ucfirst($REQUEST['FrontController']['module'])
              . '_Controller_'
              . ucfirst($REQUEST['FrontController']['controller']);

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