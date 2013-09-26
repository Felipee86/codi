<?php

/**
 * Class Params of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */
class Codi_Form_Param
{
  private $_value = null;

  private $_OElement = null;

  public function __construct(array $AConfig)
  {
    if (!empty($AConfig['id_rendus_element_form'])) {
      // ustawienie elementu
    }

    if (!empty($AConfig['default_value'])) {
      $this->setValue($AConfig['default_value']);
    }
  }

  public function getValue()
  {
    return $this->_value;
  }

  public function setValue($value)
  {
    $this->_value = $value;
  }
}