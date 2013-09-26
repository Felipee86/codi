<?php

/**
 * Class Exception of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */
class Codi_Application_Exception extends Zend_Exception  {

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