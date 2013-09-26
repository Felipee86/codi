<?php

/**
 * Class Exception of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

class Codi_Controller_Exception extends Zend_Exception {

  public function __construct($msg = '')
  {
    parent::__construct($msg);
  }

}