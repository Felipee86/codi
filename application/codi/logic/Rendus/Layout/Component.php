<?php

/**
 * Class Component of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Rendus/Layout/Abstract.php';

class Rendus_Layout_Component extends Rendus_Layout_Abstract
{
  const RENDUS_COMPONENT_PATH = '../application/codi/logic/Rendus/Layout/Component/';
  const RENDUS_COMPONENT_CLASS = 'Rendus_Layout_Component_';


  /**
   * Identifier of component.
   * @var int
   */
  private $_componentId;

  /**
   * Name of the component.
   * @var string
   */
  protected $compName = '';

  protected $label = '';

  public function __construct()
  {
    $AClass = explode('_', get_class($this));
    $this->compName = array_pop($AClass);
    $this->onInit();
  }

  /**
   * Method to be override. Must return a string value of a file name.
   *
   * @param string $fileName (optional) Name of the specify component layout.
   * @return string
   */
  protected function onInit() {}

  /**
   * Reciving id of the component object.
   *
   * @return int
   */
  public function getId()
  {
    return $this->_componentId;
  }

  /**
   * Gets the absolute path to the component layout file.
   *
   * @param string $fileName Name of the component file.
   * @param string (optional) $module Name of the component module.
   * @return string
   */
  public static function getComponentPath($fileName, $module = 'codi')
  {
    $filePath = APPLICATION_PATH . '/' . strtolower($module) . '/rendus/component/' . $fileName . '.php';
    if (file_exists($filePath)) {
      return $filePath;
    }
    Error::throwError('Nie odnaleziono pliku componentu: ' . $filePath);
  }

  public final function getLabel()
  {
    return (string)$this->getLabel();
  }

  public final function setLabel($label)
  {
    $this->label = (string)$label;
  }

  protected function onRenderHtml($AData = array())
  {
    return [];
  }

  /**
   * Creating an instance of a Component class.
   *
   * @param string $compName name of a component
   * @return Rendus_Layout_Component
   */
  public static function factory($compName)
  {
    $compName = str_replace('.php', '', $compName);
    $path = self::RENDUS_COMPONENT_PATH . $compName . '.php';

    if (file_exists($path)) {
      require_once $path;
      $class = self::RENDUS_COMPONENT_CLASS . $compName;
      $OComponent = new $class;
    }
    else {
      Error::throwError("Element formularza ($type) nie istnieje.");
    }

    return $OComponent;
  }

}