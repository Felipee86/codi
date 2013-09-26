<?php

/**
 * Class Form of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */
class Rendus_Element_Form
{

  public static function factory($name)
  {
    $db = Dbi::factory();

    $q = "SELECT
            id,
            classname,
            default_value
          FROM
            rendus_element_form
          WHERE
            name = ?";

    $AConfig = $db->fetchRow($q, array($name));

    if (!empty($AConfig['classname']) && Codi_Class::loadClass($AConfig['classname'])) {
      return new $AConfig['classname']($AConfig);
    }
    else {
      Error::throwError('Nie udalo sie zaladowac elementu.');
    }
  }
}