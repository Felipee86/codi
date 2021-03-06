<?php

namespace Codi;

/**
 * Class Lang of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Conf;

class Langus {

  private static $_ATrans = [];

  private static function _getLang($module)
  {
    if (!isset(self::$_ATrans[$module])) {
      //TODO: Obsługa języków z poziomu strony.
      $lang = Conf::getOption('default.lang');
      $filename = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'translation' . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . $module . '.php';
      if (file_exists($filename)) {
        self::$_ATrans[$module] = include $filename;
      }
      else {
        Error::throwError('Nie istnieja tlumaczenia dla danego jezyka.');
      }
    }

    return self::$_ATrans[$module];
  }

  /**
   * Prints the translation for a value key.
   *
   * @param string $value    Key for translation.
   * @param string $modul    (optional) Module for searching in.
   * @param string $context  (optional) The specify context.
   * @return string
   */
  public static function pr($value)
  {
    $ARecursiveValue = explode('.', $value);

    $ALang = self::_getLang($ARecursiveValue[0]);

    if (isset($ALang[$ARecursiveValue[1]])) {
      return $ALang[$ARecursiveValue[1]];
    }
    else {
      return "^" . $ARecursiveValue[1] . "$";
    }
  }



}