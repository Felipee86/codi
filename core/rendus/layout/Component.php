<?php namespace Rendus\Layout;

/**
 * Class Component of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\DataBase as DDb;
use Rendus\Layout\LayoutAbstract;

class Component extends LayoutAbstract
{
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
   * @param string $identifier name of a component
   * @return Rendus_Layout_Component
   */
  public static function factory($identifier)
  {
    $OComponent = null;

    if (is_numeric($identifier)) {
      $db = DDb::factory();

      $q = "SELECT
              classname
            FROM
              rendus_layout
            WHERE
              id = ?
              AND type = 'component'";

      $identifier = $db->getQueryOne($q, array($identifier));
    }

    if ($identifier) {
      $class = "\\" . $identifier;
      $OComponent = new $class();
    }

    return $OComponent;
  }

}