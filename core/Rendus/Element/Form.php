<?php namespace Rendus\Element;

/**
 * Class Form of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\DataBase as DDb;
use Codi\Error;
use Codi\Loader;

class Form
{

  public static function factory($name)
  {
    $db = DDb::factory();
    
    $q = "SELECT
            id,
            classname,
            default_value
          FROM
            rendus_element_form
          WHERE
            name = ?";

    $AConfig = $db->getQueryRow($q, array($name));

    if (!empty($AConfig['classname']) && Loader::loadClass($AConfig['classname'])) {
      return new $AConfig['classname']($AConfig);
    }
    else {
      Error::throwError('Nie udalo sie zaladowac elementu.');
    }
  }
}