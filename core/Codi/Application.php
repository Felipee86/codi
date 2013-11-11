<?php

namespace Codi;

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

  private $closeCallback;

  public function __construct()
  {
    Session::start();

    $router = Request::getRequestRouter();

    $this->OFrontController = $router->reciveController();
  }

  /**
   * Set and run the application action controller.
   */
  public final function start()
  {
    $content = $this->OFrontController->run();

    $this->render($content);
  }

  public final function close()
  {
    if (is_callable($this->closeCallback)) {
      call_user_method('closeCallback', $this);
    }
  }

  /**
   * Renders the view.
   */
  public final function render($content = '')
  {
    echo Conf::getConfig('application.doctype-header');
  }

  

  /**
   * Method to run on finishing the application screen
   */
  public function onFinish(callable $callback)
  {
    $this->closeCallback = $callback;
  }
}