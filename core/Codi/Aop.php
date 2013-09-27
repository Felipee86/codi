<?php namespace Codi;

/**
 * Class Aop of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\File;

final class Aop
{

  public static function CallMethod($class, $function, $AParams = array())
  {
    $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class);
    //TODO: Sprawdzenie pliku klasy w projekcie (klasa)
    File::exists($class_file);
  }

}

