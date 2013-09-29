<?php namespace Rendus\Element;

/**
 * Class Form of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\DataBase as Dbi;
use Codi\Error;
use Codi\Loader;

class Form
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

    if (!empty($AConfig['classname']) && Loader::loadClass($AConfig['classname'])) {
      return new $AConfig['classname']($AConfig);
    }
    else {
      Error::throwError('Nie udalo sie zaladowac elementu.');
    }
  }
}