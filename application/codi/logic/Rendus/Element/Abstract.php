<?php

/**
 * Class Rendus_Element_Abstract of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Rendus/Interface.php';

abstract class Rendus_Element_Abstract implements Rendus_Interface
{
  private $_value = null;

  private $_content = null;

  public final function getValue()
  {
    return (string)$this->_value;
  }

  public final function setValue(string $value)
  {
    $this->_value = (string)$value;
  }

  public final function getLabel()
  {
    return $this->_content;
  }

  public final function setLabel($value)
  {
    $this->_content = (string)$value;
  }

  public final function render(array $AData = [])
  {
    $AHtml = $this->onRenderHtml($AData);
    if (!is_array($AHtml)) {
      Error::throwError('Metoda onRenderHtml musi zwracac tablice');
    }

    return $AHtml;
  }

  protected abstract function onRenderHtml($AHtml = []);

}