<?php namespace Codi;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DAta
 *
 * @author fiko
 */
class Data
{
  public static function factory($type, array $AArg = [])
  {
    $OData = null;

    $type = strtolower($type);

    if (in_array($type, ['csv', 'xml', 'sql', 'soup', 'txt'])) {
      $class = "Data\\" . ucfirst($type);
      $OData = new $class($AArg);
    }

    return $OData;
  }
}

?>
