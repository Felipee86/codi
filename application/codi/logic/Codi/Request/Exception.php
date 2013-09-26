<?php

/**
 * Class Codi_Request_Exception of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Zend/Exception.php';

class Codi_Request_Exception extends Zend_Exception {

  /**
   * Construct the exception
   *
   * @param  string $msg
   * @param  int $code
   * @return void
   */
  public function __construct($msg = '')
  {
    parent::__construct($msg);
  }

}