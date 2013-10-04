<?php namespace Codi\Form;

/**
 * Class Params of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Error;
use Rendus\Element\Form;

class Param
{
  private $_value = null;

  private $_OElement = null;

  public $row = 1;

  public $col = 1;

  public function __construct(array $AConfig)
  {
    if (!empty($AConfig['form_element_name'])) {
      $this->_OElement = Form::factory($AConfig['form_element_name']);
    }
    else {
      Error::throwError('Nie pobrano informacji o elemencie formularza.');
    }

    if (!empty($AConfig['default_value'])) {
      $this->setValue($AConfig['default_value']);
    }

    if ($AConfig['row']) {
      $this->col = $AConfig['row'];
    }
    if ($AConfig['col']) {
      $this->col = $AConfig['col'];
    }
  }

  public final function render()
  {
    $this->_OElement->render($AData);
  }

  public final function getValue()
  {
    return $this->_value;
  }

  public final function setValue($value)
  {
    $this->_value = $value;
    $this->_OElement->setValue($value);
  }
}