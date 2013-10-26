<?php namespace Rendus\Element;

/**
 * Class Form of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\DataBase as DDb;
use Codi\Error;

class Form
{
  /**
   * Creates the rendus form element.
   *
   * @param string $name
   * @return \Rendus\Element\ElementAbstract
   */
  public static function factory($name)
  {
    $db = DDb::factory();

    $q = "SELECT
            ref.id,
            ref.classname,
            ref.default_value
          FROM
            rendus_element_form ref
          JOIN
            rendus_element re ON re.id = ref.id_rendus_element
          WHERE
            re.name = ?";

    $AConfig = $db->getQueryRow($q, array($name));

    if (!empty($AConfig['classname'])) {
      $OElement = new $AConfig['classname']();
      if (!empty($AConfig['default_value'])) {
        $OElement->setValue($AConfig['default_value']);
      }
      return $OElement;
    }
    else {
      Error::throwError('Nie udalo sie zaladowac elementu.');
    }
  }
}